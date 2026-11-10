<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Admin;
use App\Models\Prestataire;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest')->only(['showRegistrationForm', 'register']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Get a validator for admin registration.
     */
    protected function adminValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Get a validator for prestataire registration.
     */
    protected function prestataireValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:prestataires,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Admin register form show.
     */
    public function AdminRegisterForm()
    {
        if (Admin::count() > 0 && !Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Veuillez vous connecter en tant qu\'administrateur pour ajouter un administrateur.');
        }

        return view('auth.adminRegister');
    }

    /**
     * Create a new admin instance after a valid registration.
     */
    public function createAdmin(Request $request)
    {
        if (Admin::count() > 0 && !Auth::guard('admin')->check()) {
            abort(403, 'Action non autorisée. Seuls les administrateurs connectés peuvent créer un nouvel administrateur.');
        }

        $this->adminValidator($request->all())->validate();

        Admin::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        return redirect()->route('admin.login')->with('success', 'Administrateur créé avec succès.');
    }

    /**
     * Prestataire register form show.
     */
    public function PrestataireRegisterForm()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Seuls les administrateurs peuvent enregistrer un prestataire.');
        }

        return view('auth.prestataireRegister');
    }

    /**
     * Create a new Prestataire instance after a valid registration.
     */
    public function createPrestataire(Request $request)
    {
        if (!Auth::guard('admin')->check()) {
            abort(403, 'Action non autorisée. Seuls les administrateurs peuvent enregistrer un prestataire.');
        }

        $this->prestataireValidator($request->all())->validate();

        Prestataire::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        return redirect()->route('prestataire.login')->with('success', 'Prestataire créé avec succès.');
    }
}
