<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return('Procesando informacion...');
    }

}
