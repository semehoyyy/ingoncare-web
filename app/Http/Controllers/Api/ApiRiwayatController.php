<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatKesehatan;
use App\Models\Pet;
use Carbon\Carbon;

class ApiRiwayatController extends Controller
{
    /**
     * List riwayat kesehatan user
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $riwayats = RiwayatKesehatan::where('user_id', $userId)
            ->with('pet')
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->get();

        return response()->json([
            'success'           => true,
            'total_pemeriksaan' => $riwayats->count(),
            'hewan_diperiksa'   => $riwayats->pluck('pet_id')->unique()->count(),
            'bulan_ini'         => $riwayats->filter(function ($item) {
                return Carbon::parse($item->tanggal_pemeriksaan)->isCurrentMonth();
            })->count(),
            'riwayats' => $riwayats->map(function ($r) {
                return [
                    'id'                   => $r->id,
                    'pet_id'               => $r->pet_id,
                    'nama_hewan'           => $r->nama_hewan,
                    'pet_name'             => $r->pet?->name,
                    'tanggal_pemeriksaan'  => $r->tanggal_pemeriksaan,
                    'diagnosis'            => $r->diagnosis,
                    'tindakan'             => $r->tindakan,
                    'dokter'               => $r->dokter,
                    'catatan'              => $r->catatan,
                    'jadwal_berikutnya'    => $r->jadwal_berikutnya,
                    'created_at'           => $r->created_at,
                ];
            }),
        ]);
    }

    /**
     * Tambah riwayat kesehatan
     */
    public function store(Request $request)
    {
        $request->validate([
            'pet_id'               => 'required|exists:pets,id',
            'tanggal_pemeriksaan'  => 'required|date',
            'diagnosis'            => 'required|string',
            'tindakan'             => 'nullable|string',
            'dokter'               => 'nullable|string|max:255',
            'catatan'              => 'nullable|string',
            'jadwal_berikutnya'    => 'nullable|date',
        ]);

        $pet = Pet::findOrFail($request->pet_id);

        if ($pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Hewan tidak ditemukan.',
            ], 403);
        }

        $riwayat = RiwayatKesehatan::create([
            'pet_id'              => $pet->id,
            'nama_hewan'          => $pet->name,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'diagnosis'           => $request->diagnosis,
            'tindakan'            => $request->tindakan,
            'dokter'              => $request->dokter,
            'catatan'             => $request->catatan,
            'jadwal_berikutnya'   => $request->jadwal_berikutnya,
            'user_id'             => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat kesehatan berhasil ditambahkan!',
            'riwayat' => $riwayat,
        ], 201);
    }

    /**
     * Update riwayat kesehatan
     */
    public function update(Request $request, $id)
    {
        $riwayat = RiwayatKesehatan::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$riwayat) {
            return response()->json([
                'success' => false,
                'message' => 'Riwayat tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'tanggal_pemeriksaan' => 'required|date',
            'diagnosis'           => 'required|string|max:255',
            'tindakan'            => 'nullable|string',
            'dokter'              => 'nullable|string|max:255',
            'catatan'             => 'nullable|string',
            'jadwal_berikutnya'   => 'nullable|date',
        ]);

        $riwayat->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat kesehatan berhasil diperbarui!',
            'riwayat' => $riwayat->fresh(),
        ]);
    }

    /**
     * Hapus riwayat kesehatan
     */
    public function destroy(Request $request, $id)
    {
        $riwayat = RiwayatKesehatan::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$riwayat) {
            return response()->json([
                'success' => false,
                'message' => 'Riwayat tidak ditemukan.',
            ], 404);
        }

        $riwayat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat kesehatan berhasil dihapus!',
        ]);
    }
}
