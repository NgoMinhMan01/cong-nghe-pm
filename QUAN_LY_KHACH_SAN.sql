-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 26, 2026 lúc 05:53 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `quan_ly_khach_san`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dat`
--

CREATE TABLE `dat` (
  `MAKH` int(11) NOT NULL,
  `MAPHONG` int(11) NOT NULL,
  `NGAYDAT` datetime DEFAULT NULL,
  `NGAYNHAN` datetime DEFAULT NULL,
  `NGAYTRA` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dich_vu`
--

CREATE TABLE `dich_vu` (
  `MADV` int(11) NOT NULL,
  `TENDV` varchar(100) DEFAULT NULL,
  `GIADV` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dich_vu`
--

INSERT INTO `dich_vu` (`MADV`, `TENDV`, `GIADV`) VALUES
(1, 'Giặt ủi', 50000.00),
(2, 'Ăn sáng tại phòng', 150000.00),
(3, 'Massage & Spa', 500000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_don`
--

CREATE TABLE `hoa_don` (
  `MAHD` int(11) NOT NULL,
  `MANV` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khach_hang`
--

CREATE TABLE `khach_hang` (
  `MAKH` int(11) NOT NULL,
  `HOTENKH` varchar(100) DEFAULT NULL,
  `CCCD` varchar(20) DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `MATKHAU` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khach_hang`
--

INSERT INTO `khach_hang` (`MAKH`, `HOTENKH`, `CCCD`, `SDT`, `EMAIL`, `MATKHAU`) VALUES
(201, 'Lê Văn Cường', '123456789', '0111111111', 'cuongle@gmail.com', '123'),
(202, 'Khách Hàng Mẫu', '012345678901', '0906666666', 'khachhang@example.com', 'Khach123');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_phong`
--

CREATE TABLE `loai_phong` (
  `MALOAIPHONG` int(11) NOT NULL,
  `TENLOAIPHONG` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loai_phong`
--

INSERT INTO `loai_phong` (`MALOAIPHONG`, `TENLOAIPHONG`) VALUES
(1, 'Phòng Đơn Standard'),
(2, 'Phòng Đôi Superior'),
(3, 'Phòng VIP Luxury');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhan_vien`
--

CREATE TABLE `nhan_vien` (
  `MANV` int(11) NOT NULL,
  `HOTEN` varchar(100) DEFAULT NULL,
  `CHUCVU` varchar(50) DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `MATKHAU` varchar(255) DEFAULT '123456'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_vien`
--

INSERT INTO `nhan_vien` (`MANV`, `HOTEN`, `CHUCVU`, `SDT`, `MATKHAU`) VALUES
(102, 'Admin Huy', 'Admin', '0999999999', 'admin123'),
(103, 'Lễ Tân Hùng', 'Lễ tân', '0888888888', 'letan123'),
(104, 'Nhân Viên Nguyện', 'Nhân viên', '0777777777', 'nhanvien123');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong`
--

CREATE TABLE `phong` (
  `MAPHONG` int(11) NOT NULL,
  `MALOAIPHONG` int(11) DEFAULT NULL,
  `GIAPHONG` decimal(15,2) DEFAULT NULL,
  `TINHTRANG` varchar(30) DEFAULT NULL,
  `HINH_ANH` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phong`
--

INSERT INTO `phong` (`MAPHONG`, `MALOAIPHONG`, `GIAPHONG`, `TINHTRANG`, `HINH_ANH`) VALUES
(301, 1, 500000.00, 'Trống', 'phongdon.jpg\r\n'),
(302, 2, 800000.00, 'Có khách', 'phongdoi.jpg'),
(303, 3, 2000000.00, 'Trống', 'phongvip.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sudungdichvu`
--

CREATE TABLE `sudungdichvu` (
  `MADV` int(11) NOT NULL,
  `MAKH` int(11) NOT NULL,
  `THOIGIANSUDUNG` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuoc_hoa_don`
--

CREATE TABLE `thuoc_hoa_don` (
  `MAHD` int(11) NOT NULL,
  `MAKH` int(11) NOT NULL,
  `NGAYLAP` datetime DEFAULT NULL,
  `TONGTIEN` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `dat`
--
ALTER TABLE `dat`
  ADD PRIMARY KEY (`MAKH`,`MAPHONG`),
  ADD KEY `MAPHONG` (`MAPHONG`);

--
-- Chỉ mục cho bảng `dich_vu`
--
ALTER TABLE `dich_vu`
  ADD PRIMARY KEY (`MADV`);

--
-- Chỉ mục cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD PRIMARY KEY (`MAHD`),
  ADD KEY `MANV` (`MANV`);

--
-- Chỉ mục cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD PRIMARY KEY (`MAKH`);

--
-- Chỉ mục cho bảng `loai_phong`
--
ALTER TABLE `loai_phong`
  ADD PRIMARY KEY (`MALOAIPHONG`);

--
-- Chỉ mục cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD PRIMARY KEY (`MANV`);

--
-- Chỉ mục cho bảng `phong`
--
ALTER TABLE `phong`
  ADD PRIMARY KEY (`MAPHONG`),
  ADD KEY `MALOAIPHONG` (`MALOAIPHONG`);

--
-- Chỉ mục cho bảng `sudungdichvu`
--
ALTER TABLE `sudungdichvu`
  ADD PRIMARY KEY (`MADV`,`MAKH`,`THOIGIANSUDUNG`),
  ADD KEY `MAKH` (`MAKH`);

--
-- Chỉ mục cho bảng `thuoc_hoa_don`
--
ALTER TABLE `thuoc_hoa_don`
  ADD PRIMARY KEY (`MAHD`,`MAKH`),
  ADD KEY `MAKH` (`MAKH`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `MAKH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `dat`
--
ALTER TABLE `dat`
  ADD CONSTRAINT `dat_ibfk_1` FOREIGN KEY (`MAKH`) REFERENCES `khach_hang` (`MAKH`),
  ADD CONSTRAINT `dat_ibfk_2` FOREIGN KEY (`MAPHONG`) REFERENCES `phong` (`MAPHONG`);

--
-- Các ràng buộc cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD CONSTRAINT `hoa_don_ibfk_1` FOREIGN KEY (`MANV`) REFERENCES `nhan_vien` (`MANV`);

--
-- Các ràng buộc cho bảng `phong`
--
ALTER TABLE `phong`
  ADD CONSTRAINT `phong_ibfk_1` FOREIGN KEY (`MALOAIPHONG`) REFERENCES `loai_phong` (`MALOAIPHONG`);

--
-- Các ràng buộc cho bảng `sudungdichvu`
--
ALTER TABLE `sudungdichvu`
  ADD CONSTRAINT `sudungdichvu_ibfk_1` FOREIGN KEY (`MADV`) REFERENCES `dich_vu` (`MADV`),
  ADD CONSTRAINT `sudungdichvu_ibfk_2` FOREIGN KEY (`MAKH`) REFERENCES `khach_hang` (`MAKH`);

--
-- Các ràng buộc cho bảng `thuoc_hoa_don`
--
ALTER TABLE `thuoc_hoa_don`
  ADD CONSTRAINT `thuoc_hoa_don_ibfk_1` FOREIGN KEY (`MAHD`) REFERENCES `hoa_don` (`MAHD`),
  ADD CONSTRAINT `thuoc_hoa_don_ibfk_2` FOREIGN KEY (`MAKH`) REFERENCES `khach_hang` (`MAKH`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
