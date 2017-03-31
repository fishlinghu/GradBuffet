<?php
  session_start();
  include "dbinfo.inc";
  include "checkTable.php";
  error_reporting(E_ALL);
  ini_set('display_errors', 'On');
?>

<html lang="en">
<?php
  session_unset();
  echo "<script type=\"text/javascript\">
          alert(\"Successfully log out!\")
          location = \"index.php\"
        </script>";
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
        <h1>Logging out......</h1>
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