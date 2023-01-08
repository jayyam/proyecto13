@extends('layout')

@section('title', "Crear nuevo usuario")

@section('content')
    <?php //dd($errors)?>

    <div class="card">
        <h3 class="card-header">Crear usuario</h3>

        <div class="card-body">
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

                <div class="form-group">
                    <label for="name">Nombre:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nombre" value="{{old('name')}}">
                </div>
                <div class="form-group">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" name="email" class="form-control" placeholder="Correo electronico"  value="{{old('email')}}">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" class="form-control" placeholder="Escribe tu contraseña">
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea name="bio" class="form-control" id="bio">{{old('bio')}}</textarea>
                </div>
                <div class="form-group">
                    <label for="twitter">Twitter:</label>
                    <input type="text" name="twitter" id="twitter" class="form-control" placeholder="http:\\twitter.com/omardpana22" value="{{old('twitter')}}">
                </div>
                <button type="submit" class="btn btn-primary">Crear usuario</button>
                <a href="{{url('/usuarios')}}" class="btn btn-link">Volver al listado de usuarios</a>
                <!--<a href="{{ route('users') }}">Atras</a>-->
            </form>
        </div>
    </div>
@endsection