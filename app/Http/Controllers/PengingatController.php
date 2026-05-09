<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengingat;
use App\Models\Pet;

class PengingatController extends Controller
{
    public function index()
    {
        return view('pengingat.pengingat_list', [
            'aktif'   => Pengingat::where('user_id', auth()->id())->where('status', 'aktif')->get(),
            'selesai' => Pengingat::where('user_id', auth()->id())->where('status', 'selesai')->get(),
        ]);
    }

    public function create()
    {
        $pets = Pet::where('user_id', auth()->id())->get();

        return view('pengingat.pengingat_create', compact('pets'));
    }

    public function store(Request $request)
{
    $request->validate([
        'pet_id' => 'required|exists:pets,id',
        'kategori' => 'required',
        'tanggal' => 'required',
        'waktu' => 'required',
    ]);

    // ambil data hewan
    $pet = Pet::findOrFail($request->pet_id);

    Pengingat::create([
        'user_id' => auth()->id(),
        'pet_id' => $pet->id,
        'nama_hewan' => $pet->name, // otomatis isi nama hewan
        'kategori' => $request->kategori,
        'tanggal' => $request->tanggal,
        'waktu' => $request->waktu,
        'deskripsi' => $request->deskripsi,
        'status' => 'aktif',
    ]);

    return redirect()->route('pengingat.list')
    ->with('success', 'Pengingat berhasil ditambahkan');
}

    public function selesai($id)
    {
        Pengingat::where('id', $id)->where('user_id', auth()->id())->update(['status' => 'selesai']);
        return back()->with('success', 'Pengingat ditandai selesai!');
    }

    public function delete($id)
    {
        Pengingat::where('id', $id)->where('user_id', auth()->id())->delete();
        return back()->with('success', 'Pengingat berhasil dihapus!');
    }
}