<?php

namespace App\Http\Controllers;

use App\Mail\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    public function mail(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if($user) {
            $token = str()->random(64);

            DB::table('password_resets')->insert([
                'email' => $user->email,
                'token' => $token
            ]);

            Mail::to($user->email)->send(new PasswordReset($token));

            return 'success';
        } else {
            return 'failed';
        }
    }

    public function show($token)
    {
        $item = DB::table('password_resets')->where('token', $token)->first();

        if($item) {
            $email = $item->email;

            return view('auth.reset-password', compact('token', 'email'));
        } else {
            return view('auth.reset-password');
        }
    }

    public function update(Request $request)
    {
        $item = DB::table('password_resets')->where('token', $request->token)->first();

        if($item) {
            $request->validate([
                'password' => 'required|confirmed|min:5',
            ]);

            $user = User::where('email', $item->email)->first();
            $user->password = bcrypt($request->password);
            $user->save();

            DB::table('password_resets')->where('token', $request->token)->delete();

            return redirect()->route('home');
        } else {
            return 'Ваш ip будет забанен, при нескольких попыток взлома сайта!';
        }
    }
}