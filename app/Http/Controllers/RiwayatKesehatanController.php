<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatKesehatan;
use App\Models\Pet;
use Carbon\Carbon;

class RiwayatKesehatanController extends Controller
{
    public function index()
    {
        $riwayats = RiwayatKesehatan::where('user_id', auth()->id())
            ->with('pet') // ✅ Load relasi pet
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->get();

        return view('riwayat.kesehatan', [
            'riwayats' => $riwayats,
            'totalPemeriksaan' => $riwayats->count(),
            'hewanDiperiksa' => $riwayats->pluck('pet_id')->unique()->count(),
            'bulanIni' => $riwayats->filter(function ($item) {
                return Carbon::parse($item->tanggal_pemeriksaan)->isCurrentMonth();
            })->count(),
        ]);
    }

    public function create()
    {
        // ✅ Ambil semua hewan milik user yang login
        $pets = Pet::where('user_id', auth()->id())->get();
        
        return view('riwayat.create', compact('pets'));
    }

    public function store(Request $request)
{
    $request->validate([
        'pet_id' => 'required|exists:pets,id',
        'tanggal_pemeriksaan' => 'required',
        'diagnosis' => 'required',
    ]);

    // ambil data hewan
    $pet = Pet::findOrFail($request->pet_id);

RiwayatKesehatan::create([
    'pet_id' => $pet->id,
    'nama_hewan' => $pet->nama ?? $pet->name,
    'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
    'diagnosis' => $request->diagnosis,
    'tindakan' => $request->tindakan,
    'dokter' => $request->dokter,
    'catatan' => $request->catatan,
    'jadwal_berikutnya' => $request->jadwal_berikutnya,
    'user_id' => auth()->id(),
]);

    return redirect()->route('riwayat')
        ->with('success', 'Riwayat kesehatan berhasil ditambahkan');
}

    public function edit($id)
    {
        $riwayat = RiwayatKesehatan::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('pet')
            ->firstOrFail();

        $pets = Pet::where('user_id', auth()->id())->get();

        return view('riwayat.edit', compact('riwayat', 'pets'));
    }

   public function update(Request $request, $id)
{
    $validated = $request->validate([
        'tanggal_pemeriksaan' => 'required|date',
        'diagnosis' => 'required|string|max:255',
        'tindakan' => 'required|string',
        'dokter' => 'required|string|max:255',
        'catatan' => 'nullable|string',
        'jadwal_berikutnya' => 'nullable|date|after_or_equal:tanggal_pemeriksaan',
    ]);

    RiwayatKesehatan::where('id', $id)
        ->where('user_id', auth()->id())
        ->update($validated);

    return redirect()
        ->route('riwayat')
        ->with('success', 'Riwayat kesehatan berhasil diperbarui!');
}

    public function destroy($id)
    {
        RiwayatKesehatan::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return redirect()
            ->route('riwayat')
            ->with('success', 'Riwayat kesehatan berhasil dihapus!');
    }
}