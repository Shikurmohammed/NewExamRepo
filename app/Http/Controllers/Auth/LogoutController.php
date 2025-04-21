<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            //$user->update(['is_online' => false]); // Mark user as offline
            DB::table('users')->where('id', $user->id)->update(['is_online' => 0]);
        }
        // dd($user);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // Redirect after logout
    }
}
