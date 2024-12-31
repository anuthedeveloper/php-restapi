<?php
namespace App\Http\Middleware;

use Config\RateLimiter;
use App\Http\Request;

class RateLimitMiddleware
{
    private RateLimiter $rateLimiter;

    public function __construct()
    {
        $this->rateLimiter = new RateLimiter('api', 100, 60); // 100 requests per minute
    }

    public function handle(Request $request)
    {
        $clientIP = $request->ip();

        if (!$this->rateLimiter->isAllowed($clientIP)) {
            $retryAfter = $this->rateLimiter->getRetryAfter($clientIP);
            response()->json(
                ['error' => 'Rate limit exceeded', 'retry_after' => $retryAfter],
                429
            );
            exit;
        }
    }
}
