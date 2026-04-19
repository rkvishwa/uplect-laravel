<?php

namespace App\Http\Middleware;

use App\Models\Enrollment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareAdminViewData
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isAdmin()) {
            View::share(
                'pendingPaymentsCount',
                Enrollment::query()->where('status', Enrollment::STATUS_PENDING)->count()
            );
        }

        return $next($request);
    }
}
