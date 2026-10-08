<?php
$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? '');
$activeNav = match ($currentPage) {
    'index.php', 'datphong.php' => 'rooms-home',
    'admin.php' => 'overview',
    'ql_datphong.php', 'chitiet_datphong.php' => 'bookings',
    'lich_phong.php' => 'calendar',
    'ql_phong.php', 'edit_phong.php' => 'rooms',
    'ql_dichvu.php' => 'services',
    'ql_khachhang.php' => 'customers',
    'ql_hoadon.php', 'hoadon.php' => 'invoices',
    'view_phong.php' => 'housekeeping',
    'ql_nhanvien.php' => 'employees',
    'nhatky.php' => 'audit',
    'don_cua_toi.php' => 'my-bookings',
    'taikhoan.php' => 'account',
    'login.php' => 'login',
    'register.php' => 'register',
    default => '',
};
$navLink = static fn(string $key): string => $activeNav === $key ? ' class="is-active" aria-current="page"' : '';
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?=h($title)?> · NHM HOTEL</title><link rel="stylesheet" href="assets/style.css"><link rel="stylesheet" href="assets/buttons.css"></head><body>
<header><a class="brand" href="index.php">NHM<span> HOTEL</span></a><nav><a<?=$navLink('rooms-home')?> href="index.php">Khám phá phòng</a><?php if(user()): ?>
<?php if(staff()): ?><a<?=$navLink('overview')?> href="admin.php">Tổng quan</a><a<?=$navLink('bookings')?> href="ql_datphong.php">Đặt phòng</a><a<?=$navLink('calendar')?> href="lich_phong.php">Lịch phòng</a><a<?=$navLink('rooms')?> href="ql_phong.php">Phòng & loại phòng</a><a<?=$navLink('services')?> href="ql_dichvu.php">Dịch vụ</a><a<?=$navLink('customers')?> href="ql_khachhang.php">Khách hàng</a><a<?=$navLink('invoices')?> href="ql_hoadon.php">Hóa đơn</a><?php endif; ?>
<?php if(is_role('Nhân viên')): ?><a<?=$navLink('housekeeping')?> href="view_phong.php">Buồng phòng</a><?php endif; ?>
<?php if(is_role('Admin')): ?><a<?=$navLink('employees')?> href="ql_nhanvien.php">Nhân viên</a><a<?=$navLink('audit')?> href="nhatky.php">Nhật ký</a><?php endif; ?>
<?php if(is_role('KhachHang')): ?><a<?=$navLink('my-bookings')?> href="don_cua_toi.php">Đơn của tôi</a><?php endif; ?>
<a<?=$navLink('account')?> href="taikhoan.php"><?=h(user()['name'])?></a><form method="post" action="logout.php" class="inline"><?php token(); ?><button class="navbutton">Đăng xuất</button></form>
<?php else: ?><a<?=$navLink('login')?> href="login.php">Đăng nhập</a><a<?=$navLink('register')?> href="register.php">Đăng ký</a><?php endif; ?></nav></header><main>
<?php if(!empty($_SESSION['flash'])): ?><div class="alert"><?=h($_SESSION['flash'])?></div><?php unset($_SESSION['flash']); endif; ?>
<?php if(basename($_SERVER['SCRIPT_NAME'])!=='index.php'): ?><div class="pagehead"><div class="eyebrow">NHM HOTEL</div><h1><?=h($title)?></h1></div><?php endif; ?>
