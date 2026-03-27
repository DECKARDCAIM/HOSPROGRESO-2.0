<?php

namespace App\Http\Controllers;

use App\Models\Allergy;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAllergyRequest;
use App\Http\Requests\UpdateAllergyRequest;
use App\Exports\AllergyExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AllergyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Allergy::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $status = $request->input('status', 'active');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $perPage        = $request->input('per_page', 25);
        $allFilteredIds = (clone $query)->pluck('id')->toArray();
        $items          = $query->orderBy('id')->paginate($perPage)->appends($request->query());

        $total    = Allergy::count();
        $active   = Allergy::where('is_active', true)->count();
        $inactive = Allergy::where('is_active', false)->count();

        return view('modules.patient.allergies.index', compact('items', 'allFilteredIds', 'total', 'active', 'inactive'));
    }

    public function create()
    {
        return view('modules.patient.allergies.create');
    }

    public function store(StoreAllergyRequest $request)
    {
        $item = new Allergy();
        $item->name = $request->input('name');
        $item->save();

        $notification = ['message' => 'La alergia "' . $item->name . '" se ha creado correctamente.', 'alert-type' => 'success'];
        return redirect()->route('allergies.index')->with(compact('notification'));
    }

    public function show(Allergy $allergy) {}

    public function edit(Allergy $allergy)
    {
        return view('modules.patient.allergies.edit', ['item' => $allergy]);
    }

    public function update(UpdateAllergyRequest $request, Allergy $allergy)
    {
        $allergy->name = $request->input('name');
        $allergy->save();

        $notification = ['message' => 'La alergia "' . $allergy->name . '" se ha actualizado correctamente.', 'alert-type' => 'info'];
        return redirect()->route('allergies.index')->with(compact('notification'));
    }

    public function destroy(Allergy $allergy)
    {
        $allergy->is_active = false;
        $allergy->save();

        $notification = ['message' => 'La alergia "' . $allergy->name . '" ha sido desactivada.', 'alert-type' => 'warning'];
        return redirect()->route('allergies.index')->with(compact('notification'));
    }

    public function restore(Allergy $allergy)
    {
        $allergy->is_active = true;
        $allergy->save();

        $notification = ['message' => 'La alergia "' . $allergy->name . '" ha sido reactivada.', 'alert-type' => 'success'];
        return redirect()->route('allergies.index')->with(compact('notification'));
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron alergias.');
        Allergy::whereIn('id', $ids)->update(['is_active' => false]);
        return back()->with('success', count($ids) . ' alergias han sido desactivadas.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron alergias.');
        Allergy::whereIn('id', $ids)->update(['is_active' => true]);
        return back()->with('success', count($ids) . ' alergias han sido reactivadas.');
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
