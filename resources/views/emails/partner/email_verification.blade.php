<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Forgot Password</title>
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

            <h5 class="text-dark text-center pt-3 pb-3">Verify your email id</h5>
            <div class="form-group">
                <a class="btn btn-info" href="{{route('partner.verify.email', [$partner->email, $token])}}">Verify Email</a>
            </div>
        </div>

    </div>
</section>

</body>
</html>

