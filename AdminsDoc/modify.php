<?php
    require '../../vendor/autoload.php';

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
  <link rel = "stylesheet" href="../style/modify.css">

  <title>Admins Page</title>
</head>

<header> 
  <h1>We Are Providing A Testing Experience For Students</h1>

</header>

<body>
  <script src = "../index.js"></script>
  <!-- nav bar  -->
  <ul class ="navbar"> 
    <li class="h-bot"><a href="../AdminsDoc/Admin.php">Home</a></li>
    <li class="h-bot"><a href="#bot">About</a></li>
    <li class="h-bot"><a href="../Stats/statAdmin.php">Statistics</a></li>
    <li class ="log-out"><a href="../logout/logout.php">Log-Out</a></li>
  </ul>
  
  <!-- end of nav   -->
    <!-- click to top-->
    <button onclick="topFunction()" id="top" title="Go to top">To Top</button>
    

<div>
<form class="course" action="" method="POST">
    <p class="message">You may select the desired course, that you want to edit.Or you can even add a Course.</p>
    <select name="course_select" class="course_select">
        <?php
        include '../Connector/PDO_connect.php';
        $stmt = $conn->prepare("SELECT Course_Name,Course_ID FROM COURSES");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($result)) {
            echo "<option id=''>Select Course</option>";
            foreach ($result as $row) {
                echo "<option value='" . $row["Course_ID"] . "' data-course-name='" . $row["Course_Name"] . "'>" . $row["Course_Name"] . "</option>";
            }
        } else {
            echo "<option>No Courses Have Been Added</option>";
        }

        ?>
    </select>
    
    <input type="hidden" name="course_id" value="">
    <input type="hidden" name="course_name" value="">
    <input type='submit' class="submit_btn" name='action1' value='Edit Course Content'>
    <input type='submit' class="submit_btn" name='action2' value='Add Course'>
    <input type='submit' class="submit_btn" name='action3' value='Delete Course'>
    <input type='submit' class="submit_btn" name='action4' value='Edit Course Names'>
    <input type='submit' class="submit_btn" name='action5' value='Add Questions'>

  
</form>
</div>


<!-- JS FOR HIDDE THE IDS -->
<script>
document.querySelector('.course').addEventListener('submit', function(event) {
    let selectedOption = document.querySelector('.course_select option:checked');
    let course_id = selectedOption.value;
    let course_name = selectedOption.getAttribute('data-course-name');
    document.querySelector('input[name="course_id"]').value = course_id;
    document.querySelector('input[name="course_name"]').value = course_name;
});
</script>

<!-----------------------------ACTION 1 EDIT COURSES CODE----------------------------------->
    <?php
      include '../Connector/PDO_connect.php';

     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $id = $_POST["course_id"];
       $name = $_POST["course_name"];
       if (isset($_POST['action1'])) {
         $q = "SELECT * FROM Evaluate.COURSE_QUESTIONS WHERE Course_ID = :id";
         $stmt = $conn -> prepare($q);
         $stmt->bindParam(':id', $id);
         $stmt->execute();
         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
         if(!empty($result)){
         print("<div class='dispAnswers'>");
         $count=0;
         foreach($result as $row){
          $count +=1;
          print("<form method='post' class ='question' action='' >");
          print("<h2>
          <p class = 'message_list' id='$id'>For"."  Question  number:  ".$count. "</p></h2>". 
          "Question" .$row["Question_Text"].
          "<td><li class='ch_l'>Choice  A :".$row["Choice_A"]."</li>".
          "<li class='ch_l'>Choice  B :".$row["Choice_B"]."</li>".
          "<li class='ch_l'>Choice C :".$row["Choice_C"]."</li>".
          "<li class='ch_l'>Choice D :".$row["Choice_D"]."</li>".
          "<li class='ch_l'>Correct-Answer :".$row["Correct_Choice"]."</li>".
          "<li class='ch_l'>The question Dificulty is : ".$row["Question_Level"]."</li></td>");

          // values to pass
          print("<form method='post' class ='question' action=''>");

          print("<input type='hidden' name='question_id' value='".$row["Question_ID"]."'>");
          print("<input type='hidden' name='course_id' value='".$row["Course_ID"]."'>");
          print("<input type='submit' value='Edit' class='submit_btn_answers' name='Edit'>");
          print("<input type='submit' value='Delete' class='submit_btn_answers' name='Delete'>");
          print("</form>");


        }// for
        print("</div>");

        }else{
          print("<script>alert('Course has no question');</script>");
          print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");
        }  
        }
      }
       ?>


