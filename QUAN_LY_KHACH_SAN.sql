-- NHM HOTEL v2: cài mới hoặc tiếp tục lần nhập v2 bị gián đoạn. 
-- Không DROP/TRUNCATE bảng. Không thay thế dữ liệu trùng khóa chính.
-- Không dùng để nâng cấp cấu trúc v1: trường hợp đó chạy tools/upgrade.php.
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=1;
CREATE TABLE IF NOT EXISTS `audit_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `actor` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `target` varchar(100) DEFAULT NULL,
  `note` text NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `dich_vu` (
  `MADV` int(11) NOT NULL AUTO_INCREMENT,
  `TENDV` varchar(100) DEFAULT NULL,
  `GIADV` decimal(15,2) DEFAULT NULL,
  `ACTIVE` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`MADV`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `dich_vu` (`MADV`,`TENDV`,`GIADV`,`ACTIVE`) VALUES ('1','Giặt ủi','50000.00','1') ON DUPLICATE KEY UPDATE `MADV`=`MADV`;
INSERT INTO `dich_vu` (`MADV`,`TENDV`,`GIADV`,`ACTIVE`) VALUES ('2','Ăn sáng tại phòng','150000.00','1') ON DUPLICATE KEY UPDATE `MADV`=`MADV`;
INSERT INTO `dich_vu` (`MADV`,`TENDV`,`GIADV`,`ACTIVE`) VALUES ('3','Massage & Spa','500000.00','1') ON DUPLICATE KEY UPDATE `MADV`=`MADV`;
CREATE TABLE IF NOT EXISTS `khach_hang` (
  `MAKH` int(11) NOT NULL AUTO_INCREMENT,
  `HOTENKH` varchar(100) DEFAULT NULL,
  `CCCD` varchar(20) DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `MATKHAU` varchar(255) NOT NULL,
  `ACTIVE` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`MAKH`)
) ENGINE=InnoDB AUTO_INCREMENT=203 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `khach_hang` (`MAKH`,`HOTENKH`,`CCCD`,`SDT`,`EMAIL`,`MATKHAU`,`ACTIVE`) VALUES ('201','Khách mẫu 01',NULL,'0900000001','khach01@example.com','$2y$10$xQy5Sxp.zjPGz5fS.jhQBe.w9yY.8vR7gcWXBHHsfV/x0hxgKyP76','1') ON DUPLICATE KEY UPDATE `MAKH`=`MAKH`;
INSERT INTO `khach_hang` (`MAKH`,`HOTENKH`,`CCCD`,`SDT`,`EMAIL`,`MATKHAU`,`ACTIVE`) VALUES ('202','Khách Hàng Mẫu',NULL,'0906666666','khachhang@example.com','$2y$10$93a8nPujTa.mCXwAEUL9ru1rxV8V6Lm1FTW8thh7sW26CLsx6dBLS','1') ON DUPLICATE KEY UPDATE `MAKH`=`MAKH`;
CREATE TABLE IF NOT EXISTS `loai_phong` (
  `MALOAIPHONG` int(11) NOT NULL AUTO_INCREMENT,
  `TENLOAIPHONG` varchar(50) NOT NULL,
  `MOTA` text NULL,
  `SUCCHUA` int(11) NOT NULL DEFAULT 2,
  `DIENTICH` int(11) NOT NULL DEFAULT 25,
  `TIENNGHI` varchar(500) NOT NULL DEFAULT 'Wi-Fi, điều hòa, phòng tắm riêng',
  `ACTIVE` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`MALOAIPHONG`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `loai_phong` (`MALOAIPHONG`,`TENLOAIPHONG`,`MOTA`,`SUCCHUA`,`DIENTICH`,`TIENNGHI`,`ACTIVE`) VALUES ('1','Phòng Đơn Standard','Không gian gọn gàng cho chuyến công tác hoặc du lịch một mình.','1','22','Wi-Fi, điều hòa, phòng tắm riêng','1') ON DUPLICATE KEY UPDATE `MALOAIPHONG`=`MALOAIPHONG`;
INSERT INTO `loai_phong` (`MALOAIPHONG`,`TENLOAIPHONG`,`MOTA`,`SUCCHUA`,`DIENTICH`,`TIENNGHI`,`ACTIVE`) VALUES ('2','Phòng Đôi Superior','Phòng đôi thoải mái, phù hợp cho hai khách.','2','32','Wi-Fi, điều hòa, phòng tắm riêng','1') ON DUPLICATE KEY UPDATE `MALOAIPHONG`=`MALOAIPHONG`;
INSERT INTO `loai_phong` (`MALOAIPHONG`,`TENLOAIPHONG`,`MOTA`,`SUCCHUA`,`DIENTICH`,`TIENNGHI`,`ACTIVE`) VALUES ('3','Phòng VIP Luxury','Không gian rộng rãi, khu vực tiếp khách và tiện nghi cao cấp.','4','55','Wi-Fi, điều hòa, phòng tắm riêng','1') ON DUPLICATE KEY UPDATE `MALOAIPHONG`=`MALOAIPHONG`;
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `login_key` char(64) NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`login_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `nhan_vien` (
  `MANV` int(11) NOT NULL AUTO_INCREMENT,
  `HOTEN` varchar(100) DEFAULT NULL,
  `CHUCVU` varchar(50) DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `MATKHAU` varchar(255) DEFAULT '123456',
  `ACTIVE` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`MANV`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `nhan_vien` (`MANV`,`HOTEN`,`CHUCVU`,`SDT`,`MATKHAU`,`ACTIVE`) VALUES ('102','Admin','Admin','01','$2y$10$BHXsi56BNywzkHqm8pNXr..tTXE/i9hYUCWbW0rR8hHBp.anzuuhi','1') ON DUPLICATE KEY UPDATE `MANV`=`MANV`;
INSERT INTO `nhan_vien` (`MANV`,`HOTEN`,`CHUCVU`,`SDT`,`MATKHAU`,`ACTIVE`) VALUES ('103','Lễ Tân','Lễ tân','02','$2y$10$2y7yKSQn6Z5zki0oSflx6.DkORVV7spozldNZEBfZgHqLYaeo9e8K','1') ON DUPLICATE KEY UPDATE `MANV`=`MANV`;
INSERT INTO `nhan_vien` (`MANV`,`HOTEN`,`CHUCVU`,`SDT`,`MATKHAU`,`ACTIVE`) VALUES ('104','Nhân Viên','Nhân viên','03','$2y$10$j1rYfNoGYc4IBscxExylsONvmnlLK/94jBVAbwzFsAkVBLfOTvzmC','1') ON DUPLICATE KEY UPDATE `MANV`=`MANV`;
CREATE TABLE IF NOT EXISTS `hoa_don` (
  `MAHD` int(11) NOT NULL,
  `MANV` int(11) DEFAULT NULL,
  PRIMARY KEY (`MAHD`),
  KEY `MANV` (`MANV`),
  CONSTRAINT `hoa_don_ibfk_1` FOREIGN KEY (`MANV`) REFERENCES `nhan_vien` (`MANV`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `phong` (
  `MAPHONG` int(11) NOT NULL,
  `MALOAIPHONG` int(11) DEFAULT NULL,
  `GIAPHONG` decimal(15,2) DEFAULT NULL,
  `TINHTRANG` varchar(30) DEFAULT NULL,
  `HINH_ANH` varchar(255) DEFAULT NULL,
  `TANG` int(11) NOT NULL DEFAULT 1,
  `GHICHU` varchar(500) DEFAULT NULL,
  `ACTIVE` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`MAPHONG`),
  KEY `MALOAIPHONG` (`MALOAIPHONG`),
  CONSTRAINT `phong_ibfk_1` FOREIGN KEY (`MALOAIPHONG`) REFERENCES `loai_phong` (`MALOAIPHONG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `phong` (`MAPHONG`,`MALOAIPHONG`,`GIAPHONG`,`TINHTRANG`,`HINH_ANH`,`TANG`,`GHICHU`,`ACTIVE`) VALUES ('301','1','500000.00','Trống','phongdon.jpg','3',NULL,'1') ON DUPLICATE KEY UPDATE `MAPHONG`=`MAPHONG`;
INSERT INTO `phong` (`MAPHONG`,`MALOAIPHONG`,`GIAPHONG`,`TINHTRANG`,`HINH_ANH`,`TANG`,`GHICHU`,`ACTIVE`) VALUES ('302','2','800000.00','Trống','phongdoi.jpg','3',NULL,'1') ON DUPLICATE KEY UPDATE `MAPHONG`=`MAPHONG`;
INSERT INTO `phong` (`MAPHONG`,`MALOAIPHONG`,`GIAPHONG`,`TINHTRANG`,`HINH_ANH`,`TANG`,`GHICHU`,`ACTIVE`) VALUES ('303','3','2000000.00','Trống','phongvip.jpg','3',NULL,'1') ON DUPLICATE KEY UPDATE `MAPHONG`=`MAPHONG`;
INSERT INTO `phong` (`MAPHONG`,`MALOAIPHONG`,`GIAPHONG`,`TINHTRANG`,`HINH_ANH`,`TANG`,`GHICHU`,`ACTIVE`) VALUES ('304','1','500000.00','Trống','phongdon.jpg','3',NULL,'1') ON DUPLICATE KEY UPDATE `MAPHONG`=`MAPHONG`;
INSERT INTO `phong` (`MAPHONG`,`MALOAIPHONG`,`GIAPHONG`,`TINHTRANG`,`HINH_ANH`,`TANG`,`GHICHU`,`ACTIVE`) VALUES ('305','1','500000.00','Trống','phongdon.jpg','3',NULL,'1') ON DUPLICATE KEY UPDATE `MAPHONG`=`MAPHONG`;
INSERT INTO `phong` (`MAPHONG`,`MALOAIPHONG`,`GIAPHONG`,`TINHTRANG`,`HINH_ANH`,`TANG`,`GHICHU`,`ACTIVE`) VALUES ('306','1','550000.00','Trống','phongdon.jpg','3',NULL,'1') ON DUPLICATE KEY UPDATE `MAPHONG`=`MAPHONG`;
INSERT INTO `phong` (`MAPHONG`,`MALOAIPHONG`,`GIAPHONG`,`TINHTRANG`,`HINH_ANH`,`TANG`,`GHICHU`,`ACTIVE`) VALUES ('401','2','800000.00','Trống','phongdoi.jpg','4',NULL,'1') ON DUPLICATE KEY UPDATE `MAPHONG`=`MAPHONG`;
INSERT INTO `phong` (`MAPHONG`,`MALOAIPHONG`,`GIAPHONG`,`TINHTRANG`,`HINH_ANH`,`TANG`,`GHICHU`,`ACTIVE`) VALUES ('402','2','800000.00','Trống','phongdoi.jpg','4',NULL,'1') ON DUPLICATE KEY UPDATE `MAPHONG`=`MAPHONG`;
INSERT INTO `phong` (`MAPHONG`,`MALOAIPHONG`,`GIAPHONG`,`TINHTRANG`,`HINH_ANH`,`TANG`,`GHICHU`,`ACTIVE`) VALUES ('403','2','900000.00','Trống','phongdoi.jpg','4',NULL,'1') ON DUPLICATE KEY UPDATE `MAPHONG`=`MAPHONG`;
INSERT INTO `phong` (`MAPHONG`,`MALOAIPHONG`,`GIAPHONG`,`TINHTRANG`,`HINH_ANH`,`TANG`,`GHICHU`,`ACTIVE`) VALUES ('501','3','2000000.00','Trống','phongvip.jpg','5',NULL,'1') ON DUPLICATE KEY UPDATE `MAPHONG`=`MAPHONG`;
INSERT INTO `phong` (`MAPHONG`,`MALOAIPHONG`,`GIAPHONG`,`TINHTRANG`,`HINH_ANH`,`TANG`,`GHICHU`,`ACTIVE`) VALUES ('502','3','2200000.00','Trống','phongvip.jpg','5',NULL,'1') ON DUPLICATE KEY UPDATE `MAPHONG`=`MAPHONG`;
INSERT INTO `phong` (`MAPHONG`,`MALOAIPHONG`,`GIAPHONG`,`TINHTRANG`,`HINH_ANH`,`TANG`,`GHICHU`,`ACTIVE`) VALUES ('503','3','2500000.00','Trống','phongvip.jpg','5',NULL,'1') ON DUPLICATE KEY UPDATE `MAPHONG`=`MAPHONG`;
CREATE TABLE IF NOT EXISTS `sudungdichvu` (
  `MADV` int(11) NOT NULL,
  `MAKH` int(11) NOT NULL,
  `THOIGIANSUDUNG` datetime NOT NULL,
  PRIMARY KEY (`MADV`,`MAKH`,`THOIGIANSUDUNG`),
  KEY `MAKH` (`MAKH`),
  CONSTRAINT `sudungdichvu_ibfk_1` FOREIGN KEY (`MADV`) REFERENCES `dich_vu` (`MADV`),
  CONSTRAINT `sudungdichvu_ibfk_2` FOREIGN KEY (`MAKH`) REFERENCES `khach_hang` (`MAKH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `dat` (
  `MAKH` int(11) NOT NULL,
  `MAPHONG` int(11) NOT NULL,
  `NGAYDAT` datetime DEFAULT NULL,
  `NGAYNHAN` datetime DEFAULT NULL,
  `NGAYTRA` datetime DEFAULT NULL,
  `SONGUOI` int(11) NOT NULL DEFAULT 1,
  `YEUCAUDACBIET` varchar(500) DEFAULT NULL,
  `TRANGTHAI` varchar(30) NOT NULL DEFAULT 'Chờ nhận',
  `NGAYNHANPHUCTE` datetime DEFAULT NULL,
  `THANHTOAN` varchar(30) NOT NULL DEFAULT 'Chưa thanh toán',
  `PHUONGTHUCTT` varchar(30) DEFAULT NULL,
  `NGAYTHANHTOAN` datetime DEFAULT NULL,
  `MADAT` bigint(20) NOT NULL AUTO_INCREMENT,
  `DONGIA` decimal(15,2) DEFAULT NULL,
  `NGAYTRATHUCTE` datetime DEFAULT NULL,
  `LYDOHUY` varchar(500) DEFAULT NULL,
  `ROOM_LABEL` varchar(50) DEFAULT NULL,
  `TYPE_LABEL` varchar(100) DEFAULT NULL,
  `LEGACY_REVIEW` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`MADAT`),
  UNIQUE KEY `MADAT` (`MADAT`),
  KEY `MAPHONG` (`MAPHONG`),
  KEY `idx_customer` (`MAKH`),
  KEY `idx_availability` (`MAPHONG`,`TRANGTHAI`,`NGAYNHAN`,`NGAYTRA`),
  CONSTRAINT `dat_ibfk_1` FOREIGN KEY (`MAKH`) REFERENCES `khach_hang` (`MAKH`),
  CONSTRAINT `dat_ibfk_2` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `thuoc_hoa_don` (
  `MAHD` int(11) NOT NULL,
  `MAKH` int(11) NOT NULL,
  `NGAYLAP` datetime DEFAULT NULL,
  `TONGTIEN` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`MAHD`,`MAKH`),
  KEY `MAKH` (`MAKH`),
  CONSTRAINT `thuoc_hoa_don_ibfk_1` FOREIGN KEY (`MAHD`) REFERENCES `hoa_don` (`MAHD`),
  CONSTRAINT `thuoc_hoa_don_ibfk_2` FOREIGN KEY (`MAKH`) REFERENCES `khach_hang` (`MAKH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `booking_services` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) NOT NULL,
  `service_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `booking_services_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `dat` (`MADAT`),
  CONSTRAINT `booking_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `dich_vu` (`MADV`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `room_name` varchar(150) DEFAULT NULL,
  `nights` int(11) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `room_total` decimal(15,2) NOT NULL,
  `service_total` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_id` (`booking_id`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `dat` (`MADAT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `method` varchar(30) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `actor` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `dat` (`MADAT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `schema_version` (
  `version` int(11) NOT NULL,
  `applied_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `schema_version` (`version`,`applied_at`) VALUES ('2','2026-10-04 14:49:41') ON DUPLICATE KEY UPDATE `version`=`version`;
