<?php
session_start();
require_once 'config.php';
require_once 'permissions.php';

require_permission(PERMISSION_VIEW_ROOMS);

$rooms = [];
$result = mysqli_query($conn, "SELECT PHONG.MAPHONG, LOAI_PHONG.TENLOAIPHONG, PHONG.GIAPHONG, PHONG.TINHTRANG FROM PHONG JOIN LOAI_PHONG ON PHONG.MALOAIPHONG = LOAI_PHONG.MALOAIPHONG");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xem danh sách phòng - N2H HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="border-bottom pb-2">Danh sách phòng</h2>
            <div class="table-responsive mt-3">
                <table class="table table-hover table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Mã Phòng</th>
                            <th>Loại Phòng</th>
                            <th>Giá Phòng</th>
                            <th>Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rooms)): ?>
                            <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($room['MAPHONG']); ?></td>
                                    <td><?php echo htmlspecialchars($room['TENLOAIPHONG']); ?></td>
                                    <td><?php echo number_format($room['GIAPHONG'], 0, ',', '.'); ?>đ</td>
                                    <td><?php echo htmlspecialchars($room['TINHTRANG']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Không có dữ liệu phòng.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>