<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ env('APP_NAME') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>
<div class="text-center bg-light p-3">
    <h3 class="text-dark">{{ env('APP_NAME') }}</h3>
</div>
<section class="container-fluid">


    <div class="row">
        <div class="col-12 offset-lg-4 col-lg-4">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Well done!</h4>
                @if ($message = \Illuminate\Support\Facades\Session::get('success'))
                    <p>{{ $message }}</p>
                @endif
            </div>
        </div>

    </div>
</section>

</body>
</html>
