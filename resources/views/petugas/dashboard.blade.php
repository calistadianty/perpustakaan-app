@extends('petugas.layout')

@section('content')
<h1>Dashboard Petugas</h1>
@endsection
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">
        Logout
    </button>
</form>
