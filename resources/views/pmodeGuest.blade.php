@extends('layouts.app')


@section('navBar')
<pmode-page-navbar></pmode-page-navbar>
@endsection

@section('content')

    <div class="container-fluid">

        <div class="row justify-content-center mt-1">

            <div class="col-md">

                <voteomatic ></voteomatic>

            </div>

        </div>
    </div>


    {{ method_field('PUT') }}

    {{ method_field('PATCH') }}

    {{ method_field('DELETE') }}

    <input type="hidden" id="routeRoot" data="{{ url('') }}"/>
    <input type="hidden" id="userName" data="{{ Auth::user()->name }}"/>
    <input type="hidden" id="env" data="{{ config('app.env') }}"/>
    <input type="hidden" id="version" data="{{env('VERSION')}}"/>

@endsection

@section('scriptArea')

    <script type="text/javascript">

        window.routeRoot = document.getElementById('routeRoot').getAttribute('data');
        window.userName = document.getElementById('userName').getAttribute('data');
        window.env = document.getElementById('env').getAttribute('data');
        window.appVersion = document.getElementById('version').getAttribute('data');

        //Embed the minimal data we need to get started
        window.startData = @json($data);
        window.isPublicPmode = true;

    </script>
@endsection
