@extends('layout')

@section('title', "Usuario {$user->id}")

@section('content')
    <h1>Usuario #{{$user->id}}</h1>

    <p>Nombre del usuario: {{$user->name}}</p>
    <p>Correo electronico: {{$user->email}}</p>

    <p>
        <a href="{{url('/usuarios')}}">Atras</a>
        <!--<a href="{{ route('users') }}">Atras</a>-->
    </p>
@endsection