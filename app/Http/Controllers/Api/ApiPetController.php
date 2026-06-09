<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use Illuminate\Support\Facades\Storage;

class ApiPetController extends Controller
{
    /**
     * List semua hewan milik user
     */
    public function index(Request $request)
    {
        $pets = Pet::where('user_id', $request->user()->id)->get();

        return response()->json([
            'success'    => true,
            'total'      => $pets->count(),
            'pets'       => $pets->map(function ($pet) {
                return [
                    'id'            => $pet->id,
                    'name'          => $pet->name,
                    'species'       => $pet->species,
                    'breed'         => $pet->breed,
                    'gender'        => $pet->gender,
                    'birth_date'    => $pet->birth_date,
                    'age'           => $pet->age,
                    'weight'        => $pet->weight,
                    'special_marks' => $pet->special_marks,
                    'is_steril'     => $pet->is_steril,
                    'allergies'     => $pet->allergies,
                    'health_notes'  => $pet->health_notes,
                    'photo'         => $pet->photo ? asset('storage/' . $pet->photo) : null,
                    'created_at'    => $pet->created_at,
                ];
            }),
        ]);
    }

    /**
     * Detail satu hewan
     */
    public function show(Request $request, $id)
    {
        $pet = Pet::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Hewan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'pet'     => [
                'id'            => $pet->id,
                'name'          => $pet->name,
                'species'       => $pet->species,
                'breed'         => $pet->breed,
                'gender'        => $pet->gender,
                'birth_date'    => $pet->birth_date,
                'age'           => $pet->age,
                'weight'        => $pet->weight,
                'special_marks' => $pet->special_marks,
                'is_steril'     => $pet->is_steril,
                'allergies'     => $pet->allergies,
                'health_notes'  => $pet->health_notes,
                'photo'         => $pet->photo ? asset('storage/' . $pet->photo) : null,
                'created_at'    => $pet->created_at,
            ],
        ]);
    }

    /**
     * Tambah hewan baru
     */
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
            'photo'         => 'nullable|image|max:5120',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('pets', 'public');
        }

        $pet = Pet::create([
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

        return response()->json([
            'success' => true,
            'message' => 'Hewan berhasil ditambahkan!',
            'pet'     => $pet,
        ], 201);
    }

    /**
     * Update hewan
     */
    public function update(Request $request, $id)
    {
        $pet = Pet::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Hewan tidak ditemukan.',
            ], 404);
        }

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

        return response()->json([
            'success' => true,
            'message' => 'Data hewan berhasil diperbarui!',
            'pet'     => $pet->fresh(),
        ]);
    }

    /**
     * Hapus hewan
     */
    public function destroy(Request $request, $id)
    {
        $pet = Pet::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Hewan tidak ditemukan.',
            ], 404);
        }

        if ($pet->photo) {
            Storage::disk('public')->delete($pet->photo);
        }

        $pet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hewan berhasil dihapus!',
        ]);
    }
}
