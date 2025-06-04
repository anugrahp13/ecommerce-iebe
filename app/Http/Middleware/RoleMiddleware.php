<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Daftar URL prefix yang hanya boleh diakses oleh Admin
        $adminOnlyPaths = [
            'admin/users',
            'admin/categories',
            'admin/activity-logs',
            'admin/vouchers',
            'admin/discounts',
        ];
    
        foreach ($adminOnlyPaths as $path) {
            if ($request->is($path . '*') && (!$user || $user->role !== 'Admin')) {
                if ($request->ajax() || $request->wantsJson()) {
                    abort(403, 'Unauthorized');
                }

                return redirect('/admin')->with('error', 'Akses ditolak: Hanya Admin yang bisa mengakses halaman ini');
            }
        }

        return $next($request);
    }
}
