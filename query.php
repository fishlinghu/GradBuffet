<?php
  include "dbinfo.inc";
  error_reporting(E_ALL);
  ini_set('display_errors', 'On');
?>
<html lang="en">
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  if(!TableExists("School", $connection, DB_DATABASE)) 
    { 
    // create the School table
    createSchoolTable($connection);
    } 
  if(!TableExists("Program", $connection, DB_DATABASE)) 
    { 
    // create the Program table
    createProgramTable($connection);
    }
  // get the list for datalist
  $sql = "select name from School";
  $schoolList = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));

  $sql = "select name from Program";
  $programList = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));

  // get the query parameters sent by user through POST
  $schoolname = htmlentities($_POST['sname']);
  $programname = htmlentities($_POST['pname']);
  $L_GPA = $_POST['L_GPA'];
  $U_GPA = $_POST['U_GPA'];
  // use those parameters to do the query
  $sql = "select * from application"; 
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
        <h1>User make query here</h1>
        <form action='query.php' method='post'>
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
          Lower bound of GPA: <input type="number" name="L_GPA" value="0.0" step = 0.1><br>
          Upper bound of GPA: <input type="number" name="U_GPA" value="4.0" step = 0.1><br>
          <br><input type='submit' name = 'submit' value='submit'/>
        </form>

        <p>Search by average GPA</p>
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
/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}

function createSchoolTable($connection)
  {
  $query = "CREATE TABLE `School` (
          `ID` int(11) NOT NULL AUTO_INCREMENT,
          `name` CHAR(64) NOT NULL,
          `location` CHAR(64) DEFAULT NULL,
          PRIMARY KEY (`ID`),
          UNIQUE KEY `ID_UNIQUE` (`ID`)
       ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

  if(!mysqli_query($connection, $query)) echo("Error creating school table.");
  }
function createProgramTable($connection)
  {
  $query = "CREATE TABLE `Program` (
          `ID` int(11) NOT NULL AUTO_INCREMENT,
          `name` CHAR(64) NOT NULL,
          `avgGPA` FLOAT(4,3) DEFAULT NULL,
          `avgTOEFL` FLOAT(4,1) DEFAULT NULL,
          `avgGREV` FLOAT(4,1) DEFAULT NULL,
          `avgGREQ` FLOAT(4,1) DEFAULT NULL,
          `avgGREAWA` FLOAT(3,2) DEFAULT NULL,
          `avgGMAT` FLOAT(4,1) DEFAULT NULL,
          `ad_rate` FLOAT(4,3) DEFAULT 0,
          `school_ID` int(11) DEFAULT NULL,
          PRIMARY KEY (`ID`),
          UNIQUE KEY `ID_UNIQUE` (`ID`)
       ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

  if(!mysqli_query($connection, $query)) echo("Error creating program table.");
  }
?>