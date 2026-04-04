<?php

namespace App\Http\Controllers;

use App\Exports\GenderExport;
use App\Http\Requests\StoreGenderRequest;
use App\Http\Requests\UpdateGenderRequest;
use App\Models\Gender;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class GenderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'genders_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['genders'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = Gender::query();

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

            $total = Gender::count();
            $active = Gender::where('is_active', true)->count();
            $inactive = Gender::where('is_active', false)->count();

            return compact('items', 'allFilteredIds', 'total', 'active', 'inactive');
        });

        return view('modules.patient.genders.index', $data);
    }

    public function create()
    {
        return view('modules.patient.genders.create');
    }

    public function store(StoreGenderRequest $request)
    {
        $item = Gender::create($request->validated());

        $notification = [
            'message' => 'El género "'.$item->name.'" se ha creado correctamente.',
            'alert-type' => 'success',
        ];

        return redirect()->route('genders.index')->with(compact('notification'));
    }

    public function show(Gender $gender) {}

    public function edit(Gender $gender)
    {
        return view('modules.patient.genders.edit', ['item' => $gender]);
    }

    public function update(UpdateGenderRequest $request, Gender $gender)
    {
        $gender->update($request->validated());

        $notification = [
            'message' => 'El género "'.$gender->name.'" se ha actualizado correctamente.',
            'alert-type' => 'info',
        ];

        return redirect()->route('genders.index')->with(compact('notification'));
    }

    public function destroy(Gender $gender)
    {
        $gender->update(['is_active' => false]);

        Cache::tags(['genders'])->flush();

        $notification = [
            'message' => 'El género "'.$gender->name.'" ha sido desactivado.',
            'alert-type' => 'warning',
        ];

        return redirect()->route('genders.index')->with(compact('notification'));
    }

    public function restore(Gender $gender)
    {
        $gender->update(['is_active' => true]);

        Cache::tags(['genders'])->flush();

        $notification = [
            'message' => 'El género "'.$gender->name.'" ha sido reactivado.',
            'alert-type' => 'success',
        ];

        return redirect()->route('genders.index')->with(compact('notification'));
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        Gender::whereIn('id', $ids)->update(['is_active' => false]);
        Cache::tags(['genders'])->flush();

        return back()->with('success', count($ids).' géneros han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        Gender::whereIn('id', $ids)->update(['is_active' => true]);
        Cache::tags(['genders'])->flush();

        return back()->with('success', count($ids).' géneros han sido reactivados.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new GenderExport($ids), 'generos.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new GenderExport($ids), 'generos.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Gender::whereIn('id', $ids)->orderBy('id')->get() : Gender::orderBy('id')->get();

        $pdf = Pdf::loadView('modules.patient.genders.print', compact('items'));

        return $pdf->download('generos.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Gender::whereIn('id', $ids)->orderBy('id')->get() : Gender::orderBy('id')->get();

        return view('modules.patient.genders.print', compact('items'));
    }
}
