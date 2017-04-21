<?php
  session_start();
  include "dbinfo.inc";
  include "checkTable.php";
  //error_reporting(E_ALL);
  //ini_set('display_errors', 'On');
?>

<html lang="en">
  <?php
  if(isset($_SESSION['applicantID']) && $_SESSION['applicantID'] != null)
    { 
    // user has logged in
    echo "<a href=\"logout.php\">Logout</a>";
    }
  else
    {
    // user did not log in
    
    echo "<script type=\"text/javascript\">
            alert(\"Please log in to submit your results!\")
            location = \"login.php\"
          </script>";
    
    //echo "<a href=\"login.php\">Login</a>";
    }
  /*connection to mySQL and select database*/
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
  if(mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();
  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure User table exists */
  VerifyTable($connection, DB_DATABASE);
  //echo $schoolname;
  //echo $programdegree;
  //echo $programmajor;
  if (isset($_POST['submit'])) 
    {
    $schoolArr = array("Carnegie Mellon University", 
                      "University of California, Berkeley", 
                      "University of Texas, Austin",
                      "Georgia Institute of Technology", 
                      "University of Illinois Urbana-Champaign",
                      "University of California, San Diego", 
                      "Purdue University",
                      "Cornell University",
                      "University of California, Los Angeles", 
                      "University of Southern California",
                      "University of California, Irvine",
                      "Columbia University",
                      "San Jose State University",
                      "University of Texas, Dallas",
                      "New York University",
                      "University of Chicago"); 
    
    $majorArr = array("CS",
                      "EE",
                      "ECE",
                      "Civil Engineering",
                      "Chemistry",
                      "Mechanical Engineering");

    $degreeArr = array("MS", "PhD");

    // get term
    $term = (isset($_POST['term']) ? $_POST['term'] : null);

    // get date sub
    $tmp = (isset($_POST['dateSub']) ? $_POST['dateSub'] : null);    
    $dateSub = date('Y-m-d', strtotime($tmp));
    
    // get date result
    $tmp = (isset($_POST['dateResult']) ? $_POST['dateResult'] : null);    
    $dateResult = date('Y-m-d', strtotime($tmp));

    // get account
    $applicantID = $_SESSION['applicantID'];
    $sql = "SELECT * FROM Applicant WHERE ID = '$applicantID'";
    $sqlReturn = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));
    $applicantData = mysqli_fetch_array( $sqlReturn );
    $applicantGPA = $applicantData["gpa"];

    $t = 400 + (4-$applicantGPA) * 400;
    $randMin = 0;
    $randMax = 1000;

    $programdegree = "MS";
    foreach ($schoolArr as $schoolname) {
      // get schoolID
      $schoolID = findSchoolID($connection, $schoolname);
      if( $schoolID == NULL)
        { 
        // new school, we have to create a entry in the School table
        AddSchool($connection, $schoolname);
        $schoolID = findSchoolID($connection, $schoolname);
        }
      $tempRandMin = $randMin;
      foreach ($majorArr as $programmajor){
        // get programID
        $programID = findProgramID($connection, $programdegree, $programmajor, $schoolID);
        if( $programID == NULL)
          { 
          // new program, we have to create a entry in the Program table
          AddProgram($connection, $programdegree, $programmajor, $schoolID);
          $programID = findProgramID($connection, $programdegree, $programmajor, $schoolID);
          }

        if(rand($tempRandMin, $randMax) > $t){
          $result = 1;
        }
        else{
          $result = 0;
        }

        AddApplication($connection, $schoolID, $programID, $term, $dateSub, $dateResult, $applicantID, $result);

        updateProgram($connection, $programID, $applicantID, $result);

        $tempRandMin = $tempRandMin + 3;
      }
    $randMin = $randMin + 20;
    }

    $t = 500 + (4-$applicantGPA) * 400;
    $randMin = 0;
    $randMax = 1000;

    $programdegree = "PhD";
    foreach ($schoolArr as $schoolname) {
      // get schoolID
      $schoolID = findSchoolID($connection, $schoolname);
      if( $schoolID == NULL)
        { 
        // new school, we have to create a entry in the School table
        AddSchool($connection, $schoolname);
        $schoolID = findSchoolID($connection, $schoolname);
        }
      $tempRandMin = $randMin;
      foreach ($majorArr as $programmajor){
        // get programID
        $programID = findProgramID($connection, $programdegree, $programmajor, $schoolID);
        if( $programID == NULL)
          { 
          // new program, we have to create a entry in the Program table
          AddProgram($connection, $programdegree, $programmajor, $schoolID);
          $programID = findProgramID($connection, $programdegree, $programmajor, $schoolID);
          }

        if(rand($tempRandMin, $randMax) > $t){
          $result = 1;
        }
        else{
          $result = 0;
        }

        AddApplication($connection, $schoolID, $programID, $term, $dateSub, $dateResult, $applicantID, $result);

        updateProgram($connection, $programID, $applicantID, $result);

        $tempRandMin = $tempRandMin + 3;
      }
    $randMin = $randMin + 20;
    }

    //echo $programname;
    
    // print the message using javascript and jump to index page
    $_POST = array();
    echo "<script type=\"text/javascript\">
            alert(\"Thanks for your submission!\")
          </script>";
    //location = \"index.php\"
    }
  ?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Grad Buffet - Submit Results</title>
  <meta name="description" content=""/>
  <link href="css/styles.css" rel="stylesheet">
  <link href="css/gradients.css" rel="stylesheet">
</head>
<body>
  <div class="wrapper">
    <header>
      <nav class="website-nav">
        <ul>
          <li><a class="home-link" href="index.php">Home</a></li>
          <!--li><a href="about.html">About</a></li-->
          <li><a href="signup.php">Sign Up</a></li>
          <li><a href="submit.php">Submit Result</a></li>
          <li><a href="query.php">Look for Programs</a></li>
          <li><a href="queryApplication.php">Look for Applications</a></li>
          <li><a href="bestFit.php">Find Best Fit</a></li>
          <!--li><a href="contact.html">Contact</a></li-->
        </ul>
      </nav>
    </header>
    <section class="page-content">
      <article>
        <h1>Populate data by clicking the button</h1>
        <form action="populateData.php" method="post">
          Term: <select name="term">
                  <option value="Fall 2017">Fall 2017</option>
                  <option value="Spring 2017">Spring 2017</option>
                  <option value="Fall 2016">Fall 2016</option>
                  <option value="Spring 2016">Spring 2016</option>
                </select><br>
          Date of submission: <input type="date" name="dateSub"><br>
          Date of result: <input type="date" name="dateResult"><br>
          <input type='submit' name = 'submit' value='Populate Data!'/>
        </form>

      </article>
    </section>
    <div class="push"></div>

  </div>
  <footer>
    <p class="footer-contents">
      Designed for GaTech CS4440
    </p>
  </footer>

  <!--script src="js/set-background.js"></script-->
</body>
</html>


<?php

/* Add an applicant to the table. */
function AddApplication($connection, $schoolID, $programID, $term, $dateSub, $dateResult, $applicantID, $result) {
    # $clean_account = mysqli_real_escape_string($connection, $account);

    $query = "INSERT INTO `Application` (`schoolID`, `programID`, `term`, `dateSub`, `dateResult`, `applicantID`, `result`)
              VALUES ('$schoolID', '$programID', '$term', '$dateSub', '$dateResult', '$applicantID', '$result');";

    if(!mysqli_query($connection, $query)) echo("Error adding application data.". mysqli_error($connection));
}

/* Add a new school to the table */
function AddSchool($connection, $schoolname){
  $sql = "INSERT INTO `School` (`name`)
          VALUES ('$schoolname');";
  if(!mysqli_query($connection, $sql)) echo("Error adding school.". mysqli_error($connection));
}

/* Add a new program to the table */
function AddProgram($connection, $programdegree, $programmajor, $schoolID){
  $sql = "INSERT INTO `Program` (`degree`, `major`, `school_ID`)
          VALUES ('$programdegree', '$programmajor', '$schoolID');";
  if(!mysqli_query($connection, $sql)) echo("Error adding program.". mysqli_error($connection));
}

/* map school name to school ID */
function findSchoolID($connection, $schoolname){
  $sql = "SELECT ID, name FROM School 
            WHERE name = '$schoolname'";
  $sqlReturn = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));
  $sqlReturn = mysqli_fetch_array( $sqlReturn );
  $schoolID = $sqlReturn['ID'];
  return $schoolID;
}

