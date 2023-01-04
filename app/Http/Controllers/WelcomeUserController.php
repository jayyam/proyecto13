<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeUserController extends Controller
{
    public function __invoke($name, $nickname = null)
    {
        $name =ucfirst($name);
        if ($nickname) {
            return "Bienvenido {$name}. Tu apodo es {$nickname}.";
        } else {
            return "Bienvenido {$name}. No tienes apodo.";
        }
    }
}
