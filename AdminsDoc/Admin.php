<?php
   session_start();
   if (!isset($_SESSION['logged_in']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'teacher'){
   echo("<script>alert('Unauthorized Access');</script>");
   print ("<script>window.location.href='http://localhost/sphy140/PROJECT%20PHP/main/Login/home.php'; </script>");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel = "stylesheet" href="../style/logedin.css">
</head>

<header> 
  <h1>We Are Providing A Testing Experience For Students</h1>
</header>

<body>

  <script src = "../index.js"></script>

  <!-- nav bar  -->
  <ul class ="navbar"> 
    <li ><a href="../AdminsDoc/Admin.php">Home</a></li>
    <li ><a href="../Admin/modify.php">Modify</a></li>
    <li class="h-bot"><a href="../Stats/statAdmin.php">Statistics</a></li>
    <li ><a href="#bot">About</a></li>
    <li class ="log-out">
  <a href="../logout/logout.php">Log Out</a>
</li>
  </ul>
  
  <!-- end of nav   -->
    <div  class="answer">
      <p>We are welcoming you in the Teachers Page where you can Add Create Or Even Delete your Questions.</p>
      <form action="modify.php" method="post">
       <input class="submit_btn"type="submit" value="Modify">
      </form>
      <form action="../Stats/statAdmin.php" method="post">
       <input class="submit_btn"type="submit" value="Statistics">
      </form>
    </div>
    
    
    <!-- <input class= "submit_class_reg" type="button" value="Go back!" onclick="./modify.php"> -->
    
    
    
    <!-- click to top-->
    <button onclick="topFunction()" id="top" title="Go to top">To Top</button>
    
    
    
    
    <footer> 
      <a name = "bot"></a>
      <li ><a href = "policies.asp">Policy</a></li>
      <li ><a href="about.asp">About</a></li> 
      <li ><a href = "policies.asp">Terms and Condition</a></li>
    </footer>
  </main>
  </body>
    
</html>