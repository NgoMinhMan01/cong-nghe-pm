<?php
session_start();
include 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Vui lòng nhập email và mật khẩu.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT MAKH FROM KHACH_HANG WHERE EMAIL = ? LIMIT 1');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $error = 'Email này đã được đăng ký.';
            }
            mysqli_stmt_close($stmt);
        }

        if ($error === '') {
            $stmt = mysqli_prepare($conn, 'INSERT INTO KHACH_HANG (HOTENKH, EMAIL, MATKHAU) VALUES (?, ?, ?)');
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'sss', $email, $email, $password);
                if (mysqli_stmt_execute($stmt)) {
                    header('Location: login.php');
                    exit();
                }
                mysqli_stmt_close($stmt);
            }
            if ($error === '') {
                $error = 'Đăng ký thất bại, vui lòng thử lại.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký - N2H HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">Đăng ký</h3>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <form method="post" action="register.php">
                        <p class="text-muted">Chỉ cần email và mật khẩu. Thông tin đặt phòng sẽ được thu thập khi bạn đặt phòng.</p>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                    </form>
                    <p class="mt-3 text-center">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
