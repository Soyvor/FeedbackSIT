<?php
include "code.php";
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="icon" href="./public/favicon.ico" type="image/x-icon">

	<title>Login Page</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style1.css">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body
    {
      overflow-y: hidden;
    }
    @media screen and (max-width:600px) {
      html {
        height: 100%;
      }

      body {
        position: relative;
        padding: 0;
        margin: 0;
        height: 100%;
      }

      .Left-Gradient-Box {
        margin: 0;
        top: 0px;
        width: 100%;
        height: 55%;

        /* to align the divs side by side */
        top: 0px;
        background-image: linear-gradient(180deg, rgba(73, 162, 71, 0.95) 0%, rgba(0, 255, 86, 0.21) 100%);
        box-shadow: 10px 0px 45px 5px rgba(0, 0, 0, 0.25);
      }

      .Right-White-Box {
        width: 100%;
        

        /* to align the divs side by side */
        box-sizing: border-box;
        background-image: white;
        position: relative;
      }

      .Welcome-text {
        position: relative;
        top:0;
        left: 5%;
        
        font-family: 'Arial';
        font-style: normal;
        font-weight: 700;
        font-size: 25px;
        line-height: 50px;

      }

      .contain {
        position: relative;
        
        top:0;
        text-align: center;
        left: 0;

      }

      .contain .text {
        font-family: 'Arial';
        font-style: normal;
        font-weight: 400;
        font-size: 15px;
        line-height: 23px;
        
        color: #000000;
      }

      .contain .RectangleForm {
        
        
        width: 300px;
        background: #FFFFFF;
        border: 1px solid #000000;
        box-shadow: 0px 4px 14px rgba(0, 0, 0, 0.25);
        border-radius: 39px;



        
        font-family: 'Arial';
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 46px;

        color: rgba(0, 0, 0, 0.782);



      }
      .formy{
        width: 100%;
      }

      .contain .RectangleForm::placeholder {
        text-align: left;
        color: rgba(19, 3, 3, 0.35);

      }

      .RectangleFormLogin {
        
        
        width: 300px;
        border: 0px;
        background: #55CC7D;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.25);
        border-radius: 39px;



        padding-left: 20px;
        font-family: 'Arial';
        font-style: normal;
        font-weight: 400;
        font-size: 25px;
        line-height: 46px;

        color: rgba(0, 0, 0, 0.782);
      }


    

    footer {
      bottom: 0px;
    
      flex-wrap: wrap;
      justify-content: space-between;
      position: relative;
      width: 100%;
      clear: both;
      font-family: 'Arial';
      font-style: normal;
      font-weight: 700;
      font-size: 16px;
      line-height: 21px;
    }

    .FooterTop {
      padding: 1%;
      background-color: #D9D9D9;
    }

    .FTp1,
    .FTp2,
    .FTp3 {
      flex-basis: calc((100% / 3) - 2%);
    }

    .FTp1 {
      text-align: left;
    }

    .FTp2 {
      text-align: center;
    }

    .FTp3 {
      text-align: right;
    }

    .FooterTop {
      padding: 1%;
      background-color: #ffffff;
    }

    .FooterBottom {
      padding-top: .5%;
      justify-content: center;
      background-color: #000000;
      color: #FFFFFF;
    }
}
  </style>


</head>
<body>
	<div class="Left-Gradient-Box">
				<div class="svg-IconDesignLogin">
                    <img src="./public/IconDesignLogin.svg" alt=Design >
                </div>
	</div>

	<div class="Right-White-Box">

		<div class="Welcome-text">Hello,</br>Welcome Back</div>

		
			<div class="contain">
				<h2 class="text">&nbsp;&nbsp;&nbsp;Log in </h2>
				
				<form action="code.php" method="post">
					<div class="form-group">
						<input type="text" name="username" class="RectangleForm" placeholder="Username">
					</div>
					<br/>
					<div class="form-group">
						<input type="password" name="password" class="RectangleForm" placeholder="Password">
					</div>
					<br/>
					<p class="text-center" style='color:red'>
						<?php echo $message; ?>
					</p>
					<br/>
					<div class="form-group">
						<input type="submit" name="btnLogin" class="RectangleFormLogin" value="Login">
					</div>
				</form>
			

		</div>
	</div>
	<script type="text/javascript" src="js.jquery.min.js"></script>
	<script type="text/javascript" src="js.bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>

	
	
	<footer class="FooterTop">


			<p class ="FTp1">Feedback | © COPYRIGHT 2023</p>
            <p class ="FTp2">Ideation By: Head CSE <p>
            <p class ="FTp3">Developed By: <a href="https://www.linkedin.com/in/swayam-pendgaonkar-ab4087232/" target="_blank" class="link" style="color:black">Swayam Pendgaonkar</a><br/>UI/UX: <a href="https://www.linkedin.com/in/sakshamgupta912/" target="_blank" class="link" style="color:black">Saksham Gupta</a> ,<a href="https://www.linkedin.com/in/yajushreshtha-shukla/" target="_blank" class="link" style="color:black">Yajushreshtha Shukla</a> </p>
	</footer>
	<footer class="FooterBottom">
			<p class="bottomText">© Copyright.All Rights Reserved<p>
	</footer>

</body>
</html>
