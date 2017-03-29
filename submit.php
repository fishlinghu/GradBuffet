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

 $applicant_account = htmlentities($_POST['account']);
 $applicant_pwd = htmlentities($_POST['pwd']);
 $applicant_gpa = htmlentities($_POST['gpa']);
 $applicant_toefl = htmlentities($_POST['toefl']);
 $applicant_greV = htmlentities($_POST['greV']);
 $applicant_greQ = htmlentities($_POST['greQ']);
 $applicant_greAWA = htmlentities($_POST['greAWA']);
 $applicant_gmat = htmlentities($_POST['gmat']);
 $applicant_foreign_student = htmlentities($_POST['foreign_student']);
 $applicant_num_pub = htmlentities($_POST['num_pub']);

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
          <li><a href="signup.html">Sign Up</a></li>
          <li><a href="submit.html">Submit Result</a></li>
          <li><a href="query.html">Make a Query</a></li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
      </nav>
    </header>
    <section class="page-content">
      <article>
        <h1>User submit their result here</h1>
        <form action="submit.php" method="post">
          Account: <input type="text" name="account"><br>
          Password: <input type="password" name="pwd"><br>
          GPA: <input type="text" name="gpa"><br>
          TOEFL: <input type="text" name="toefl"><br>
          GRE Verbal: <input type="text" name="greV"><br>
          GRE Quantity: <input type="text" name="greQ"><br>
          GRE AWA: <input type="text" name="greAWA"><br>
          GMAT: <input type="text" name="gmat"><br>
          Foreign student or not: <input type="text" name="foreign_student"><br>
          Number of publications: <input type="text" name="num_pub"><br>
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
