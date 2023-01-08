<?php

namespace App\Http\Controllers;

use App\{User, UserProfile};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {

        $users = User::all();

        $title = 'Listado de usuarios';
/*
        return view('users.index')
            ->with('users', User::all())
            ->with('title' ,'Listado de usuarios');
*/
        return view('users.index', compact('title', 'users'));
    }

    public function show(User $user)
    {
         //dd($user);

        /*
        $user = User::findOrFail($id);

        if($user==null)
        {
            return response()->view('errors.404', [], 404);
        }
        */

        //exit('Linea no alcanzada');

        return view('users.show', compact('user'));


    }

    public function create()
    {
        return view('users.create');
    }

    public function store()
    {
        //return redirect('usuarios/nuevo')->withInput();//redireccion manual de paginas con contenido guardado

       $data = request()->validate([
           'name' => 'required',
           'email' => 'required|email|unique:users,email',
           'password' => 'required',
           'bio' => 'required',
           'twitter' => 'url',//hacer regex para validar url
       ],[
           'name.required' => 'El campo nombre es obligatorio',
           'email.required' => 'El campo email es obligatorio',
           'password.required' => 'El campo password es obligatorio',
       ]);

       //dd($data);//vuelca el contenido de la variable data
/*
        if (empty($data['name'])) //ejemplo base de validacion. si el nombre esta vacio
        {return redirect('usuarios/nuevo')->withErrors([]);}//redirige y muestra errores
*/
       $user = User::create([
           'name' => $data['name'],
           'email' => $data['email'],
           'password' =>bcrypt($data['password']),
       ]);

       $user->profile()->create([
           'bio' => $data['bio'],
           'twitter' => $data['twitter'],
       ]);

        return redirect('usuarios');
        //return redirect()->route('users.index');//redireccion no funciona

    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    public function update(User $user)
    {
        //dd('actualizar usuario');

        $data = request()->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => '',
        ]);
        if ($data['password'] != null) {
            $data['password'] = bcrypt($data['password']);
        }
        else
        {
            unset($data['password']);
        }
        $user->update($data);

        return redirect()->route('users.show', ['user' => $user]);
    }
    function destroy(User $user)
    {
        $user->delete();

        return redirect('usuarios');
    }
}
