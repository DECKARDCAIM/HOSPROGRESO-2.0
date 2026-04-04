<?php

namespace App\Http\Controllers;

use App\Exports\CivilStatusExport;
use App\Http\Requests\StoreCivilStatusRequest;
use App\Http\Requests\UpdateCivilStatusRequest;
use App\Models\CivilStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class CivilStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'civil_statuses_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['civil_statuses'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = CivilStatus::query();

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
            $items = $query->orderBy('id')->paginate($perPage)->appends($request->query());

            $total = CivilStatus::count();
            $active = CivilStatus::where('is_active', true)->count();
            $inactive = CivilStatus::where('is_active', false)->count();

            return compact('items', 'allFilteredIds', 'total', 'active', 'inactive');
        });

        return view('modules.patient.civil-statuses.index', $data);
    }

    public function create()
    {
        return view('modules.patient.civil-statuses.create');
    }

    public function store(StoreCivilStatusRequest $request)
    {
        $item = CivilStatus::create($request->validated());

        return redirect()->route('civil-statuses.index')->with('success', 'El estado civil "'.$item->name.'" se ha creado correctamente.');
    }

    public function show(CivilStatus $civilStatus) {}

    public function edit(CivilStatus $civilStatus)
    {
        return view('modules.patient.civil-statuses.edit', ['item' => $civilStatus]);
    }

    public function update(UpdateCivilStatusRequest $request, CivilStatus $civilStatus)
    {
        $civilStatus->update($request->validated());

        return redirect()->route('civil-statuses.index')->with('success', 'El estado civil "'.$civilStatus->name.'" se ha actualizado correctamente.');
    }

    public function destroy(CivilStatus $civilStatus)
    {
        $civilStatus->update(['is_active' => false]);

        Cache::tags(['civil_statuses'])->flush();

        return redirect()->route('civil-statuses.index')->with('success', 'El estado civil "'.$civilStatus->name.'" ha sido desactivado.');
    }

    public function restore(CivilStatus $civilStatus)
    {
        $civilStatus->update(['is_active' => true]);

        Cache::tags(['civil_statuses'])->flush();

        return redirect()->route('civil-statuses.index')->with('success', 'El estado civil "'.$civilStatus->name.'" ha sido reactivado.');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        CivilStatus::whereIn('id', $ids)->update(['is_active' => false]);
        Cache::tags(['civil_statuses'])->flush();

        return back()->with('success', count($ids).' estados civiles han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        CivilStatus::whereIn('id', $ids)->update(['is_active' => true]);
        Cache::tags(['civil_statuses'])->flush();

        return back()->with('success', count($ids).' estados civiles han sido reactivados.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new CivilStatusExport($ids), 'estados-civiles.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new CivilStatusExport($ids), 'estados-civiles.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? CivilStatus::whereIn('id', $ids)->orderBy('id')->get() : CivilStatus::orderBy('id')->get();

        $pdf = Pdf::loadView('modules.patient.civil-statuses.print', compact('items'));

        return $pdf->download('estados-civiles.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? CivilStatus::whereIn('id', $ids)->orderBy('id')->get() : CivilStatus::orderBy('id')->get();

        return view('modules.patient.civil-statuses.print', compact('items'));
    }
}
