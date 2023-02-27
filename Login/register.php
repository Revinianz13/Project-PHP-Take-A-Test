<!DOCTYPE html>
<html>
<head>
<title>Φόρμα εγγραφής</title>
<meta charset="utf8">
<link rel = "stylesheet" href="../style/home.css">
</head>
<body>


<header><h1>Registration Form</h1></header>
<main>

    <form action="" method="POST" class="form_class">
        <p>First Name: <input class="field_class" type="text" name="fname" value="<?php echo ($_POST['fname']); ?>" required></p>
        <p>Last Name: <input class="field_class" type="text" name="lname" value="<?php echo ($_POST['lname']) ?>" required></p>
        <p>Email: <input class="field_class" type="text" name="email" value="<?php echo ($_POST['email']) ?>" required></p>
        <p>User Name: <input class="field_class" type="text" name="usrname" value="<?php echo ($_POST['usrname']) ?>" required></p>
        <p>Password: <input class="field_class" type="password" name="psw" required></p>
        <p>Comfirm Password: <input class="field_class" type="password" name="pswconf" required></p>
      
        <input  class= "submit_class"  type="submit" value="Register" name="actionreg">
        <button class="submit_class" id="sub"> Go Back</button>
      </form>

      </div>
      <script >
        const btn = document.getElementById("sub"); 
        btn.addEventListener("click", function() {
        location.href = "http://localhost/sphy140/PROJECT%20PHP/main/Login/home.php";
      });

      </script>

<?php
include '../Connector/PDO_connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  //Sanitize the input data 
  $fname = htmlspecialchars(trim($_POST['fname']));
  $lname = htmlspecialchars(trim($_POST['lname']));
  $email = htmlspecialchars(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $username = htmlspecialchars(trim($_POST['usrname']));
  $psw = htmlspecialchars(trim($_POST['psw']));
  $pswconf =htmlspecialchars(trim($_POST['pswconf']));
  $t = "student";
  // Check for any injection attacks
  if($pswconf != $psw){
    print( "<script>alert('Error confirm the psw');</script>");
  }else if ($pswconf == $psw){
  $psw = password_hash($psw, PASSWORD_DEFAULT);
  $stmt = $conn->prepare("INSERT INTO USERS (email,first_name,last_name,user_name,pass,type) 
  VALUES (:email,:firstname,:lastname,:username,:pass,:t)");
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':firstname', $fname);
  $stmt->bindParam(':lastname', $lname);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':pass', $psw);
  $stmt->bindParam(':t', $t);
  $stmt->execute();
  print( "<script>alert('New user created successfully');</script>");
  }else{
  print( "<script>alert('Unexpcectited Error Try Again');</script>");
  }
}

?>
</main>




<footer>
    <p>About me<a href="#">  Revinianz</a></p>
</footer>
</body>
</html>