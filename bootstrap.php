<?php
declare(strict_types=1);
date_default_timezone_set('Asia/Ho_Chi_Minh');
if (PHP_SAPI !== 'cli') {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax','secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
    session_start();
    header('Content-Type: text/html; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: same-origin');
    header('Cache-Control: no-store');
}
function db(): PDO {
    static $db;
    if (!$db) {
        $c = require __DIR__.'/../config.php';
        $db = new PDO("mysql:host={$c['host']};port={$c['port']};dbname={$c['database']};charset=utf8mb4", $c['username'], $c['password'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
        $db->exec("SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED");
        $db->exec("SET time_zone = '+07:00'");
    }
    return $db;
}
function query(string $sql, array $args=[]): PDOStatement { $s=db()->prepare($sql); $s->execute($args); return $s; }
function row(string $sql,array $args=[]): ?array { return query($sql,$args)->fetch() ?: null; }
function rows(string $sql,array $args=[]): array { return query($sql,$args)->fetchAll(); }
function h($s): string { return htmlspecialchars((string)($s ?? ''),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8'); }
function money($s): string { return number_format((float)$s,0,',','.').' đ'; }
function go(string $url): void { header('Location: '.$url); exit; }
function flash(string $s): void { $_SESSION['flash']=$s; }
function csrf(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(32)); }
function token(): void {
    $nonce=bin2hex(random_bytes(16)); $_SESSION['forms'][$nonce]=time();
    if(count($_SESSION['forms'])>200) $_SESSION['forms']=array_slice($_SESSION['forms'],-200,null,true);
    echo '<input type="hidden" name="csrf" value="'.h(csrf()).'"><input type="hidden" name="form_token" value="'.h($nonce).'">';
}
function check_csrf(): void {
    $nonce=(string)($_POST['form_token']??'');
    if (!hash_equals(csrf(),(string)($_POST['csrf']??'')) || !isset($_SESSION['forms'][$nonce]) || $_SESSION['forms'][$nonce]<time()-7200) { http_response_code(419); exit('Biểu mẫu đã xử lý hoặc hết hạn. Vui lòng tải lại trang.'); }
    unset($_SESSION['forms'][$nonce]);
}
function user(): ?array {
    static $loaded=false,$u=null;
    if (!$loaded) {
        $loaded=true;
        if (!empty($_SESSION['user_id'])) {
            $staff=($_SESSION['kind']??'')==='staff';
            $u=$staff ? row('SELECT MANV id,HOTEN name,CHUCVU role,ACTIVE FROM nhan_vien WHERE MANV=?',[$_SESSION['user_id']]) : row("SELECT MAKH id,HOTENKH name,'KhachHang' role,ACTIVE FROM khach_hang WHERE MAKH=?",[$_SESSION['user_id']]);
            if (!$u || !$u['ACTIVE']) { $u=null; unset($_SESSION['user_id'],$_SESSION['kind']); }
        }
    }
    return $u;
}
function is_role(string ...$roles): bool { return in_array(user()['role']??'', $roles,true); }
function staff(): bool { return is_role('Admin','Lễ tân'); }
function require_login(): void { if (!user()) go('login.php'); }
function require_roles(string ...$roles): void { require_login(); if (!is_role(...$roles)) { http_response_code(403); exit('Bạn không có quyền thực hiện chức năng này.'); } }
function transaction(callable $fn) { db()->beginTransaction(); try { $r=$fn(); db()->commit(); return $r; } catch(Throwable $e) { if(db()->inTransaction()) db()->rollBack(); throw $e; } }
function audit(string $action,string $target,string $note=''): void { query('INSERT INTO audit_log(actor,action,target,note) VALUES(?,?,?,?)',[(user()['role']??'Hệ thống').':'.(user()['id']??0),$action,$target,$note]); }
function fail(string $s): void { throw new DomainException($s); }
function attempt(callable $fn): string { try { $fn(); return ''; } catch(DomainException $e) { return $e->getMessage(); } catch(PDOException $e) { error_log($e->getMessage()); return 'Không thể lưu. Kiểm tra dữ liệu trùng hoặc dữ liệu đang được sử dụng, rồi thử lại.'; } }
function text_input(string $key,int $max=255,bool $required=true): string { $v=trim((string)($_POST[$key]??'')); if (($required && $v==='') || mb_strlen($v)>$max) fail('Thông tin '.$key.' bị thiếu hoặc quá dài.'); return $v; }
function number_input(string $key,int $min,int $max): int { $v=filter_var($_POST[$key]??null,FILTER_VALIDATE_INT); if($v===false || $v<$min || $v>$max) fail('Giá trị '.$key.' không hợp lệ.'); return $v; }
function valid_date(string $d): bool { $v=DateTimeImmutable::createFromFormat('!Y-m-d',$d); return $v && $v->format('Y-m-d')===$d; }
function nights(string $start,string $end): int { $a=substr($start,0,10); $b=substr($end,0,10); if(!valid_date($a)||!valid_date($b)||$b<=$a) fail('Ngày trả phải sau ngày nhận.'); return (int)(new DateTimeImmutable($a))->diff(new DateTimeImmutable($b))->days; }
function image_path(?string $p): string { $p=trim($p??''); return preg_match('~^[a-zA-Z0-9_./-]+\.(jpg|jpeg|png|webp)$~i',$p) && !str_contains($p,'..') && !str_starts_with($p,'/') ? $p : 'phongdon.jpg'; }
function error_box(string $s): void { if($s!=='') echo '<div class="alert danger" role="alert">'.h($s).'</div>'; }
function input(string $name,string $label,$value='',string $type='text',string $extra=''): void { echo '<label>'.h($label).'<input name="'.h($name).'" type="'.h($type).'" value="'.h($value).'" '.$extra.'></label>'; }
function select_field(string $name,string $label,array $options,$value=''): void { echo '<label>'.h($label).'<select name="'.h($name).'">'; foreach($options as $id=>$title) echo '<option value="'.h($id).'" '.((string)$id===(string)$value?'selected':'').'>'.h($title).'</option>'; echo '</select></label>'; }
function page(string $title): void { require __DIR__.'/layout.php'; }
function endpage(): void { echo '</main><footer>NHM HOTEL · Quản lý lưu trú & dịch vụ</footer></body></html>'; }
set_exception_handler(function(Throwable $e) { error_log((string)$e); if(PHP_SAPI==='cli') { fwrite(STDERR,$e->getMessage().PHP_EOL); exit(1); } http_response_code(500); echo '<h2>Chưa thể tải dữ liệu</h2><p>Kiểm tra config.php và chạy nâng cấp cơ sở dữ liệu theo HUONG_DAN.md.</p>'; });
