<?php
session_start();
include 'config.php';

$rooms = [];
$result = mysqli_query($conn, "SELECT PHONG.MAPHONG, LOAI_PHONG.TENLOAIPHONG, PHONG.GIAPHONG, PHONG.TINHTRANG, PHONG.HINH_ANH FROM PHONG JOIN LOAI_PHONG ON PHONG.MALOAIPHONG = LOAI_PHONG.MALOAIPHONG LIMIT 3");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>N2H HOTEL - Đặt phòng giá tốt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --main-blue: #007bff; --light-blue: #e3f2fd; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .hero-section {
            position: relative;
            padding: 0;
            overflow: hidden;
            margin-bottom: 2.5rem;
        }
        .hero-carousel img {
            width: 100%;
            height: 420px;
            object-fit: cover;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: rgba(0, 0, 0, 0.35);
            color: white;
            z-index: 2;
            padding: 0 20px;
        }
        .hero-overlay h1,
        .hero-overlay p {
            text-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
        }
        .hero-overlay .card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            max-width: 640px;
            width: 100%;
            padding: 2rem;
        }
        .search-box { background: white; padding: 20px; border-radius: 10px; margin-top: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); position: relative; z-index: 1; }
        .btn-primary { background-color: var(--main-blue); border: none; }
        .room-card { border: none; transition: 0.3s; border-radius: 15px; overflow: hidden; display: flex; flex-direction: column; }
        .room-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .room-card img {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }
        .room-card .card-body { flex: 1; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="hero-section">
    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <?php if (!empty($rooms)): ?>
                <?php for ($i = 0; $i < 3; $i++):
                    $room = $rooms[$i] ?? $rooms[array_key_first($rooms)];
                    $image = !empty($room['HINH_ANH']) ? $room['HINH_ANH'] : 'phongdon.jpg';
                ?>
                    <div class="carousel-item <?php echo $i === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo htmlspecialchars($image); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($room['TENLOAIPHONG'] ?? 'Phòng'); ?>">
                    </div>
                <?php endfor; ?>
            <?php else: ?>
                <div class="carousel-item active">
                    <img src="phongdon.jpg" class="d-block w-100" alt="Phòng">
                </div>
                <div class="carousel-item">
                    <img src="room2.jpg" class="d-block w-100" alt="Phòng">
                </div>
                <div class="carousel-item">
                    <img src="room3.jpg" class="d-block w-100" alt="Phòng">
                </div>
            <?php endif; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <div class="hero-overlay">
        <div class="card">
            <h1 class="display-4 fw-bold">N2H HOTEL</h1>
            <p class="lead">Ưu đãi tốt nhất cho hơn 100 phòng hạng sang</p>
        </div>
    </div>
</section>

<div class="container">
    <div class="search-box">
        <form class="row g-3">
            <div class="col-md-4">
                <label class="form-label text-dark fw-bold">Ngày nhận phòng</label>
                <input type="date" class="form-control" name="checkin">
            </div>
            <div class="col-md-4">
                <label class="form-label text-dark fw-bold">Loại phòng</label>
                <select class="form-select" name="room_type">
                    <option>Phòng VIP Luxury</option>
                    <option>Phòng Đôi Superior</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100 py-2" type="submit"><i class="fa fa-search"></i> Tìm ngay</button>
            </div>
        </form>
    </div>

    <h3 class="mt-5 mb-4 fw-bold text-dark">Phòng gợi ý cho bạn</h3>
    <div class="row">
        <?php if (!empty($rooms)): ?>
            <?php foreach ($rooms as $room): ?>
                <div class="col-md-4 mb-4">
                    <div class="card room-card">
                        <img src="<?php echo htmlspecialchars(!empty($room['HINH_ANH']) ? $room['HINH_ANH'] : 'phongdon.jpg'); ?>" class="card-img-top img-fluid" alt="<?php echo htmlspecialchars($room['TENLOAIPHONG']); ?>">
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($room['TENLOAIPHONG']); ?></h5>
                            <p class="text-muted small"><i class="fa fa-check text-success"></i> Trạng thái: <?php echo htmlspecialchars($room['TINHTRANG']); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold fs-5"><?php echo number_format($room['GIAPHONG'], 0, ',', '.'); ?>đ / đêm</span>
                                <a href="datphong.php?id_phong=<?php echo htmlspecialchars($room['MAPHONG']); ?>" class="btn btn-sm btn-outline-primary">Đặt ngay</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <?php for ($i = 1; $i <= 3; $i++): ?>
                <div class="col-md-4 mb-4">
                    <div class="card room-card">
                        <img src="https://via.placeholder.com/400x250" class="card-img-top" alt="Room">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Phòng Luxury VIP <?php echo $i; ?></h5>
                            <p class="text-muted small"><i class="fa fa-check text-success"></i> Miễn phí ăn sáng</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold fs-5">2.000.000đ / đêm</span>
                                <a href="#" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
