<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Mail\EmailVerifyNotification;
use App\Models\User;
use App\Models\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthenticationController extends Controller
{
    public function showLoginPage()
    {
        return view('auth.login');
    }

    public function register(Request $request)
    {
        $failedInputs = [];
        $errorMessages = [];

        //name
        if (User::where('name', $request->name)->first()) {
            array_push($failedInputs, 'name');
            array_push($errorMessages, 'Корбаре бо чуни ном аллакай вуҷуд дорад');
        }
        //email
        if (User::where('email', $request->email)->first()) {
            array_push($failedInputs, 'email');
            array_push($errorMessages, 'Корбаре бо чунин почтаи электронӣ аллакай вуҷуд дорад');
        }
        //password
        if (mb_strlen($request->password) < 5) {
            array_push($failedInputs, 'password');
            array_push($errorMessages, 'Парол хеле кӯтоҳ аст');
        }
        //password-confirmartion
        if ($request->password != $request->password_confirmation) {
            array_push($failedInputs, 'password_confirmation');
            array_push($errorMessages, 'Рамзи номувофиқ');
        }

        //return erros on validation fail
        if (count($failedInputs)) {
            return [
                'validation' => 'failed',
                'failedInputs' => $failedInputs,
                'errorMessages' => $errorMessages,
            ];
        }

        //else store user and redirect to email verify notification page
        $user = User::create([
            'name' => $request->name,
            'slug' => Helper::generateUniqueSlug($request->name, 'App\Models\User'),
            'email' => $request->email,
            'verified_email' => false,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'gender' => $request->gender,
            'image' => UserController::DEFAULT_IMAGE
        ]);

        //send email veify notification
        $token = str()->random(64);

        VerifyEmail::create([
            'email' => $user->email,
            'token' => $token
        ]);

        Auth::login($user, true);

        Mail::to($user->email)->send(new EmailVerifyNotification($token));

        return [
            'validation' => 'success'
        ];
    }

    public function login(Request $request)
    {
        $authenticate = Auth::attempt(['email' => $request->email, 'password' => $request->password], true);
        $ajaxRequest = $request->ajax == '1' ? true : false;

        // on success authentication
        if ($authenticate) {
            $request->session()->regenerate();

            return $ajaxRequest ? 'success' : redirect()->intended(route('home'));
            // if authenticate failed
        } else {
            return $ajaxRequest ? 'failed' : redirect()->back()->withInput()->withErrors(['auth' => 'failed']);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->intended('/');
    }
}