/* map program name to program ID */
function findProgramID($connection, $programdegree, $programmajor, $schoolID){
  $sql = "SELECT ID, degree, major, school_ID FROM Program 
            WHERE degree = '$programdegree' AND major = '$programmajor' AND school_ID = '$schoolID'";
  $sqlReturn = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));
  $sqlReturn = mysqli_fetch_array( $sqlReturn );
  $programID = $sqlReturn['ID'];
  return $programID;
}

/* update the statistic data of the program */
function updateProgram($connection, $programID, $applicantID, $result){
  // get the data of the applicant
  $sql = "SELECT * FROM Applicant WHERE ID = '$applicantID'";
  $sqlReturn = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));
  $applicantData = mysqli_fetch_array( $sqlReturn );

  // get the data of the program
  $sql = "SELECT * FROM Program WHERE ID = '$programID'";
  $sqlReturn = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));
  $programData = mysqli_fetch_array( $sqlReturn );

  
  $temp_total_count = $programData['total_count'] + 1;

  if($result == 1){
    // admitted
    $temp_ad_count = $programData['ad_count'] + 1;
    if($applicantData['foreign_student'] == 1){
      $temp_foreign_count = $programData['foreign_count'] + 1;
    }
    else{
      $temp_foreign_count = $programData['foreign_count'];
    }
    $tempGPA = ($programData['avgGPA'] * $programData['ad_count'] + $applicantData['gpa']) / $temp_ad_count;
    $tempTOEFL = ($programData['avgTOEFL'] * $programData['ad_count'] + $applicantData['toefl']) / $temp_ad_count;
    $tempGREV = ($programData['avgGREV'] * $programData['ad_count'] + $applicantData['greV']) / $temp_ad_count;
    $tempGREQ = ($programData['avgGREQ'] * $programData['ad_count'] + $applicantData['greQ']) / $temp_ad_count;
    $tempGREAWA = ($programData['avgGREAWA'] * $programData['ad_count'] + $applicantData['greAWA']) / $temp_ad_count;
    $tempGMAT = ($programData['avgGMAT'] * $programData['ad_count'] + $applicantData['gmat']) / $temp_ad_count;
    # update the program data
    $sql = "UPDATE Program 
            SET avgGPA = $tempGPA,
                avgTOEFL = $tempTOEFL,
                avgGREV = $tempGREV,
                avgGREQ = $tempGREQ,
                avgGREAWA = $tempGREAWA,
                avgGMAT = $tempGMAT,
                foreign_count = $temp_foreign_count,
                ad_count = $temp_ad_count,
                total_count = $temp_total_count 
            WHERE ID = $programID";
    $sqlReturn = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));
  }
  else{
    // rejected, update only total count
    $sql = "UPDATE Program 
            SET total_count = $temp_total_count
          WHERE ID = $programID";
    $sqlReturn = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));
  }
}
?>