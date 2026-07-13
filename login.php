<?php
session_start();
require_once 'config.php';
require_once 'permissions.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $error = 'Vui lòng nhập email/SĐT và mật khẩu.';
    } else {
        $userFound = false;

        $stmt = mysqli_prepare($conn, 'SELECT MAKH, HOTENKH, MATKHAU FROM KHACH_HANG WHERE EMAIL = ? OR SDT = ? LIMIT 1');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ss', $login, $login);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id, $name, $storedPassword);
            if (mysqli_stmt_fetch($stmt)) {
                if ($password === $storedPassword) {
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['role'] = ROLE_CUSTOMER;
                    header('Location: index.php');
                    exit();
                }
                $userFound = true;
            }
            mysqli_stmt_close($stmt);
        }

        if (!$userFound) {
            $stmt = mysqli_prepare($conn, 'SELECT MANV, HOTEN, MATKHAU, CHUCVU FROM nhan_vien WHERE SDT = ? LIMIT 1');
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 's', $login);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $id, $name, $staffPassword, $role);
                if (mysqli_stmt_fetch($stmt)) {
                    if ($password === $staffPassword) {
                        $_SESSION['user_id'] = $id;
                        $_SESSION['user_name'] = $name;
                        $_SESSION['role'] = $role;
                        header('Location: index.php');
                        exit();
                    }
                    $userFound = true;
                }
                mysqli_stmt_close($stmt);
            }
        }

        if ($error === '' && $userFound) {
            $error = 'Mật khẩu không đúng.';
        } elseif ($error === '') {
            $error = 'Tài khoản không tồn tại.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - N2H HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">Đăng nhập</h3>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <form method="post" action="login.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email hoặc SĐT</label>
                            <input type="text" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                    </form>
                    <p class="mt-3 text-center">Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