<!-- -------------------------POSTING FROM EDIT COURSE---------------------------------------------------------- -->
<!-- -------------------------POSTING FROM EDIT COURSE---------------------------------------------------------- -->
<?php
          include '../Connector/PDO_connect.php';
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $id = $_POST["course_id"];
          $idq = $_POST["question_id"];

          if (isset($_POST['Edit'])) {

             $q = "SELECT * FROM Evaluate.COURSE_QUESTIONS WHERE Course_ID = :id AND Question_ID =:idq";
             $stmt = $conn->prepare($q);
             $stmt->bindParam(':id', $id);
             $stmt->bindParam(':idq', $idq);
             $stmt->execute();
             $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
             foreach($result as $row){
              print("<form method='post' class ='question_f' action='' onsubmit='formSubmit()'>");
              print("<h2>
              <p class = 'message_list'>For"."  Question  number:  ".$row["Question_ID"]. "</p></h2>". 
              "<input type='hidden' name='question_id' value='".$row['Question_ID']."'>");
             print("<script>alert('".$row['Question_ID']."');</script>");
              print("<input type='hidden' name='course_id' value='".$row['Course_ID']."'>");

              print("</h2 class='disp'>Question");
              print("<input type='textbox' name='Question_Text' placeholder='Question Text' value='".$row['Question_Text']."'>");  
              print("</h2>");

              print("</h2 class='disp'>Choice A");
              print("<input type='textbox' name='Choice_A' placeholder='Choice A' value='".$row['Choice_A']."'>");
              print("</h2>");

              print("</h2 class='disp'>Choice B");
              print("<input type='textbox' name='Choice_B' placeholder='Choice B' value='".$row['Choice_B']."'>");
              print("</h2>");
              print("</h2 class='disp'>Choice C");
              print("<input type='textbox' name='Choice_C' placeholder='Choice C' value='".$row['Choice_C']."'>");
              print("</h2>");

              print("</h2 class='disp'>Choice D");
              print("<input type='textbox' name='Choice_D' placeholder='Choice D' value='".$row['Choice_D']."'>");
              print("</h2>");

              print("</h2 class='disp'>Correct Choice");
              print("<input type='textbox' name='Correct_Choice' placeholder='Correct Choice' value='".$row['Correct_Choice']."'>");
              print("</h2>");
             
              print("<h2 class='cre-dif'>Difficulty Level:");
              print("<input type='radio' name='Question_Level' value='".$row['Question_Level']."'>Easy");
              print("<input type='radio' name='Question_Level' value='".$row['Question_Level']."'>Medium");
              print("<input type='radio' name='Question_Level' value='".$row['Question_Level']."'>Hard");
              print("</h2>");
             
              print("<input type='submit' value='Save Changes' class='submit_btn_answers' name='submit'>");


             print("</form>");
             
            }
            // print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");
          }//if 
        }
        ?>
<script>
  function formSubmit(){
    // Insert form data into database
    location.href = "../AdminsDoc/modify.php";
  }
</script>
        <?php 
            include '../Connector/PDO_connect.php';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              $idq = $_POST["question_id"];
            if (isset($_POST['Delete'])) {
              $stmt = $conn->prepare("DELETE FROM COURSE_QUESTIONS WHERE Question_ID = :idq");
              $stmt->bindParam(':idq', $idq);
              $stmt->execute();   
               print("<script>alert('Question Deleted Succsefuly');</script>");     
               print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");

            }
          }
          ?>
