<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$maPhong = isset($_GET['id_phong']) ? intval($_GET['id_phong']) : 0;
$room = null;
$customer = null;
$needsInfo = false;
if ($maPhong > 0) {
    $roomSql = "SELECT PHONG.MAPHONG, LOAI_PHONG.TENLOAIPHONG, PHONG.GIAPHONG, PHONG.TINHTRANG FROM PHONG JOIN LOAI_PHONG ON PHONG.MALOAIPHONG = LOAI_PHONG.MALOAIPHONG WHERE PHONG.MAPHONG = $maPhong LIMIT 1";
    $roomResult = mysqli_query($conn, $roomSql);
    if ($roomResult) {
        $room = mysqli_fetch_assoc($roomResult);
    }
}

$maKH = $_SESSION['user_id'];
$userSql = "SELECT MAKH, HOTENKH, SDT, CCCD, EMAIL FROM KHACH_HANG WHERE MAKH = $maKH LIMIT 1";
$userResult = mysqli_query($conn, $userSql);
if ($userResult) {
    $customer = mysqli_fetch_assoc($userResult);
    $needsInfo = empty($customer['SDT']) || empty($customer['CCCD']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $room) {
    $ngayDat = date('Y-m-d H:i:s');
    $ngayNhan = $_POST['ngayNhan'] ?? '';
    $ngayTra = $_POST['ngayTra'] ?? '';
    $sdt = trim($_POST['sdt'] ?? $customer['SDT']);
    $cccd = trim($_POST['cccd'] ?? $customer['CCCD']);

    if ($ngayNhan === '' || $ngayTra === '') {
        $message = 'Vui lòng chọn ngày nhận và ngày trả.';
    } elseif ($ngayNhan > $ngayTra) {
        $message = 'Ngày trả phải sau hoặc bằng ngày nhận.';
    } elseif ($room['TINHTRANG'] === 'Có khách') {
        $message = 'Phòng hiện đang có khách, vui lòng chọn phòng khác.';
    } elseif ($needsInfo && ($sdt === '' || $cccd === '')) {
        $message = 'Vui lòng cung cấp số điện thoại và CCCD để hoàn tất đặt phòng.';
    } else {
        if ($needsInfo) {
            $updateStmt = mysqli_prepare($conn, 'UPDATE KHACH_HANG SET SDT = ?, CCCD = ? WHERE MAKH = ?');
            if ($updateStmt) {
                mysqli_stmt_bind_param($updateStmt, 'ssi', $sdt, $cccd, $maKH);
                mysqli_stmt_execute($updateStmt);
                mysqli_stmt_close($updateStmt);
                $customer['SDT'] = $sdt;
                $customer['CCCD'] = $cccd;
                $needsInfo = false;
            }
        }

        $stmt = mysqli_prepare($conn, 'INSERT INTO DAT (MAKH, MAPHONG, NGAYDAT, NGAYNHAN, NGAYTRA) VALUES (?, ?, ?, ?, ?)');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'iisss', $maKH, $maPhong, $ngayDat, $ngayNhan, $ngayTra);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_query($conn, "UPDATE PHONG SET TINHTRANG = 'Có khách' WHERE MAPHONG = $maPhong");
                $message = 'Đặt phòng thành công!';
            } else {
                $message = 'Đặt phòng thất bại, vui lòng thử lại.';
            }
            mysqli_stmt_close($stmt);
        } else {
            $message = 'Lỗi hệ thống, không thể đặt phòng.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt phòng - N2H HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">Đặt phòng</h3>
                    <?php if ($message): ?>
                        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>

                    <?php if ($room): ?>
                        <div class="mb-4">
                            <h5><?php echo htmlspecialchars($room['TENLOAIPHONG']); ?> - Mã phòng <?php echo htmlspecialchars($room['MAPHONG']); ?></h5>
                            <p class="mb-1">Giá: <?php echo number_format($room['GIAPHONG'], 0, ',', '.'); ?>đ</p>
                            <p class="mb-0">Trạng thái: <?php echo htmlspecialchars($room['TINHTRANG']); ?></p>
                        </div>
                        <form method="post" action="datphong.php?id_phong=<?php echo $maPhong; ?>">
                            <div class="mb-3">
                                <label for="ngayNhan" class="form-label">Ngày nhận</label>
                                <input type="date" class="form-control" id="ngayNhan" name="ngayNhan" required>
                            </div>
                            <div class="mb-3">
                                <label for="ngayTra" class="form-label">Ngày trả</label>
                                <input type="date" class="form-control" id="ngayTra" name="ngayTra" required>
                            </div>
                            <?php if ($needsInfo): ?>
                                <div class="alert alert-warning">Chúng tôi cần thêm thông tin để hoàn tất đặt phòng.</div>
                                <div class="mb-3">
                                    <label for="sdt" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" id="sdt" name="sdt" value="<?php echo htmlspecialchars($customer['SDT'] ?? ''); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cccd" class="form-label">Số CCCD/CMND</label>
                                    <input type="text" class="form-control" id="cccd" name="cccd" value="<?php echo htmlspecialchars($customer['CCCD'] ?? ''); ?>" required>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary">Xác nhận đặt phòng</button>
                            <a href="index.php" class="btn btn-secondary ms-2">Quay lại</a>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">Không tìm thấy phòng. Vui lòng chọn lại phòng từ trang chủ.</div>
                        <a href="index.php" class="btn btn-secondary">Trang chủ</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
