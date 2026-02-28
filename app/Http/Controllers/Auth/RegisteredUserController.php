<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function registro(): View
    {
        return view('registro');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request): RedirectResponse
    {
        // Normalizar email en minúsculas para que no falle la validación 'lowercase'
        if ($request->has('email')) {
            $request->merge(['email' => Str::lower($request->input('email'))]);
        }

        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'date' => ['nullable', 'date'],
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date' => $request->input('date') ?: now()->toDateString(),
        ]);

        // Crear perfil de usuario con username opcional
        UserProfile::create([
            'user_id' => $user->id,
            'username' => $request->email, // Usar email como username por defecto
            'bio' => null,
            'phone' => null
        ]);

        event(new Registered($user));

    Auth::login($user);
    // Regenerar sesión para prevenir fijación y asegurar persistencia de la bandera
    $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function Userprofile(){
        return $this->hasOne(UserProfile::class);
    }
}
