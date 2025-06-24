<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class BearerTokenMiddleware
{
    private $validToken = '2BH52wAHrAymR7wP3CASt';
    private $maxAttempts = 10;
    private $blockMinutes = 10;

    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $blockedKey = "blocked_ip_{$ip}";
        $attemptsKey = "token_attempts_{$ip}";

        if (Cache::has($blockedKey)) {
            return response()->json(['message' => 'IP adresiniz geçici olarak engellendi. Lütfen daha sonra tekrar deneyin.'], Response::HTTP_FORBIDDEN);
        }

        $authHeader = $request->header('Authorization');
        $token = null;
        if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }

        if ($token !== $this->validToken) {
            if (!Cache::has($attemptsKey)) {
                Cache::put($attemptsKey, 1, $this->blockMinutes * 60);
                $attempts = 1;
            } else {
                $attempts = Cache::increment($attemptsKey);
            }

            if ($attempts >= $this->maxAttempts) {
                Cache::put($blockedKey, true, $this->blockMinutes * 60);
                Cache::forget($attemptsKey);
                return response()->json(['message' => 'Çok fazla hatalı deneme yaptınız. IP adresiniz geçici olarak engellendi.'], Response::HTTP_FORBIDDEN);

            }

            return response()->json(['message' => 'Yetkisiz erişim. Geçersiz veya eksik token.'], Response::HTTP_UNAUTHORIZED);
        }

        Cache::forget($attemptsKey);
        return $next($request);
    }
}