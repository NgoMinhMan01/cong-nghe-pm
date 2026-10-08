<?php
require 'app/bootstrap.php'; require_roles('Admin','Lễ tân'); $error='';
if($_SERVER['REQUEST_METHOD']==='POST') { check_csrf(); $error=attempt(function() {
    $id=(int)($_POST['id']??0); $name=text_input('name',100); $email=mb_strtolower(text_input('email',50,false)); $phone=text_input('phone',15); $cccd=text_input('cccd',20,false); $active=number_input('active',0,1);
    if(($email!==''&&!filter_var($email,FILTER_VALIDATE_EMAIL))||!preg_match('/^\+?[0-9]{9,14}$/',$phone)) fail('Email hoặc số điện thoại không hợp lệ.');
    if(!(int)row("SELECT GET_LOCK('n2h_customers',5) n")['n']) fail('Hệ thống bận, thử lại sau.');
    try {
        if(row("SELECT MAKH FROM khach_hang WHERE MAKH<>? AND (SDT=? OR (EMAIL=? AND EMAIL<>''))",[$id,$phone,$email])) fail('Email hoặc số điện thoại đã tồn tại.');
        if($id) {
            if(!row('SELECT MAKH FROM khach_hang WHERE MAKH=?',[$id])) fail('Khách hàng không tồn tại.');
            query('UPDATE khach_hang SET HOTENKH=?,EMAIL=?,SDT=?,CCCD=?,ACTIVE=? WHERE MAKH=?',[$name,$email?:null,$phone,$cccd,$active,$id]);
        } else {
            // Khách vãng lai không dùng mật khẩu mặc định có thể đoán được.
            query('INSERT INTO khach_hang(HOTENKH,EMAIL,SDT,CCCD,ACTIVE,MATKHAU) VALUES(?,?,?,?,?,?)',[$name,$email?:null,$phone,$cccd,$active,password_hash(bin2hex(random_bytes(24)),PASSWORD_DEFAULT)]); $id=(int)db()->lastInsertId();
        }
        audit('Lưu khách hàng',(string)$id);
    } finally { query("SELECT RELEASE_LOCK('n2h_customers')"); }
    flash('Đã lưu hồ sơ khách hàng.'); go('ql_khachhang.php');
}); }
$q=trim((string)($_GET['q']??'')); $count=(int)row('SELECT COUNT(*) n FROM khach_hang WHERE HOTENKH LIKE ? OR SDT LIKE ? OR EMAIL LIKE ?',array_fill(0,3,'%'.$q.'%'))['n']; $p=max(1,min(max(1,(int)ceil($count/20)),(int)($_GET['p']??1))); $offset=($p-1)*20;
$list=rows('SELECT k.*,(SELECT COUNT(*) FROM dat d WHERE d.MAKH=k.MAKH) bookings FROM khach_hang k WHERE HOTENKH LIKE ? OR SDT LIKE ? OR EMAIL LIKE ? ORDER BY MAKH DESC LIMIT 20 OFFSET '.$offset,array_fill(0,3,'%'.$q.'%')); $edit=row('SELECT * FROM khach_hang WHERE MAKH=?',[(int)($_GET['edit']??0)]);
page('Quản lý khách hàng'); error_box($error); ?><details class="panel" <?=isset($_GET['edit'])?'open':''?>><summary><?=$edit?'Sửa hồ sơ khách hàng':'Thêm khách vãng lai'?></summary><form method="post"><?php token(); ?><input type="hidden" name="id" value="<?=h($edit['MAKH']??0)?>"><div class="row"><?php input('name','Họ và tên',$edit['HOTENKH']??'','text','required maxlength="100"'); input('phone','Số điện thoại',$edit['SDT']??'','tel','required maxlength="15"'); input('email','Email (không bắt buộc)',$edit['EMAIL']??'','email','maxlength="50"'); ?></div><div class="row"><?php input('cccd','CCCD / giấy tờ tùy thân',$edit['CCCD']??'','text','maxlength="20"'); select_field('active','Tài khoản',[1=>'Hoạt động',0=>'Khóa đăng nhập / đặt mới'],$edit['ACTIVE']??1); ?></div><p class="muted">Hồ sơ khách vãng lai dùng để lễ tân đặt hộ. Không tự cấp mật khẩu đăng nhập. Khách muốn tự đặt nên đăng ký tài khoản trước. Khóa tài khoản vẫn giữ lịch sử lưu trú.</p><button>Lưu khách hàng</button> <a href="ql_khachhang.php">Nhập mới</a></form></details><form class="panel row" method="get"><?php input('q','Tìm tên, SĐT, email',$q); ?><label>&nbsp;<button>Tìm khách</button></label></form><div class="table-wrap"><table><thead><tr><th>Mã</th><th>Họ tên</th><th>Liên hệ</th><th>Giấy tờ</th><th>Số đơn</th><th>Trạng thái</th><th></th></tr></thead><tbody><?php foreach($list as $r): ?><tr><td><?=$r['MAKH']?></td><td><?=h($r['HOTENKH'])?></td><td><?=h($r['SDT'])?><br><small><?=h($r['EMAIL'])?></small></td><td><?=h($r['CCCD'])?></td><td><a href="ql_datphong.php?q=<?=urlencode($r['SDT'])?>"><?=$r['bookings']?> đơn</a></td><td><?=$r['ACTIVE']?'Hoạt động':'Đã khóa'?></td><td><a href="?edit=<?=$r['MAKH']?>">Sửa</a></td></tr><?php endforeach; ?></tbody></table></div><div class="pagination"><?php if($p>1): ?><a href="?<?=h(http_build_query(['q'=>$q,'p'=>$p-1]))?>">← Trước</a><?php endif; ?><span>Trang <?=$p?> · <?=$count?> khách</span><?php if($offset+20<$count): ?><a href="?<?=h(http_build_query(['q'=>$q,'p'=>$p+1]))?>">Sau →</a><?php endif; ?></div><?php endpage(); ?>
