<?php
// config/Session.php
namespace Config;

class Session
{
    /**
     * Start the session with secure settings.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => 0,
                'cookie_secure' => true,
                'cookie_httponly' => true,
                'use_strict_mode' => true,
                'use_only_cookies' => true,
                'sid_length' => 48,
                'sid_bits_per_character' => 6,
                'read_and_close' => false,
            ]);
        }
    }

    /**
     * Get a session value by key.
     */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set a session value by key.
     */
    public static function put(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Check if a session key exists.
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session key.
     */
    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the session entirely.
     */
    public static function destroy(): void
    {
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
    }

    /**
     * Regenerate the session ID.
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    /**
     * Flash a message for one-time use.
     */
    public static function flash(string $key, $value): void
    {
        $_SESSION['flash'][$key] = $value;
    }

    /**
     * Retrieve and remove a flash message.
     */
    public static function pull(string $key, $default = null)
    {
        $value = $_SESSION['flash'][$key] ?? $default;
        if (isset($_SESSION['flash'][$key])) {
            unset($_SESSION['flash'][$key]);
        }
        return $value;
    }
}
