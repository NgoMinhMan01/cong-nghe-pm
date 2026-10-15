<?php require 'app/bootstrap.php'; require_roles('Admin','Lễ tân'); go('ql_phong.php?edit='.(int)($_GET['id_phong']??0));
