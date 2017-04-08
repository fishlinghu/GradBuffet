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
?>
<head>
  <meta charset="utf-8">
  <title>GradBuffet</title>
  <meta name="description" content=""/>
  <link href="css/styles.css" rel="stylesheet">
  <link href="css/gradients.css" rel="stylesheet">
</head>
  <body class="">
    <div class="wrapper">
      <div class="graphics">
        <div class="tower">
          <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
          width="1000px" height="429px" viewBox="0 0 1000 429">
          <path class="path" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-miterlimit="10" d="M0,424h176l7-204h-2v-3h2v-3h-2v-3h2v-3
          c0,0-15.624-5.4-16-21c-0.307-12.746,12-18,12-18l1-27h-2v-3h7l1-6v-9l1-4V82l1-5V9h4v68l1,5v38l1,4v9l1,6h7v3h-2l1,27
          c0,0,12.498,6.014,12,18c-0.662,15.954-16,21-16,21v3h2v3h-2v3h2v3h-2l6,204h512v-35c0,0-5.593-2.459-9-5
          c-0.945-0.705-6.091-0.363-8,0c-2.969,0.565-8.961,3.528-12-1c-3.87-5.767,3.521-9.102,2.5-12c-0.664-1.886-6.895-2.312-7.5-6
          c-0.944-5.75,1.433-7.821,4-8.75c2.938-1.062,4.564-3.043,6-4.25c2.03-1.707,0.5-6.25,6-9c5.087-2.544,7.651,2.158,10,2
          c2.847-0.192,5.188-5,10-5c6.667,0,6.515,4.066,9,5c3.12,1.173,7.43-2.11,13,1c4.812,2.688,5.329,6.7,7,9
          c1.398,1.926,5.688,1.287,8.25,5.5c2.812,4.625-0.905,8.374-1.625,11.438c-0.538,2.29,3.812,6.688,0.375,10.062
          c-9.463,9.29-16.758,2.181-22,4c-3.466,1.203-8,3-8,3v35h277"/>
          <path class="path" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-miterlimit="10" d="M19,223.711c0-13.528,12.132-10.846,15.562-14.743
          c2.778-3.156-0.977-8.806,9.01-13.925c7.918-4.059,9.674,1.023,13.105,0.819c4.455-0.264,3.276-6.718,14.744-6.553
          c12.664,0.182,11.245,8.414,14.744,10.648c3.021,1.929,7.917-2.126,13.924,4.096c3.822,3.958,1.291,7.436,2.458,9.829
          c2.163,4.438,14.744,0.938,14.744,13.924c0,12.175-13.481,8.295-17.201,10.648c-3.622,2.293-4.367,9.303-13.105,11.468
          c-7.989,1.978-9.472-3.97-13.924-4.096c-4.991-0.144-5.485,8.613-18.839,6.553c-12.285-1.893-12.263-9.115-16.382-12.286
          C32.874,236.273,19,238.591,19,223.711z"/>
          <path class="path" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-miterlimit="10" d="M336.081,143.938
          c-3.944,3.037-3.922,9.953-15.687,11.765c-12.787,1.973-13.26-6.413-18.04-6.274c-4.264,0.121-5.684,5.815-13.334,3.921
          c-8.367-2.072-9.081-8.785-12.549-10.98C272.91,140.117,260,143.832,260,132.174c0-12.436,12.047-9.084,14.118-13.334
          c1.117-2.292-1.307-5.621,2.353-9.412c5.752-5.958,10.441-2.075,13.333-3.921c3.351-2.14,1.992-10.022,14.118-10.197
          c10.981-0.158,9.853,6.022,14.118,6.275c3.286,0.195,4.967-4.671,12.55-0.784c9.563,4.902,5.968,10.312,8.627,13.333
          c3.286,3.732,14.902,1.164,14.902,14.119C354.121,142.5,340.835,140.28,336.081,143.938z"/>
        </svg>
      </div>
    </div>

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

    <div class="message">
      <div class="text">
        <h1>GradBuffet</h1>
        <p>Sign in first to submit your result</p>
      </div>
    </div>
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
