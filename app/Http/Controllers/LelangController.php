<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lelang;
use Illuminate\Http\Request;
use App\Notifications\NewAuctionNotification;

class LelangController extends Controller
{
    public function index()
    {
        return Lelang::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'harga_awal' => 'required|numeric',
            'waktu_berakhir' => 'required|date',
        ]);
        $auction = Lelang::create($request->all());

        // Mengirim notifikasi ke semua pengguna
        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new NewAuctionNotification($auction));
        }
        return Lelang::create($request->all());

    }

    public function show($id)
    {
        return Lelang::find($id);
    }

    public function update(Request $request, $id)
    {
        $lelang = Lelang::find($id);
        $lelang->update($request->all());
        return $lelang;
    }

    public function destroy($id)
    {
        $lelang = Lelang::find($id);
        if ($lelang) {
            $lelang->delete();
            return response()->json(['message' => 'Lelang deleted successfully.']);
        }
        return response()->json(['message' => 'Lelang not found.'], 404);
    }

    public function ended()
    {
        $lelangs = Lelang::where('waktu_berakhir', '<', now())->get();
        return response()->json($lelangs);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:lelangs,id',
            'status' => 'required|string',
        ]);
        $lelang = Lelang::find($request->id);
        $lelang->status = $request = "berakhir";
        $lelang->save();
        return response()->json($lelang);
    }
}
