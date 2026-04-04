<?php

namespace App\Http\Controllers;

use App\Exports\UnityExecutionExport;
use App\Models\UnityExecution;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class UnityExecutionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'unity_executions_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['unity_executions'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = UnityExecution::query();

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'LIKE', "%{$request->search}%")
                        ->orWhere('code', 'LIKE', "%{$request->search}%");
                });
            }

            $status = $request->input('status', 'active');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }

            $perPage = $request->input('per_page', 25);
            $allFilteredIds = (clone $query)->pluck('id')->toArray();
            $unityExecutions = $query->orderBy('id')->paginate($perPage)->appends($request->query());

            $totalCount = UnityExecution::count();
            $activeCount = UnityExecution::where('is_active', true)->count();
            $inactiveCount = UnityExecution::where('is_active', false)->count();

            return compact('unityExecutions', 'allFilteredIds', 'totalCount', 'activeCount', 'inactiveCount');
        });

        return view('modules.maintenance.unity-executions.index', $data);
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new UnityExecutionExport($ids), 'unidades_ejecutoras.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new UnityExecutionExport($ids), 'unidades_ejecutoras.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? UnityExecution::whereIn('id', $ids)->orderBy('id')->get() : UnityExecution::orderBy('id')->get();

        $pdf = Pdf::loadView('modules.maintenance.unity-executions.print', compact('items'));

        return $pdf->download('unidades_ejecutoras.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? UnityExecution::whereIn('id', $ids)->orderBy('id')->get() : UnityExecution::orderBy('id')->get();

        return view('modules.maintenance.unity-executions.print', compact('items'));
    }
}
