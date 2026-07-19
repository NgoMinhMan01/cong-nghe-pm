<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/permissions.php';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="fa-solid fa-hotel"></i> N2H HOTEL</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>

                <?php if (has_role(ROLE_ADMIN)) : ?>
                    <li class="nav-item"><a class="nav-link" href="ql_phong.php">Quản lý phòng</a></li>
                    <li class="nav-item"><a class="nav-link" href="ql_hoadon.php">Hóa đơn</a></li>
                <?php elseif (has_role(ROLE_LETAN)) : ?>
                    <li class="nav-item"><a class="nav-link" href="view_phong.php">Xem phòng</a></li>
                    <li class="nav-item"><a class="nav-link" href="ql_hoadon.php">Xem hóa đơn</a></li>
                    <li class="nav-item"><a class="nav-link" href="ql_khachhang.php">Khách hàng</a></li>
                <?php elseif (has_role(ROLE_STAFF)) : ?>
                    <li class="nav-item"><a class="nav-link" href="view_phong.php">Xem phòng</a></li>
                    <li class="nav-item"><a class="nav-link" href="ql_hoadon.php">Xem hóa đơn</a></li>
                <?php endif; ?>

                <?php if (has_role(ROLE_ADMIN)) : ?>
                    <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user_name'])) : ?>
                    <li class="nav-item">
                        <span class="nav-link text-white">Chào, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Đăng xuất</a></li>
                <?php else : ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Đăng nhập</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-light text-primary ms-lg-2" href="register.php">Đăng ký</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
