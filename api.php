<?php
include_once('connect.php');

$relation = '';
isset($_GET['relation']) == true ? $relation = $_GET['relation'] : $relation= 'def';


if ($relation=='universityData') {
    if ($_POST['android']) {

        $sql = $con->prepare("SELECT university_id, name  FROM universities WHERE 1");
        $sql->execute();
        $universities = $sql->fetchAll(PDO::FETCH_ASSOC);

        $universities =  json_encode($universities);
        echo 'success';
        print_r( $universities);

    }
}
    if ($relation=='facultyData'){
        if ($_POST['android']){

            $university_id = htmlentities($_POST['university_id']);


            $sql = $con->prepare( "SELECT faculty_id,name FROM faculties WHERE fk_university_id=:university_id");
            $sql->bindParam(':university_id', $university_id );
            $sql->execute();
            $faculties = $sql->fetchAll(PDO::FETCH_ASSOC);

            $faculties =  json_encode($faculties);
            echo 'success';
            echo $faculties;

        }
    }
    if ($relation=='departmentData'){
        if (isset($_POST['android'])){


            $faculty_id = htmlentities($_POST['faculty_id']);



            $sql = $con->prepare( "SELECT department FROM ac_paths WHERE fk_faculty_id = $faculty_id");
           // $sql->bindParam(':fk_faculty_id', $faculty_id );
            $sql->execute();
            $departments = $sql->fetchAll(PDO::FETCH_ASSOC);
            $departments =  json_encode($departments);
            echo 'success';
            echo $departments;

        }
    }
    if ($relation=='specializeData'){
        if (isset($_POST['android'])){

            $department = htmlentities($_POST['department']);


            $sql = $con->prepare( "SELECT specialize FROM ac_paths WHERE department = :department");
            $sql->bindParam(':department', $department );
            $sql->execute();
            $specializes = $sql->fetchAll(PDO::FETCH_ASSOC);
            $specializes =  json_encode($specializes);
            echo 'success';
            echo $specializes;

        }
    }
