<?php
require 'app/booking.php'; require_login(); if(!staff()&&!is_role('KhachHang')) { http_response_code(403); exit('Nhân viên buồng phòng không có quyền đặt phòng.'); }
$id=(int)($_GET['id_phong']??$_POST['room']??0);
$r=row('SELECT p.*,l.TENLOAIPHONG,l.SUCCHUA,l.TIENNGHI,l.MOTA FROM phong p JOIN loai_phong l ON l.MALOAIPHONG=p.MALOAIPHONG WHERE p.MAPHONG=?',[$id]);
if(!$r) { http_response_code(404); exit('Phòng không tồn tại.'); }
$error='';
if($_SERVER['REQUEST_METHOD']==='POST') { check_csrf(); $error=attempt(function() use($id) {
    $customer=staff()?number_input('customer',1,2147483647):(int)user()['id'];
    $bid=create_booking($id,$customer,text_input('start',10),text_input('end',10),number_input('guests',1,20),text_input('note',500,false));
    flash('Đặt phòng thành công. Mã đơn #'.$bid); go('chitiet_datphong.php?id='.$bid);
}); }
$start=$_POST['start']??$_GET['start']??date('Y-m-d'); $end=$_POST['end']??$_GET['end']??date('Y-m-d',strtotime('+1 day'));
page('Đặt phòng '.$id); error_box($error); ?><div class="split"><div class="card"><img style="height:300px" src="<?=h(image_path($r['HINH_ANH']))?>" alt="Ảnh phòng"><div class="body"><h2><?=h($r['TENLOAIPHONG'])?></h2><p><?=h($r['MOTA'])?></p><p><?=h($r['TIENNGHI'])?></p><p>Tầng <?=h($r['TANG'])?> · Tối đa <?=h($r['SUCCHUA'])?> khách</p><p class="price"><?=money($r['GIAPHONG'])?> / đêm</p></div></div><div class="panel"><form method="post"><?php token(); ?>
<?php if(staff()): $customers=rows('SELECT MAKH,HOTENKH,SDT FROM khach_hang WHERE ACTIVE=1 ORDER BY HOTENKH'); $opts=[]; foreach($customers as $c) $opts[$c['MAKH']]=$c['HOTENKH'].' · '.$c['SDT']; select_field('customer','Khách hàng',$opts,$_POST['customer']??''); ?><p><a href="ql_khachhang.php">Thêm hồ sơ khách mới</a> trước khi đặt.</p><?php else: ?><p>Người đặt: <strong><?=h(user()['name'])?></strong> · <a href="taikhoan.php">Cập nhật hồ sơ</a></p><?php endif; ?>
<div class="row"><?php input('start','Ngày nhận',$start,'date','required min="'.date('Y-m-d').'"'); input('end','Ngày trả',$end,'date','required'); ?></div><?php input('guests','Số khách',$_POST['guests']??$_GET['guests']??1,'number','required min="1" max="'.(int)$r['SUCCHUA'].'"'); ?><label>Yêu cầu đặc biệt<textarea name="note" maxlength="500"><?=h($_POST['note']??'')?></textarea></label><p class="muted">Tối đa 90 đêm. Tiền phòng = số đêm đã đặt × giá/đêm. Trả sớm vẫn tính theo lịch đã đặt. Dịch vụ phát sinh được tính riêng. Thu tiền do lễ tân ghi nhận; chưa có thanh toán trực tuyến.</p><button>Xác nhận đặt phòng</button></form></div></div><?php endpage(); ?>
