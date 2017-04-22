<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ProfileController extends Controller
{
    // creamos la funcion edit para preparar la vista de actualizacion de perfil
    public function edit()
    {
        // creamosun objeto null de user a dado caso no tenga un perfil para que no genere un error
        $profile = auth()->user()->profile;

        $posts = Post::orderBy('title', 'ASC')->where('user_id', auth()->id())->get();

        return view('users.profile', compact('profile', 'posts'));
    }

    // actualizamos el perfil
    // para mostrar la imagen: creamos un enlace simbolico llendo a tinker primero
    // php artisan storage:link
    public function update(Request $request)
    {
        // optenemos el perfil del usuario
        $profile = auth()->user()->profile;

        $this->validate($request, [
            'description' => 'required|min:10|max:1000',
            // al actualizar ignoramos esta columna si es el mismo user el que la actualiza
            'nickname' => Rule::unique('user_profiles')->ignore($profile->id),
            //'nickname' => "unique:user_profiles,nickname,{$profile->id}",
            'avatar' => [
                'image',
                Rule::dimensions()->maxWidth(200)->maxHeight(200)
                /**Rule::dimensions([
                    'max_width' => 200,
                    'max_height' => 200,
                ])**/
                //'dimensions:max_width=200,max_height=200'
            ],
            // espesificamos el nombre de la tabla en donde queremos buscar y buscamos en la columna id
            'featured_post_id' => Rule::exists('posts', 'id')
                ->where('user_id', auth()->id())
                ->where('points', '>=', 50)
                //->using() callback
        ]);

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
