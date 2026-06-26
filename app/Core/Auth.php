<?php

namespace App\Core;

class Auth
{
    private const ADMIN_SESSION_KEY = 'gl_acha_admin';
    private const CSRF_SESSION_KEY = 'gl_acha_csrf_token';
    private const FLASH_SESSION_KEY = 'gl_acha_flash';

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        if (!headers_sent()) {
            $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

            session_set_cookie_params([
                'lifetime' => 0,
                'path' => self::cookiePath(),
                'domain' => '',
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }

        session_start();
    }

    public static function check(): bool
    {
        self::start();

        return isset($_SESSION[self::ADMIN_SESSION_KEY]);
    }

    public static function attempt(string $username, string $password, bool $remember = false): bool
    {
        self::start();

        $config = require __DIR__ . '/../../config/auth.php';
        $admin = $config['admin'] ?? [];
        $validUsername = isset($admin['username']) && hash_equals((string) $admin['username'], $username);
        $validPassword = isset($admin['password_hash']) && password_verify($password, (string) $admin['password_hash']);

        if (!$validUsername || !$validPassword) {
            return false;
        }

        session_regenerate_id(true);

        $_SESSION[self::ADMIN_SESSION_KEY] = [
            'username' => $username,
            'login_at' => date('Y-m-d H:i:s'),
        ];

        if ($remember) {
            self::extendSessionCookie();
        }

        return true;
    }

    public static function logout(): void
    {
        self::start();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]);
        }

        session_destroy();
    }

    public static function requireAdmin(): void
    {
        if (self::check()) {
            return;
        }

        self::redirect('/admin/login');
    }

    public static function csrfToken(): string
    {
        self::start();

        if (empty($_SESSION[self::CSRF_SESSION_KEY])) {
            $_SESSION[self::CSRF_SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::CSRF_SESSION_KEY];
    }

    public static function verifyCsrf(?string $token): bool
    {
        self::start();

        return is_string($token)
            && isset($_SESSION[self::CSRF_SESSION_KEY])
            && hash_equals($_SESSION[self::CSRF_SESSION_KEY], $token);
    }

    public static function flash(string $key, string $message): void
    {
        self::start();

        $_SESSION[self::FLASH_SESSION_KEY][$key] = $message;
    }

    public static function pullFlash(string $key): ?string
    {
        self::start();

        $message = $_SESSION[self::FLASH_SESSION_KEY][$key] ?? null;
        unset($_SESSION[self::FLASH_SESSION_KEY][$key]);

        if (empty($_SESSION[self::FLASH_SESSION_KEY])) {
            unset($_SESSION[self::FLASH_SESSION_KEY]);
        }

        return is_string($message) ? $message : null;
    }

    public static function redirect(string $path): void
    {
        header('Location: ' . self::url($path));
        exit;
    }

    private static function url(string $path): string
    {
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        $normalizedPath = '/' . trim($path, '/');

        return ($basePath === '' ? '' : $basePath) . ($normalizedPath === '/' ? '/' : $normalizedPath);
    }

    private static function cookiePath(): string
    {
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

        return $basePath === '' ? '/' : $basePath;
    }

    private static function extendSessionCookie(): void
    {
        if (headers_sent() || session_id() === '') {
            return;
        }

        $params = session_get_cookie_params();

        setcookie(session_name(), session_id(), [
            'expires' => time() + 60 * 60 * 24 * 7,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }
}
