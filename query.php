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
  // use those parameters to do the query
  if(isset($_POST['submit'])){
    if($_POST['submit'] == 'Look for Applications'){
      $application = findApplication($connection, $programdegree, $programmajor, $schoolname, $term);
      print_r($application);  
    }
    else if($_POST['submit'] == 'Look for Programs'){
      $L_GPA = (isset($_POST['L_GPA']) ? $_POST['L_GPA'] : null);
      $U_GPA = (isset($_POST['U_GPA']) ? $_POST['U_GPA'] : null);
      $L_TOEFL = (isset($_POST['L_TOEFL']) ? $_POST['L_TOEFL'] : null);
      $U_TOEFL = (isset($_POST['U_TOEFL']) ? $_POST['U_TOEFL'] : null);
      $L_GREQ = (isset($_POST['L_GREQ']) ? $_POST['L_GREQ'] : null);
      $U_GREQ = (isset($_POST['U_GREQ']) ? $_POST['U_GREQ'] : null);
      $L_GREV = (isset($_POST['L_GREV']) ? $_POST['L_GREV'] : null);
      $U_GREV = (isset($_POST['U_GREV']) ? $_POST['U_GREV'] : null);
      $L_GREAWA = (isset($_POST['L_GREAWA']) ? $_POST['L_GREAWA'] : null);
      $U_GREAWA = (isset($_POST['U_GREAWA']) ? $_POST['U_GREAWA'] : null);
      $L_GMAT = (isset($_POST['L_GMAT']) ? $_POST['L_GMAT'] : null);
      $U_GMAT = (isset($_POST['U_GMAT']) ? $_POST['U_GMAT'] : null);
      $L_AdRate = (isset($_POST['L_AdRate']) ? $_POST['L_AdRate'] : null);
      $U_AdRate = (isset($_POST['U_AdRate']) ? $_POST['U_AdRate'] : null);
      $L_ForeignRate = (isset($_POST['L_ForeignRate']) ? $_POST['L_ForeignRate'] : null);
      $U_ForeignRate = (isset($_POST['U_ForeignRate']) ? $_POST['U_ForeignRate'] : null);
      $program = findProgram($connection, $programdegree, $programmajor, $schoolname, $U_GPA, $L_GPA, $U_TOEFL, $L_TOEFL, $U_GREQ, $L_GREQ, $U_GREV, $L_GREV, $U_GREAWA, $L_GREAWA, $U_GMAT, $L_GMAT, $U_AdRate, $L_AdRate, $U_ForeignRate, $L_ForeignRate);
      
      //echo $program['major'];
      //echo $program['degree'];
    }
  }
  
