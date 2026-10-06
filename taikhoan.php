<?php
require 'app/bootstrap.php'; require_login();
$customer=is_role('KhachHang'); $table=$customer?'khach_hang':'nhan_vien'; $pk=$customer?'MAKH':'MANV'; $namecol=$customer?'HOTENKH':'HOTEN';
$u=row("SELECT * FROM $table WHERE $pk=?",[user()['id']]); $error='';
if($_SERVER['REQUEST_METHOD']==='POST') { check_csrf(); $error=attempt(function() use($table,$pk,$namecol,$u,$customer) {
    if(($_POST['action']??'')==='password') {
        if(!password_verify((string)($_POST['old']??''),$u['MATKHAU'])) fail('Mật khẩu hiện tại không đúng.');
        $new=(string)($_POST['new']??''); if(strlen($new)<8 || strlen($new)>72 || $new!==($_POST['confirm']??'')) fail('Mật khẩu mới cần 8–72 ký tự và xác nhận trùng khớp.');
        query("UPDATE $table SET MATKHAU=? WHERE $pk=?",[password_hash($new,PASSWORD_DEFAULT),user()['id']]); session_regenerate_id(true);
    } else {
        if(!$customer) fail('Thông tin nhân viên do quản trị cập nhật.');
        $name=text_input('name',100); $phone=text_input('phone',15); $cccd=text_input('cccd',20,false);
        if(!preg_match('/^\+?[0-9]{9,14}$/',$phone)) fail('Số điện thoại không hợp lệ.');
        if(!(int)row("SELECT GET_LOCK('n2h_customers',5) n")['n']) fail('Hệ thống bận.');
        try { if(row('SELECT MAKH FROM khach_hang WHERE SDT=? AND MAKH<>?',[$phone,user()['id']])) fail('Số điện thoại đã được sử dụng.'); query("UPDATE khach_hang SET HOTENKH=?,SDT=?,CCCD=? WHERE MAKH=?",[$name,$phone,$cccd,user()['id']]); } finally { query("SELECT RELEASE_LOCK('n2h_customers')"); }
    }
    flash('Đã cập nhật tài khoản.'); go('taikhoan.php');
}); }
page('Tài khoản của tôi'); error_box($error); ?><div class="split"><div class="panel"><h2>Thông tin cá nhân</h2><?php if($customer): ?><form method="post"><?php token(); input('name','Họ và tên',$u[$namecol],'text','required maxlength="100"'); input('phone','Số điện thoại',$u['SDT'],'tel','required maxlength="15"'); input('cccd','CCCD / Giấy tờ tùy thân',$u['CCCD'],'text','maxlength="20"'); ?><p>Email: <?=h($u['EMAIL'])?></p><button>Lưu thông tin</button></form><?php else: ?><p><?=h($u['HOTEN'])?> · <?=h($u['CHUCVU'])?></p><p>Liên hệ quản trị để thay đổi hồ sơ.</p><?php endif; ?></div><div class="panel"><h2>Đổi mật khẩu</h2><form method="post"><?php token(); ?><input type="hidden" name="action" value="password"><?php input('old','Mật khẩu hiện tại','','password','required'); input('new','Mật khẩu mới','','password','required minlength="8" maxlength="72"'); input('confirm','Xác nhận mật khẩu mới','','password','required'); ?><button>Đổi mật khẩu</button></form></div></div><?php endpage(); ?>
