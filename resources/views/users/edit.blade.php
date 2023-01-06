@extends('layout')

@section('title', "Editar usuario")

@section('content')

    <h1>Editando usuario</h1>

    <?php //dd($errors)?>

    @if ($errors->any())
        <div class="alert alert-danger">
            <h5>Por favor corrige los siguientes errores</h5>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{url('/usuarios')}}">

        {{ csrf_field() }}

        <label for="name">Nombre:</label>
        <input type="text" name="name" id="name" placeholder="Nombre" value="{{old('name', $user->name)}}">
        <br>
        <label for="email">Correo electrónico:</label>
        <input type="email" name="email" placeholder="Correo electronico"  value="{{old('email', $user->email)}}">
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" placeholder="Escribe tu contraseña">
        <br>

        <button type="submit">Actualizar usuario</button>

    </form>
    <p>
        <a href="{{url('/usuarios')}}">Atras</a>
        <!--<a href="{{ route('users') }}">Atras</a>-->

    </p>
@endsection
