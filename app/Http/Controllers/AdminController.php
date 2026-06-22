<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerificationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class AdminController extends Controller
{

    // controller sa logout
    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with([
            'message' => 'Logged out successfully',
            'alert-type' => 'success'
        ]);
    }

    // Generate OTP (reusable method para hindi redundant
    private function generateOtp(): int
    {
        return random_int(100000, 999999);
    }

    private function storeOtpSession(int $code): void
    {
        session([
            'verification_code' => $code,
            'verification_expires_at' => now()->addMinutes(5),
        ]);
    }

     private function clearOtpSession(): void
    {
        session()->forget([
            'verification_code',
            'verification_expires_at',
            'user_id'
        ]);
    }

    // controller sa login
    public function AdminLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Invalid Credentials Provided'
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        $code = $this->generateOtp();

        session(['user_id' => $user->id]);

        $this->storeOtpSession($code);

        Mail::to($user->email)->send(new VerificationCodeMail($code));

        Auth::logout(); // force logout until verified

        return redirect()
            ->route('custom.verification.form')
            ->with('status', 'Verification Code sent to your mail');
    }

    public function ShowVerification()
    {
        return view('auth.verify');
    }


        public function VerificationVerify(VerificationRequest $request)
        {
            // check session user
            if (!session()->has('user_id')) {
                return redirect('/login');
            }
            // expiration check
            if (
                !session()->has('verification_expires_at') ||
                now()->greaterThan(session('verification_expires_at'))
            ) {
                $this->clearOtpSession();

                return back()->withErrors([
                    'code' => 'Code is expired. Please request a new OTP.'
                ]);
            }
            // correct code
            if ($request->code == session('verification_code')) {

                Auth::loginUsingId(session('user_id'));

                $this->clearOtpSession();

                return redirect('/dashboard');
            }
            return back()->withErrors([
                'code' => 'Invalid code.'
            ]);
        }

        // controller para sa resent ng otp
    public function resendOtp()
    {
        $user = User::find(session('user_id'));

        if (!$user) {
            session()->flush();
            return redirect('/login');
        }

        $code = $this->generateOtp();
        $this->storeOtpSession($code);

        Mail::to($user->email)->send(new VerificationCodeMail($code));

        return redirect()
            ->route('custom.verification.form')
            ->with('status', 'New OTP sent!');
    }


}
