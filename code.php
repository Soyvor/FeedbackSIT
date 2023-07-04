<?php 
session_start();
require_once "connection.php";
$message = "";
$role="";
if(isset($_POST["btnLogin"]))
{
	$username = $_POST["username"];
	$password = $_POST["password"];
    

	$query = "SELECT * FROM login WHERE username='$username' AND is_valid='1'";
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
			else if($row["role"] == "coordinator")
			{
				$_SESSION['coordinator']= $row["username"];
				$_SESSION['branch']= $row["branch"];
				$_SESSION['usertype'] = "coordinator";
				
				header('Location: coordinator.php');
			}
			else if($row["role"] == "student")
			{
				if($row["acad_year"]=="fy"){
					$query = "SELECT * FROM fy_student WHERE prn='$username' AND is_valid='1'";
					$result1 = mysqli_query($conn, $query);
					if(mysqli_num_rows($result1)>0){
						$_SESSION['student']= $row["username"];
						$_SESSION['usertype'] = "student";
						$_SESSION['branch_student']="FY";
						header('Location: student.php');
				}
					else{
						echo "<script>
					if(window.confirm('First Year Student Login Turned OFF!')){
						window.location.href = 'index.php';
						
					}
					else{
						window.location.href = 'index.php';
					}
				</script>";
			
						}
				}
				else{
					$branch = $row["branch"];
					$branch_name = $branch.""."_student";
					$query = "SELECT * FROM $branch_name WHERE prn='$username' AND is_valid='1'";
					$result2 = mysqli_query($conn, $query);
					if(mysqli_num_rows($result2)>0){
						$_SESSION['student']= $row["username"];
						$_SESSION['usertype'] = "student";
						$_SESSION['branch_student']=$branch;
						header('Location: student.php');
					}
					else{
							echo "<script>
						if(window.confirm('Student Login Turned OFF!')){
							window.location.href = 'index.php';
							
						}
						else{
							window.location.href = 'index.php';
						}
								</script>";
				
						}
				}
				
				}
				
			
			else if($row["role"] == "teacher")
			{
				$_SESSION['Teacher']= $row["username"];
				$_SESSION['usertype'] = "teacher";
				header('Location: teacher.php');
			}
		    
            else{
    	echo "<script>
                if(window.confirm('Incorrect Username or Password!')){
                    window.location.href = 'index.php';
					
                }
                else{
                    window.location.href = 'index.php';
                }
             </script>";
        
    }
        } }  
	}
	else
	{
		echo "<script>
                if(window.confirm('Incorrect Username or Password! lol')){
                    window.location.href = 'index.php';
                }
                else{
                    window.location.href = 'index.php';
                }
             </script>";
	}
}


 ?>