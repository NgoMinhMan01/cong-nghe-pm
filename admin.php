<?php
session_start();
require_once 'config.php';
require_once 'permissions.php';

require_permission(PERMISSION_ADMIN_DASHBOARD);

$counts = [
    'rooms' => 0,
    'bookings' => 0,
    'customers' => 0,
    'employees' => 0,
];

$result = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM PHONG');
if ($result) {
    $counts['rooms'] = mysqli_fetch_assoc($result)['total'];
}
$result = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM DAT');
if ($result) {
    $counts['bookings'] = mysqli_fetch_assoc($result)['total'];
}
$result = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM KHACH_HANG');
if ($result) {
    $counts['customers'] = mysqli_fetch_assoc($result)['total'];
}
$result = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM nhan_vien');
if ($result) {
    $counts['employees'] = mysqli_fetch_assoc($result)['total'];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - N2H HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Admin Dashboard</h2>
            <p class="text-muted">Chào mừng, <?php echo htmlspecialchars($_SESSION['user_name']); ?>. Đây là trang quản trị dành cho Admin.</p>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Tổng phòng</h5>
                    <p class="display-6 mb-0"><?php echo $counts['rooms']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Đặt phòng</h5>
                    <p class="display-6 mb-0"><?php echo $counts['bookings']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Khách hàng</h5>
                    <p class="display-6 mb-0"><?php echo $counts['customers']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Nhân viên</h5>
                    <p class="display-6 mb-0"><?php echo $counts['employees']; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-3">
            <a class="btn btn-primary w-100" href="ql_phong.php">Quản lý phòng</a>
        </div>
        <div class="col-md-3">
            <a class="btn btn-success w-100" href="ql_nhanvien.php">Quản lý nhân viên</a>
        </div>
        <div class="col-md-3">
            <a class="btn btn-secondary w-100" href="ql_hoadon.php">Hóa đơn</a>
        </div>
        <div class="col-md-3">
            <a class="btn btn-outline-primary w-100" href="index.php">Về trang chủ</a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-3">
            <a class="btn btn-info w-100 text-white" href="ql_khachhang.php">Quản lý khách hàng</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
