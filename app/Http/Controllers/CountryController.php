<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
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
        $query = Country::query();

        // Buscador
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $is_active = $request->status === 'active';
            $query->where('is_active', $is_active);
        }

        $perPage            = $request->input('per_page', 25);
        $countries          = $query->orderBy('name')->paginate($perPage)->appends($request->query());
        
        $totalCountries     = Country::count();
        $activeCountries    = Country::where('is_active', true)->count();
        $inactiveCountries  = Country::where('is_active', false)->count();

        return view('modules.ubication.countries.index', compact(
            'countries', 'totalCountries', 'activeCountries', 'inactiveCountries'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.ubication.countries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:5',
            'description' => 'nullable|string|max:320',
        ];
        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.min' => 'El campo nombre debe tener al menos 5 caracteres.',
            'description.string' => 'El campo descripción debe ser una cadena de texto.',
            'description.max' => 'El campo descripción no puede tener más de 320 caracteres.',
        ];

        $this->validate($request, $rules, $messages);

        $countries = new Country();
        $countries->name = $request->input('name');
        $countries->description = $request->input('description');
        $countries->save();
        $notification = [
            'message' => 'El pais ' . $countries->name . ' se ha creado correctamente.',
            'alert-type' => 'Creación Éxitosa'
        ];
        return redirect()->route('countries.index')->with(compact('notification'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
    //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        return view('modules.ubication.countries.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        $rules = [
            'name' => 'required|string|min:5',
            'description' => 'nullable|string|max:320',
        ];
        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.min' => 'El campo nombre debe tener al menos 5 caracteres.',
            'description.string' => 'El campo descripción debe ser una cadena de texto.',
            'description.max' => 'El campo descripción no puede tener más de 320 caracteres.',
        ];

        $this->validate($request, $rules, $messages);

        $country->name = $request->input('name');
        $country->description = $request->input('description');
        $country->save();
        $notification = [
            'message' => 'El pais ' . $country->name . ' se ha actualizado correctamente.',
            'alert-type' => 'Actualización Éxitosa'
        ];
        return redirect()->route('countries.index')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        $country->is_active = false;
        $country->save();
        $notification = [
            'message'    => 'El país ' . $country->name . ' ha sido desactivado correctamente.',
            'alert-type' => 'Actualización Éxitosa'
        ];
        return redirect()->route('countries.index')->with(compact('notification'));
    }

    /**
     * Reactivar el recurso.
     */
    public function restore(Country $country)
    {
        $country->is_active = true;
        $country->save();
        $notification = [
            'message'    => 'El país ' . $country->name . ' ha sido reactivado correctamente.',
            'alert-type' => 'Actualización Éxitosa'
        ];
        return redirect()->route('countries.index')->with(compact('notification'));
    }
}