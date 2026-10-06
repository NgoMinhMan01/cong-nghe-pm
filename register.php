<?php
require 'app/bootstrap.php';
if(user()) go('index.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST') { check_csrf(); $error=attempt(function() {
    $name=text_input('name',100); $email=mb_strtolower(text_input('email',50)); $phone=text_input('phone',15); $pass=(string)($_POST['password']??'');
    if(!filter_var($email,FILTER_VALIDATE_EMAIL) || !preg_match('/^\+?[0-9]{9,14}$/',$phone)) fail('Email hoặc số điện thoại không hợp lệ.');
    if(strlen($pass)<8 || strlen($pass)>72 || $pass!==($_POST['confirm']??'')) fail('Mật khẩu cần 8–72 ký tự và xác nhận trùng khớp.');
    // Khóa tư vấn dùng chung với quản trị khách hàng để tránh đăng ký trùng đồng thời.
    if(!(int)row("SELECT GET_LOCK('n2h_customers',5) n")['n']) fail('Hệ thống bận, vui lòng thử lại.');
    try { if(row('SELECT MAKH FROM khach_hang WHERE EMAIL=? OR SDT=?',[$email,$phone])) fail('Email hoặc số điện thoại đã được sử dụng.');
    query('INSERT INTO khach_hang(HOTENKH,EMAIL,SDT,MATKHAU) VALUES(?,?,?,?)',[$name,$email,$phone,password_hash($pass,PASSWORD_DEFAULT)]);
    } finally { query("SELECT RELEASE_LOCK('n2h_customers')"); }
    flash('Đăng ký thành công. Bạn có thể đăng nhập.'); go('login.php');
}); }
page('Tạo tài khoản khách hàng'); ?><div class="panel narrow"><?php error_box($error); ?><form method="post"><?php token(); input('name','Họ và tên',$_POST['name']??'','text','required maxlength="100"'); input('email','Email',$_POST['email']??'','email','required maxlength="50"'); input('phone','Số điện thoại',$_POST['phone']??'','tel','required maxlength="15"'); input('password','Mật khẩu (từ 8 ký tự)','','password','required minlength="8" maxlength="72"'); input('confirm','Nhập lại mật khẩu','','password','required'); ?><button>Đăng ký</button></form></div><?php endpage(); ?>
