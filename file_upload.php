<?php
include"init.php";
// session_destroy();
if (!isset($_SESSION['name']) or $_SESSION['name']!='abdo'){
    session_destroy();
    header("Location: login.php");
    die();
}
//print_r (phpinfo());
if (isset($_POST['submit']) and isset($_FILES['file'])){
  /*  $target_dir = "uploads/";
    $file = $_FILES['file']['name'];
    $path = pathinfo($file);
    $filename = $path['filename'];
    $ext = $path['extension'];
    $temp_name = $_FILES['file']['tmp_name'];
    $path_filename_ext = $target_dir.$filename.".".$ext;*/


    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
  //  move_uploaded_file($_Files['file']['tmp_name'],$target_file);






    function csvToArray($filename = '', $delimiter = ',')
    {
        move_uploaded_file($_FILES['file']['tmp_name'],$filename);
        if (!file_exists($filename) || !is_readable($filename)) {
            echo 'WTF';
            return false;
        }
        else {
            $header = NULL;
            $result = array();
            if (($handle = fopen($filename, 'r')) !== FALSE) {

                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    if (!$header)
                        $header = $row;
                    else

                        $result[] = array_combine($header, $row);
                }
                fclose($handle);
            }


            return $result;
        }
    }



// Insert data into database

    $all_data = csvToArray($target_file);
 //   echo $_FILES['file']['name'];


   print_r($all_data);

    for($i=0; $i<count($all_data); $i++) {



        $sql = $con->prepare( "INSERT INTO data(full_name,phone,car_type,model,makeing_year) VALUES (:shit,:phone,:car,:model,:year)");
        $sql->bindParam(':shit', $all_data[$i]['full_name']);
        $sql->bindParam(':phone', $all_data[$i]['phone']);
        $sql->bindParam(':car', $all_data[$i]['car_type']);
        $sql->bindParam(':model', $all_data[$i]['model']);
        $sql->bindParam(':year', $all_data[$i]['making_year']);
        $sql->execute();


    }


}

?>
<title> Upload Leads sheet </title>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2" style="position: relative; top: 50px">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Upload Leads' sheet </h4>

                </div>
                <div class="card-body">
                    <form action="<?php $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
                        <input type="file" name="file"  accept="text/csv">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group bmd-form-group">
                                    <label class="">Upload file</label>
                                </div>

                            </div>

                        </div>
                        <div class="row">

                            <button type="submit" name="submit" class="btn btn-primary pull-right">Upload File</button>
                            <div class="clearfix"></div>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php  include "footer.php"?>
</body>
</html>
