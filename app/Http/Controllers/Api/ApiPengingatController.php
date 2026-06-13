<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengingat;
use App\Models\Pet;
use App\Models\Notification;
use Carbon\Carbon;

class ApiPengingatController extends Controller
{
    /**
     * List pengingat user (aktif & selesai)
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $aktif = Pengingat::where('user_id', $userId)
            ->where('status', 'aktif')
            ->orderBy('tanggal', 'asc')
            ->get();

        $selesai = Pengingat::where('user_id', $userId)
            ->where('status', 'selesai')
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'aktif'   => $aktif,
            'selesai' => $selesai,
        ]);
    }

    /**
     * Tambah pengingat baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'pet_id'    => 'required|exists:pets,id',
            'kategori'  => 'required|string',
            'tanggal'   => 'required|date',
            'waktu'     => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        $pet = Pet::findOrFail($request->pet_id);

        // Pastikan pet milik user
        if ($pet->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Hewan tidak ditemukan.',
            ], 403);
        }

        $pengingat = Pengingat::create([
            'user_id'    => $request->user()->id,
            'pet_id'     => $pet->id,
            'nama_hewan' => $pet->name,
            'kategori'   => $request->kategori,
            'tanggal'    => $request->tanggal,
            'waktu'      => $request->waktu,
            'deskripsi'  => $request->deskripsi,
            'status'     => 'aktif',
        ]);

        // Buat notifikasi agar muncul di halaman Notifikasi Flutter
        $notifyAt = null;

        try {
            $notifyAt = Carbon::parse($request->tanggal . ' ' . $request->waktu);
        } catch (\Exception $e) {
            $notifyAt = Carbon::parse($request->tanggal);
        }

        Notification::create([
            'user_id'   => $request->user()->id,
            'type'      => 'pengingat',
            'title'     => 'Pengingat Hewan',
            'message'   => 'Pengingat ' . $request->kategori . ' untuk ' . $pet->name . ' telah dibuat.',
            'link'      => '/pengingat',
            'notify_at' => $notifyAt,
            'is_read'   => false,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Pengingat berhasil ditambahkan!',
            'pengingat' => $pengingat,
        ], 201);
    }

    /**
     * Tandai selesai
     */
    public function selesai(Request $request, $id)
    {
        $pengingat = Pengingat::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$pengingat) {
            return response()->json([
                'success' => false,
                'message' => 'Pengingat tidak ditemukan.',
            ], 404);
        }

        $pengingat->update(['status' => 'selesai']);

        Notification::create([
            'user_id'   => $request->user()->id,
            'type'      => 'pengingat',
            'title'     => 'Pengingat Selesai',
            'message'   => 'Pengingat ' . $pengingat->kategori . ' untuk ' . $pengingat->nama_hewan . ' telah ditandai selesai.',
            'link'      => '/pengingat',
            'notify_at' => now(),
            'is_read'   => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengingat ditandai selesai!',
        ]);
    }

    /**
     * Hapus pengingat
     */
    public function destroy(Request $request, $id)
    {
        $pengingat = Pengingat::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$pengingat) {
            return response()->json([
                'success' => false,
                'message' => 'Pengingat tidak ditemukan.',
            ], 404);
        }

        $pengingat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengingat berhasil dihapus!',
        ]);
    }
}