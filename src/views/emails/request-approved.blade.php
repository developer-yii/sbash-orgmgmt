<!doctype html>
<html lang="en">
  <head>
    <title>Request Approved</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style type="text/css">
      .btn-primary{font-size: 16px; font-weight: 600; color: #fff; padding: 16px 24px; box-shadow: none; background: #dde0e1; border-radius: 16px; position:relative; border: 0;}
      .btn {
        text-decoration:none;
        display: inline-block;
        font-weight: 400;
        line-height: 1.5;
        color: white;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-color: green;
        border: 1px solid transparent;
        padding: 0.45rem 0.9rem;
        font-size: .9rem;
        border-radius: 0.15rem;
        -webkit-transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    }
    </style>
  </head>
  <body>
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-sm-12 m-auto">
                <p>Hi {{$userName}},</p>
                
                <p>Your request to Join {{ $name }} has been approved.</p>

                <p>Click below button to Join</p>                
                
                <p>From,</p>
                <p>Team {{$name}}</p>
            </div>
        </div>
    </div>
  </body>
</html>