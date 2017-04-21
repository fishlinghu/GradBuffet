<?php
  session_start();
  include "dbinfo.inc";
  include "checkTable.php";
  error_reporting(E_ALL);
  ini_set('display_errors', 'On');
?>
<html lang="en">
<?php
  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the User table exists. */
  VerifyTable($connection, DB_DATABASE);

  if(isset($_SESSION['applicantID']) && $_SESSION['applicantID'] != null)
    { 
    // user has logged in
    echo "<a href=\"logout.php\">Logout</a>";
    }
  else
    {
    // user did not log in
    echo "<script type=\"text/javascript\">
            alert(\"Please log in to find your best fit!\")
            location = \"login.php\"
          </script>";
    }

  $sql = "SELECT DISTINCT(major) FROM Program";
  $majorList = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));

  $applicantID = $_SESSION['applicantID'];

  $programArray = null;

  if(isset($_POST['submit'])){
    $programmajor = (isset($_POST['pmajor']) ? $_POST['pmajor'] : null);
    $programArray = getSortedProgram($connection, $programmajor, $applicantID);
    }

?>
<style>
  .fancytable{border:1px solid #cccccc; width:100%;border-collapse:collapse;}
  .fancytable td{border:1px solid #cccccc; color:#555555;text-align:center;line-height:28px;}
  .headerrow{ background-color:#555555;}
  .headerrow td{ color:#ffffff; text-align:center;}
  .datarowodd{background-color:#ffa64d;}
  .datarowodd td{background-color:#ffa64d;}
</style>
<head>
  <meta charset="utf-8">
  <title>Grad Buffet - Sign Up</title>
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
          <!--li><a href="contact.html">Contact</a></li-->
        </ul>
      </nav>
    </header>
    <section class="page-content">
      <article>
        <form action='bestFit.php' method='post'>
        <h2>Select your major</h2>
          <label for="pmajor">Major: </label>
          <input type="text" list="majorname" autocomplete="off" name="pmajor">
          <datalist id="majorname">
            <?php
            while($row = mysqli_fetch_array($majorList)) 
              { ?>
              <option value="<?php echo $row['major']; ?>"><?php echo $row['major']; ?></option>
              <?php } 
            ?>
          </datalist><br><br>
        <input type='submit' name = 'submit' value='Look for Programs'/>

        <br><br>
        <h2>Your top 10 best fit!</h2>
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
            while($row = mysqli_fetch_array($programArray))
              {
              $tempSchoolName = findSchoolName($connection, $row["school_ID"]);
              $foreign_rate = 0;
              if($row["ad_count"] != 0){
                $foreign_rate = intval(100*$row["foreign_count"]/$row["ad_count"]);
              }
              echo "<tr class=\"datarowodd\">";
              echo "<td>".$tempSchoolName."</td>"; // need to get school name
              echo "<td>".$row["degree"]."</td>";
              echo "<td>".$row["major"]."</td>";
              echo "<td>".$row["avgGPA"]."</td>";
              echo "<td>".$row["avgTOEFL"]."</td>";
              echo "<td>".$row["avgGREQ"]."/".$row["avgGREV"]."/".$row["avgGREAWA"]."</td>";
              echo "<td>".$row["avgGMAT"]."</td>";
              echo "<td>".intval(100*$row["ad_count"]/$row["total_count"])."%</td>";
              echo "<td>".$foreign_rate."%</td>";
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

  <!--script src="js/set-background.js"></script-->
</body>
</html>

<?php
/* find program */
function getSortedProgram($connection, $programmajor, $applicantID){
  // get the data of the applicant
  $sql = "SELECT * FROM Applicant WHERE ID = '$applicantID'";
  $sqlReturn = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));
  $applicantData = mysqli_fetch_array( $sqlReturn );
  $gpa = $applicantData['gpa'];
  $toefl = $applicantData['toefl'];
  $greV = $applicantData['greV'];
  $greQ = $applicantData['greQ'];
  $greAWA = $applicantData['greAWA'];
  $gmat = $applicantData['gmat'];

  $sql = "SELECT *, SQRT($gpa-avgGPA) AS x FROM Program
            WHERE major = '$programmajor'
            ORDER BY x ASC
            LIMIT 10";

  $sqlReturn = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));

  return $sqlReturn;
}

/* find school name */
function findSchoolName($connection, $schoolID){
  $sql = "SELECT ID, name FROM School 
            WHERE ID = '$schoolID'";
  $sqlReturn = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));
  $sqlReturn = mysqli_fetch_array( $sqlReturn );
  $schoolname = $sqlReturn['name'];
  return $schoolname;
}
?>
