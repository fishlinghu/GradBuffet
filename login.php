<?php
  session_start();
  include "dbinfo.inc";
  include "checkTable.php";
  error_reporting(E_ALL);
  ini_set('display_errors', 'On');
?>

<html lang="en">
<?php
  if(isset($_SESSION['applicantID']) && $_SESSION['applicantID'] != null)
    {
    // already logged in, this page is accessed by accident
    echo "<script type=\"text/javascript\">location = \"index.php\"</script>";
    }
  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the User table exists. */
  VerifyTable($connection, DB_DATABASE);

  $account = (isset($_POST['account']) ? $_POST['account'] : null);
  $pwd = (isset($_POST['pwd']) ? $_POST['pwd'] : null);
  $_POST = array();
  if($account != null)
    { 
    // check if username and pwd are correct
    $sql = "SELECT ID, account, pwd FROM Applicant 
            WHERE account = '$account' AND pwd = '$pwd'";
    $sqlReturn = mysqli_query($connection, $sql) or die("Error " . mysqli_error($connection));
    $sqlReturn = mysqli_fetch_array( $sqlReturn );
  
    if($sqlReturn != null)
      { 
      // user has logged in with correct account-pwd pair
      $applicantID = $sqlReturn['ID'];
      $_SESSION['applicantID'] = $applicantID;
      echo "<script type=\"text/javascript\">
              alert(\"Successfully log in!\")
              location = \"index.php\"
            </script>";
      }
    else
      { 
      echo "<script type=\"text/javascript\">
              alert(\"Wrong account or password!\")
            </script>";
      }
    }
  else
    {
    // no account data
    }
?>
<head>
  <meta charset="utf-8">
  <title>GradBuffet</title>
  <meta name="description" content=""/>
  <link href="css/styles.css" rel="stylesheet">
  <link href="css/gradients.css" rel="stylesheet">
</head>
<body>
  <div class="wrapper">
    <section class="page-content">
      <article>
        <h1>Please log in here</h1>
        <form action="login.php" method="post">
            Account: <input type="text" name="account"><br>
            Password: <input type="password" name="pwd"><br>
            <input type="submit" value="Log In">
        </form>
        <p>Do not have an account? Create your account <a href="signup.php">"HERE"</a></p>
      </article>
    </section>
    <div class="push"></div>
  </div>

  <footer>
    <p class="footer-contents">Designed for GaTech CS4440</p>
  </footer>

  <script src="js/set-background.js"></script>
</body>
</html>
