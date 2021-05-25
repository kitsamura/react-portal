<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

session_start();

error_reporting(E_ALL);
ini_set("display_errors", 0);

if($_REQUEST['act']=='query'){
  $result = getDocs();
}
elseif($_REQUEST['act']=='add'){
  $result = addDoc();
}
elseif($_REQUEST['act']=='del'){
 $result = delDoc($_REQUEST['id']);
}
elseif($_REQUEST['act']=='edit'){
 $result = editDoc($_REQUEST['id']);
}
elseif($_REQUEST['act']=='querydoc'){
 $result = queryDoc($_REQUEST['id']);
}
//include "function.php";
function connect(){
	$mysqli = new mysqli('localhost', 'website', 'it_admin', 'portal');
    //$mysqli = new mysqli('u441880.mysql.masterhost.ru', 'u441880_portal', 's6tEN.uou5_', 'u441880_portal');
	$mysqli->set_charset("utf8");

  return $mysqli;
}

function addDoc(){

  $response = array();
  $upload_dir = '/api/downloads/';
  $server_url = 'http://192.168.100.243/';

  if($_FILES['doc']){
      $file_name = $_FILES["doc"]["name"];
      $file_tmp_name = $_FILES["doc"]["tmp_name"];
      $error = $_FILES["doc"]["error"];

      if($error > 0){
          $response = array(
              "status" => "error",
              "error" => true,
              "message" => "Error uploading the file!"
          );
      } else {
          $random_name = rand(1000,1000000)."-".$file_name;
          $upload_name = $upload_dir.strtolower($random_name);
          $upload_name = preg_replace('/\s+/', '-', $upload_name);

          mkdir(getcwd().DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'downloads'.DIRECTORY_SEPARATOR);

        $uploaddir = getcwd().DIRECTORY_SEPARATOR.'downloads'.DIRECTORY_SEPARATOR;
        $uploadfile = $uploaddir.basename($_FILES['doc']['name']);
      
          if(move_uploaded_file($file_tmp_name , $uploadfile)) {
              

                $mysqli = connect();
                $type = $_REQUEST['type'];
                $title = $_REQUEST['title'];
                $filename = $_REQUEST['file'];
                  
                $query = "INSERT INTO `tbl_doc` (`id`, `type`, `title`, `path`, `author`, `enable`) VALUES (NULL, '".$type."', '".$title."', '".$_FILES['doc']['name']."', 'AD', 'Y');";
                $res = mysqli_query($mysqli, $query);
                $rows = mysqli_num_rows($res);

                $response = array(
                  "status" => "success",
                  "error" => false,
                  "message" => $query
                );
                
                

    
          } else {
              $response = array(
                  "status" => "error",
                  "error" => true,
                  "message" => "Error uploading the file! ".$_FILES["doc"]["error"].""
              );
          }
      }
  }
   
    return json_encode($response);
}
function delDoc($id=''){
  $mysqli = connect();
    
  $query = "DELETE FROM `tbl_doc` WHERE `id` = ".$id."";
  $res = mysqli_query($mysqli, $query);
    $rows = mysqli_num_rows($res);
    $r = '';
    $dbdata = '';
    if($rows > 0){
      while ($row = $res->fetch_assoc()){
						$dbdata[]=$row;
      }
    }
    else{
      $dbdata = array(
          'status' => 'ok', 
          'result' => 'nodata'
      );
    }

    $r = json_encode($dbdata, JSON_PRETTY_PRINT);
    return $r;

}
function editDoc($id=''){
  $mysqli = connect();
  $response = array();
  $upload_dir = '/public/downloads/';
  $server_url = 'http://localhost';

  if($_FILES['doc']){
      $file_name = $_FILES["doc"]["name"];
      $file_tmp_name = $_FILES["doc"]["tmp_name"];
      $error = $_FILES["doc"]["error"];

      if($error > 0){
          $response = array(
              "status" => "error",
              "error" => true,
              "message" => "Error uploading the file!"
          );
      } else {
          $random_name = rand(1000,1000000)."-".$file_name;
          $upload_name = $upload_dir.strtolower($random_name);
          $upload_name = preg_replace('/\s+/', '-', $upload_name);

          mkdir(getcwd().DIRECTORY_SEPARATOR.'downloads'.DIRECTORY_SEPARATOR);

        $uploaddir = getcwd().DIRECTORY_SEPARATOR.'downloads'.DIRECTORY_SEPARATOR;
        $uploadfile = $uploaddir.basename($_FILES['doc']['name']);
      
          if(move_uploaded_file($file_tmp_name , $uploadfile)) {
              

                
                $type = $_REQUEST['type'];
                $title = $_REQUEST['title'];
                $filename = $_REQUEST['file'];
                  
                 $query = "UPDATE `tbl_doc` SET `type` = '".$type."', `title` = '".$title."', `path` = '".$_FILES['doc']['name']."' WHERE `id` = ".$id."";
                $res = mysqli_query($mysqli, $query);
                $rows = mysqli_num_rows($res);

                $response = array(
                  "status" => "success",
                  "error" => false,
                  "message" => "File uploaded successfully"
                );
                
                

    
          } else {
              $response = array(
                  "status" => "error",
                  "error" => true,
                  "message" => "Error uploading the file! ".$_FILES["doc"]["name"].""
              );
          }
      }
  } else {
                $type = $_REQUEST['type'];
                $title = $_REQUEST['title'];
                
                  
                 $query = "UPDATE `tbl_doc` SET `type` = '".$type."', `title` = '".$title."' WHERE `id` = ".$id."";
                $res = mysqli_query($mysqli, $query);
                $rows = mysqli_num_rows($res);

                $response = array(
                  "status" => "success",
                  "error" => false,
                  "message" => "File uploaded successfully"
                );
  }
   
    return json_encode($response);
  

}
function queryDoc($id=''){

  $mysqli = connect();
    
  $query = "SELECT * FROM tbl_doc WHERE  enable='Y' AND id = '".$id."'";
  $res = mysqli_query($mysqli, $query);
    $rows = mysqli_num_rows($res);
    $r = '';
    $dbdata = '';
    if($rows > 0){
      while ($row = $res->fetch_assoc()){
						$dbdata[]=$row;
      }
    }
    else{
      $dbdata = array(
          'status' => 'ok', 
          'result' => 'nodata'
      );
    }

    $r = json_encode($dbdata, JSON_PRETTY_PRINT);
    return $r;

}

function getDocs(){

  $mysqli = connect();
    
$query = "SELECT * FROM tbl_doc WHERE  enable='Y' ";

       

		$res = mysqli_query($mysqli, $query);
    $rows = mysqli_num_rows($res);

    $r = '';
    //print var_dump($rows);
    $dbdata = '';
    if($rows > 0){
      while ($row = $res->fetch_assoc()){
						$dbdata[]=$row;
      }
      
    }
    else{
      $dbdata = array(
          'status' => 'ok', 
          'result' => 'nodata'
      );
    }

    $r = json_encode($dbdata, JSON_PRETTY_PRINT);
    return $r;
        
        
}
$body = file_get_contents('php://input');

//$result = getDocs();


print $result;
