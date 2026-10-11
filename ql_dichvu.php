<?php
require 'app/bootstrap.php'; require_roles('Admin','Lễ tân'); $error='';
if($_SERVER['REQUEST_METHOD']==='POST') { check_csrf(); $error=attempt(function() {
    $id=(int)($_POST['id']??0); $name=text_input('name',100); $price=number_input('price',0,100000000); $active=number_input('active',0,1);
    if($id) { if(!row('SELECT MADV FROM dich_vu WHERE MADV=?',[$id])) fail('Dịch vụ không tồn tại.'); query('UPDATE dich_vu SET TENDV=?,GIADV=?,ACTIVE=? WHERE MADV=?',[$name,$price,$active,$id]); }
    else { query('INSERT INTO dich_vu(TENDV,GIADV,ACTIVE) VALUES(?,?,?)',[$name,$price,$active]); $id=(int)db()->lastInsertId(); }
    audit('Lưu dịch vụ',(string)$id); flash('Đã lưu dịch vụ. Giá mới chỉ áp dụng cho lần sử dụng tiếp theo.'); go('ql_dichvu.php');
}); }
$edit=row('SELECT * FROM dich_vu WHERE MADV=?',[(int)($_GET['edit']??0)]); $list=rows('SELECT * FROM dich_vu ORDER BY MADV');
page('Quản lý dịch vụ'); error_box($error); ?><div class="panel"><form method="post"><?php token(); ?><input type="hidden" name="id" value="<?=h($edit['MADV']??0)?>"><div class="row"><?php input('name','Tên dịch vụ',$edit['TENDV']??'','text','required maxlength="100"'); input('price','Đơn giá (VND)',(int)($edit['GIADV']??0),'number','required min="0" step="1"'); select_field('active','Trạng thái',[1=>'Đang cung cấp',0=>'Ngừng cung cấp'],$edit['ACTIVE']??1); ?><label>&nbsp;<button>Lưu dịch vụ</button></label></div><a href="ql_dichvu.php">Nhập mới</a></form></div><div class="table-wrap"><table><thead><tr><th>Mã</th><th>Dịch vụ</th><th>Đơn giá</th><th>Trạng thái</th><th></th></tr></thead><tbody><?php foreach($list as $r): ?><tr><td><?=$r['MADV']?></td><td><?=h($r['TENDV'])?></td><td><?=money($r['GIADV'])?></td><td><?=$r['ACTIVE']?'Đang cung cấp':'Ngừng cung cấp'?></td><td><a href="?edit=<?=$r['MADV']?>">Sửa</a></td></tr><?php endforeach; ?></tbody></table></div><?php endpage(); ?>
