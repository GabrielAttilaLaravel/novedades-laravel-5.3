<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ProfileController extends Controller
{
    // creamos la funcion edit para preparar la vista de actualizacion de perfil
    public function edit()
    {
        // creamosun objeto null de user a dado caso no tenga un perfil para que no genere un error
        $profile = auth()->user()->profile;

        return view('users.profile', compact('profile'));
    }

    // actualizamos el perfil
    // para mostrar la imagen: creamos un enlace simbolico llendo a tinker primero
    // php artisan storage:link
    public function update(Request $request)
    {
        $this->validate($request, [
            'description' => 'min:10',
            'avatar' => [
                'image',
                'dimensions:max_width=200,max_height=200'
            ]
        ]);
        // optenemos el perfil del usuario
        $profile = auth()->user()->profile;
        $profile->fill($request->all());
        //comprobamos si el usuario esta subiendo un avatar
        if ($request->hasFile('avatar')){
            // metodo store(): grabamos la imagen en el archivo avatars
            // metodo storeAs(): le podemos colocar el nombre del archivo
            $profile->avatar = $request->file('avatar')->store('avatars/'.auth()->id());
        }

        //pasamos el nombre del campo el archivo.
        //dd($request->file('avatar'));

        // llamamos al metodo store() y espesificamos el directorio relativo a donde queremos subir el archivo
        //dd($request->file('avatar')->store('avatars'));
        $profile->save();

        return back();
    }

    public function avatar()
    {
        // optenemos el perfil del usuario
        $profile = auth()->user()->profile;

        $file = storage_path("app/{$profile->avatar}");

        $headers = [
            'Content-Length' => File::size($file),
            'Content-Type' => File::mimeType($file)
        ];
        // download:
        // 1. el archivo que queremos descargar
        // 2. el nombre que queremos darle al archivo
        // 3. los headers: podemos en viar la informacion del archivo
        //      'Content-Length' => File::size(ruta del archivo) : tamaño del archivo
        //      'Content-Type' => File::mimeType(ruta del archivo) : tipo del archivo
        // 4. la disposicion, si queremos que sea vea en linea usamos ResponseHeaderBag::DISPOSITION_INLINE
        //    o se descargue por defecto no colocamos el 4to argumento
        return response()->download(
            $file,
            null,
            $headers,
            ResponseHeaderBag::DISPOSITION_INLINE
        );
    }
}
