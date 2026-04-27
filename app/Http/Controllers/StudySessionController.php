<?php

namespace App\Http\Controllers;

use App\Models\StudyGroup;
use App\Models\StudySession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudySessionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Ambil data statistik asli
        $todayMinutes = StudySession::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->sum('duration');

        $todaySessions = StudySession::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        // 2. Ambil Daftar Room Study asli dari Database
        $studyGroups = StudyGroup::with('creator')->latest()->get();

        return view('study-room', compact('todayMinutes', 'todaySessions', 'studyGroups'));
    }

    // Fungsi untuk membuat Room baru
    public function storeGroup(Request $request)
    {
        // Proteksi: Hanya Admin atau Tutor yang bisa buat (Opsional sesuai role kamu nanti)
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:100',
        ]);

        StudyGroup::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
            'created_by' => Auth::id(),
        ]);

        return back()->with([
            'success' => 'Room study berhasil dibuat!',
            'active_tab' => 'group' // Sinyal tab aktif
        ]);
    }

    // Fungsi untuk menghapus Room
    public function destroyGroup(StudyGroup $group)
    {
        // Cek apakah user berhak menghapus (opsional tapi bagus buat keamanan)
        if (Auth::user()->role === 'admin' || Auth::id() === $group->created_by) {
            $group->delete();
            
            return back()->with([
                'success' => 'Room berhasil dihapus!',
                'active_tab' => 'group' // Sinyal biar tetep di tab group
            ]);
        }

        return back()->with('error', 'Kamu tidak punya akses menghapus room ini.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'duration' => ['required', 'integer', 'min:1'],
        ]);

        $session = StudySession::create([
            'user_id' => Auth::id(),
            'duration' => $validated['duration'],
            'category' => 'focus', 
        ]);

        return response()->json([
            'message' => 'Session saved successfully',
            'session' => $session,
        ]);
    }

    public function stats(Request $request)
    {
        $user = $request->user();

        $todayMinutes = StudySession::query()
            ->where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->sum('duration');

        $todaySessions = StudySession::query()
            ->where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        $weekly = StudySession::query()
            ->selectRaw('DATE(created_at) as date, SUM(duration) as total')
            ->where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $weeklyData = collect(range(0, 6))->map(function ($i) use ($weekly) {
            $date = now()->subDays(6 - $i)->toDateString();

            return [
                'date' => $date,
                'label' => Carbon::parse($date)->translatedFormat('D'),
                'minutes' => (int) ($weekly[$date]->total ?? 0),
            ];
        });

        return response()->json([
            'today_minutes' => (int) $todayMinutes,
            'today_sessions' => (int) $todaySessions,
            'streak' => $this->calculateStreak($user->id),
            'weekly' => $weeklyData,
        ]);
    }

    private function calculateStreak(int $userId): int
    {
        $streak = 0;
        $date = today();

        while (true) {
            $hasSession = StudySession::query()
                ->where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->exists();

            if (! $hasSession) {
                break;
            }

            $streak++;
            $date = $date->copy()->subDay();
        }

        return $streak;
    }
}