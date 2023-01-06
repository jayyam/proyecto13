@extends('layout')

@section('title', "Crear nuevo usuario")

@section('content')

    <h1>Creando usuario</h1>

    <?php //dd($errors)?>

    @if ($errors->any())
        <div class="alert alert-danger">
            <h5>Por favor corrige los siguientes errores</h5>
            <!--<ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>-->
        </div>
    @endif

    <form method="POST" action="{{url('/usuarios')}}">

        {{ csrf_field() }}

        <label for="name">Nombre:</label>
        <input type="text" name="name" id="name" placeholder="Nombre" value="{{old('name')}}">
        @if($errors->has('name'))
            <p>{{$errors->first('name')}}</p>
        @endif
        <br>
        <label for="email">Correo electrónico:</label>
        <input type="email" name="email" placeholder="Correo electronico"  value="{{old('email')}}">
        @if($errors->has('email'))
            <p>{{$errors->first('email')}}</p>
        @endif
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" placeholder="Escribe tu contraseña">
        @if($errors->has('password'))
            <p>{{$errors->first('password')}}</p>
        @endif
        <br>

        <button type="submit">Crear usuario</button>

    </form>
    <p>
        <a href="{{url('/usuarios')}}">Atras</a>
        <!--<a href="{{ route('users') }}">Atras</a>-->

    </p>
@endsection