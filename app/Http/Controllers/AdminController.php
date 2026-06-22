<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function applications()
    {
        $pendingApps = DB::table('tutor_applications')
            ->join('users', 'users.id', '=', 'tutor_applications.user_id')
            ->select(
                'tutor_applications.*',
                'users.name',
                'users.email',
                'users.role'
            )
            ->where('tutor_applications.status', 'pending')
            ->latest()
            ->get();

        $approvedApps = DB::table('tutor_applications')
            ->join('users', 'users.id', '=', 'tutor_applications.user_id')
            ->select(
                'tutor_applications.*',
                'users.name',
                'users.email',
                'users.role'
            )
            ->where('tutor_applications.status', 'approved')
            ->latest()
            ->get();

        return view('admin.tutor_applications', compact(
            'pendingApps',
            'approvedApps'
        ));
    }

    public function approve($id){
        $app = DB::table('tutor_applications')->where('id', $id)->first();

        DB::table('users')
            ->where('id', $app->user_id)
            ->update(['role' => 'tutor']);

        DB::table('tutor_applications')
            ->where('id', $id)
            ->update(['status' => 'approved']);

        return back();
    }
}
