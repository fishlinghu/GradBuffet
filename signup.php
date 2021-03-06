<?php
  session_start();
  include "dbinfo.inc";
  include "checkTable.php";
  //error_reporting(E_ALL);
  //ini_set('display_errors', 'On');
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
    echo "<a href=\"login.php\">Login</a>";
    }

  /* If input fields are populated, add a row to the application table. */
  $applicant_account = (isset($_POST['account']) ? $_POST['account'] : null);
  $applicant_pwd = (isset($_POST['pwd']) ? $_POST['pwd'] : null);
  $applicant_gpa = (isset($_POST['gpa']) ? $_POST['gpa'] : null);
  $applicant_toefl = (isset($_POST['toefl']) ? $_POST['toefl'] : null);
  $applicant_greV = (isset($_POST['greV']) ? $_POST['greV'] : null);
  $applicant_greQ = (isset($_POST['greQ']) ? $_POST['greQ'] : null);
  $applicant_greAWA = (isset($_POST['greAWA']) ? $_POST['greAWA'] : null);
  $applicant_gmat = (isset($_POST['gmat']) ? $_POST['gmat'] : null);
  $applicant_foreign_student = (isset($_POST['foreign_student']) ? $_POST['foreign_student'] : null);
  $applicant_num_pub = (isset($_POST['num_pub']) ? $_POST['num_pub'] : null);

  $_POST = array();
  if (strlen($applicant_account) && strlen($applicant_pwd)) {
    AddApplicant($connection,
      $applicant_account,
      $applicant_pwd,
      $applicant_gpa,
      $applicant_toefl,
      $applicant_greV,
      $applicant_greQ,
      $applicant_greAWA,
      $applicant_gmat,
      $applicant_foreign_student,
      $applicant_num_pub);

    echo "<script type=\"text/javascript\">
            alert(\"Thanks for your registration!\")
            location = \"index.php\"
          </script>";
  }

?>
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
          <li><a href="bestFit.php">Find Best Fit</a></li>
          <!--li><a href="contact.html">Contact</a></li-->
        </ul>
      </nav>
    </header>
    <section class="page-content">
      <article>
        <h1>Sign up here to continue submit your application result</h1>
          <form action="signup.php" method="post">
            Account: <input type="text" name="account"><br>
            Password: <input type="password" name="pwd"><br>
            GPA: <input type="number" name="gpa" step=0.01><br>
            TOEFL: <input type="number" name="toefl" value=0 step=1><br>
            GRE Verbal: <input type="number" name="greV" value=0 step=1><br>
            GRE Quantity: <input type="number" name="greQ" value=0 step=1><br>
            GRE AWA: <input type="number" name="greAWA" value=0 step=0.5><br>
            GMAT: <input type="number" name="gmat" value=0 step=1><br>
            Foreign student or not: <select name="foreign_student">
                                      <option value=0>No</option>
                                      <option value=1>Yes</option>
                                    </select><br>
            Number of publications: <input type="number" name="num_pub" value=0 step=1><br>
            <input type="submit" value="submit">
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
function AddApplicant($connection, $account, $pwd, $gpa, $toefl, $greV, $greQ, $greAWA, $gmat, $foreign_student, $num_pub) {
   $clean_account = mysqli_real_escape_string($connection, $account);

   $query = "INSERT INTO `Applicant` (`account`, `pwd`, `gpa`, `toefl`, `greV`, `greQ`, `greAWA`, `gmat`, `foreign_student`, `num_pub`)
              VALUES ('$clean_account', '$pwd', '$gpa', '$toefl', '$greV', '$greQ', '$greAWA', '$gmat', '$foreign_student', '$num_pub');";

   if(!mysqli_query($connection, $query)) echo("Error adding applicant data.". mysqli_error($connection));
}
?>
