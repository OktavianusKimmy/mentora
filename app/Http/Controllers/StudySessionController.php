<?php

namespace App\Http\Controllers;

use App\Models\StudySession;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudySessionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'duration' => ['required', 'integer', 'min:1'],
        ]);

        $session = StudySession::create([
            'user_id' => $request->user()->id,
            'duration' => $validated['duration'],
            'category' => 'focus', // <--- UBAH DI SINI: null diganti jadi 'focus'
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