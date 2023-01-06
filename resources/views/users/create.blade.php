@extends('layout')

@section('title', "Creando Usuario")

@section('content')

    <h1>Creando usuario</h1>

    <form method="POST" action="{{url('/usuarios')}}">

        {{ csrf_field() }}

        <button type="submit">Crear usuario</button>

    </form>
    <p>
        <a href="{{url('/usuarios')}}">Atras</a>
        <!--<a href="{{ route('users') }}">Atras</a>-->
    </p>
@endsection