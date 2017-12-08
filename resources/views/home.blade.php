@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!

                        {{dd(DB::connection('internalreceipt')->select('SELECT * FROM migrations'))}}
                    {{dd(app(\App\Markuskoehler\Billomat\Incomings::class)->get())}}

                    <script>
                        jQuery(function() {
                            console.log('start');
                        axios.get('api/user')
                            .then(function(response) {
                            console.log(response.data);
                        }).catch(function (error) {
                            console.log(error);
                        });
                            }
                        );
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
