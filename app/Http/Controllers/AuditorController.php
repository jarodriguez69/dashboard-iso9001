<?php

namespace App\Http\Controllers;

use App\Models\Auditor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuditorController extends Controller
{
    public function index()
    {
        // Traemos todos los auditores ordenados alfabéticamente
        $auditores = Auditor::orderBy('nombre')->get();
        return view('auditores.index', compact('auditores'));
    }

    public function create()
    {
        // Mostramos el formulario
        return view('auditores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required',
            'firma' => 'nullable|image|mimes:png,jpg,jpeg|max:1024', // Validamos que sea imagen < 1MB
        ]);

        $data = $request->all();

        if ($request->hasFile('firma')) {
            // Guardamos la imagen en la carpeta 'public/firmas'
            $data['firma'] = $request->file('firma')->store('firmas', 'public');
        }

        Auditor::create($data);
        return redirect()->route('auditores.index')->with('success', 'Auditor creado.');
    }

    public function edit(Auditor $auditor)
    {
        return view('auditores.edit', compact('auditor'));
    }

    public function update(Request $request, Auditor $auditor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required',
            'firma' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
        ]);

        $data = $request->all();

        if ($request->hasFile('firma')) {
            // Ahora puedes usar Storage directamente de forma limpia
            if($auditor->firma) { 
                Storage::disk('public')->delete($auditor->firma); 
            }
            
            $data['firma'] = $request->file('firma')->store('firmas', 'public');
        }

        $auditor->update($data);
        return redirect()->route('auditores.index')->with('success', 'Auditor actualizado.');
    }

    public function destroy(Auditor $auditor)
    {
        $auditor->delete();
        return redirect()->route('auditores.index')->with('success', 'Auditor eliminado.');
    }
}
