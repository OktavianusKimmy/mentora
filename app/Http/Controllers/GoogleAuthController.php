<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            
            // Cari user berdasarkan google_id atau email
            $findUser = User::where('google_id', $user->id)
                            ->orWhere('email', $user->email)
                            ->first();
       
            if($findUser){
                // Kalau user sudah ada, update google_id-nya (jika belum ada) lalu login
                if (!$findUser->google_id) {
                    $findUser->update(['google_id' => $user->id]);
                }
                Auth::login($findUser);
                return redirect()->intended('/');
            }else{
                // Kalau user belum ada, buat user baru
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => Hash::make('mentora-google-auth-' . str()->random(10)) 
                ]);
      
                Auth::login($newUser);
                return redirect()->intended('/');
            }
      
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google.');
        }
    }
}