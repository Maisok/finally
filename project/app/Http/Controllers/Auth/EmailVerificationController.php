<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function notice()
    {
        return view('auth.verify-email');
    }

    public function verify(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Недействительная ссылка для подтверждения');
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->markEmailAsVerified();

        return redirect()->route('dashboard')->with('status', 'Email успешно подтвержден!');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Ссылка для подтверждения отправлена!');
    }
}