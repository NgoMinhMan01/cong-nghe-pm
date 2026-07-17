<?php
session_start();
require_once 'config.php';
require_once 'permissions.php';

require_permission(PERMISSION_MANAGE_CUSTOMERS);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_customer'])) {
    $hoten = trim($_POST['hoten'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $sdt = trim($_POST['sdt'] ?? '');
    $cccd = trim($_POST['cccd'] ?? '');

    if ($hoten === '' || $email === '' || $password === '') {
        $message = 'Vui lòng nhập đầy đủ họ tên, email và mật khẩu.';
    } else {
        $hoten = mysqli_real_escape_string($conn, $hoten);
        $email = mysqli_real_escape_string($conn, $email);
        $password = mysqli_real_escape_string($conn, $password);
        $sdt = mysqli_real_escape_string($conn, $sdt);
        $cccd = mysqli_real_escape_string($conn, $cccd);
        $sql = "INSERT INTO KHACH_HANG (HOTENKH, EMAIL, MATKHAU, SDT, CCCD) VALUES ('$hoten', '$email', '$password', '$sdt', '$cccd')";
        if (mysqli_query($conn, $sql)) {
            header('Location: ql_khachhang.php?success=add');
            exit();
        } else {
            $message = 'Lỗi thêm khách hàng: ' . mysqli_error($conn);
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $deleteSql = "DELETE FROM KHACH_HANG WHERE MAKH = $id";
        if (mysqli_query($conn, $deleteSql)) {
            header('Location: ql_khachhang.php?success=delete');
            exit();
        } else {
            $message = 'Lỗi xóa khách hàng: ' . mysqli_error($conn);
        }
    }
}

if (isset($_GET['success'])) {
    if ($_GET['success'] === 'add') {
        $message = 'Thêm khách hàng thành công.';
    } elseif ($_GET['success'] === 'delete') {
        $message = 'Xóa khách hàng thành công.';
    }
}

$customers = [];
$result = mysqli_query($conn, 'SELECT MAKH, HOTENKH, EMAIL, SDT, CCCD FROM KHACH_HANG');
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $customers[] = $row;
    }
}

$bookings = [];
$bookingSql = "SELECT KHACH_HANG.MAKH, KHACH_HANG.HOTENKH, PHONG.MAPHONG, LOAI_PHONG.TENLOAIPHONG, DAT.NGAYDAT, DAT.NGAYNHAN, DAT.NGAYTRA
        FROM DAT
        JOIN KHACH_HANG ON DAT.MAKH = KHACH_HANG.MAKH
        JOIN PHONG ON DAT.MAPHONG = PHONG.MAPHONG
        JOIN LOAI_PHONG ON PHONG.MALOAIPHONG = LOAI_PHONG.MALOAIPHONG
        ORDER BY DAT.NGAYNHAN DESC";
$bookingResult = mysqli_query($conn, $bookingSql);
if ($bookingResult) {
    while ($row = mysqli_fetch_assoc($bookingResult)) {
        $bookings[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý khách hàng - N2H HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Quản lý khách hàng</h2>
            <p class="text-muted">Danh sách khách hàng đăng ký và thông tin liên hệ.</p>
            <?php if ($message): ?>
                <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Thêm khách hàng mới</h5>
                    <form method="post" class="row g-3">
                        <div class="col-md-4">
                            <label for="hoten" class="form-label">Họ tên</label>
                            <input type="text" id="hoten" name="hoten" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="text" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="sdt" class="form-label">SĐT</label>
                            <input type="text" id="sdt" name="sdt" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="cccd" class="form-label">CCCD</label>
                            <input type="text" id="cccd" name="cccd" class="form-control">
                        </div>
                        <div class="col-12">
                            <button type="submit" name="add_customer" class="btn btn-success">Thêm khách hàng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>MAKH</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>SĐT</th>
                            <th>CCCD</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customers)): ?>
                            <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($customer['MAKH']); ?></td>
                                    <td><?php echo htmlspecialchars($customer['HOTENKH']); ?></td>
                                    <td><?php echo htmlspecialchars($customer['EMAIL']); ?></td>
                                    <td><?php echo htmlspecialchars($customer['SDT']); ?></td>
                                    <td><?php echo htmlspecialchars($customer['CCCD']); ?></td>
                                    <td>
                                        <a href="ql_khachhang.php?action=delete&id=<?php echo intval($customer['MAKH']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa khách hàng này?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Chưa có khách hàng.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <h4>Khách hàng đang sử dụng dịch vụ / phòng</h4>
            <p class="text-muted">Danh sách khách hàng, phòng và loại phòng theo đặt phòng hiện tại.</p>
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th>MAKH</th>
                            <th>Khách hàng</th>
                            <th>Phòng</th>
                            <th>Loại phòng</th>
                            <th>Ngày nhận</th>
                            <th>Ngày trả</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bookings)): ?>
                            <?php foreach ($bookings as $booking): ?>
                                <?php
                                    $today = date('Y-m-d');
                                    if ($booking['NGAYNHAN'] <= $today && $booking['NGAYTRA'] >= $today) {
                                        $status = 'Đang ở';
                                    } elseif ($booking['NGAYNHAN'] > $today) {
                                        $status = 'Sắp nhận';
                                    } else {
                                        $status = 'Đã trả';
                                    }
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($booking['MAKH']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['HOTENKH']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['MAPHONG']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['TENLOAIPHONG']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['NGAYNHAN']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['NGAYTRA']); ?></td>
                                    <td><?php echo htmlspecialchars($status); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Chưa có thông tin đặt phòng.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <a href="admin.php" class="btn btn-secondary">Quay lại Dashboard</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
