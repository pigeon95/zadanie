<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" 
              integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <title>Cryptocurrency Converter Calculator</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="container">               
            <div class="row text-center">
                <h1>Cryptocurrency Converter Calculator</h1>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">

                    {{ Form::open(['class' => 'form']) }}
                    <div class="form-group">
                        {{ Form::label('Amount:') }}
                        {{ Form::text('amount', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('From currency:') }}
                        {{ Form::select('first_currency', $array, null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('To currency:') }}
                        {{ Form::select('second_currency', $array, null, ['class' => 'form-control']) }}
                    </div>

                    {{ Form::submit('Check', ['class' => 'btn btn-info']) }}

                    {{ Form::close() }}

                    <br>
                    @if(session()->has('message'))
                    <div class="alert text-center">
                        {{ session()->get('message') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </body>
    <script>

    </script>
    <style>
        #container{
            margin-top: 1%;
            margin-left: 20%;
            margin-right: 20%;
        }
    </style>
</html>
