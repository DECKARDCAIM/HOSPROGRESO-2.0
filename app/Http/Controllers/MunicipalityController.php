<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\Department;
use App\Models\Country;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Municipality::with('department.country');

        // Buscador
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('department', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%")
                         ->orWhereHas('country', function($q3) use ($search) {
                             $q3->where('name', 'LIKE', "%{$search}%");
                         });
                  });
            });
        }

        // Filtro por País (a través de depto)
        if ($request->filled('country_id')) {
            $query->whereHas('department', function($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }

        // Filtro por Departamento
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $is_active = $request->status === 'active';
            $query->where('is_active', $is_active);
        }

        $perPage                = $request->input('per_page', 25);
        $municipalities         = $query->orderBy('name')->paginate($perPage)->appends($request->query());
        
        $totalMunicipalities    = Municipality::count();
        $activeMunicipalities   = Municipality::where('is_active', true)->count();
        $inactiveMunicipalities = Municipality::where('is_active', false)->count();
        
        $countries              = Country::orderBy('name')->get();
        
        $departmentsQuery = Department::query();
        if ($request->filled('country_id')) {
            $departmentsQuery->where('country_id', $request->country_id);
        }
        $departments = $departmentsQuery->orderBy('name')->get();

        return view('modules.ubication.municipalities.index', compact(
            'municipalities', 'countries', 'departments',
            'totalMunicipalities', 'activeMunicipalities', 'inactiveMunicipalities'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $countries   = Country::all();
        $departments = collect();

        if ($request->filled('country_id')) {
            $departments = Department::where('country_id', $request->country_id)->get();
        }

        return view('modules.ubication.municipalities.create', compact('departments', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'          => 'required|string|min:5',
            'department_id' => 'required|exists:departments,id',
            'description'   => 'nullable|string|max:320',
        ];

        $messages = [
            'name.required'          => 'El campo nombre es obligatorio.',
            'name.string'            => 'El campo nombre debe ser una cadena de texto.',
            'name.min'               => 'El campo nombre debe tener al menos 5 caracteres.',
            'department_id.required' => 'El campo departamento es obligatorio.',
            'department_id.exists'   => 'El departamento seleccionado no es válido.',
            'description.string'     => 'El campo descripción debe ser una cadena de texto.',
            'description.max'        => 'El campo descripción no puede tener más de 320 caracteres.',
        ];

        $this->validate($request, $rules, $messages);

        $municipality                = new Municipality();
        $municipality->department_id = $request->department_id;
        $municipality->name          = $request->name;
        $municipality->description   = $request->description;
        $municipality->save();

        $notification = [
            'message'    => 'El municipio ' . $municipality->name . ' se ha creado correctamente.',
            'alert-type' => 'Creación Éxitosa',
        ];

        return redirect()->route('municipalities.index')->with(compact('notification'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Municipality $municipality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Municipality $municipality)
    {
        $countries   = Country::all();
        $departments = Department::all();

        return view('modules.ubication.municipalities.edit', compact('municipality', 'departments', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Municipality $municipality)
    {
        $rules = [
            'name'          => 'required|string|min:5',
            'department_id' => 'required|exists:departments,id',
            'description'   => 'nullable|string|max:320',
        ];

        $messages = [
            'name.required'          => 'El campo nombre es obligatorio.',
            'name.string'            => 'El campo nombre debe ser una cadena de texto.',
            'name.min'               => 'El campo nombre debe tener al menos 5 caracteres.',
            'department_id.required' => 'El campo departamento es obligatorio.',
            'department_id.exists'   => 'El departamento seleccionado no es válido.',
            'description.string'     => 'El campo descripción debe ser una cadena de texto.',
            'description.max'        => 'El campo descripción no puede tener más de 320 caracteres.',
        ];

        $this->validate($request, $rules, $messages);

        $municipality->name          = $request->input('name');
        $municipality->department_id = $request->input('department_id');
        $municipality->description   = $request->input('description');
        $municipality->save();

        $notification = [
            'message'    => 'El municipio ' . $municipality->name . ' se ha actualizado correctamente.',
            'alert-type' => 'Actualización Éxitosa',
        ];

        return redirect()->route('municipalities.index')->with(compact('notification'));
    }

    public function destroy(Municipality $municipality)
    {
        $municipality->is_active = false;
        $municipality->save();
        $notification = [
            'message'    => 'El municipio ' . $municipality->name . ' ha sido desactivado correctamente.',
            'alert-type' => 'Actualización Éxitosa'
        ];
        return redirect()->route('municipalities.index')->with(compact('notification'));
    }

    /**
     * Reactivar el recurso.
     */
    public function restore(Municipality $municipality)
    {
        $municipality->is_active = true;
        $municipality->save();
        $notification = [
            'message'    => 'El municipio ' . $municipality->name . ' ha sido reactivado correctamente.',
            'alert-type' => 'Actualización Éxitosa'
        ];
        return redirect()->route('municipalities.index')->with(compact('notification'));
    }
}