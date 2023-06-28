<?php
include "code.php";
?>
<!DOCTYPE html>
<html>

<head>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">

  <!-- Include Font Awesome CSS (for the icons) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- Include Footer CSS -->
  <link rel="stylesheet" href="./css/footer_style.css">
  <link href="./Coordinator Dashboard css and js/dist/css/style.min.css" rel="stylesheet">

  <style>
    body {
      background: #17212C;

    }

    .content {
      height: calc(100vh - 97px);
    }



    .card {
      border-radius: 1.5rem;
      background: #273444;
      color: white;
      box-shadow: 0px 4px 59px -8px rgba(0, 0, 0, 0.25);
      width: 25rem;




    }

    .form-control {
      color: white;
    }

    .custom-input-box {
      background: #1C2631;
      box-shadow: 4px 4px 19px 2px rgba(0, 0, 0, 0.25);
      border-radius: 10px;
      border: 0;
      font-size: .75rem;
    }

    .custom-button {
      background-color: #3D8BFD;
      border-radius: 30px;
      width: 5.5rem;


      font-weight: 700;
      font-size: 0.75rem;

    }

    .custom-anchor {

      font-style: normal;
      font-weight: 700;
      font-size: 15px;
      line-height: 17px;
      color: #3D8BFD;
    }
  </style>
</head>


<body>
  <div class="content">
    <div class="container py-5 h-100 d-flex justify-content-center align-items-center ">
      <div class="row d-flex justify-content-center align-items-center ">
        <div class="card ">
          <div class="card-body  text-center py-5">

            <div class="container">


              <h3 class="mt-1 mb-1" style="text-align:start;font-size:1.3rem;">Login</h3>

              <div class="d-flex justify-content-start  mb-4">
                <label class="form-check-label" for="form1Example3" style="color: rgba(255, 255, 255, 0.29);text-align:start;font-size:0.75rem;">Sign in to your account to continue </label>
              </div>

            </div>

            <form action="code.php" method="post">
              <div class="container  ">
                <div class="d-flex justify-content-start ml-1  ">
                  <label class="form-check-label mb-0" for="form1Example3" style="color: rgba(255, 255, 255, 0.29);text-align:start;font-size:0.75rem;"> Username </label>
                </div>
                <div class="form-outline mb-4  mx-auto">
                  <input type="text" name="username" id="typeEmailX-2" class="form-control form-control-lg custom-input-box" placeholder="" />
                </div>
              </div>


              <div class="container">
                <div class="d-flex justify-content-start ml-1  ">
                  <label class="form-check-label mb-0" for="form1Example3" style="color: rgba(255, 255, 255, 0.29);text-align:start;font-size:0.75rem;"> Password </label>
                </div>
                <div class="form-outline mb-4 ">
                  <input type="password" name="password" id="typePasswordX-2" class="form-control form-control-lg custom-input-box" placeholder="" />

                </div>
              </div>


              <div class="container d-flex justify-content-between align-items-center mt-5">
                <div><input type="submit" class="btn btn-primary custom-button" name="btnLogin" value="Login"></button></div>

                <div> <a class="custom-anchor text-decoration-none" href="forgot-password.php" style="font-size:.75rem;">Forgot Password ?</a></div>
              </div>
            </form>
          </div>

        </div>

      </div>
    </div>
  </div>
  <!-- Site footer -->
  <footer class="site-footer footer-bottom d-flex py-1">

    <div class="container" style="min-width:100%">
      <hr class="mt-3 mb-2" style="color: #CDCDCD;">
      <div class="row">
        <div class="col-xs-12 col-md-4 copyright-text">


          <p style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Feedback |
            <a>Copyright &copy; 2023 </a>
          </p>
        </div>

        <div class="col-xs-12 col-md-4 ">


          <ul class="footer-links text-center">

            <li><a style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Ideation By Dr. Deepali Vora, Head CS IT</a></li>


          </ul>
        </div>

        <div class="col-xs-12 col-md-4 ">

          <ul class="footer-links text-right custom-developed-by">
            <li><a style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Developed By: </a>
              <a href="https://www.linkedin.com/in/skp2208/" style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Swayam Pendgaonkar</a>
            </li>
            <li><a href="https://www.linkedin.com/in/sakshamgupta912/" style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Saksham Gupta </a>
              <a href="https://www.linkedin.com/in/yajushreshtha-shukla/" style="font-size: 14px;font-weight: 700;color: #CDCDCD;;">Yajushreshtha Shukla</a>
            </li>

          </ul>
        </div>

      </div>

    </div>

    </div>
  </footer>

  <!-- Include Bootstrap JavaScript (jQuery and Popper.js are required) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://unpkg.com/@popperjs/core@2.11.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"></script>
</body>



</html>