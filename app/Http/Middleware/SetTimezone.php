<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;


class SetTimezone
{
    public function handle(Request $request, Closure $next)
    {
        if ($timezone = $request->header('X-Timezone')) {
            config(['app.timezone' => $timezone]);
            Carbon::setLocale($timezone);
            date_default_timezone_set($timezone);
    }
    return $next($request);
}
}
