<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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
       ],[
           'name.required' => 'El campo nombre es obligatorio',
           'email.required' => 'El campo email es obligatorio',
           'password.required' => 'El campo password es obligatorio',
       ]);


       //dd($data);
/*
        if (empty($data['name'])) //ejemplo de validacion
        {
            return redirect('usuarios/nuevo')->withErrors([

            ]);
        }
*/
       User::create([
           'name' => $data['name'],
           'email' => $data['email'],
           'password' =>bcrypt($data['password']),
       ]);

        return redirect('usuarios');
        //dd($data);
        //return redirect()->route('users.index');

    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    public function update(User $user)
    {
        $data = (request()->all());
        $data['password'] = bcrypt($data['password']);

        $user->update($data);

        return redirect()->route('users.show', ['user' => $user]);
    }


}
