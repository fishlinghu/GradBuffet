<?php
  include "dbinfo.inc";
  include "checkTable.php";
  error_reporting(E_ALL);
  ini_set('display_errors', 'On');
?>
<html lang="en">
<?php
  // create the log out / log in button at top left corner
  if(isset($_SESSION['applicantID']) && $_SESSION['applicantID'] != null)
    { 
    // user has logged in
    echo "<a href=\"logout.php\">Logout</a>";
    }
  else
    {
    // user did not log in
    echo "<a href=\"login.php\">Login</a>";
    }
  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

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
  $term = (isset($_POST['term']) ? $_POST['term'] : null);
  $L_GPA = (isset($_POST['L_GPA']) ? $_POST['L_GPA'] : null);
  $U_GPA = (isset($_POST['U_GPA']) ? $_POST['U_GPA'] : null);
  // use those parameters to do the query
  if($_POST['submit'] == 'Look for Applications'){
    $application = findApplication($connection, $programdegree, $programmajor, $schoolname, $term);
    print_r($application);  
  }
  else if($_POST['submit'] == 'Look for Programs'){
    $program = findProgram($connection);
    print_r($program);
    //echo "Look for program";
  }
  
?>
<head>
  <meta charset="utf-8">
  <title>Grad Buffet - Make a Query</title>
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
        <form action='query.php' method='post'>
        <h2>Search For Application</h2>
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
          <input type='submit' name = 'submit' value='Look for Applications'/>
        
        <br><br>
        <h2>Search For Program</h2>
          GPA: <input type="number" name="L_GPA" value="0.0" step = 0.1> ~ <input type="number" name="U_GPA" value="4.0" step = 0.1><br>
          TOEFL: <input type="number" name="L_TOEFL" value="0" step = 1> ~ <input type="number" name="U_TOEFL" value="120" step = 1><br>
          GRE Q: <input type="number" name="L_GREQ" value="0" step = 1> ~ <input type="number" name="U_GREQ" value="170" step = 1><br>
          GRE V: <input type="number" name="L_GREV" value="0" step = 1> ~ <input type="number" name="U_GREV" value="170" step = 1><br>
          GMAT: <input type="number" name="L_GMAT" value="0" step = 1> ~ <input type="number" name="U_GMAT" value="900" step = 1><br>
          Admission rate: <input type="number" name="L_AdRate" value="0.00" step = 0.01> ~ <input type="number" name="U_AdRate" value="1.00" step = 0.01><br>
          <input type='submit' name = 'submit' value='Look for Programs'/>
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

/* find application */
function findApplication($connection, $programdegree, $programmajor, $schoolname, $term){
  $schoolID = findSchoolID($connection, $schoolname);
  $programID = findProgramID($connection, $programdegree, $programmajor, $schoolID);

  if($schoolID == null && $programID == null){
    echo "<script type=\"text/javascript\">
            alert(\"Please enter something!\")
          </script>";
    return;
  }

  $sql = "SELECT * FROM Application
            WHERE (programID = '$programID' OR '$programID' = '') 
              AND (schoolID = '$schoolID' OR '$schoolID' = '') 
              AND term = '$term'";
  $sqlReturn = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));
  $sqlReturn = mysqli_fetch_array( $sqlReturn );

  return $sqlReturn;
}

/* find program */
function findProgram($connection){

}
?>