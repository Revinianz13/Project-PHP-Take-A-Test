<?php
// Start the session
require '../../vendor/autoload.php';

session_start();

include '../Connector/PDO_connect.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = htmlspecialchars(trim($_POST['user']));
  $password = htmlspecialchars(trim($_POST['pass']));

  $q = "SELECT type, pass,id_user FROM Evaluate.USERS WHERE user_name = :username";
  $stmt = $conn->prepare($q);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if ($stmt->rowCount() > 0) {
    $user = $result[0]['id_user'];
  
    if(password_verify($password, $result[0]['pass'])){
      $role = $result[0]['type'];
      if ($role === 'teacher') {
          $_SESSION['logged_in'] = true;
          $_SESSION['username'] = $username;
          $_SESSION['role'] = 'teacher';
          $_SESSION['user']=$user;
          print "<script>window.location.href='http://localhost/sphy140/PROJECT%20PHP/main/AdminsDoc/Admin.php'; </script>";
         
          exit;
      } else if ($role === 'student') {
          $_SESSION['logged_in'] = true;
          $_SESSION['username'] = $username;
          $_SESSION['role'] = 'student';
          $_SESSION['user'] = $user;
          
          print "<script>window.location.href='http://localhost/sphy140/PROJECT%20PHP/main/StudentDoc/student.php'; </script>";
          exit;
      }
    }else {
      print("<script>alert('Wrong Credentials.');</script>");
      print "<script>window.location.href='http://localhost/sphy140/PROJECT%20PHP/main/Login/home.php'; </script>";
      exit;
  }
  }
  } 

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Main</title>
  <link rel = "stylesheet" href="../style/home.css">

</head>
<body>
  <header>
      <h1>Skill Testing.</h1>
  </header>
  <main>
  <div class="container">

<div class="form_class" >
  <form  action=" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
    <label>Login:</label>
    <input class="field_class" name="user" type="text" placeholder="User Name" value="<?php if(isset($_POST["user"])){echo($name);}?>">
    <label>Password:</label>
    <input id="pass" class="field_class" name="pass" type="password" placeholder="Password" value="<?php if(isset($_POST["pass"])){echo($name);}?>">
    <input type="submit" class="submit_class" name="login" value="Log In" class="btn">
    
  </form>
</div>

  <div class = "form_class_reg">
      <div class="cont">Not Registered Yet? You can use our services by click the button.
        <button class="submit_class" id="sub"> Register!</button>
      </div>
      <script >
        const btn = document.getElementById("sub"); 
        btn.addEventListener("click", function() {
        location.href = "http://localhost/sphy140/PROJECT%20PHP/main/Login/register.php";
      });

      </script>

  </form>
</div>

</div>

</main>

<footer>
    <p><a href>About me</a>  <a href="#"> @Revinianz</a></p>
</footer>


</body> 

</html>



