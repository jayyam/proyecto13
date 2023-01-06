@extends ('layout')

@section('content')
    <h1>{{$title}}</h1>
    <ul>
        @forelse ($users as $user)
            <li>
                {{$user->name}}, ({{$user->email}})
                <!--<a href="{{ url("/usuarios/{$user->id}") }}">Detalles</a>-->
                <a href="{{ route('users.show', ['id' => $user->id]) }}">Detalles</a>
            </li>


        @empty
            <li>No hay usuarios registrados.</li>
        @endforelse
    </ul>
@endsection
