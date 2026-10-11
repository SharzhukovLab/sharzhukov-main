<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class HealthController extends Controller
{
    public function __invoke(Request $request)
    {
        // Защита: только с токеном из env (HEALTH_TOKEN) или с localhost
        $token = config('app.health_token', env('HEALTH_TOKEN'));
        $given = $request->query('token');

        if (!empty($token) && $given !== $token) {
            $ip = $request->ip();
            if (!in_array($ip, ['127.0.0.1', '::1'])) {
                abort(403, 'Forbidden');
            }
        }

        $checks = [];
        $ok     = true;

        // DB
        try {
            DB::select('SELECT 1');
            $checks['db'] = ['status' => 'ok'];
        } catch (\Throwable $e) {
            $checks['db'] = ['status' => 'fail', 'error' => $e->getMessage()];
            $ok = false;
        }

        // Cache
        try {
            $key = 'health.check.' . time();
            Cache::put($key, 1, 5);
            $val = Cache::get($key);
            Cache::forget($key);
            $checks['cache'] = ['status' => $val === 1 ? 'ok' : 'fail'];
            if ($val !== 1) $ok = false;
        } catch (\Throwable $e) {
            $checks['cache'] = ['status' => 'fail', 'error' => $e->getMessage()];
            $ok = false;
        }

        // Queue backlog
        try {
            $jobs   = DB::table('jobs')->count();
            $failed = DB::table('failed_jobs')->count();
            $checks['queue'] = [
                'status'   => $jobs < 200 ? 'ok' : 'warn',
                'pending'  => $jobs,
                'failed'   => $failed,
            ];
            if ($jobs >= 500) $ok = false;
        } catch (\Throwable $e) {
            $checks['queue'] = ['status' => 'fail', 'error' => $e->getMessage()];
            $ok = false;
        }

        // Disk
        try {
            $free = @disk_free_space('/');
            $total = @disk_total_space('/');
            $freeGb = $free ? round($free / 1073741824, 2) : null;
            $checks['disk'] = [
                'status'  => ($freeGb !== null && $freeGb > 1) ? 'ok' : 'fail',
                'free_gb' => $freeGb,
                'total_gb' => $total ? round($total / 1073741824, 2) : null,
            ];
            if ($freeGb !== null && $freeGb < 1) $ok = false;
        } catch (\Throwable $e) {
            $checks['disk'] = ['status' => 'fail', 'error' => $e->getMessage()];
        }

        // Storage writable
        try {
            $testFile = storage_path('app/health-test.txt');
            file_put_contents($testFile, time());
            @unlink($testFile);
            $checks['storage'] = ['status' => 'ok'];
        } catch (\Throwable $e) {
            $checks['storage'] = ['status' => 'fail', 'error' => $e->getMessage()];
            $ok = false;
        }

        return response()->json([
            'status'    => $ok ? 'ok' : 'fail',
            'timestamp' => now()->toIso8601String(),
            'app'       => config('app.name'),
            'env'       => config('app.env'),
            'checks'    => $checks,
        ], $ok ? 200 : 503);
    }
}