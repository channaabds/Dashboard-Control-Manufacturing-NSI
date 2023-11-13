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

        $userDepartement = auth()->user()->departement;

        if ($userDepartement == 'qc' || $userDepartement == 'qa') {
            $userDepartement = 'quality';
        }

        if ($userDepartement == 'it') {
            return $next($request);
        }

        if ($userDepartement != $departement) {
            return redirect("/$userDepartement");
        }
        return $next($request);
    }
}
