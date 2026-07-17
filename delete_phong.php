<?php
session_start();
require_once 'config.php';
require_once 'permissions.php';

require_permission(PERMISSION_MANAGE_ROOMS);

$maPhong = isset($_GET['id_phong']) ? intval($_GET['id_phong']) : 0;
$message = '';

if (!$maPhong) {
    header('Location: ql_phong.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = mysqli_prepare($conn, 'DELETE FROM PHONG WHERE MAPHONG = ?');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $maPhong);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: ql_phong.php');
            exit();
        }
        mysqli_stmt_close($stmt);
    }
    $message = 'Xóa phòng thất bại. Vui lòng thử lại.';
}

$room = null;
$roomResult = mysqli_query($conn, "SELECT PHONG.MAPHONG, LOAI_PHONG.TENLOAIPHONG, PHONG.GIAPHONG, PHONG.TINHTRANG FROM PHONG JOIN LOAI_PHONG ON PHONG.MALOAIPHONG = LOAI_PHONG.MALOAIPHONG WHERE PHONG.MAPHONG = $maPhong LIMIT 1");
if ($roomResult) {
    $room = mysqli_fetch_assoc($roomResult);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xóa phòng - N2H HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">Xác nhận xóa phòng</h3>
                    <?php if ($message): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>
                    <?php if ($room): ?>
                        <p>Bạn có chắc chắn muốn xóa phòng sau không?</p>
                        <ul>
                            <li><strong>Mã phòng:</strong> <?php echo htmlspecialchars($room['MAPHONG']); ?></li>
                            <li><strong>Loại phòng:</strong> <?php echo htmlspecialchars($room['TENLOAIPHONG']); ?></li>
                            <li><strong>Giá phòng:</strong> <?php echo number_format($room['GIAPHONG'], 0, ',', '.'); ?>đ</li>
                            <li><strong>Trạng thái:</strong> <?php echo htmlspecialchars($room['TINHTRANG']); ?></li>
                        </ul>
                        <form method="post" action="delete_phong.php?id_phong=<?php echo $maPhong; ?>">
                            <button type="submit" class="btn btn-danger">Xóa ngay</button>
                            <a href="ql_phong.php" class="btn btn-secondary ms-2">Hủy</a>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">Không tìm thấy phòng để xóa.</div>
                        <a href="ql_phong.php" class="btn btn-secondary">Quay lại</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
