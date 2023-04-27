<!doctype html>
<html lang="en">
  <head>
    <title>Request Approved</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">   
  </head>
  <body>
    <div class="container">
        <div class="row">
            <div>
                <p>Hi {{$userName}},</p>
                
                <p>Your request to Join {{ $name }} has been approved.</p>                            
                
                <p>From,</p>
                <p>Team {{$name}}</p>
            </div>
        </div>
    </div>
  </body>
</html>