<?php
require 'app/bootstrap.php'; require_roles('Admin','Lễ tân','Nhân viên'); $manager=staff(); $error='';
if($_SERVER['REQUEST_METHOD']==='POST') { check_csrf(); $error=attempt(function() use($manager) {
    $id=number_input('room',1,2147483647); $action=(string)($_POST['action']??'');
    transaction(function() use($id,$action,$manager) {
        $old=row('SELECT * FROM phong WHERE MAPHONG=? FOR UPDATE',[$id]);
        if($action==='clean') {
            if(!$old || $old['TINHTRANG']!=='Chờ dọn') fail('Chỉ đánh dấu đã dọn cho phòng chờ dọn.');
            query("UPDATE phong SET TINHTRANG='Trống' WHERE MAPHONG=?",[$id]); audit('Dọn xong',(string)$id); return;
        }
        if(!$manager) fail('Bạn chỉ được cập nhật phòng đã dọn.');
        $type=number_input('type',1,2147483647); $price=number_input('price',1,100000000); $floor=number_input('floor',0,200); $active=number_input('active',0,1); $state=text_input('state',30); $img=text_input('image',255,false); $note=text_input('note',500,false);
        $t=row('SELECT * FROM loai_phong WHERE MALOAIPHONG=? FOR UPDATE',[$type]); if(!$t) fail('Loại phòng không tồn tại.');
        if(!in_array($state,['Trống','Chờ dọn','Bảo trì','Có khách'],true)) fail('Tình trạng không hợp lệ.');
        if($state==='Có khách' && (!$old || $old['TINHTRANG']!=='Có khách')) fail('Trạng thái Có khách được cập nhật qua nhận phòng.');
        $hasBookings=(bool)row("SELECT MADAT FROM dat WHERE MAPHONG=? AND TRANGTHAI IN ('Chờ nhận','Đang ở') LIMIT 1",[$id]);
        if($old && $hasBookings && ($type!==(int)$old['MALOAIPHONG'] || $active!==(int)$old['ACTIVE'] || $state!==$old['TINHTRANG'])) fail('Phòng còn đơn hoạt động. Xử lý các đơn trước khi đổi loại, tình trạng hoặc ngừng bán.');
        if($old && $old['TINHTRANG']==='Có khách' && $hasBookings && $state!=='Có khách') fail('Khách phải trả phòng qua đơn đặt phòng.');
        if($action==='add' && $old) fail('Mã phòng đã tồn tại.');
        if($action==='save' && !$old) fail('Phòng không tồn tại.');
        if(!in_array($action,['add','save'],true)) fail('Thao tác không hợp lệ.');
        if($old) query('UPDATE phong SET MALOAIPHONG=?,GIAPHONG=?,TANG=?,ACTIVE=?,TINHTRANG=?,HINH_ANH=?,GHICHU=? WHERE MAPHONG=?',[$type,$price,$floor,$active,$state,image_path($img),$note,$id]);
        else query('INSERT INTO phong(MALOAIPHONG,GIAPHONG,TANG,ACTIVE,TINHTRANG,HINH_ANH,GHICHU,MAPHONG) VALUES(?,?,?,?,?,?,?,?)',[$type,$price,$floor,$active,$state,image_path($img),$note,$id]);
        audit('Lưu phòng',(string)$id);
    }); flash('Đã cập nhật phòng.'); go('ql_phong.php');
}); }
$type=(int)($_GET['type']??0); $q=trim((string)($_GET['q']??''));
$list=rows('SELECT p.*,l.TENLOAIPHONG FROM phong p LEFT JOIN loai_phong l ON l.MALOAIPHONG=p.MALOAIPHONG WHERE (?=0 OR p.MALOAIPHONG=?) AND CAST(p.MAPHONG AS CHAR) LIKE ? ORDER BY p.MALOAIPHONG,p.MAPHONG',[$type,$type,'%'.$q.'%']);
$types=array_column(rows('SELECT * FROM loai_phong ORDER BY MALOAIPHONG'),'TENLOAIPHONG','MALOAIPHONG'); $edit=row('SELECT * FROM phong WHERE MAPHONG=?',[(int)($_GET['edit']??0)]);
page($manager?'Quản lý phòng':'Theo dõi buồng phòng'); error_box($error); ?>
<?php if($manager): ?><p><a class="btn" href="ql_loaiphong.php">Quản lý loại phòng</a> <a class="btn secondary" href="lich_phong.php">Lịch phòng</a></p><details class="panel" <?=isset($_GET['edit'])?'open':''?>><summary><?=$edit?'Sửa phòng '.$edit['MAPHONG']:'Thêm phòng vào loại phòng'?></summary><form method="post"><?php token(); ?><input type="hidden" name="action" value="<?=$edit?'save':'add'?>"><div class="row"><?php input('room','Mã / số phòng',$edit['MAPHONG']??'','number','required min="1" '.($edit?'readonly':'')); select_field('type','Thuộc loại phòng',$types,$edit['MALOAIPHONG']??$type); input('floor','Tầng',$edit['TANG']??1,'number','min="0" max="200" required'); input('price','Giá / đêm (VND)',(int)($edit['GIAPHONG']??500000),'number','min="1" step="1" required'); ?></div><div class="row"><?php $states=['Trống'=>'Trống','Chờ dọn'=>'Chờ dọn','Bảo trì'=>'Bảo trì']; if(($edit['TINHTRANG']??'')==='Có khách') $states['Có khách']='Có khách'; select_field('state','Tình trạng vận hành',$states,$edit['TINHTRANG']??'Trống'); select_field('active','Kinh doanh',[1=>'Mở bán',0=>'Ngừng bán'],$edit['ACTIVE']??1); select_field('image','Ảnh phòng',['phongdon.jpg'=>'Phòng đơn','phongdoi.jpg'=>'Phòng đôi','phongvip.jpg'=>'Phòng VIP'],trim($edit['HINH_ANH']??'phongdon.jpg')); ?></div><?php input('note','Ghi chú nội bộ',$edit['GHICHU']??'','text','maxlength="500"'); ?><button>Lưu phòng</button> <a href="ql_phong.php">Hủy sửa</a></form></details><?php endif; ?>
<form method="get" class="panel row"><?php select_field('type','Loại phòng',[0=>'Tất cả']+$types,$type); input('q','Tìm số phòng',$q); ?><label>&nbsp;<button>Lọc</button></label></form><div class="table-wrap"><table><thead><tr><th>Phòng</th><th>Loại phòng</th><th>Tầng</th><th>Giá / đêm</th><th>Vận hành</th><th>Mở bán</th><th>Thao tác</th></tr></thead><tbody><?php foreach($list as $r): ?><tr><td><strong><?=h($r['MAPHONG'])?></strong></td><td><?=h($r['TENLOAIPHONG'])?></td><td><?=$r['TANG']?></td><td><?=money($r['GIAPHONG'])?></td><td><span class="badge"><?=h($r['TINHTRANG'])?></span></td><td><?=$r['ACTIVE']?'Có':'Không'?></td><td><?php if($manager): ?><a href="ql_phong.php?edit=<?=$r['MAPHONG']?>">Sửa</a><?php endif; ?> <?php if($r['TINHTRANG']==='Chờ dọn'): ?><form class="inline" method="post"><?php token(); ?><input type="hidden" name="action" value="clean"><input type="hidden" name="room" value="<?=$r['MAPHONG']?>"><button>Đã dọn xong</button></form><?php endif; ?></td></tr><?php endforeach; if(!$list): ?><tr><td colspan="7" class="empty">Chưa có phòng trong loại này.</td></tr><?php endif; ?></tbody></table></div><p class="muted">Ngừng bán thay cho xóa để giữ lịch sử. Phòng trống theo khoảng ngày được tra ở trang Đặt phòng; trạng thái vận hành phản ánh tình trạng hiện tại.</p><?php endpage(); ?>
