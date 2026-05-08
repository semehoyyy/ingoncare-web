<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengingat;

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
        return view('pengingat.pengingat_create');
    }

    public function store(Request $req)
    {
        $req->validate([
            'nama_hewan' => 'required|string|max:255',
            'kategori'   => 'required|string|max:255',
            'tanggal'    => 'required|date',
            'waktu'      => 'required',
            'deskripsi'  => 'nullable|string',
        ]);

        Pengingat::create([
            'user_id'    => auth()->id(),
            'nama_hewan' => $req->nama_hewan,
            'kategori'   => $req->kategori,
            'tanggal'    => $req->tanggal,
            'waktu'      => $req->waktu,
            'deskripsi'  => $req->deskripsi,
            'status'     => 'aktif',
        ]);

        return redirect()->route('pengingat.list')->with('success', 'Pengingat berhasil ditambahkan!');
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