?>
<style>
  .fancytable{border:1px solid #cccccc; width:100%;border-collapse:collapse;}
  .fancytable td{border:1px solid #cccccc; color:#555555;text-align:center;line-height:28px;}
  .headerrow{ background-color:#555555;}
  .headerrow td{ color:#ffffff; text-align:center;}
  .datarowodd{background-color:#ffa64d;}
  .dataroweven{ background-color:#efefef;}
  .datarowodd td{background-color:#ffa64d;}
  .dataroweven td{ background-color:#efefef;}
</style>
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
          <label for="sname">School Name: </label>
          <input type="text" list="schoolname" autocomplete="off" name="sname">
          <datalist id="schoolname">
            <?php
            while($row = mysqli_fetch_array($schoolList)) 
              { ?>
              <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
              <?php } 
            ?>
          </datalist><br>

          <label for="pdegree">Degree level: </label>
          <input type="text" list="degreename" autocomplete="off" name="pdegree">
          <datalist id="degreename">
            <?php
            while($row = mysqli_fetch_array($degreeList)) 
              { ?>
              <option value="<?php echo $row['degree']; ?>"><?php echo $row['degree']; ?></option>
              <?php } 
            ?>
          </datalist><br>

          <label for="pmajor">Major: </label>
          <input type="text" list="majorname" autocomplete="off" name="pmajor">
          <datalist id="majorname">
            <?php
            while($row = mysqli_fetch_array($majorList)) 
              { ?>
              <option value="<?php echo $row['major']; ?>"><?php echo $row['major']; ?></option>
              <?php } 
            ?>
          </datalist><br>
          GPA: <input type="number" name="L_GPA" value="0.0" step = 0.1> ~ <input type="number" name="U_GPA" value="4.0" step = 0.1><br>
          TOEFL: <input type="number" name="L_TOEFL" value="0" step = 1> ~ <input type="number" name="U_TOEFL" value="120" step = 1><br>
          GRE Q: <input type="number" name="L_GREQ" value="0" step = 1> ~ <input type="number" name="U_GREQ" value="170" step = 1><br>
          GRE V: <input type="number" name="L_GREV" value="0" step = 1> ~ <input type="number" name="U_GREV" value="170" step = 1><br>
          GRE AWA: <input type="number" name="L_GREAWA" value="0.0" step = 1> ~ <input type="number" name="U_GREAWA" value="6.0" step = 0.5><br>
          GMAT: <input type="number" name="L_GMAT" value="0" step = 1> ~ <input type="number" name="U_GMAT" value="900" step = 1><br>
          Admission rate: <input type="number" name="L_AdRate" value="0.00" step = 0.01> ~ <input type="number" name="U_AdRate" value="1.00" step = 0.01><br>
          Foreign students rate: <input type="number" name="L_ForeignRate" value="0.00" step = 0.01> ~ <input type="number" name="U_ForeignRate" value="1.00" step = 0.01><br>
          <input type='submit' name = 'submit' value='Look for Programs'/>
        </form>

        <br><br>
        <h2>Result</h2>
        <table class="fancytable">
          <tr class="headerrow">
            <th>School</th>
            <th>Degree</th>
            <th>Major</th> 
            <th>GPA</th>
            <th>TOEFL</th>
            <th>GRE(Q/V/AWA)</th>
            <th>GMAT</th>
            <th>AD rate</th>
            <th>Foreign rate</th>
          </tr>
          <?php 
            
            while($row = mysqli_fetch_array($program))
              {
              echo "<tr class=\"datarowodd\">";
              echo "<td>Georgia Institute of Technology</td>"; // need to get school name
              echo "<td>".$row["degree"]."</td>";
              echo "<td>".$row["major"]."</td>";
              echo "<td>".$row["avgGPA"]."</td>";
              echo "<td>".$row["avgTOEFL"]."</td>";
              echo "<td>".$row["avgGREQ"]."/".$row["avgGREV"]."/".$row["avgGREAWA"]."</td>";
              echo "<td>".$row["avgGMAT"]."</td>";
              echo "<td>".intval(100*$row["ad_count"]/$row["total_count"])."%</td>";
              echo "<td>".intval(100*$row["foreign_count"]/$row["ad_count"])."%</td>";
              echo "</tr>";
              }
          ?>
        </table>
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
  //$sqlReturn = mysqli_fetch_array( $sqlReturn );

  return $sqlReturn;
}

/* find program */
function findProgram($connection, $programdegree, $programmajor, $schoolname, $U_GPA, $L_GPA, $U_TOEFL, $L_TOEFL, $U_GREQ, $L_GREQ, $U_GREV, $L_GREV, $U_GREAWA, $L_GREAWA, $U_GMAT, $L_GMAT, $U_AdRate, $L_AdRate, $U_ForeignRate, $L_ForeignRate){
  $schoolID = findSchoolID($connection, $schoolname);
  $programID = findProgramID($connection, $programdegree, $programmajor, $schoolID);

  $sql = "SELECT * FROM Program
            WHERE (ID = '$programID' OR '$programID' = '') 
              AND (school_ID = '$schoolID' OR '$schoolID' = '') 
              AND (avgGPA >= $L_GPA AND avgGPA <= $U_GPA)
              AND (avgTOEFL >= $L_TOEFL AND avgTOEFL <= $U_TOEFL)
              AND (avgGREV >= $L_GREV AND avgGREV <= $U_GREV)
              AND (avgGREQ >= $L_GREQ AND avgGREQ <= $U_GREQ)
              AND (avgGREAWA >= $L_GREAWA AND avgGREAWA <= $U_GREAWA)
              AND (avgGMAT >= $L_GMAT AND avgGMAT <= $U_GMAT)
              AND (ad_count/total_count >= $L_AdRate AND ad_count/total_count <= $U_AdRate)
              AND (foreign_count/total_count >= $L_ForeignRate AND foreign_count/total_count <= $U_ForeignRate)";
  $sqlReturn = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));
  //$sqlReturn = mysqli_fetch_array( $sqlReturn );

  return $sqlReturn;
}
?>