if ($relation=='yearData'){
    if (isset($_POST['android'])){

        $specialize = htmlentities($_POST['specialize']);


        $sql = $con->prepare( "SELECT year FROM ac_paths WHERE specialize = :specialize");
        $sql->bindParam(':specialize', $specialize );
        $sql->execute();
        $years = $sql->fetchAll(PDO::FETCH_ASSOC);
        $years =  json_encode($years);
        echo 'success';
        echo $years;


    }
}


    if ($relation=='signUp'){
        if (isset($_POST['android'])){

            $name = htmlentities($_POST['name']);
            $email = htmlentities($_POST['email']);
            $pass = htmlentities($_POST['password']);
            $department = htmlentities($_POST['department']);
            $specialize = htmlentities($_POST['specialize']);
            $year = htmlentities($_POST['year']);
            $term = htmlentities($_POST['term']);
            $faculty_id = htmlentities($_POST['faculty_id']);

            $pass = md5($pass);


                $sql = $con->prepare("SELECT email FROM users WHERE email = :email");
                $sql->bindParam(':email', $email);
                $sql->execute();


           $sql = $con->prepare( "SELECT ac_path_id FROM ac_paths WHERE department=:department and specialize=:specialize and year=:year and term=:term 
 and fk_faculty_id=:faculty_id");
            $sql->bindParam(':department',$department );
            $sql->bindParam(':specialize',$specialize );
            $sql->bindParam(':year',$year );
            $sql->bindParam(':term', $term);
            $sql->bindParam(':faculty_id',$faculty_id );

            $sql->execute();
            $ac_path_id =  $sql->fetchAll(PDO::FETCH_ASSOC);
            $ac_path_id = implode($ac_path_id);


            $sql = $con->prepare( "INSERT INTO users(name,email,password,fk_ac_path_id) VALUES (:name,:email,:pass,:ac_path_id)");
            $sql->bindParam(':name', $name);
            $sql->bindParam(':email', $email);
            $sql->bindParam(':pass', $pass);
            $sql->bindParam(':ac_path_id',$ac_path_id );
            $sql->execute();

            $sql = $con->prepare( "SELECT user_id, name,fk_ac_path_id FROM users WHERE email = :email");
            $sql->bindParam(':email',$email);
            $sql->execute();

            $user = $sql->fetchAll(PDO::FETCH_ASSOC);
            $user =  json_encode($user);
            echo 'success';
            echo $user;



            if (!empty($user)){
                echo 'success';
            }
        }
    }





if ($relation=='logIn'){

    if (isset($_POST['android'])) {
        $email = htmlentities($_POST['email']);
        $pass = htmlentities($_POST['password']);
        $pass = md5($pass);


        $sql = $con->prepare( "SELECT user_id, name, fk_ac_path_id FROM users WHERE email = :email and password =:pass");
        $sql->bindParam(':email', $email );
        $sql->bindParam(':pass', $pass );
        $sql->execute();
        $user = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (empty($user)){
            echo( ' fail error login');

        }
        else {
            echo 'success';
            $user= json_encode($user);
            echo $user;
        }

    }

}
if ($relation=='file_upload'){
    if (isset($_POST['android']) and isset($_FILES['file'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        move_uploaded_file($_FILES['file']['tmp_name'],$target_file);
        $link =  "uploads/" . $_FILES["file"]["name"];
        $uploaded_file = pathinfo($target_file);

        $sql = $con->prepare( "INSERT INTO materials(name,type,link) VALUES(:name,:type,:link)");
        $sql->bindParam(':name', $uploaded_file['filename']  );
        $sql->bindParam(':type', $uploaded_file['extension'] );
        $sql->bindParam(':type', $link );
        $sql->execute();
        $material = $sql->fetchAll(PDO::FETCH_ASSOC);

        print_r($material);




    }

}
if ($relation=='subject'){
    if (1/*isset($_POST['android'])*/) {
       //$ac_path_id = htmlentities($_POST['ac_path_id']);

        $sql = $con->prepare( "SELECT fk_subject_id,mtm_id FROM mtm_subjects_ac_paths where fk_ac_path_id =2");
        //$sql->bindParam(':ac_path_id', $ac_path_id  );
        $sql->execute();
        $subject_data = $sql->fetchAll(PDO::FETCH_ASSOC);
   // print_r($subject_data[0]['fk_subject_id']);

        $subjects = [];
        $mtm_data = [];

        for ($i=0; $i<sizeof($subject_data);$i++){
            $sql = $con->prepare( "SELECT subject_id, name, image_number  FROM subjects where subject_id = :subject_id");
            $sql->bindParam(':subject_id', $subject_data[$i]['fk_subject_id'] );
            $sql->execute();
            $row =  $sql->fetchAll(PDO::FETCH_ASSOC);
            //print_r($row);
            array_push($subjects,$row);
           array_push($mtm_data,$subject_data[$i]['mtm_id']);
        }

        if (empty($subjects) or empty($mtm_data)){
            echo( ' fail error 5ra');

        }
        else {
            echo 'success[';
            $subjects= json_encode($subjects);
            $mtm_data= json_encode($mtm_data);
            echo $subjects . ',';
            echo $mtm_data.']';
        }





    }

}

if ($relation=='tab') {
    if (1/*isset($_POST['android'])*/) {
        //$mtm_subject_ac_path_id = htmlentities($_POST['mtm_id']);

        $sql = $con->prepare("SELECT fk_tab_id,mtm_id FROM mtm_tabs_subjects_ac_paths where mtm_subject_ac_paths_id =1");
        //$sql->bindParam(':mtm_subject_ac_path_id', $mtm_subject_ac_path_id  );
        $sql->execute();
        $tab_data = $sql->fetchAll(PDO::FETCH_ASSOC);



        $subjects = [];
        $mtm_data = [];

        for ($i = 0; $i < sizeof($tab_data); $i++) {
            $sql = $con->prepare("SELECT tab_id,name FROM tabs where tab_id=:fk_tab_id");
            $sql->bindParam(':fk_tab_id', $tab_data[$i]['fk_tab_id']);
            $sql->execute();
            $row = $sql->fetchAll(PDO::FETCH_ASSOC);
            array_push($subjects,$row);
            array_push($mtm_data,$tab_data[$i]['mtm_id']);

        }
        if (empty($subjects) or empty($mtm_data)){
            echo( ' fail error 5ra');

        }
        else {
            echo 'success[';
            $subjects= json_encode($subjects);
            $mtm_data= json_encode($mtm_data);
            echo $subjects . ',';
            echo $mtm_data.']';
        }



    }
}
if ($relation=='material'){
    if (1/*isset($_POST['android'])*/){
        //$mtm_tab_subject_ac_path_id = htmlentities($_POST['mtm_id']);
        $sql = $con->prepare("SELECT sub_tab_id, name FROM sub_tabs where fk_mtm_id =4");
        //$sql->bindParam(':mtm_subject_ac_path_id', $mtm_subject_ac_path_id  );
        $sql->execute();
        $sub_tab_data = $sql->fetchAll(PDO::FETCH_ASSOC);
        print_r($sub_tab_data[0]['sub_tab_id']);
        die();

        $subjects = [];
        $mtm_data = [];

        for ($i = 0; $i < sizeof($sub_tab_data); $i++) {
            $sql = $con->prepare("SELECT name,link,fk_sub_tab_id FROM materials where fk_sub_tab_id=:fk_sub_tab_id");
            $sql->bindParam(':fk_sub_tab_id', $sub_tab_data[$i]['sub_tab_id']);
            $sql->execute();
            $row = $sql->fetchAll(PDO::FETCH_ASSOC);
            array_push($subjects,$row);
            //array_push($mtm_data,$sub_tab_data[$i]['sub_tab_id']);

        }
        print_r($subjects);
        die();
        if (empty($subjects)){
            echo( ' fail error 5ra');

        }
        else {
            echo 'success[';
            $subjects= json_encode($subjects);
          //  $mtm_data= json_encode($mtm_data);
            echo $subjects . ',';
          //  echo $mtm_data.']';
        }






    }


}
    if ($relation == 'post') {
        //ana keda sagelto, na2es b2a azero fe elsharing
        if (isset($_POST['android'])) {
            $text = $_POST['text'];
            $user_id = $_POST['user_id'];
            if (isset($_FILES['file'])) {
                $target_dir = "post_uploads/";
                $target_file = $target_dir . basename($_FILES["file"]["name"]);
                move_uploaded_file($_FILES['file']['tmp_name'], $target_file);


                $sql = $con->prepare("INSERT INTO posts(text,image_url,fk_user_id) VALUES(:text,:image,:user_id)");
                $sql->bindParam(':text', $text);
                $sql->bindParam(':image', $target_file);
                $sql->bindParam(':user_id', $user_id);

                $sql->execute();
                //$material = $sql->fetch(PDO::FETCH_ASSOC);
            } else {

                $sql = $con->prepare("INSERT INTO posts(text,fk_user_id) VALUES(:name,:user_id)");
                $sql->bindParam(':text', $text);
                $sql->bindParam(':user_id', $user_id);
                $sql->execute();
                // $material = $sql->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
    if ($relation == 'reply') {

    }
    if ($relation == 'tasks') {

        $text = $_POST['text'];
        $user_id = $_POST['user_id'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $priority = $_POST['priority'];

        $sql = $con->prepare("INSERT INTO tasks(text,start_time,end_time,fk_user_id) VALUES(:text,start_time,end_time,:user_id)");
        $sql->bindParam(':text', $text);
        $sql->bindParam(':start_time', $start_time);
        $sql->bindParam(':end_time', $end_time);
        $sql->bindParam(':user_id', $user_id);
        $sql->execute();
    }




