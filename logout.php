<?php
require 'app/bootstrap.php';
if($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); exit('Dùng nút Đăng xuất.'); }
check_csrf(); $_SESSION=[]; session_destroy(); go('login.php');
