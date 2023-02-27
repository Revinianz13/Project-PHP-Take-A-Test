<?php
   session_start();
   if (!isset($_SESSION['logged_in']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'student'){
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
    
    <!-- click to top-->
    <button onclick="topFunction()" id="top" title="Go to top">To Top</button>
    
  <!-- nav bar  -->
  <ul class ="navbar"> 
    <li class="h-bot"><a href="<?php header("Refresh");?>">Home</a></li>
    <li class="h-bot"><a href="#bot">About</a></li>
    <li class ="log-out"><a href="../logout/logout.php">Log-Out</a></li>
    <li class="h-bot"><a href="../Stats/statStudent.php">Statistics</a></li>

  </ul>
  <!-- end of nav   -->
  <div>
  <form class="course"  method= "POST" action="">  
      <p class="message">We are welcoming you in the Students Page where you can take a Test. You can also track your progress regarding your knoledge.</p>
      <select name="course_select" class="course_select">
      <?php
        include '../Connector/PDO_connect.php';
        $stmt = $conn->prepare("SELECT Course_Name,Course_ID FROM COURSES");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            echo "<option id='' hidden >Select Course</option>";
            foreach ($result as $row) {

                echo "<option value='" . $row["Course_ID"] . "'  name='" . $row["Course_Name"] . "'>" . $row["Course_Name"] . "</option>";
            }
          } else {
            echo "<option>No Courses Have Been Added</option>";
          }
      ?>
    </select>
    <input type='submit' class="submit_btn" name='atest' value='Take a Test'>
    </div>
  </form>
  
  </div>

  <?php
      include '../Connector/PDO_connect.php';
      require '../../vendor/autoload.php';
      session_start();
      if(isset($_POST['atest'])){
          $Course_ID =$_POST['course_select'];
          $stmt = $conn->prepare("SELECT * FROM COURSE_QUESTIONS 
          WHERE Course_ID =:Course_ID and Question_Level= 'easy' 
          ORDER BY RAND() LIMIT 4");
          $stmt -> bindParam(':Course_ID',$Course_ID);
          $stmt->execute();
          $questionsez = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $stmt = $conn->prepare("SELECT * FROM COURSE_QUESTIONS 
          WHERE Course_ID =:Course_ID and Question_Level= 'medium' 
          ORDER BY RAND() LIMIT 4");
          $stmt -> bindParam(':Course_ID',$Course_ID);
          $stmt->execute();
          $questionsm = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $stmt = $conn->prepare("SELECT * FROM COURSE_QUESTIONS 
          WHERE Course_ID =:Course_ID and Question_Level= 'hard' 
          ORDER BY RAND() LIMIT 4");
          $stmt -> bindParam(':Course_ID',$Course_ID);
          $stmt->execute();
          $questionsh = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $questions= array_merge($questionsez,$questionsm,$questionsh);
          $_SESSION['count'] = count ($questions); 
          $_SESSION['questions']=$questions;

          shuffle($_SESSION['questions']);
          print("<form   id='fongo' class= 'dispAnswersform'  action='' method='POST' >");
          if(count($_SESSION['questions'])>=12){
            $count =1;
            foreach($_SESSION['questions']as $question){
              $answers = array($question['Choice_A'], $question['Choice_B'], $question['Choice_C'],$question['Choice_D']);
              shuffle($answers);
            print("<div class='question_f'>");
              print("<h2>
                  <p class = 'message_list'>"."  Question  number:  ".$count. "</p></h2>". 
                  "<h2>Question :   ".$question['Question_Text']."</h2>");
              foreach($answers as $answer){
                  print("<li class='ch_l'>
                  <input type='radio' name='answer[{$question['Question_ID']}]' value='$answer'>".$answer."
                  </li>");
              }
              $count++;
              print("</div>");
              }//loops
              }else{//else <11
                if(isset($_POST['atest']) && count($questions)<11){
                print("<script>alert('Not enough questions for the course!');</script>");     
                print "<script> window.location.href='../StudentDoc/student.php'; </script>";
              }
            }//if
            print("<input type='submit' value='Submit Test' action='' method='POST'  class='ffaa'  name='Submit'>");
            print("</form>");

         }
  ?>
 <!---------------------------Course Selection --------------------------------------------->

<!-----------------------------ACTION 1 Display TEST  CODE----------------------------------->
  <?php

    session_start();
    include '../Connector/PDO_connect.php';
    include '../../vendor/autoload.php';  
    if(isset($_POST['Submit'])){
    foreach ($_SESSION['questions'] as $question) {
      if (strcmp($question['Correct_Choice'], $_POST['answer'][$question['Question_ID']]) === 0) {
        $correct_attempts = 1;
        $total_attempts = 1;
        $score++;
        $stmt = $conn->prepare("INSERT INTO QUESTION_STATS (Question_ID, Course_ID, Question_Level, total_attempts, correct_attempts) VALUES (:question_id, :course_id, :question_level, :total_attempts, :correct_attempts) ON DUPLICATE KEY UPDATE total_attempts = total_attempts + 1, correct_attempts = correct_attempts + :correct_attempts");
        $stmt->bindParam(':question_id', $question['Question_ID']);
        $stmt->bindParam(':course_id', $question['Course_ID']);
        $stmt->bindParam(':question_level', $question['Question_Level']);
        $stmt->bindParam(':correct_attempts', $correct_attempts);
        $stmt->bindParam(':total_attempts', $total_attempts);
        $stmt->execute();
        }else{
        $total_attempts = 1;
        $correct_attempts = 0;
        $stmt = $conn->prepare("INSERT INTO QUESTION_STATS (Question_ID, Course_ID, Question_Level, total_attempts, correct_attempts)
         VALUES (:question_id, :course_id, :question_level, :total_attempts, :correct_attempts) ON 
         DUPLICATE KEY UPDATE total_attempts = total_attempts + 1, correct_attempts = correct_attempts + :correct_attempts");
        $stmt->bindParam(':question_id', $question['Question_ID']);
        $stmt->bindParam(':course_id', $question['Course_ID']);
        $stmt->bindParam(':question_level', $question['Question_Level']);
        $stmt->bindParam(':correct_attempts', $correct_attempts);
        $stmt->bindParam(':total_attempts', $total_attempts);
        $stmt->execute();
      }// after chec
    } //for
        $date = date('Y-m-d');
        $percentage = round(($score * 100) / ($_SESSION['count']));
        $user = $_SESSION['user'];
        print("<script>alert('You have scored scored ".$percentage."%');</script>");
        $stmt = $conn->prepare("INSERT INTO TEST_ANSWERS (id_user, Course_ID, test_result, test_date)               
        VALUES (:id_user, :course_id, :test_result, :test_date)");
        $stmt->bindParam(':test_result', $percentage);
        $stmt->bindParam(':id_user',$user);
        $stmt->bindParam(':course_id', $question['Course_ID']);
        $stmt->bindParam(':test_date',$date);
         if($stmt->execute()){
          print("<script> alert('The Quiz has been submited!'); </script>");    
          print ("<script>window.location.href='../StudentDoc/student.php'; </script>");
         }
    }//set
?>
  <footer> 
  <a name = "bot"></a>
  <li class="h-bot"><a href = "#"><p>Policy</p></a></li>
  <li class="h-bot"><a href="#"><p>About</p></a></li> 
  <li class="h-bot"><a href = "#"><p>Terms and Condition</p></a></li>
  </footer>
</body>
</html>
