<?php
session_start();
if(!isset($_SESSION["user_id"]))
  header("Location:../index.php");
?>
<?php
  include '../../database/config.php';

  require_once('../assets/vendor/excel_reader2.php');
  require_once('../assets/vendor/SpreadsheetReader.php');

  if(isset($_POST['general_settings_update'])) {
    $test_id = $_POST['test_id'];
    $test_name = $_POST['test_name'];
    $test_subject = $_POST['subject_name'];
    $test_date = $_POST['test_date'];
    $total_questions = $_POST['total_questions'];
    $test_status = $_POST['test_status'];
    $test_class = $_POST['test_class'];
    $status_id = $class_id = -1;
    $general_settings = false;

    //getting status id
    $status_sql = "SELECT id from status where name LIKE '%$test_status%'";
    $status = mysqli_query($conn,$status_sql);
    if(mysqli_num_rows($status) > 0) {
      $status_row = mysqli_fetch_assoc($status);
      $status_id = $status_row["id"];
    }
    //getting class id
    // $class_sql = "SELECT id from classes where name LIKE '%$test_class%'";
    // $class_result = mysqli_query($conn,$class_sql);
    // if(mysqli_num_rows($class_result) > 0) {
    //   $class_row = mysqli_fetch_assoc($class_result);
    //   $class_id = $class_row["id"];
    // }
    $sql = "UPDATE tests SET name = '$test_name', date = '$test_date', status_id = '$status_id', subject = '$test_subject', total_questions = '$total_questions' WHERE id = '$test_id'";
    $result = mysqli_query($conn,$sql);
    if($result) {
      $general_settings = true;
    }
  }

  function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  if(isset($_POST['other_settings'])) {
    $test_id = $_POST['test_id'];
    $student_roll_no = $_POST['student_roll_no'];
    $other_settings = false;

    $temp = 8 - strlen($test_id);
    $random = generateRandomString($temp);
    $random = $random . $test_id;

    $sql = "INSERT INTO student_data(rollno,class_id) values ($student_roll_no,null)";
    $result = mysqli_query($conn,$sql);
    $roll_no_id = mysqli_insert_id($conn);
    if($result) {
      $other_settings = true;
      $sql1 = "INSERT INTO students (test_id,rollno,password,score,status) values('$test_id','$roll_no_id','$random',0,0)";
      $result1 = mysqli_query($conn, $sql1);
      if($result1) {
        $other_settings = true;
      }
      else {
        $other_settings = false;
      }
    }
  }


  if(isset($_POST['test_id'])) {
    $test_id = $_POST['test_id'];
    $sql = "SELECT * from tests where id = $test_id";
    $result = mysqli_query($conn,$sql);
    $test_details = mysqli_fetch_assoc($result);
    $status_id = $test_details["status_id"];
    $class_id = $test_details["class_id"];
    
    $sql1 = "SELECT name from status where id = $status_id";
    $result1 = mysqli_query($conn,$sql1);
    $gen = mysqli_fetch_assoc($result1);
    $status = $gen["name"];

    $sql2 = "SELECT name from classes where id = $class_id";
    $result2 = mysqli_query($conn,$sql2);
    $gen1 = mysqli_fetch_assoc($result2);
    $class = $gen1["name"];    
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="robots" content="noindex">
  <meta http-equiv="pragma" content="no-cache" />
  <meta http-equiv="expires" content="-1" />
  <title>
    <?=ucfirst(basename($_SERVER['PHP_SELF'], ".php"));?>
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <!-- <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../assets/css/now-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
  <!-- <link type="text/css" rel="stylesheet" href="http://jqueryte.com/css/jquery-te.css" charset="utf-8"> -->
  <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" charset="utf-8">
  <link href="../assets/css/main.css" rel="stylesheet" />
</head>

<body class="">
<div style="margin:20px; display: flex; justify-content:space-between;">
        <?php
            $sql = "select subject from tests where test_id = $test_id"
            
        ?>
        <div style="text-align:center;">
            <h2> GIAO DUC VA DAO TAO</h2>
            <hr>
            <h3>DE THI CHINH THUC</h3>
            <h5>(De thi co 5 trang)</h5>
        </div>
        <div style="text-align:center;">
            <h2>KI THI TOT NGHIEP TRUNG HOC PHO THONG 2022</h2>
            <h4>Bai thi: </h4>
            <h5>Thoi gian lam bai: 90 phut, khong ke thoi gian phat de</h5>
            <hr>
        </div>
    </div>
    <div style="margin: 20px;">
        <h4>Ho ten thi sinh:..................................</h4>
        <h4>So bao danh:..................................</h4>
    </div>
      <div class="content" style="min-height: auto;">
        <div class="row">                      
          <div class="col-md-12">
            <div class="card" style="min-height:400px;">
              <div class="card-body">
                  <table id="pdf" class="table table-striped table-bordered" style="width:100%">
                        <?php
                          $sql = "select question_id from question_test_mapping where test_id = $test_id";
                          $result = mysqli_query($conn,$sql);
                          $i = 1;
                          while($row = mysqli_fetch_assoc($result)) {
                            $question_id = $row["question_id"];
                            $sql1 = "select * from Questions where id = $question_id";
                            $result1 = mysqli_query($conn,$sql1);
                            $row1 = mysqli_fetch_assoc($result1);
                            ?>
                            <dl id = "<?= $row1["id"]; ?>">
                              <input type="hidden" id="question_id" value="<?= $row1["id"]; ?>">
                              <dt><?= $i;?>. <?=$row1["title"];?></dt>
                              <dd>A. <?= $row1["optionA"];?></dd>
                              <dd>B. <?= $row1["optionB"];?></dd>
                              <dd>C. <?= $row1["optionC"];?></dd>
                              <dd>D. <?= $row1["optionD"];?></dd> 
                             
                            </dl>
                            

                          <?php
                          $i++;
                          }    
                        ?>

                    
                  </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      


      
    </div>
  </div>
<!--   Core JS Files   -->
<script src="../assets/js/core/jquery.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<!--  Notifications Plugin    -->
<script src="../assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/now-ui-dashboard.min.js?v=1.1.0" type="text/javascript"></script>
<!-- <script src="http://jqueryte.com/js/jquery-te-1.4.0.min.js"></script> -->
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
    function redirect_to_add_question() {
      document.getElementById("form-add-questions").submit();
    }

    $(document).ready(function() {
        $('#example').DataTable();
    });

    function submit(val1) {
    document.getElementById("test_id").value = val1;
    document.getElementById("test_details").submit();
  }
</script>
</body>
</html>