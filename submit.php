<?php
  include "dbinfo.inc";
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
 VerifyApplicantTable($connection, DB_DATABASE);

 /* If input fields are populated, add a row to the Employees table. */

 if (strlen($applicant_account) && strlen($applicant_pwd)) {
   AddApplication();
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

/* Check whether the table exists and, if not, create it. */
function VerifyApplicationTable($connection, $dbName) {
  if(!TableExists("Application", $connection, $dbName))
  {
  $query = "CREATE TABLE `Application` (
          `ID` int(11) NOT NULL AUTO_INCREMENT,
          `schoolID` int(11) NOT NULL,
          `programID` int(11) NOT NULL,
          `term` CHAR(40) DEFAULT NULL,
          `dateSub` DATE DEFAULT NULL,
          `dateResult` DATE DEFAULT NULL,
          `applicantID` int(11) NOT NULL,
          `result` TINYINT(1) DEFAULT 0,
          PRIMARY KEY (`ID`),
          UNIQUE KEY `ID_UNIQUE` (`ID`)
       ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

     if(!mysqli_query($connection, $query)) echo("Error creating table.");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>
