<?php
    require '../../vendor/autoload.php';

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
  <link rel = "stylesheet" href="../style/stats.css">

  <title>Admins Page</title>
</head>






  <body>
  <header> 
  <h1>We Are Providing A Testing Experience For Students</h1>

</header>
<ul class ="navbar"> 
    <li class="h-bot"><a href="../AdminsDoc/Admin.php">Home</a></li>
    <li class="h-bot"><a href="#bot">About</a></li>
    <li class="h-bot"><a href="../Stats/statAdmin.php">Statistics</a></li>
    <li class ="log-out"><a href="../logout/logout.php">Log-Out</a></li>
  </ul>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

  <script src = "../index.js"></script>
  <!-- nav bar  -->

  
  <!-- end of nav   -->
   <!-- click to top-->
   <button onclick="topFunction()" id="top" title="Go to top">To Top</button>
   <!-- click to top-->

  <main>

<form class="course" action="" method="POST">
<select name="course_select" class="course_select">
        <?php
    require '../../vendor/autoload.php';

        include '../Connector/PDO_connect.php';
        $stmt = $conn->prepare("SELECT Course_Name,Course_ID FROM COURSES");
        $stmt->execute();
        $resultcourse = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION['course'] = $resultcourse['Course_Name'];
        if (!empty($resultcourse)) {
            echo "<option id=''hidden>Select Course</option>";
            foreach ($resultcourse as $row) {
              
                echo "<option value='" . $row["Course_ID"] . "' data-course-name='" . $row["Course_Name"] . "'>" . $row["Course_Name"] . "</option>";
            }
        } else {
            echo "<option>No Courses Have Been Added</option>";
        }
        
        ?>
    </select>
    <input type='submit' class="submit_btn" name='action1' value='See Results'>
    <input type="hidden" name="course_id" value="">
    <input type="hidden" name="course_name" value="">
  </form>
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

<?php
session_start();
      include '../Connector/PDO_connect.php';
      if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action1'])) {
          $name = $_POST["course_name"];
          $_SESSION['course_name'] = $name;
          $Course_ID= $_POST['course_select'];
          $id_user= $_SESSION['user'];
          $stmt = $conn->prepare("SELECT test_result FROM TEST_ANSWERS WHERE 
          id_user = :id_user AND Course_ID = :Course_ID");
          $stmt -> bindParam(':Course_ID',$Course_ID);
          $stmt -> bindParam(':id_user',$id_user);
          $stmt->execute();
          $testans = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $_SESSION['test_result'] = $testans;
          $_SESSION['MoreSix'] =0;
          $_SESSION['LessSix'] =0;
          foreach($_SESSION['test_result'] as $res){
            if($res['test_result']>60){
              $_SESSION['MoreSix'] = $_SESSION['MoreSix'] +1;
  
            }else{
              $_SESSION['LessSix'] = $_SESSION['LessSix'] +1;
          }
        }
        }
          }
        ?>
<?php 
// session_start();
// include '../Connector/PDO_connect.php';
// if($_SERVER['REQUEST_METHOD'] === 'POST') {
//   if (isset($_POST['action1'])) {
//     $name = $_POST["course_name"];
//     $_SESSION['course_name'] = $name;
//     $Course_ID= $_POST['course_select'];
//     $id_user= $_POST['user'];
//     $stmt = $conn->prepare("SELECT * FROM QUESTION_STATS WHERE 
//     Course_ID = :Course_ID");
//     $stmt -> bindParam(':Course_ID',$Course_ID);
//     $stmt->execute();
//     $testans = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     $_SESSION['quest_stat'] = $testans;
//     dump($_SESSION['MoreSix']);
//   }
//     }
  ?>


<input type="hidden" id="LessSix" value="<?php echo $_SESSION['LessSix']; ?>">
<input type="hidden" id="MoreSix" value="<?php echo $_SESSION['MoreSix']; ?>">


<div class= "dispAnswersform">
  <h1>For Course of <?php echo $_SESSION['course_name'];?></h1>
  <canvas class='dispdonut' id="doughnutChart">Test of user</canvas>
</div>
<script>
var more = document.getElementById("MoreSix").value;
var less = document.getElementById("LessSix").value;

var ctx = document.getElementById("doughnutChart").getContext("2d");
var doughnutChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ["More than 60%", "Less than 60%"],
        datasets: [{
            label: '# of Tests',
            data: [more,less],
            backgroundColor: [
                'rgba(0, 128, 0, 0.5)',
                'rgba(255, 0, 0, 0.5)'
            ],
            borderColor: [
                'rgba(0, 128, 0, 1)',
                'rgba(255, 0, 0, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        legend: {
            position: 'top',
        },
        title: {
            dispdonutlay: true,
            text: 'Test Results 1'
        },
        animation: {
            animateScale: true,
            animateRotate: true
        }
    }
});

doughnutChart.render();
</script>




</main>


<footer> 
  <a name = "bot"></a>
  <li class="h-bot"><a href = "#"><p>Policy</p></a></li>
  <li class="h-bot"><a href="#"><p>About</p></a></li> 
  <li class="h-bot"><a href = "#"><p>Terms and Condition</p></a></li>
</footer>
</body>
</html>