<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lelang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Notifications\AuctionEndedNotification;

class WebhookController extends Controller
{
    public function notify(Request $request)
    {
        // Validasi data yang diterima dari webhook
        $request->validate([
            'id' => 'required|exists:lelangs,id',
            'status' => 'required|string',
        ]);

        // Temukan lelang berdasarkan ID
        $lelang = Lelang::find($request->id);

        if ($lelang) {
            // Perbarui status lelang
            $lelang->status = 'berakhir';
            $lelang->save();
            $users = User::all(); // Anda bisa menyesuaikan ini untuk hanya pengguna yang berpartisipasi
            foreach ($users as $user) {
                $user->notify(new AuctionEndedNotification($lelang));
            }

            // Log informasi untuk debugging
            Log::info('Status lelang telah diperbarui', [
                'id' => $lelang->id,
                'new_status' => $lelang->status,
            ]);

            // Kembalikan respons sukses
            return response()->json([
                'message' => 'Status lelang berhasil diperbarui.',
                'lelang' => $lelang,
            ], 200);
        }

        // Jika lelang tidak ditemukan, kembalikan respons error
        return response()->json([
            'message' => 'Lelang tidak ditemukan.',
        ], 404);
    }
}
