<?php
require 'app/bootstrap.php';
$start=(string)($_GET['start']??date('Y-m-d')); $end=(string)($_GET['end']??date('Y-m-d',strtotime('+1 day'))); $guests=max(1,min(20,(int)($_GET['guests']??1))); $type=(int)($_GET['type']??0); $error=''; $rooms=[];
$error=attempt(function() use($start,$end,$guests,$type,&$rooms) {
    if(!valid_date($start)||!valid_date($end)||$start<date('Y-m-d')||nights($start,$end)>90) fail('Chọn khoảng lưu trú từ hôm nay, tối đa 90 đêm.');
    $rooms=rows("SELECT p.*,l.TENLOAIPHONG,l.SUCCHUA,l.DIENTICH,l.TIENNGHI,l.MOTA FROM phong p JOIN loai_phong l ON l.MALOAIPHONG=p.MALOAIPHONG WHERE p.ACTIVE=1 AND l.ACTIVE=1 AND p.GIAPHONG>0 AND p.TINHTRANG IN ('Trống','Có khách') AND l.SUCCHUA>=? AND (?=0 OR l.MALOAIPHONG=?) AND NOT EXISTS(SELECT 1 FROM dat d WHERE d.MAPHONG=p.MAPHONG AND d.TRANGTHAI IN ('Chờ nhận','Đang ở') AND ((d.NGAYNHAN<? AND d.NGAYTRA>?) OR (d.TRANGTHAI='Đang ở' AND d.NGAYTRA<=CURDATE()))) ORDER BY l.MALOAIPHONG,p.GIAPHONG,p.MAPHONG",[$guests,$type,$type,$end,$start]);
});
$types=rows('SELECT * FROM loai_phong WHERE ACTIVE=1 ORDER BY MALOAIPHONG');
page('Khám phá phòng'); ?>
<section class="hero"><div class="eyebrow">NHM HOTEL · Chào mừng bạn</div><h1>Một kỳ nghỉ thoải mái,<br>một lựa chọn vừa ý.</h1><p>Chọn loại phòng phù hợp, xem từng phòng còn trống và chủ động đặt lịch lưu trú của bạn.</p></section>
<form class="panel row" method="get"><?php input('start','Ngày nhận',$start,'date','required min="'.date('Y-m-d').'"'); input('end','Ngày trả',$end,'date','required'); input('guests','Số khách',$guests,'number','min="1" max="20" required'); select_field('type','Loại phòng',[0=>'Tất cả loại phòng']+array_column($types,'TENLOAIPHONG','MALOAIPHONG'),$type); ?><label>&nbsp;<button>Tìm phòng trống</button></label></form>
<?php error_box($error); ?><h2>Loại phòng & phòng còn trống</h2><p class="muted">Giá theo đêm, nhận từ 14:00 · trả trước 12:00. Lịch được tính theo khoảng ngày nhận đến ngày trả.</p>
<?php if(!$rooms): ?><div class="panel empty">Không có phòng phù hợp. Hãy thử ngày khác, loại phòng khác hoặc số khách ít hơn.</div><?php endif; ?>
<?php foreach($types as $t): $group=array_values(array_filter($rooms,fn($r)=>$r['MALOAIPHONG']==$t['MALOAIPHONG'])); if(!$group) continue; ?>
<section class="panel"><div class="row"><div style="flex:1"><div class="eyebrow">Nhóm phòng</div><h2><?=h($t['TENLOAIPHONG'])?></h2><p><?=h($t['MOTA'])?></p><p class="muted">Tối đa <?=h($t['SUCCHUA'])?> khách · <?=h($t['DIENTICH'])?> m² · <?=h($t['TIENNGHI'])?></p></div><span class="badge good"><?=count($group)?> phòng còn trống</span></div><div class="grid">
<?php foreach($group as $r): $qs=http_build_query(['id_phong'=>$r['MAPHONG'],'start'=>$start,'end'=>$end,'guests'=>$guests]); ?><article class="card"><img src="<?=h(image_path($r['HINH_ANH']))?>" alt="<?=h($r['TENLOAIPHONG'])?>"><div class="body"><h3>Phòng <?=h($r['MAPHONG'])?></h3><p class="muted">Tầng <?=h($r['TANG'])?> · <?=h($r['TENLOAIPHONG'])?></p><p><span class="price"><?=money($r['GIAPHONG'])?></span> / đêm</p><a class="btn" href="datphong.php?<?=h($qs)?>">Xem & đặt phòng</a></div></article><?php endforeach; ?></div></section><?php endforeach; endpage(); ?>
