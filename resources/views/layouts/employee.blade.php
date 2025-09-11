@extends('layouts.app')

@section('title', 'Employee Panel')

@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container-fluid">
    </div>
</nav>

<div class="container">
    @yield('employee-content')
</div>
@endsection