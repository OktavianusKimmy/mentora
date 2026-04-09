<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        # Graph weekly
        $weekly = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');

            $weekly[$date] = [
                'total' => 0,
                'TPS' => 0,
                'Numerasi' => 0,
                'Literasi' => 0,
            ];
        }

        if ($user) {
            $sessions = DB::table('study_sessions')
                ->where('user_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->subDays(6))
                ->get();

            foreach ($sessions as $session) {
                $date = Carbon::parse($session->created_at)->format('Y-m-d');

                if (isset($weekly[$date])) {
                    $hours = $session->duration / 60;

                    $weekly[$date]['total'] += $hours;
                    $weekly[$date][$session->category] += $hours;
                }
            }
        }

        # Graph kategori
        if ($user) {
            $data = DB::table('study_sessions')
                ->select('category', DB::raw('SUM(duration) as total'))
                ->where('user_id', $user->id)
                ->groupBy('category')
                ->pluck('total', 'category');

            $tps = ($data['TPS'] ?? 0) / 60;
            $numerasi = ($data['Numerasi'] ?? 0) / 60;
            $literasi = ($data['Literasi'] ?? 0) / 60;
        } else {
            $tps = 0;
            $numerasi = 0;
            $literasi = 0;
        }

        $max = max($tps, $numerasi, $literasi, 1); // biar ga bagi 0

        return view('home', compact(
            'tps',
            'numerasi',
            'literasi',
            'max',
            'weekly'
        ));
    }
}