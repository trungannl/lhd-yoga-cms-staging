<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Repositories\Staff\StaffRepositoryInterface;
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

    protected $staffRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StaffRepositoryInterface $staffRepository)
    {
        $this->middleware('guest')->except('logout');
        $this->staffRepository = $staffRepository;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);
        if (Auth::guard('web')->attempt($credentials)) {

            if (!Auth::user()->joined_date) {
                $this->staffRepository->update(Auth::user()->id, ['joined_date' => date('Y-m-d H:i:s'), 'last_login' => date('Y-m-d H:i:s')]);
                return redirect()->route('profile.change_password');
            }

            $this->staffRepository->update(Auth::user()->id, ['last_login' => date('Y-m-d H:i:s')]);

//            if (!Auth::user()->can('dashboard')) {
//                return redirect()->route('profile');
//            }

            return redirect()->route('dashboard');

        } else {
            return redirect()->back()->withInput()->withErrors(['These credentials do not match our records.']);
        }
    }
}
