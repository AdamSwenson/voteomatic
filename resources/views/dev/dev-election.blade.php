
@extends('layouts.app')

@section('content')
    <div class="container-fluid">


<election-card></election-card>


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
