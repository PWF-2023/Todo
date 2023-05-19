<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if ($request->user() && $request->user()->is_admin) {
        // if(Auth::check() && $request->user()->is_admin){
        if ($request->user()->is_admin) {
            return $next($request);
        }
        // return abort(403);
        // return abort(403, 'Unauthorized action.');
        // return redirect('/dashboard');
        // return redirect()->route('dashboard');
        return to_route('dashboard');
    }
}
