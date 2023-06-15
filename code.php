<?php 
session_start();
require_once "connection.php";
$message = "";
$role="";
if(isset($_POST["btnLogin"]))
{
	$username = $_POST["username"];
	$password = $_POST["password"];
    

	$query = "SELECT * FROM tbluser WHERE username='$username' AND is_valid='1'";
	$result = mysqli_query($conn, $query); 

	if(mysqli_num_rows($result) > 0)
	{

		while($row = mysqli_fetch_assoc($result))
		{
            $encoded_string = $row["password"];
            if(base64_decode($encoded_string) == $password)
            {
			if($row["role"] == "superadmin")
			{
				$_SESSION['SuperAdmin']= $row["username"];
				$_SESSION['usertype'] = "superadmin";
				header('Location: admin.php');
			}
			else if($row["role"] == "coordinato")
			{
				$_SESSION['coordinator']= $row["username"];
				$_SESSION['usertype'] = "coordinator";
				header('Location: coordinator.php');
			}
			else if($row["role"] == "student")
			{
				$_SESSION['student']= $row["username"];
				$_SESSION['usertype'] = "student";
				header('Location: student.php');
			}
			else if($row["role"] == "teacher")
			{
				$_SESSION['Teacher']= $row["username"];
				$_SESSION['usertype'] = "teacher";
				header('Location: teacher.php');
			}
		    }
            else
    {
    	echo "<script>
                if(window.confirm('Incorrect Username or Password!')){
                    window.location.href = 'index.php';
                }
                else{
                    window.location.href = 'index.php';
                }
             </script>";
        
    }
        }   
	}
	else
	{
		echo "<script>
                if(window.confirm('Incorrect Username or Password!')){
                    window.location.href = 'index.php';
                }
                else{
                    window.location.href = 'index.php';
                }
             </script>";
	}
}


 ?>