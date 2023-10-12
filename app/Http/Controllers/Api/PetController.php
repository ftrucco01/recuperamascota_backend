<?php


namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Pet;
use App\Http\Controllers\Controller;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pets = Pet::all();
        return view('pets.index', compact('pets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valida los datos del formulario
        $request->validate([
            'name' => 'required',
            'specie' => 'required',
            'age' => 'required|integer',
            // Agrega más validaciones según tus necesidades
        ]);

        // Crea una nueva mascota
        $pet = new Pet([
            'name' => $request->input('name'),
            'specie' => $request->input('specie'),
            'age' => $request->input('age'),
            // Completa con otros campos
        ]);

        $pet->save();

        return redirect('/pets')->with('success', 'Mascota creada con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pet = Pet::find($id);
        return view('pets.show', compact('pet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pet = Pet::find($id);
        return view('pets.edit', compact('pet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Valida los datos del formulario
        $request->validate([
            'name' => 'required',
            'specie' => 'required',
            'age' => 'required|integer',
            // Agrega más validaciones según tus necesidades
        ]);

        $pet = Pet::find($id);

        // Actualiza los datos de la mascota
        $pet->name = $request->input('name');
        $pet->specie = $request->input('specie');
        $pet->age = $request->input('age');
        // Completa con otros campos

        $pet->save();

        return redirect('/pets')->with('success', 'Mascota actualizada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pet = Pet::find($id);
        $pet->delete();

        return redirect('/pets')->with('success', 'Mascota eliminada con éxito');
    }
}
