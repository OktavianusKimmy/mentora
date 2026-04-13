<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudySession; 
use Illuminate\Support\Facades\Auth; 

class StudyRoomController extends Controller
{
    public function index(Request $request)
    {
        // 2. Kita tampung dulu ID user yang sedang login agar tidak error
        $userId = Auth::id();

        $todayMinutes = StudySession::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('duration');

        $todaySessions = StudySession::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->count();

        return view('study-room', compact('todayMinutes', 'todaySessions'));
    }

    public function storeSession(Request $request)
    {
        // 1. Validasi request dari JavaScript
        $request->validate([
            'duration' => 'required|integer'
        ]);

        // 2. Pastikan ada user yang sedang login menggunakan Facade Auth
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // 3. Simpan ke database sesuai struktur tabel kamu
        StudySession::create([
            'user_id' => Auth::id(), // Menggunakan Facade agar VS Code tidak merah
            'category' => 'focus',   // Kategori default
            'duration' => $request->duration,
        ]);

        // 4. Kasih respon sukses ke JavaScript
        return response()->json([
            'success' => true, 
            'message' => 'Sesi berhasil disimpan ke database!'
        ]);
    }
}