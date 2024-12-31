<?php
namespace Config;

use DateTime;

class RateLimiter
{
    private string $keyPrefix;
    private int $limit;
    private int $timeWindow;
    private array $storage;

    public function __construct(string $keyPrefix = 'rate_limit', int $limit = 100, int $timeWindow = 60)
    {
        $this->keyPrefix = $keyPrefix;
        $this->limit = $limit;
        $this->timeWindow = $timeWindow;
        $this->storage = [];
    }

    public function isAllowed(string $identifier): bool
    {
        $key = "{$this->keyPrefix}_{$identifier}";
        $now = (new DateTime())->getTimestamp();

        // Check storage for key
        if (!isset($this->storage[$key])) {
            $this->storage[$key] = ['count' => 1, 'expires_at' => $now + $this->timeWindow];
            return true;
        }

        $rateLimit = &$this->storage[$key];

        // If the window has expired, reset
        if ($rateLimit['expires_at'] < $now) {
            $rateLimit['count'] = 1;
            $rateLimit['expires_at'] = $now + $this->timeWindow;
            return true;
        }

        // If within the limit, increment and allow
        if ($rateLimit['count'] < $this->limit) {
            $rateLimit['count']++;
            return true;
        }

        // Limit exceeded
        return false;
    }

    public function getRemainingAttempts(string $identifier): int
    {
        $key = "{$this->keyPrefix}_{$identifier}";
        $rateLimit = $this->storage[$key] ?? ['count' => 0];
        return max($this->limit - $rateLimit['count'], 0);
    }

    public function getRetryAfter(string $identifier): int
    {
        $key = "{$this->keyPrefix}_{$identifier}";
        $rateLimit = $this->storage[$key] ?? ['expires_at' => 0];
        $now = (new DateTime())->getTimestamp();
        return max($rateLimit['expires_at'] - $now, 0);
    }
}
