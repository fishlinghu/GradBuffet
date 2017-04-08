<?php
  session_start();
  include "dbinfo.inc";
  include "checkTable.php";
  error_reporting(E_ALL);
  ini_set('display_errors', 'On');
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

  // get the list for datalist
  $sql = "SELECT name FROM School";
  $schoolList = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));

  $sql = "SELECT DISTINCT(degree) FROM Program";
  $degreeList = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));

  $sql = "SELECT DISTINCT(major) FROM Program";
  $majorList = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));

  // get the query parameters sent by user through POST
  $schoolname = (isset($_POST['sname']) ? $_POST['sname'] : null);
  $programdegree = (isset($_POST['pdegree']) ? $_POST['pdegree'] : null);
  $programmajor = (isset($_POST['pmajor']) ? $_POST['pmajor'] : null);
  //echo $schoolname;
  //echo $programdegree;
  //echo $programmajor;
  if (strlen($schoolname) && strlen($programdegree) && strlen($programmajor)) 
    {
    // should we use degree level + program major?
    //echo $schoolname;
    //echo $programname;
    
    // get schoolID
    $schoolID = findSchoolID($connection, $schoolname);
    if( $schoolID == NULL)
      { 
      // new school, we have to create a entry in the School table
      AddSchool($connection, $schoolname);
      $schoolID = findSchoolID($connection, $schoolname);
      }
    
    // get programID
    
    $programID = findProgramID($connection, $programdegree, $programmajor, $schoolID);
    if( $programID == NULL)
      { 
      // new program, we have to create a entry in the Program table
      AddProgram($connection, $programdegree, $programmajor, $schoolID);
      $programID = findProgramID($connection, $programdegree, $programmajor, $schoolID);
      }
    
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

    // get result
    $result = (isset($_POST['result']) ? $_POST['result'] : null);
    
    /*
    echo $schoolID, '<br>';
    echo $term, '<br>';
    echo $dateSub, '<br>';
    echo $dateResult, '<br>';
    echo $result, '<br>';
    */
    AddApplication($connection, $schoolID, $programID, $term, $dateSub, $dateResult, $applicantID, $result);
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
          <li><a href="about.html">About</a></li>
          <li><a href="signup.php">Sign Up</a></li>
          <li><a href="submit.php">Submit Result</a></li>
          <li><a href="query.php">Make a Query</a></li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
      </nav>
    </header>
    <section class="page-content">
      <article>
        <h1>User submit their result here</h1>
        <form action="submit.php" method="post">
          <label for="sname">School Name</label>
          <input type="text" list="schoolname" autocomplete="off" name="sname">
          <datalist id="schoolname">
            <?php
            while($row = mysqli_fetch_array($schoolList)) 
              { ?>
              <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
              <?php } 
            ?>
          </datalist><br>

          <label for="pdegree">Program Name: </label>
          <input type="text" list="degreename" autocomplete="off" name="pdegree">
          <datalist id="degreename">
            <?php
            while($row = mysqli_fetch_array($degreeList)) 
              { ?>
              <option value="<?php echo $row['degree']; ?>"><?php echo $row['degree']; ?></option>
              <?php } 
            ?>
          </datalist>

          <label for="pmajor"> in </label>
          <input type="text" list="majorname" autocomplete="off" name="pmajor">
          <datalist id="majorname">
            <?php
            while($row = mysqli_fetch_array($majorList)) 
              { ?>
              <option value="<?php echo $row['major']; ?>"><?php echo $row['major']; ?></option>
              <?php } 
            ?>
          </datalist><br>

          Term: <select name="term">
                  <option value="Fall 2017">Fall 2017</option>
                  <option value="Spring 2017">Spring 2017</option>
                </select><br>
          Date of submission: <input type="date" name="dateSub"><br>
          Date of result: <input type="date" name="dateResult"><br>
          Result: <select name="result">
                    <option value=1>Admitted</option>
                    <option value=0>Rejected</option>
                  </select><br>
          <input type="submit">
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

  <script src="js/set-background.js"></script>
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

?>
