# Sửa lỗi nhập SQL ở lần cài đặt vừa rồi

Ảnh đã gửi báo `#1050 - Table 'audit_log' already exists`. Đây là lỗi nhập lặp vào CSDL đã có một phần bảng v2. Không cần xóa CSDL hoặc đổi tên CSDL cho tình huống này.

## Nhập tiếp vào CSDL đang chỉ có bảng audit_log

1. Giải nén gói đã sửa vào thư mục riêng để lấy `QUAN_LY_KHACH_SAN.sql` mới.
2. Mở phpMyAdmin `http://localhost:8082`.
3. Chọn CSDL `webkhachsan` bên trái, bấm Nhập.
4. Chọn `QUAN_LY_KHACH_SAN.sql` **trong gói đã sửa này**, bấm Thực hiện.
5. Bấm Cấu trúc hoặc tải lại trang. Khi hoàn tất phải có 15 bảng, trong đó có `loai_phong`, `phong`, `dat`, `khach_hang`, `nhan_vien`, `booking_services`, `payments`, `invoices`.

SQL mới dùng CREATE TABLE IF NOT EXISTS, tạo bảng cha trước bảng con và giữ dữ liệu khi trùng khóa chính. Không có DROP hoặc TRUNCATE. Có thể nhập lại sau khi bị gián đoạn. Nếu có lỗi khác, dừng lại và xem nội dung lỗi; không tự xóa bảng.

**Nếu CSDL có đầy đủ các bảng phiên bản cũ và dữ liệu khách/đơn thật, dùng công cụ nâng cấp tools/upgrade.php theo HUONG_DAN.md; SQL cài mới không thay thế công cụ nâng cấp.**

## Cấu hình Docker

- Giữ tên CSDL `webkhachsan` nếu đang làm theo các bước trên. Không cần đổi sang `webkhachsan_v2`.
- SQL có thể nhập vào MySQL hoặc MariaDB. Cấu hình compose chính của gói dùng MariaDB; gói cũ bạn gửi ban đầu dùng MySQL 5.7. Không khởi chạy MariaDB trên volume dữ liệu MySQL 5.7 đang có.
- Nếu đang giữ volume MySQL 5.7 cũ, cấu hình tương thích được kèm trong `docker-compose.mysql57.yml`. Cấu hình này giả định tài khoản CSDL cũ là root, mật khẩu rỗng đúng như gói ban đầu. Nếu đã đổi tài khoản/mật khẩu, cập nhật DB_USER/DB_PASSWORD và lệnh healthcheck tương ứng trước khi chạy.

Tại thư mục dự án Docker cũ (giữ cùng tên project/thư mục để dùng đúng volume), cập nhật mã nguồn và chạy:

```sh
docker compose -f docker-compose.yml -f docker-compose.mysql57.yml up -d --build
```

Nếu Docker báo container cũ đang chiếm cổng, dừng stack cũ bằng compose cũ hoặc Docker Desktop trước. Không xóa volume. Cấu hình tương thích dùng cùng dịch vụ db và volume db_data; nếu trước đây đã đặt tên project khác, giữ nguyên tên đó khi chạy.

Khi dùng máy chủ MySQL 5.7 cũ, môi trường web phải có DB_HOST=db, DB_NAME=webkhachsan, DB_USER=root và DB_PASSWORD rỗng (hoặc đúng thông tin CSDL bạn đã đặt). Sửa config.php đơn lẻ không ghi đè được biến môi trường DB_NAME/DB_USER/DB_PASSWORD đang có.

Mở `http://localhost:8088`. Tài khoản cài mới: chọn Nhân viên / Quản trị, tài khoản `01`, mật khẩu `Admin@12345`. Đối với tài khoản đã có, SQL nhập lại giữ mật khẩu đã lưu chứ không đặt lại theo mật khẩu mẫu.

## Đã kiểm tra bản sửa

- Nhập vào CSDL trống.
- Nhập vào CSDL chỉ có audit_log (giống ảnh lỗi).
- Nhập lại lần hai, giữ nguyên bản ghi và giá đã sửa, không nhân đôi phòng.
- Kiểm tra quan hệ khóa ngoại khi tạo bảng với FOREIGN_KEY_CHECKS bật.

Cấu hình Docker MySQL 5.7 là cấu hình tương thích để dùng với volume cũ; chưa chạy Docker thực tế trong môi trường kiểm tra này.
