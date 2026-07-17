<?php
session_start();
require_once 'config.php';
require_once 'permissions.php';

require_permission(PERMISSION_VIEW_INVOICES);

$invoices = [];
$sql = "SELECT DAT.MADT, KHACH_HANG.HOTENKH, PHONG.MAPHONG, LOAI_PHONG.TENLOAIPHONG, DAT.NGAYDAT, DAT.NGAYNHAN, DAT.NGAYTRA \
        FROM DAT \
        JOIN KHACH_HANG ON DAT.MAKH = KHACH_HANG.MAKH \
        JOIN PHONG ON DAT.MAPHONG = PHONG.MAPHONG \
        JOIN LOAI_PHONG ON PHONG.MALOAIPHONG = LOAI_PHONG.MALOAIPHONG \
        ORDER BY DAT.NGAYDAT DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $invoices[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn - N2H HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h3 class="card-title mb-1">Quản lý hóa đơn</h3>
                            <p class="text-muted mb-0">Danh sách đặt phòng và hóa đơn.</p>
                        </div>
                        <a href="index.php" class="btn btn-outline-primary">Trang chủ</a>
                    </div>
                    <?php if (!empty($invoices)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Mã đặt</th>
                                        <th>Khách hàng</th>
                                        <th>Phòng</th>
                                        <th>Loại phòng</th>
                                        <th>Ngày đặt</th>
                                        <th>Ngày nhận</th>
                                        <th>Ngày trả</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($invoices as $invoice): ?>
                                        <?php
                                        $today = date('Y-m-d');
                                        $status = 'Chưa nhận';
                                        if ($invoice['NGAYNHAN'] <= $today && $invoice['NGAYTRA'] >= $today) {
                                            $status = 'Đang ở';
                                        } elseif ($invoice['NGAYTRA'] < $today) {
                                            $status = 'Đã trả';
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($invoice['MADT']); ?></td>
                                            <td><?php echo htmlspecialchars($invoice['HOTENKH']); ?></td>
                                            <td><?php echo htmlspecialchars($invoice['MAPHONG']); ?></td>
                                            <td><?php echo htmlspecialchars($invoice['TENLOAIPHONG']); ?></td>
                                            <td><?php echo htmlspecialchars($invoice['NGAYDAT']); ?></td>
                                            <td><?php echo htmlspecialchars($invoice['NGAYNHAN']); ?></td>
                                            <td><?php echo htmlspecialchars($invoice['NGAYTRA']); ?></td>
                                            <td><?php echo htmlspecialchars($status); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">Không có hóa đơn nào để hiển thị.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
