<?php
declare(strict_types=1);
final class Auth
{
    public static function attempt(string $username, string $password): bool
    {
        $stmt=db()->prepare('SELECT user_id, username, password_hash, role, status, full_name FROM users WHERE username=? LIMIT 1');
        $stmt->execute([$username]); $user=$stmt->fetch();
        if (!$user || $user['status']!=='active' || !password_verify($password, $user['password_hash'])) return false;
        session_regenerate_id(true);
        $_SESSION['user']=['user_id'=>(int)$user['user_id'],'username'=>$user['username'],'name'=>$user['full_name'],'role'=>$user['role']];
        return true;
    }
    public static function logout(): void { $_SESSION=[]; if (ini_get('session.use_cookies')) { $p=session_get_cookie_params(); setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']); } session_destroy(); }
}
