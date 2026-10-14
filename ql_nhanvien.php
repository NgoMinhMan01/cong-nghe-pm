<?php
require 'app/bootstrap.php'; require_roles('Admin'); $error='';
if($_SERVER['REQUEST_METHOD']==='POST') { check_csrf(); $error=attempt(function() {
    $id=(int)($_POST['id']??0); $name=text_input('name',100); $phone=text_input('phone',15); $role=text_input('role',50); $active=number_input('active',0,1); $pass=(string)($_POST['password']??'');
    if(!preg_match('/^\+?[0-9]{2,14}$/',$phone)) fail('Số điện thoại không hợp lệ.');
    if(!in_array($role,['Admin','Lễ tân','Nhân viên'],true)) fail('Vai trò không hợp lệ.');
    if((!$id || $pass!=='') && (strlen($pass)<8 || strlen($pass)>72)) fail('Mật khẩu cần 8–72 ký tự.');
    if($id===(int)user()['id'] && ($role!=='Admin'||!$active)) fail('Không được tự khóa hoặc tự hạ quyền.');
    transaction(function() use($id,$name,$phone,$role,$active,$pass) {
        query('SELECT MANV FROM nhan_vien ORDER BY MANV FOR UPDATE');
        if($id && !row('SELECT MANV FROM nhan_vien WHERE MANV=?',[$id])) fail('Nhân viên không tồn tại.');
        if(row('SELECT MANV FROM nhan_vien WHERE SDT=? AND MANV<>?',[$phone,$id])) fail('Số điện thoại nhân viên đã tồn tại.');
        if($id) {
            query('UPDATE nhan_vien SET HOTEN=?,SDT=?,CHUCVU=?,ACTIVE=? WHERE MANV=?',[$name,$phone,$role,$active,$id]);
            if($pass!=='') query('UPDATE nhan_vien SET MATKHAU=? WHERE MANV=?',[password_hash($pass,PASSWORD_DEFAULT),$id]);
        } else { query('INSERT INTO nhan_vien(HOTEN,SDT,CHUCVU,ACTIVE,MATKHAU) VALUES(?,?,?,?,?)',[$name,$phone,$role,$active,password_hash($pass,PASSWORD_DEFAULT)]); }
        if(!row("SELECT MANV FROM nhan_vien WHERE CHUCVU='Admin' AND ACTIVE=1")) fail('Phải có ít nhất một quản trị viên hoạt động.');
        audit('Lưu nhân viên',(string)$id,$role);
    }); flash('Đã lưu nhân viên.'); go('ql_nhanvien.php');
}); }
$edit=row('SELECT * FROM nhan_vien WHERE MANV=?',[(int)($_GET['edit']??0)]); $list=rows('SELECT MANV,HOTEN,SDT,CHUCVU,ACTIVE FROM nhan_vien ORDER BY MANV');
page('Quản lý nhân viên'); error_box($error); ?><div class="panel"><h2><?=$edit?'Sửa nhân viên':'Thêm nhân viên'?></h2><form method="post"><?php token(); ?><input type="hidden" name="id" value="<?=h($edit['MANV']??0)?>"><div class="row"><?php input('name','Họ và tên',$edit['HOTEN']??'','text','required maxlength="100"'); input('phone','Số điện thoại đăng nhập',$edit['SDT']??'','tel','required maxlength="15"'); select_field('role','Vai trò',['Admin'=>'Quản trị','Lễ tân'=>'Lễ tân','Nhân viên'=>'Nhân viên buồng phòng'],$edit['CHUCVU']??'Nhân viên'); ?></div><div class="row"><?php input('password',$edit?'Mật khẩu mới (để trống nếu giữ nguyên)':'Mật khẩu','','password','minlength="8" maxlength="72" '.($edit?'':'required')); select_field('active','Trạng thái',[1=>'Hoạt động',0=>'Khóa'],$edit['ACTIVE']??1); ?></div><button>Lưu nhân viên</button> <a href="ql_nhanvien.php">Nhập mới</a></form></div><div class="table-wrap"><table><thead><tr><th>Mã</th><th>Tên</th><th>SĐT</th><th>Vai trò</th><th>Trạng thái</th><th></th></tr></thead><tbody><?php foreach($list as $r): ?><tr><td><?=$r['MANV']?></td><td><?=h($r['HOTEN'])?></td><td><?=h($r['SDT'])?></td><td><?=h($r['CHUCVU'])?></td><td><?=$r['ACTIVE']?'Hoạt động':'Khóa'?></td><td><a href="?edit=<?=$r['MANV']?>">Sửa</a></td></tr><?php endforeach; ?></tbody></table></div><?php endpage(); ?>
