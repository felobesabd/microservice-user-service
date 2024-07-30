<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JWTAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard): Response
    {
        if ($guard != null) {
            auth()->shouldUse($guard);

            $token = $request->header('api-token');
            $request->headers->set('api-token', (string) $token, true);
            $request->headers->set('Authorization', 'Bearer ' . $token, true);

            try {
                $user = JWTAuth::parseToken()->authenticate();
                dd($user);
            } catch (TokenExpiredException $exception) {
                return response()->json(['msg' =>  $exception->getMessage(), 'code' => $exception->getCode()]);
            } catch (JWTException $exception) {
                return response()->json(['msg' =>  $exception->getMessage(), 'code' => $exception->getCode()]);
            }
        }

        return $next($request);
    }
}
