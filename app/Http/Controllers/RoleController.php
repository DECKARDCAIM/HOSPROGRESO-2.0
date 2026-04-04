<?php

namespace App\Http\Controllers;

use App\Exports\RoleExport;
use App\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'roles_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['roles'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = Role::query();

            if ($request->filled('search')) {
                $query->where('name', 'LIKE', "%{$request->search}%");
            }

            $status = $request->input('status', 'active');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }

            $perPage = $request->input('per_page', 25);
            $allFilteredIds = (clone $query)->pluck('id')->toArray();
            $roles = $query->orderBy('id')->paginate($perPage)->appends($request->query());

            $totalCount = Role::count();
            $activeCount = Role::where('is_active', true)->count();
            $inactiveCount = Role::where('is_active', false)->count();

            return compact('roles', 'allFilteredIds', 'totalCount', 'activeCount', 'inactiveCount');
        });

        return view('modules.maintenance.roles.index', $data);
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new RoleExport($ids), 'roles.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new RoleExport($ids), 'roles.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Role::whereIn('id', $ids)->orderBy('id')->get() : Role::orderBy('id')->get();

        $pdf = Pdf::loadView('modules.maintenance.roles.print', compact('items'));

        return $pdf->download('roles.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Role::whereIn('id', $ids)->orderBy('id')->get() : Role::orderBy('id')->get();

        return view('modules.maintenance.roles.print', compact('items'));
    }
}
