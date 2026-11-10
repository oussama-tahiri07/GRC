<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'adminLoginForm', 'loginAdmin', 'logoutAdmin', 'prestataireLoginForm', 'loginPrestataire', 'logoutPrestataire']);
        $this->middleware('guest:admin')->only(['adminLoginForm', 'loginAdmin']);
        $this->middleware('guest:prestataire')->only(['prestataireLoginForm', 'loginPrestataire']);
    }

    // Admin login form
    public function adminLoginForm()
    {
        return view('auth.adminLogin');
    }

    // Admin login check with rate limiting
    public function loginAdmin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            return redirect()->intended('/admin/home');
        }

        $this->incrementLoginAttempts($request);
        return back()->withInput($request->only('email', 'remember'))->withErrors(['email' => trans('auth.failed')]);
    }

    // Admin logout
    public function logoutAdmin(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // Prestataire login form
    public function prestataireLoginForm()
    {
        return view('auth.prestataireLogin');
    }

    // Prestataire login check with rate limiting
    public function loginPrestataire(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if (Auth::guard('prestataire')->attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            return redirect()->intended('/prestataire/home');
        }

        $this->incrementLoginAttempts($request);
        return back()->withInput($request->only('email', 'remember'))->withErrors(['email' => trans('auth.failed')]);
    }

    // Prestataire logout
    public function logoutPrestataire(Request $request)
    {
        Auth::guard('prestataire')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('prestataire.login');
    }
}
