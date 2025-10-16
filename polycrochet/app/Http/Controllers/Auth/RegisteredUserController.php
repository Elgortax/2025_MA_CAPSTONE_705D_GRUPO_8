<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'first_name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s\'-]+$/u'],
                'last_name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s\'-]+$/u'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'phone' => ['required', 'digits:9'],
                'password' => ['required', 'confirmed', Password::defaults()],
            ],
            [
                'first_name.required' => 'El nombre es obligatorio.',
                'first_name.string' => 'Ingresa un nombre válido.',
                'first_name.max' => 'El nombre no puede tener más de 255 caracteres.',
                'first_name.regex' => 'El nombre solo puede contener letras y espacios.',
                'last_name.required' => 'El apellido es obligatorio.',
                'last_name.string' => 'Ingresa un apellido válido.',
                'last_name.max' => 'El apellido no puede tener más de 255 caracteres.',
                'last_name.regex' => 'El apellido solo puede contener letras y espacios.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'Ingresa un correo electrónico válido.',
                'email.unique' => 'Ya existe una cuenta registrada con este correo electrónico.',
                'phone.required' => 'El número de teléfono es obligatorio.',
                'phone.digits' => 'El número de teléfono debe tener exactamente 9 dígitos.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.confirmed' => 'Las contraseñas no coinciden.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.letters' => 'La contraseña debe incluir al menos una letra.',
                'password.mixed' => 'La contraseña debe incluir letras mayúsculas y minúsculas.',
                'password.numbers' => 'La contraseña debe incluir al menos un número.',
                'password.symbols' => 'La contraseña debe incluir al menos un símbolo.',
                'password.uncompromised' => 'La contraseña ingresada aparece en filtraciones conocidas. Intenta con otra distinta.',
            ],
            [
                'first_name' => 'nombre',
                'last_name' => 'apellido',
                'email' => 'correo electrónico',
                'phone' => 'teléfono',
                'password' => 'contraseña',
                'password_confirmation' => 'confirmación de contraseña',
            ]
        );

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => $validated['password'],
            'role' => 'customer',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
