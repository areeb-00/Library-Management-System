<?php
declare(strict_types=1);

function e(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function redirect(string $page='dashboard'): never { header('Location: index.php?page='.rawurlencode($page)); exit; }
function flash(string $type, string $message): void { $_SESSION['flash'] = ['type'=>$type, 'message'=>$message]; }
function take_flash(): ?array { $f=$_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f; }
function csrf_token(): string { if (empty($_SESSION['_csrf'])) $_SESSION['_csrf']=bin2hex(random_bytes(32)); return $_SESSION['_csrf']; }
function csrf_field(): string { return '<input type="hidden" name="_csrf" value="'.e(csrf_token()).'">'; }
function verify_csrf(): void { if (!hash_equals($_SESSION['_csrf'] ?? '', $_POST['_csrf'] ?? '')) { http_response_code(419); exit('Invalid or expired form token. Go back and reload the page.'); } }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void { if (!current_user()) redirect('login'); }
function require_role(array $roles): void { require_login(); if (!in_array(current_user()['role'], $roles, true)) { http_response_code(403); exit('403 — You do not have permission to access this page.'); } }
function setting(string $key, mixed $default=null): mixed { static $all=null; if ($all===null) { try { $all=db()->query('SELECT setting_key, setting_value FROM system_settings')->fetchAll(PDO::FETCH_KEY_PAIR); } catch(Throwable) { $all=[]; } } return $all[$key] ?? $default; }
function money(float|string $amount): string { return '₹'.number_format((float)$amount, 2); }
function is_post(): bool { return $_SERVER['REQUEST_METHOD']==='POST'; }
