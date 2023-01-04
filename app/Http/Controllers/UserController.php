<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $title = 'Listado de Usuarios';

        $users = [
          'Joel',
          'Ellie',
          'Tess',
          'Tommy',
          'Bill',
          '<script>alert("Clicker")</script>',
        ];

        return view('users', compact(
            $title,
            $users)
        );
    }

    public function show($id)
    {
        return 'Mostrando detalles del usuario ' . $id;
    }

    public function create()
    {
        return 'Creando nuevo usuario';
    }

}
