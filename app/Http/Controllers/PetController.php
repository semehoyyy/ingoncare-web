<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $pets = Pet::where('user_id', $user->id)->get();
        $totalHewan = $pets->count();

        return view('pets.hewan_saya', compact('pets', 'totalHewan'));
    }

    public function create()
    {
        return view('pets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'species'       => 'required|string|max:255',
            'breed'         => 'nullable|string|max:255',
            'gender'        => 'required|string',
            'birth_date'    => 'required|date',
            'weight'        => 'nullable|numeric',
            'special_marks' => 'nullable|string',
            'is_steril'     => 'required|boolean',
            'allergies'     => 'nullable|string',
            'health_notes'  => 'nullable|string',
            'photo'         => 'nullable|image|max:5120'
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('pets', 'public');
        }

        Pet::create([
            'user_id'       => $request->user()->id,
            'name'          => $request->name,
            'species'       => $request->species,
            'breed'         => $request->breed,
            'gender'        => $request->gender,
            'birth_date'    => $request->birth_date,
            'weight'        => $request->weight,
            'special_marks' => $request->special_marks,
            'is_steril'     => $request->is_steril,
            'allergies'     => $request->allergies,
            'health_notes'  => $request->health_notes,
            'photo'         => $photoPath,
        ]);

        return redirect()->route('hewan-saya')
            ->with('success', 'Hewan berhasil ditambahkan!');
    }

    public function show(Pet $pet)
    {
        return view('pets.show', compact('pet'));
    }

    public function edit(Pet $pet)
    {
        return view('pets.edit', compact('pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'species'       => 'required|string|max:255',
            'breed'         => 'nullable|string|max:255',
            'gender'        => 'required|string',
            'birth_date'    => 'required|date',
            'weight'        => 'nullable|numeric',
            'special_marks' => 'nullable|string',
            'is_steril'     => 'required|boolean',
            'allergies'     => 'nullable|string',
            'health_notes'  => 'nullable|string',
            'photo'         => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            if ($pet->photo) {
                Storage::disk('public')->delete($pet->photo);
            }

            $pet->photo = $request->file('photo')->store('pets', 'public');
        }

        $pet->update([
            'name'          => $request->name,
            'species'       => $request->species,
            'breed'         => $request->breed,
            'gender'        => $request->gender,
            'birth_date'    => $request->birth_date,
            'weight'        => $request->weight,
            'special_marks' => $request->special_marks,
            'is_steril'     => $request->is_steril,
            'allergies'     => $request->allergies,
            'health_notes'  => $request->health_notes,
        ]);

        return redirect()->route('hewan-saya')
            ->with('success', 'Data hewan berhasil diperbarui!');
    }

    public function destroy(Pet $pet)
    {
        if ($pet->photo) {
            Storage::disk('public')->delete($pet->photo);
        }

        $pet->delete();

        return redirect()->route('hewan-saya')
            ->with('success', 'Hewan berhasil dihapus!');
    }
}