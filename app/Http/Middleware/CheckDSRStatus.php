<?php

namespace App\Http\Middleware;

use Closure;
use Helper;

class CheckDSRStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty(Helper::showDailyStatusReportPage())) {
            return $next($request);
        }

        if (auth()->user()->dsr_late_notify == 0) {
            return $next($request);
        }

        return redirect('/update-status-report');
    }
}
