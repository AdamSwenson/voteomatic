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

                    <form id="waitlistForm"
                          name="waitlistForm"
                          action="{{ route('waitlist') }}"
                          method="POST"
                    >

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>


                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control"
                                   aria-describedby="emailHelp">
                            <small id="emailHelp"
                                   class="form-text text-muted"
                            >We'll never share your email with anyone else.</small>
                        </div>


                        <div class="form-group">
                            <label for="organization">Organization (optional)</label>
                            <input type="text" id="organization"
                                   name="organization"
                                   class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes (optional)</label>
                            <textarea id="notes"
                                      name="notes"
                                      class="form-control"
                            ></textarea>
                            <small id="notesHelp"
                                   class="form-text text-muted"
                            >If there's anything else you'd like us to know....</small>
                        </div>

                        @csrf

                        <button type='submit' class="btn btn-primary"
                                onclick="document.waitlistForm.submit();"

                        >Add me to the waitlist
                        </button>

                    </form>
{{--                    document.getElementById('waitlistForm').submit();"--}}
{{--                    event.preventDefault();--}}

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
