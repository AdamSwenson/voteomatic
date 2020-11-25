@extends('layouts.app')


@section('navBar')
    {{--    <page-navbar></page-navbar>--}}
@endsection

@section('content')


    <div class="container-fluid">
        <div class="waitlist">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Voteomatic waitlist</h1>
                </div>

                <div class="card-body">
                    <form id="waitlist-form"
                          action="{{ route('waitlist') }}"
                          method="POST"
                    >

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" class="form-control">
                        </div>


                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email"
                                   id="email"
                                   class="form-control"
                                   aria-describedby="emailHelp">
                            <small id="emailHelp"
                                   class="form-text text-muted"
                            >We'll never share your email with anyone else.</small>
                        </div>


                        <div class="form-group">
                            <label for="organization">Organization</label>
                            <input type="text" id="organization" class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea id="notes"
                                      class="form-control"
                            ></textarea>
                        </div>

                        @csrf

                    </form>

                    <button type='submit' class="btn btn-primary"
                            onclick="event.preventDefault();
            document.getElementById('waitlist-form').submit();"
                    >Add me to the waitlist
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scriptArea')

    <script type="text/javascript">

        // window.routeRoot = document.getElementById('routeRoot').getAttribute('data');
        // window.userName = document.getElementById('userName').getAttribute('data');
        // window.env = document.getElementById('env').getAttribute('data');

        //Embed the minimal data we need to get started
        {{--window.startData = @json($data);--}}

    </script>
@endsection
