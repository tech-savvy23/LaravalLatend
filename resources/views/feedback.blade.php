<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Feedback</title>
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
            <h5 class="text-dark text-center pt-3 pb-3">Feedback</h5>
        <form action="{{route('feedback.add')}}" method="POST">
                {{csrf_field()}}
                
                <input type="hidden" name="booking_id" value="{{$booking->id}}"> 
                <input type="hidden" name="user_id" value="{{$booking->user->id}}"> 
                <input type="hidden" name="partner_id" value="{{$bookingAllottee->partner->id}}"> 

                <div class="form-group">
                    <label class="custom-control-label">Rating </label>
                    <select name="rating" class="form-control">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    @if($errors->has('body'))
                        <div class="has-error"> <div class="text-danger">{{ $errors->first('rating') }}</div></div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="custom-control-label">Comment </label>
                    <textarea name="comment" cols="30" rows="5" class="form-control" placeholder="Comments"></textarea>
                    @if($errors->has('comment'))
                        <div class="has-error"> <div class="text-danger">{{ $errors->first('comment') }}</div></div>
                    @endif
                </div>

                <div class="form-group text-center">
                    <input type="submit" class="btn btn-info"  name="Submit" />
                </div>

            </form>
        </div>

    </div>
</section>
<script src="{{asset('/js/app.js')}}"></script>
</body>
</html>