<!-- -------------------------POSTING FROM EDIT OF THE QUESTION COURSE---------------------------------------------------------- -->
<!-- -------------------------POSTING FROM EDIT OF THE QUESTION COURSE---------------------------------------------------------- -->
    <?php
      include '../Connector/PDO_connect.php';
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $question_id = $_POST["question_id"];
        $course_id = $_POST["course_id"];
        $Choice_A = $_POST["Choice_A"];
        $Choice_B = $_POST["Choice_B"];
        $Choice_C = $_POST["Choice_C"];
        $Choice_D = $_POST["Choice_D"];
        $Correct_Choice = $_POST["Correct_Choice"];
        $Question_Level = $_POST["Question_Level"];
      if(isset($_POST['submit']) &&(!empty($_POST["question_id"]) && !empty($course_id)&& !empty($Choice_A)
      && !empty($Choice_B)&& !empty($Choice_C)&& !empty($Choice_D)&& !empty($Correct_Choice)&& 
      !empty($Question_Level))){
        $Choice_A = htmlspecialchars(trim($Choice_A), 'UTF-8');
        $Choice_B = htmlspecialchars(trim($Choice_B), 'UTF-8');
        $Choice_C = htmlspecialchars(trim($Choice_C), ENT_QUOTES, 'UTF-8');
        $Choice_D = htmlspecialchars(trim($Choice_D), 'UTF-8');
        $Correct_Choice = htmlspecialchars(trim($Correct_Choice), ENT_QUOTES, 'UTF-8');
        $Question_Level = htmlspecialchars(trim($Question_Level), ENT_QUOTES, 'UTF-8');
        
        $q = "UPDATE Evaluate.COURSE_QUESTIONS SET Choice_A = :Choice_A, Choice_B = :Choice_B, Choice_C = :Choice_C, Choice_D = :Choice_D, Correct_Choice = :Correct_Choice, Question_Level = :Question_Level WHERE Course_ID = :course_id AND Question_ID = :question_id";
        $stmt = $conn->prepare($q);
        $stmt->bindParam(':Choice_A', $Choice_A);
        $stmt->bindParam(':Choice_B', $Choice_B);
        $stmt->bindParam(':Choice_C', $Choice_C);
        $stmt->bindParam(':Choice_D', $Choice_D);
        $stmt->bindParam(':Correct_Choice', $Correct_Choice);
        $stmt->bindParam(':Question_Level', $Question_Level);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->execute();
        print("<script>alert('Question Updated Succsefuly');</script>");     
        print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");


        
        } else{
        if(isset($_POST['submit'])&&(empty($_POST["question_id"]) || empty($course_id) || empty($Choice_A)
        && empty($Choice_B) || empty($Choice_C) || empty($Choice_D) || empty($Correct_Choice) || 
        empty($Question_Level))){
          print("<script>alert('Question is not submited you must fill every box');</script>");     
          print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");


        }
      }

    } 
  ?>
<!-- -------------------------POSTING FROM EDIT COURSE---------------------------------------------------------- -->
<!-- -------------------------POSTING FROM EDIT COURSE---------------------------------------------------------- -->
<!-- -------------------------POSTING FROM EDIT COURSE---------------------------------------------------------- -->




       
<!-----------------------------ACTION 1 EDIT COURSES CODE----------------------------------->

<!-----------------------------ACTION 2 ADD COURSES CODE----------------------------------->
      <?php    
             include '../Connector/PDO_connect.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $id = $_POST["course_id"];
            if (isset($_POST['action2'])) {
              print(
              "<form class='course' action='' method='POST'>
              <p class='message'>You may select the desired course, that you want </p>
              <input type='textbox' name='desiredn' placeholder='Course Name' value=''>
              <input type='submit' value='Save Changes' class='submit_btn_answers' name='save'>
              <input type='submit' value='Back to Edit' class='submit_btn_answers' name='else'>
              </form>");
            }

        }
      ?>
      <?php
                  include '../Connector/PDO_connect.php';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              if(isset($_POST['save'])&& !empty($_POST["desiredn"])){
                $n = $_POST["desiredn"];
                $n = htmlspecialchars(trim($n));
                $q = "INSERT INTO COURSES (Course_Name) VALUES (:na)";
                $stmt = $conn->prepare($q);
                $stmt->bindParam(':na', $n);
                $stmt->execute();
               print("<script>alert('Course:".$n." Added.');</script>");     
               print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");


              }
              if(isset($_POST['save']) && empty($_POST["desiredn"])){
               print("<script>alert('You have to write the  Name try Again');</script>");  
               print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");


              }
            }
      
      ?>
<!------------------------------------------------------------------------------------------------------>

