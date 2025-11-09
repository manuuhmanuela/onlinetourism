<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> mpesa payments</title>
    <link
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link href="" rel="stylesheet" />
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" ">
    <script
      type="text/javascript"
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"
    ></script>
    <style>
        body {
            background-image: url('6.jpg');
            background-size: cover; /* Adjust as needed */
            background-position: center; /* Adjust as needed */
        }
      
    </style>
  </head>

  <?php
 
?>



  <body oncontextmenu="return false" class="snippet-body">
    <div class="container d-flex justify-content-center">
      <div class="card mt-5 px-3 py-4">
        <div class="d-flex flex-row justify-content-around">
          <div class="mpesa"><span>payment procedure </span></div>
          
        </div>
        <div class="media mt-4 pl-2">
         
          <div class="media-body">
            <h6 class="mt-1">Pay here</h6>
          </div>
        </div>
        <div class="media mt-3 pl-2">
                          <!--bs5 input-->

                          <form class="row g-3" action="./stk_initiate.php" method="POST">
    <div class="col-12">
        <label for="inputAddress" class="form-label">Amount</label>
        <input type="text" class="form-control" name="amount" placeholder="Enter Amount" value="">
    </div>
    <div class="col-12">
        <label for="inputAddress2" class="form-label">Phone Number</label>
        <input type="text" class="form-control" name="phone" placeholder="Enter Phone Number">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-success" name="submit" value="submit">Proceed to pay</button>
    </div>
</form>

              <!--bs5 input-->
          </div>
        </div>
      </div>
    </div>
    <script
      type="text/javascript"
      src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"
    ></script>
    <script type="text/javascript" src=""></script>
    <script type="text/javascript" src=""></script>
    <script type="text/Javascript"></script>
    <?php

?>


        </div>

      </div>
    </nav>



       
        <br>





  </body>
</html>

