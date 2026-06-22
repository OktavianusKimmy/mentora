<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;  
use App\Models\Tutor;  

class TutorController extends Controller
{
    public function index()
    {
        $tutors = Tutor::with('user')
        ->latest()
        ->get();

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
            'tps' => $request->has('tps'),
            'literasi' => $request->has('literasi'),
            'numerasi' => $request->has('numerasi'),
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return redirect()->route('tutor')->with('success', 'Apply berhasil, tunggu approval!');
    }

    public function show($id)
    {
        $tutor = Tutor::with('user')->findOrFail($id);

        return view('tutor.show', compact('tutor'));
    }
}