<?php
/* Check whether the table exists and, if not, create it. */
function VerifyTable($connection, $dbName) 
  {
  if(!TableExists("Applicant", $connection, $dbName))
  	{
  	$query = "CREATE TABLE `Applicant` (
          `ID` int(11) NOT NULL AUTO_INCREMENT,
          `account` CHAR(32) NOT NULL,
          `pwd` CHAR(40) DEFAULT NULL,
          `gpa` FLOAT(3,2) DEFAULT NULL,
          `toefl` TINYINT(3) DEFAULT NULL,
          `greV` SMALLINT(3) DEFAULT NULL,
          `greQ` SMALLINT(3) DEFAULT NULL,
          `greAWA` FLOAT(2,1) DEFAULT NULL,
          `gmat` SMALLINT(3) DEFAULT NULL,
          `foreign_student` TINYINT(1) DEFAULT 0,
          `num_pub` TINYINT(3) DEFAULT NULL,
          PRIMARY KEY (`ID`),
          UNIQUE KEY `ID_UNIQUE` (`ID`)
       ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

    if(!mysqli_query($connection, $query)) echo("Error creating table.");
  	}

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

    if(!mysqli_query($connection, $query)) echo("Error creating application table.");
    }

  if(!TableExists("School", $connection, $dbName))
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

  if(!TableExists("Program", $connection, $dbName))
    {
    $query = "CREATE TABLE `Program` (
          `ID` int(11) NOT NULL AUTO_INCREMENT,
          `degree` CHAR(64) NOT NULL,
          `major` CHAR(64) NOT NULL,
          `avgGPA` FLOAT(4,3) DEFAULT 0,
          `avgTOEFL` FLOAT(4,1) DEFAULT 0,
          `avgGREV` FLOAT(4,1) DEFAULT 0,
          `avgGREQ` FLOAT(4,1) DEFAULT 0,
          `avgGREAWA` FLOAT(3,2) DEFAULT 0,
          `avgGMAT` FLOAT(4,1) DEFAULT 0,
          `foreign_count` INT DEFAULT 0,
          `ad_count` INT DEFAULT 0,
          `total_count` INT DEFAULT 0,
          `school_ID` int(11) DEFAULT NULL,
          PRIMARY KEY (`ID`),
          UNIQUE KEY `ID_UNIQUE` (`ID`)
       ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

    if(!mysqli_query($connection, $query)) echo("Error creating program table.");
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