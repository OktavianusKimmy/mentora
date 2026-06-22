<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;  

class TutorController extends Controller
{
    public function index()
    {
        $tutors = User::where('role', 'tutor')->get();
        return view('tutor.index', compact('tutors'));
    }


    public function applyPage()
    {
        return view('tutor.apply');
    }

    public function apply(Request $request)
    {
        $request->validate([
            'utbk_file' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'reason' => 'required'
        ]);
        
        if (DB::table('tutor_applications')->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'Kamu sudah pernah apply!');
        }

        // upload file
        $path = $request->file('utbk_file')->store('utbk_files', 'public');

        DB::table('tutor_applications')->insert([
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'utbk_file' => $path,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return redirect()->route('tutor')->with('success', 'Apply berhasil, tunggu approval!');
    }

    public function show($name)
    {
        // dummy data
        $tutors = [
            'Andi Pratama' => [
                'name' => 'Andi Pratama',
                'email' => 'andi@gmail.com',
                'subject' => 'TPS',
                'rating' => 4.8,
                'bio' => 'Tutor berpengalaman dalam TPS, fokus logika dan penalaran.',
            ],
            'Budi Santoso' => [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'subject' => 'Numerasi',
                'rating' => 4.7,
                'bio' => 'Spesialis numerasi dan strategi cepat UTBK.',
            ],
            'Citra Lestari' => [
                'name' => 'Citra Lestari',
                'email' => 'citra@gmail.com',
                'subject' => 'Bahasa Indonesia',
                'rating' => 4.9,
                'bio' => 'Tutor ahli dalam Bahasa Indonesia, fokus pada pemahaman dan analisis teks.',
            ],
        ];

        $tutor = $tutors[$name] ?? null;

        if (!$tutor) {
            abort(404);
        }

        return view('tutor.show', compact('tutor'));
    }
}