<!-----------------------------ACTION 3 DELETE COURSES CODE--------------------------------------------->
<?php    
             include '../Connector/PDO_connect.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $id = $_POST["course_id"];
          $name = $_POST["course_name"];
          if(($name !=='Select Course') && (!empty($name))){
            if (isset($_POST['action3'])) {
              $id = $_POST['course_id'];
              $stmt = $conn->prepare("DELETE FROM COURSE_QUESTIONS WHERE Course_ID = :id");
              $stmt->bindParam(':id', $id);
              $stmt->execute();
              $stmt2 = $conn->prepare("DELETE FROM COURSES WHERE Course_ID = :id");
              $stmt2->bindParam(':id', $id);
              $stmt2->execute();
              print("<script>alert('Course ".$name." Has been deleted');</script>");
              print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");


            }
            }
            if((($name === 'Select Course') || (empty($name)))&&isset($_POST['action3'])){
                print("<script>alert('Invalid course try again.');</script>");
          print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");
          print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");


              }
          }//empty 

    ?>
<!-----------------------------ACTION 3 DELETE COURSES CODE-------------------------------------------->


<!-----------------------------ACTION 4 EDIT COURSES NAME CODE-------------------------------------------->
    
<?php    
             include '../Connector/PDO_connect.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $id = $_POST["course_id"];
          $name = $_POST["course_name"];
            if (isset($_POST['action4'])) {
              print(
              "<form class='course' action='' method='POST'>
              <h2 class ='disp'>
              <p class='message'>You can rename the course of : ".$name."</p>
              <input type='textbox' name='desiredn' placeholder='".$name."' value=''>
              </h2>
              <input type='hidden' name='course_id' ' value='".$id."'>
              <input type='hidden' name='old_name' ' value='".$name."'>
              <input type='submit' value='Save Changes' class='submit_btn_answers' name='saveit'>
              <input type='submit' value='Back to Edit' class='submit_btn_answers' name='else'>
              </form>");
            }

        }
      ?>
      <?php
            include '../Connector/PDO_connect.php';
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         $old=$_POST["old_name"];
          $n = $_POST["desiredn"];            
         $id = $_POST["course_id"];
      if(isset($_POST['saveit']) && !empty($_POST["desiredn"])){
            $old = htmlspecialchars(trim($old));
            $n = htmlspecialchars(trim($n));
            print "<script>alert('".$n.$old.$id."');</script>";
            $q = "UPDATE COURSES SET  Course_Name=:na WHERE Course_ID =:id";
            $stmt = $conn->prepare($q);
            $stmt->bindParam(':na', $n);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            if ($stmt->execute()) {
              print( "<script> alert('Name Changed to : " .$n. " from  ".$old."'); </script>");
            }else if(isset($_POST['saveit']) && empty($_POST["desiredn"])){
                print "<script>alert('Error updating name');</script>";
                print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");


              }
              if(isset($_POST['else'])){
                print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");


              }
            }
      }


?>

<!-----------------------------ACTION 4 EDIT COURSES NAME CODE-------------------------------------------->


