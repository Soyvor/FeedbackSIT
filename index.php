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

  <style>
    body {
      background: #17212C;

    }

    section {
      height: 100vh;
    }

    .custom-container-1 {
      background: #1C2631;
      box-shadow: 0px 4px 59px -8px rgba(0, 0, 0, 0.25);
      border-radius: 1.5rem;
      height: 30rem;
      width: 40rem;

    }

    .card {
      border-radius: 1.5rem;
      background: #273444;
      color: white;
      box-shadow: 0px 4px 59px -8px rgba(0, 0, 0, 0.25);
      width: 30rem;

    }

    .form-control {
      color: white;
    }

    .custom-input-box {
      background: #1C2631;
      box-shadow: 4px 4px 19px 2px rgba(0, 0, 0, 0.25);
      border-radius: 10px;
      border: 0;
    }

    .custom-button {
      background-color: #3D8BFD;
      border-radius: 30px;
      width: 120px;


      font-weight: 700;
      font-size: 15px;

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
  <section>
    <div class="container py-5 h-100 d-flex justify-content-center align-items-center ">
      <div class="row d-flex justify-content-center align-items-center ">
        <div class="card ">
          <div class="card-body p-5 text-center">

            <div class="container">


              <h3 class="mb-1" style="text-align:start">Login</h3>

              <div class="d-flex justify-content-start  mb-4">
                <label class="form-check-label" for="form1Example3" style="color: rgba(255, 255, 255, 0.29);">Sign in to your account to continue </label>
              </div>

            </div>

            <form action="code.php" method="post">
              <div class="container  ">
                <div class="d-flex justify-content-start ml-1 mb-1 ">
                  <label class="form-check-label" for="form1Example3" style="color:rgba(255, 255, 255, 0.4);"> Username </label>
                </div>
                <div class="form-outline mb-4  mx-auto">
                  <input type="text" name="username" id="typeEmailX-2" class="form-control form-control-lg custom-input-box" placeholder="" />
                </div>
              </div>


              <div class="container">
                <div class="d-flex justify-content-start ml-1 mb-1  ">
                  <label class="form-check-label" for="form1Example3" style="color:rgba(255, 255, 255, 0.4);"> Password </label>
                </div>
                <div class="form-outline mb-4 ">
                  <input type="password" name="password" id="typePasswordX-2" class="form-control form-control-lg custom-input-box" placeholder="" />

                </div>
              </div>


              <div class="container d-flex justify-content-between align-items-center mt-5">
                <div><input type="submit" class="btn btn-primary custom-button" name="btnLogin" value="Login"></button></div>

                <div> <a class="custom-anchor text-decoration-none" href="forgot-password.php">Forgot Password ?</a></div>
              </div>
            </form>
          </div>

        </div>

      </div>
    </div>




    <!-- Site footer -->
    <footer class="site-footer footer-bottom d-flex">

      <div class="container">
        <hr>
        <div class="row">
          <div class="col-xs-12 col-md-4">


            <p class="copyright-text ">Copyright &copy; 2023 |
              <a>Feedback</a>.
            </p>
          </div>

          <div class="col-xs-6 col-md-4 ">
            <h6 class="text-center">Ideation By</h6>

            <ul class="footer-links text-center">

              <li><a href="">Dr. Deepali Vora, Head CS IT</a></li>


            </ul>
          </div>

          <div class="col-xs-6 col-md-4 ">
            <h6 class="text-right custom-developed-by">Developed By</h6>
            <ul class="footer-links text-right custom-developed-by">
              <li><a href="https://www.linkedin.com/in/swayam-pendgaonkar-ab4087232/">Swayam Pendgaonkar</a></li>
              <li><a href="https://www.linkedin.com/in/sakshamgupta912/">Saksham Gupta</a></li>
              <li><a href="https://www.linkedin.com/in/yajushreshtha-shukla/">Yajushreshtha Shukla</a></li>
            </ul>
          </div>

        </div>

      </div>

      </div>
    </footer>
  </section>

  <!-- Include Bootstrap JavaScript (jQuery and Popper.js are required) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://unpkg.com/@popperjs/core@2.11.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"></script>
</body>



</html>