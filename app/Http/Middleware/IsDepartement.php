<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsDepartement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $departement): Response
    {
        if (auth()->user()->username == 'admin' || auth()->user()->username == 'manager') {
            return $next($request);
        }

        $user = auth()->user()->username;
        $username = auth()->user()->username;
        if ($user == 'qc' || $user == 'qa') {
            $username = 'quality';
        }

        if ($username != $departement) {
            $url = $username;
            return redirect("/$url");
        }
        return $next($request);
    }
}
