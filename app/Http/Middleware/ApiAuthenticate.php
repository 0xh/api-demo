<?php

namespace App\Http\Middleware;

use Closure;
use Lcobucci\JWT\Parser;
use App\Models\User;

class ApiAuthenticate
{
    public function handle($request, \Closure $next)
    {
        $jwtToken = \Request::header('Authorization');

        if(!$jwtToken){
            return \Response::json([
                'success' => false,
                'status' => 400,
                'message' => 'Token not provided',
                'data' => null
            ], 400);
        }

        $jwtToken = (new Parser())->parse((string) $jwtToken);

        if($jwtToken->isExpired()){
            return \Response::json([
                'success' => false,
                'status' => 401,
                'message' => 'Token expired',
                'data' => null
            ], 401);
        }

        $user = User::where('id', $jwtToken->getClaim('id'))->where('email', $jwtToken->getClaim('email'))->first();

        if(!$user)
        {
            return \Response::json([
                'success' => false,
                'status' => 401,
                'message' => 'Unauthorized',
                'data' => null
            ], 401);
        }
        \Auth::login($user);

        return $next($request);
    }
}
