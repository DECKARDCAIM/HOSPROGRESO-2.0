<?php

namespace App\Http\Controllers;

use App\Exports\AllergyExport;
use App\Http\Requests\StoreAllergyRequest;
use App\Http\Requests\UpdateAllergyRequest;
use App\Models\Allergy;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class AllergyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'allergies_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['allergies'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = Allergy::query();

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

            $total = Allergy::count();
            $active = Allergy::where('is_active', true)->count();
            $inactive = Allergy::where('is_active', false)->count();

            return compact('items', 'allFilteredIds', 'total', 'active', 'inactive');
        });

        return view('modules.patient.allergies.index', $data);
    }

    public function create()
    {
        return view('modules.patient.allergies.create');
    }

    public function store(StoreAllergyRequest $request)
    {
        $item = Allergy::create($request->validated());

        return redirect()->route('allergies.index')->with('success', 'La alergia "'.$item->name.'" se ha creado correctamente.');
    }

    public function show(Allergy $allergy) {}

    public function edit(Allergy $allergy)
    {
        return view('modules.patient.allergies.edit', ['item' => $allergy]);
    }

    public function update(UpdateAllergyRequest $request, Allergy $allergy)
    {
        $allergy->update($request->validated());

        return redirect()->route('allergies.index')->with('success', 'La alergia "'.$allergy->name.'" se ha actualizado correctamente.');
    }

    public function destroy(Allergy $allergy)
    {
        $allergy->update(['is_active' => false]);

        Cache::tags(['allergies'])->flush();

        return redirect()->route('allergies.index')->with('success', 'La alergia "'.$allergy->name.'" ha sido desactivada.');
    }

    public function restore(Allergy $allergy)
    {
        $allergy->update(['is_active' => true]);

        Cache::tags(['allergies'])->flush();

        return redirect()->route('allergies.index')->with('success', 'La alergia "'.$allergy->name.'" ha sido reactivada.');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron alergias.');
        }

        Allergy::whereIn('id', $ids)->update(['is_active' => false]);
        Cache::tags(['allergies'])->flush();

        return back()->with('success', count($ids).' alergias han sido desactivadas.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron alergias.');
        }

        Allergy::whereIn('id', $ids)->update(['is_active' => true]);
        Cache::tags(['allergies'])->flush();

        return back()->with('success', count($ids).' alergias han sido reactivadas.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new AllergyExport($ids), 'alergias.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new AllergyExport($ids), 'alergias.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Allergy::whereIn('id', $ids)->orderBy('id')->get() : Allergy::orderBy('id')->get();

        $pdf = Pdf::loadView('modules.patient.allergies.print', compact('items'));

        return $pdf->download('alergias.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Allergy::whereIn('id', $ids)->orderBy('id')->get() : Allergy::orderBy('id')->get();

        return view('modules.patient.allergies.print', compact('items'));
    }
}
