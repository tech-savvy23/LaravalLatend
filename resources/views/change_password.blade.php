<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Change Password</title>
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
            <h5 class="text-dark text-center pt-3 pb-3">Change Password</h5>
            <form action="{{route('reset.password', [$email, $token])}}" method="POST">
                {{csrf_field()}}
                <div class="form-group ">
                    <label class="custom-control-label">Email Address </label>
                    <input class="form-control" type="email" name="email"  value="{{$email}}" disabled>
                    @if($errors->has('email'))
                        <div class="has-error">
                            <div class="text-danger">{{ $errors->first('email') }}</div>
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="custom-control-label">Password </label>
                    <input class="form-control" type="password" name="password" placeholder="Password" >
                    @if($errors->has('password'))
                        <div class="has-error"><div class="text-danger">{{ $errors->first('password') }}</div></div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="custom-control-label">Confirm Password </label>
                    <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password">
                    @if($errors->has('password_confirmation'))
                        <div class="has-error"> <div class="text-danger">{{ $errors->first('password_confirmation') }}</div></div>
                    @endif
                </div>

                <div class="form-group text-center">
                    <input type="submit" class="btn btn-info" value="Change Password" name="Change Password" />
                </div>

            </form>
        </div>

    </div>
</section>

</body>
</html>
