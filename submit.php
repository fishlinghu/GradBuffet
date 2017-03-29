<?php
  include "dbinfo.inc";
  include "checkTable.php";
  error_reporting(E_ALL);
  ini_set('display_errors', 'On');
  ?>

  <html lang="en">
  <?php

/*connection to mySQL and select database*/
 $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
 if(mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();
 $database = mysqli_select_db($connection, DB_DATABASE);

 /* Ensure User table exists */
 VerifyTable($connection, DB_DATABASE);

 /* If input fields are populated, add a row to the Employees table. */

 //if (strlen($applicant_account) && strlen($applicant_pwd)) {
   //AddApplication();
 //}

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
          <li><a class="home-link" href="index.html">Home</a></li>
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

          <label for="pname">Program Name</label>
          <input type="text" list="programname" autocomplete="off" name="pname">
          <datalist id="programname">
            <?php
            while($row = mysqli_fetch_array($programList)) 
              { ?>
              <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
              <?php } 
            ?>
          </datalist><br>
          Date of submission: <input type="date" name="dateSub"><br>
          Date of result: <input type="date" name="dateResult"><br>
          Result: <select name="result">
                    <option value=0>Rejected</option>
                    <option value=1>Admitted</option>
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
function AddApplication($connection, $schoolName, $programName, $term, $dateSub, $dateResult, $account, $result) {
    # $clean_account = mysqli_real_escape_string($connection, $account);
    # use SQL query to get schoolID, programID, by schoolName, programName

    $query = "INSERT INTO `Application` (`schoolID`, `programID`, `term`, `dateSub`, `dateResult`, `applicantID`, `result`)
              VALUES ('$schoolID', '$programID', '$term', '$dateSub', '$dateResult', '$account', '$result');";

    if(!mysqli_query($connection, $query)) echo("Error adding application data.". mysqli_error($connection));
}
?>
