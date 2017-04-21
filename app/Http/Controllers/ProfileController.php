<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // creamos la funcion edit para preparar la vista de actualizacion de perfil
    public function edit()
    {
        $profile = auth()->user()->profile()->firstOrNew([]);

        return view('users.profile', compact('profile'));
    }

    // actualizamos el perfil
    // para mostrar la imagen: creamos un enlace simbolico llendo a tinker primero
    // php artisan storage:link
    public function update(Request $request)
    {
        $profile = auth()->user()->profile()->firstOrNew([]);
        $profile->fill($request->all());
        //comprobamos si el usuario esta subiendo un avatar
        if ($request->hasFile('avatar')){
            $profile->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        //pasamos el nombre del campo el archivo.
        //dd($request->file('avatar'));

        // llamamos al metodo store() y espesificamos el directorio relativo a donde queremos subir el archivo
        //dd($request->file('avatar')->store('avatars'));
        $profile->save();

        return back();
    }
}
