@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}

                    </div>


                    <div class="card-body">

                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();  document.getElementById('logout-form').submit();"
                        >
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                    </div>


                </div>

                <home-page></home-page>

            </div>
        </div>


        {{ method_field('PUT') }}

        {{ method_field('PATCH') }}

        {{ method_field('DELETE') }}

        <input type="hidden" id="routeRoot" data="{{ url('') }}"/>
        <input type="hidden" id="userName" data="{{ Auth::user()->name }}"/>
        <input type="hidden" id="env" data="{{ config('app.env') }}"/>

    </div>
@endsection


@section('scriptArea')

    <script type="text/javascript">
        window.routeRoot = document.getElementById('routeRoot').getAttribute('data');
        window.userName = document.getElementById('userName').getAttribute('data');
        window.env = document.getElementById('env').getAttribute('data');
    </script>
@endsection
