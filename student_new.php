<?php
session_start();
if (!isset($_SESSION['student'])) {
    header('Location: index.php');
    exit;
}


require_once "connection.php";
$username = $_SESSION['student']; //gives prn
$branch = $_SESSION['branch_student'];
$branch_check = $branch . '' . "_student";
$branch_teacher = $branch . '' . "_teacher";
$branch_feedback = $branch . '' . "_feedback";
echo "$branch_check";
echo "$username";

if ($branch_check == "FY") {
	$query = "SELECT * FROM fy_student WHERE prn='$username'";
	$result = mysqli_query($conn, $query);

	if ($result) {
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$name = ucwords($row["name"]);
			}
		} else {
			$name = 'Please Contact Coordinator!';
		}
	} else {
		// Query execution failed
		echo "Error: " . mysqli_error($conn);
	}
} else {
	$query = "SELECT * FROM $branch_check WHERE prn='$username'";
	$result = mysqli_query($conn, $query);

	if ($result) {
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$name = ucwords($row["name"]);
			}
		} else {
			$name = 'Please Contact Coordinator!';
		}
	} else {
		// Query execution failed
		echo "Error: " . mysqli_error($conn);
	}
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="./slick/slick.css">
    <link rel="stylesheet" href="./slick/slick-theme.css" integrity="sha512-6lLUdeQ5uheMFbWm3CP271l14RsX1xtx+J5x2yeIDkkiBpeVTNhTqijME7GgRKKi6hCqovwCoBTlRBEC20M8Mg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        body {
            background-color: black;
        }

        .container {
            min-height: calc(100vh - 100px);
            background-color: aqua;
        }

        .wrapper {
            padding: 70px 0;
            overflow-x: hidden;
        }


        .my-slider {
            padding: 0 70px;
        }

        .slick-initialized .slick-slide {
            background-color: #b32532;
            color: #FFF;
            min-height: 50vh;
            margin: 0 15px 0 0;


        }



        .slick-next,
        .slick-prev {
            z-index: 5;
        }

        .slick-next {
            right: 15px;
        }

        .slick-prev {
            left: 15px;
        }

        .slick-next:before,
        .slick-prev:before {
            color: #000;
            font-size: 26px;
        }



        /* Add the following styles to hide the arrows */
        .slick-arrow {}
    </style>

    </style>

</head>

