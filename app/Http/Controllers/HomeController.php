<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Release;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        $globalData = Cache::tags(['releases', 'patients'])->remember('home_global_data', now()->addDays(1), function () {
            return [
                'totalPatients' => Patient::count(),
                'recentReleases' => Release::published()
                    ->with('author')
                    ->latest()
                    ->take(4)
                    ->get(),
                'patientsToday' => Patient::whereDate('created_at', now()->toDateString())->count(),
            ];
        });

        $deptId = $user->staff?->work_department_id ?? 0;
        $teamData = Cache::tags(['users'])->remember("home_dept_team_{$deptId}", now()->addDays(1), function () use ($deptId) {
            if (! $deptId) {
                return collect();
            }

            return User::whereHas('staff', function ($q) use ($deptId) {
                $q->where('work_department_id', $deptId);
            })
                ->where('is_active', true)
                ->limit(6)
                ->get();
        });

        return view('home', array_merge(
            $globalData,
            [
                'user' => $user,
                'team' => $teamData,
            ]
        ));
    }
}
