
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md">
                {{--                <h1>[Assignment name] {{ $name }}</h1>--}}
            </div>
        </div>
<h1>Setup tools dev</h1>
        <div class="row justify-content-center">

            <div class="col-md">

                <setup-page></setup-page>
            </div>

        </div>
    </div>
         {{ method_field('PUT') }}

    {{ method_field('PATCH') }}

    {{ method_field('DELETE') }}

    <input type="hidden" id="routeRoot" data="{{ url('') }}"/>
@endsection

@section('scriptArea')

    <script type="text/javascript">
        window.routeRoot = document.getElementById('routeRoot').getAttribute('data');
        //Embed the minimal data we need to get started
        window.startData = @json($data);

    </script>
@endsection