<body>

    <div class="container d-flex align-items-center justify-content-center px-0">

        <div class="row d-flex justify-content-center wrapper " style="width: 100%">
            <form method="post" action="submit_feedback.php" style="display:inline-block;background:none">
                <div class="my-slider px-0">

                    <?php
                    $query = "SELECT * FROM $branch_feedback WHERE prn='$username'";
                    $result = mysqli_query($conn, $query);
                    $row = mysqli_fetch_assoc($result);


                    if (mysqli_num_rows($result) == 0) {
                        // Retrieve student information
                        $query = "SELECT * FROM $branch_check WHERE prn='$username'";
                        $result = mysqli_query($conn, $query);
                        $row = mysqli_fetch_assoc($result);
                        $student_name = $row['name'];
                        echo "$student_name";
                        $specialization = $row['open'];
                        $general = $row['general'];
                        $acad_year = $row["acad_year"];
                        $branch = $row["branch"];
                        $class = $row["class"];
                        echo " ";
                        echo "$general";
                        echo "$specialization";

                        //$query_spec = "SELECT * FROM specialization WHERE course_name ='$specialization'";

							// // $subjects_arr = array_merge($open_elective_arr, $specialization_arr);

							// // // Build the query
							// // $subject_condition = "subject IN ('" . implode("', '", $subjects_arr) . "')";
							// // $year_branch_class_condition = "year_branch_class='$class_batch' AND is_valid='1'";
							// // $year_branch_class_without_batch = substr($class_batch, 0, -1);
							// // $year_branch_class_without_batch_condition = "year_branch_class='$year_branch_class_without_batch' AND is_valid='1'";

							// // Construct the final query with both conditions
							//$query = "SELECT * FROM $branch_teacher WHERE (acad_year = '$acad_year' AND branch = '$branch' AND class = '$class') OR subject = '$general'";


                            $query = "SELECT name, subject
							FROM specialization
							WHERE course_name = '$specialization'
							UNION
							SELECT name, subject
							FROM $branch_teacher
							WHERE (acad_year = '$acad_year' AND branch = '$branch' AND class = '$class') OR subject = '$general'";
				  

							$result = mysqli_query($conn, $query);

                        $slideNumber = 1;

                        // Display feedback form for each teacher
							while ($row = mysqli_fetch_assoc($result)) {
								$teacher_name = $row['name'];
								$teacher_subject = $row['subject'];

                            echo "<div class='carousel-item" . ($slideNumber == 1 ? " active" : "") . "'>";
                            echo "<h3>Slide $slideNumber</h3>";

                            // Retrieve questions from question_data table
								$query = "SELECT * FROM question";
								$question_result = mysqli_query($conn, $query);
								$question_number = 1;

                            // Display teacher and subject

                            echo "<h3>" . $teacher_name . "</h3>";
                            echo "<h4>" . " ( " . $teacher_subject . " )</h4>";
                            echo "<br>";

                           // Display questions and radio buttons
								while ($question_row = mysqli_fetch_assoc($question_result)) {
									$question = $question_row['questions'];
									// echo "<p>Q$question_number. $question</p>";
									echo "<tr><td style='padding: 10px;  font-family: Arial, Helvetica, sans-serif'><b>" . "Q" . "" . $question . "</b></td></tr>";
									echo "<tr><td style='padding: 10px;  font-family: Arial, Helvetica, sans-serif'>";
									echo "<input type='radio' name='feedback[$teacher_name][$teacher_subject][$question_number]' value='1' required> 1  ";
									echo "<input type='radio' name='feedback[$teacher_name][$teacher_subject][$question_number]' value='2' required> 2  ";
									echo "<input type='radio' name='feedback[$teacher_name][$teacher_subject][$question_number]' value='3' required> 3  ";
									echo "<input type='radio' name='feedback[$teacher_name][$teacher_subject][$question_number]' value='4' required> 4  ";
									echo "<input type='radio' name='feedback[$teacher_name][$teacher_subject][$question_number]' value='5' required> 5  <br></td></tr>";
									$question_number++;
                            }

                            echo "</div>";

                            $slideNumber++;
                        }
                        echo "<div> <label>Feedback:</label><input type='text' name='remark[$username]' size='35'></div>";


                            $_SESSION['prn'] = $username;
							$_SESSION['student_name'] = $student_name;
							$_SESSION['acad_year'] = $acad_year;
							$_SESSION['branch'] = $branch;
							$_SESSION['class'] = $class;
							$_SESSION['branch_feedback']=$branch_feedback;
							$_SESSION['branch_check']=$branch_check;
							$_SESSION['branch_teacher']=$branch_teacher;
							$try = 0;
                        // Display submit button
                        echo "<br><input type='submit' name='submit' value='Submit Feedback'>";
							echo "</form>";
							echo "<br>";
                        $slideNumber++;
                    } else {
                        $try = 1;
                        echo "You have already submitted the form!";
                    }
                    ?>

                </div>




                <input class="form-submit" type='submit' name='submit' value='Submit Feedback' style="display:none">
                <button class='slide-prev btn btn-primary me-1'>Previous Slide</button>
                <button class='slide-next btn btn-primary'>Next Slide</button>
            </form>
            <button class='btn btn-danger' onclick="checkAllRadio()">Submit Feedback</button>
        </div>

        
    </div>



    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="./slick/slick.min.js"></script>
    <script type="text/javascript">
        var total_questions = 9;





        $(document).ready(function() {



            $('.my-slider').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                dots: false,
                speed: 300,
                infinite: false,
                autoplaySpeed: 5000,
                autoplay: false,
                swipe: false,

            });


            $('.slide-prev').click(function(e) {
                e.preventDefault();
                $('.my-slider').slick('slickPrev');
            });

            $('.slide-next').click(function(e) {
                e.preventDefault();

                console.log("");

                var activeSlide = document.querySelector('.slick-active');
                var radioButtons = activeSlide.querySelectorAll('input[type="radio"]');
                var isFilled = true;
                var count = 0;

                radioButtons.forEach(function(radioButton) {
                    if (radioButton.checked === true) {
                        count++;
                    }
                });


                if (count < total_questions) { // 9 questions 


                    alert('Please fill in all the radio buttons on the active slide.');
                    return false; // Prevent carousel slide if any radio button is not filled
                } else {

                    $('.my-slider').slick('slickNext');


                }
            });

        });






        function checkAllRadio() {
            console.log("hi");
            var radioButtons = document.querySelectorAll('input[type="radio"]');
            var isFilled = true;
            var count = 0;


            radioButtons.forEach(function(radioButton) {
                if (radioButton.checked === true) {
                    count++;
                }
            });
            var totalSlides = $('.carousel-item').length;
            var total_buttons = totalSlides * (total_questions);
            console.log(count);
            if (count < total_buttons) {
                alert('Please fill in all the options in every slide');
            } else {
                $('.form-submit').click();
            }

        }
    </script>

    <!-- Add the following JavaScript code after the existing code -->
    <script type="text/javascript">
        // Variables to track touch positions
        let startX = 0;
        let endX = 0;

        // Get the slider container element
        const sliderContainer = document.querySelector('.my-slider');

        // Add touch event listeners to the slider container
        sliderContainer.addEventListener('touchstart', handleTouchStart, false);
        sliderContainer.addEventListener('touchmove', handleTouchMove, false);
        sliderContainer.addEventListener('touchend', handleTouchEnd, false);

        // Handle touchstart event
        function handleTouchStart(event) {
            startX = event.touches[0].clientX;
        }

        // Handle touchmove event
        function handleTouchMove(event) {
            endX = event.touches[0].clientX;
        }

        // Handle touchend event
        function handleTouchEnd() {
            const touchDistance = endX - startX;
            const swipeThreshold = 150; // Adjust this value to control swipe sensitivity

            if (touchDistance > swipeThreshold) {
                // Swipe right, go to previous slide
                $('.my-slider').slick('slickPrev');
            } else if (touchDistance < -swipeThreshold) {
                // Swipe left, go to next slide

                var activeSlide = document.querySelector('.slick-active');
                var radioButtons = activeSlide.querySelectorAll('input[type="radio"]');
                var isFilled = true;
                var count = 0;

                radioButtons.forEach(function(radioButton) {
                    if (radioButton.checked === true) {
                        count++;
                    }
                });


                if (count < total_questions) { // 9 questions 


                    alert("Please fill the active slide")
                    return false; // Prevent carousel slide if any radio button is not filled
                } else {

                    $('.slide-next').click();


                }

            }
        }
    </script>


</body>

</html>