<!-----------------------------ACTION 5 ADD QUESTION COURSES CODE-------------------------------------------->


  <?php
                include '../Connector/PDO_connect.php';
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST["course_id"];
            $name = $_POST["course_name"];
          if (isset($_POST['action5']) && !empty($name)) {
              print("<form method='POST' class ='question_f' action=''>");
              print("<h2>
              <p class = 'message_list'>You are about to add a question  for  course: ".$name. "</p></h2>");
              print("<input type='hidden' name='course_id' value='".$id."'>");

              print("<h2 class ='disp'>Question");
              print("<input type='textbox' name='Question_Text' placeholder='Question Text' value=''>");
              print("</h2>");

              print("<h2 class ='disp'>Choice A");
              print("<input type='textbox' name='Choice_A' placeholder='Choice A' value=''>");
              print("</h2>");

              print("<h2 class ='disp'>Choice B");
              print("<input type='textbox' name='Choice_B' placeholder='Choice B' value=''>");
              print("</h2>");

              print("<h2 class ='disp'>Choice C");
              print("<input type='textbox' name='Choice_C' placeholder='Choice C' value=''>");
              print("</h2>");

              print("<h2 class ='disp'>Choice D");
              print("<input type='textbox' name='Choice_D' placeholder='Choice D' value=''>");
              print("</h2>");

              print("<h2 class ='disp'>Correct Choice");
              print("<input type='textbox' name='Correct_Choice' placeholder='Correct Choice' value=''>");
              print("</h2>");

              print("<h2 class='cre-dif'>Difficulty Level:");
              print("<input type='radio' class='rad' name='Question_Level' value='Easy' >Easy");
              print("<input type='radio' class='rad' name='Question_Level' value='Medium' checked>Medium");
              print("<input type='radio' class='rad'name='Question_Level' value='Hard' >Hard");
              print("</h2>");
              print("<input type='submit' value='Save Question' class='submit_btn_answers' name='addquest'>");
              print("<input type='submit' value='Back' class='submit_btn_answers' name='Go Back'>");
             print("</form>");
           }else{
            if (isset($_POST['action5']) && empty($name)){
            print("<script>alert('You must choose a  from the dropdown menu.'); 
            window.location.href='../AdminsDoc/modify.php'; </script>)");
           }
          }
          }
        ?>

  
    <?php
            include '../Connector/PDO_connect.php';
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(!empty($course_id)&&isset($_POST['addquest'])){
        $course_id = $_POST["course_id"];
        $Choice_A = $_POST["Choice_A"];
        $Choice_B = $_POST["Choice_B"];
        $Choice_C = $_POST["Choice_C"];
        $Choice_D = $_POST["Choice_D"];
        $Question_Text = $_POST["Question_Text"];
        $Correct_Choice = $_POST["Correct_Choice"];
        $Question_Level = $_POST["Question_Level"];
      if(!empty($course_id)&&!empty($Choice_A) && !empty($Choice_B) && !empty($Choice_C) && !empty($Choice_D) 
      && !empty($Question_Level) && !empty($Question_Text) && !empty($Correct_Choice)){
      if(isset($_POST['addquest'])){
        $Choice_A = htmlspecialchars(trim($Choice_A));
        $Choice_B = htmlspecialchars(trim($Choice_B));
        $Choice_C = htmlspecialchars(trim($Choice_C));
        $Choice_D = htmlspecialchars(trim($Choice_D));
        $Correct_Choice = htmlspecialchars(trim($Correct_Choice));
        $Question_Level = htmlspecialchars(trim($Question_Level) );
        $Question_Text = htmlspecialchars(trim($Question_Text));
        $q = "INSERT INTO Evaluate.COURSE_QUESTIONS  (Course_ID,Question_Level,Question_Text,Choice_A,Choice_B,Choice_C,Choice_D,Correct_Choice)
        VALUES (:course_id,:Question_Level,:Question_Text,:Choice_A,:Choice_B,:Choice_C,:Choice_D,:Correct_Choice)";
        $stmt = $conn->prepare($q);
        $stmt->bindParam(':Question_Text',$Question_Text);
        $stmt->bindParam(':Choice_A', $Choice_A);
        $stmt->bindParam(':Choice_B', $Choice_B);
        $stmt->bindParam(':Choice_C', $Choice_C);
        $stmt->bindParam(':Choice_D', $Choice_D);
        $stmt->bindParam(':Correct_Choice', $Correct_Choice);
        $stmt->bindParam(':Question_Level', $Question_Level);
        $stmt->bindParam(':course_id', $course_id);
        print_r($stmt);
        $stmt->execute();
        print("<script>alert('Question has been added.');</script>");     
        print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");


      }
    }else{
      if(isset($_POST['addquest'])){
        print("<script>alert('You cant have empty fields.');</script>");     
        print ("<script>window.location.href='../AdminsDoc/modify.php'; </script>");


      }

    }
    }
    } 
  ?>
<!-----------------------------ACTION 5 ADD QUESTION COURSES CODE-------------------------------------------->



</main>

  <footer> 
      <a name = "bot"></a>
      <li class="h-bot"><a href = "#"><p>Policy</p></a></li>
      <li class="h-bot"><a href="#"><p>About</p></a></li> 
      <li class="h-bot"><a href = "#"><p>Terms and Condition</p></a></li>
</footer>
  </body>
</html>

