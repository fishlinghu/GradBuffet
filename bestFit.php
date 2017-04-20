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
    echo "<a href=\"login.php\">Login</a>";
    }

  $sql = "SELECT DISTINCT(major) FROM Program";
  $majorList = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));

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
          <!--li><a href="contact.html">Contact</a></li-->
        </ul>
      </nav>
    </header>
    <section class="page-content">
      <article>
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
