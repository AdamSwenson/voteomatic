@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif


        <home-page></home-page>


        {{ method_field('PUT') }}

        {{ method_field('PATCH') }}

        {{ method_field('DELETE') }}

        <input type="hidden" id="routeRoot" data="{{ url('') }}"/>
        <input type="hidden" id="userName" data="{{ Auth::user()->name }}"/>
            <input type="hidden" id="isAdmin" data="{{Auth::user()->isAdministrator()}}"/>
        <input type="hidden" id="env" data="{{ config('app.env') }}"/>
        <input type="hidden" id="version" data="{{config('app.version')}}"/>


    </div>
@endsection


@section('scriptArea')

    <script type="text/javascript">
        window.routeRoot = document.getElementById('routeRoot').getAttribute('data');
        window.userName = document.getElementById('userName').getAttribute('data');
        window.isAdmin = document.getElementById('isAdmin').getAttribute('data');
        window.env = document.getElementById('env').getAttribute('data');
    </script>
@endsection
