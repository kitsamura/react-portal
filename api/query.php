<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
ini_set('session.gc_maxlifetime', 86400);
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 0);

if($_REQUEST['act']=='addbday'){
  getCalendar();
}
else if($_REQUEST['act']=='outbday'){
//print $_REQUEST['act'];
print outDates();
} 
else if($_REQUEST['act']=='addevent'){
//print $_REQUEST['act'];
print addEvent();
} 
else if($_REQUEST['act']=='editevent'){
//print $_REQUEST['act'];
print editEvent();
} 
else if($_REQUEST['act']=='delevent'){
  print delEvent($_REQUEST['id']);
}
else if($_REQUEST['act']=='queryevent'){
  print queryEvent($_REQUEST['id']);
}
else if($_REQUEST['act']=='bookroom'){
  print bookRoom();
}
else if($_REQUEST['act']=='sendpropusk'){
  print sendPropusk();
}


function connect(){
	$mysqli = new mysqli('localhost', 'website', 'it_admin', 'portal');
    //$mysqli = new mysqli('u441880.mysql.masterhost.ru', 'u441880_portal', 's6tEN.uou5_', 'u441880_portal');
	$mysqli->set_charset("utf8");

  return $mysqli;
}
  
  function getCalendar(){
    $mysqli = connect();

        
        $url = 'https://ldap-dev.amulex.ru/v1/user-ldap/list?access-token=$NX(!l507S%3E%3EE_30,\(%3C7;y*8d2aS1\t';
        $curl  = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        //curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
       curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headerCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $responseBody = substr($response, $header_size);

       
        $result = $responseBody;

        $json_res = json_decode($responseBody, true);

       // print count($json_res)."<br>";
        $count = count($json_res);

        if($count > 0 ){
          foreach ($json_res as $key => $value) {
            if($value &&  $value['birthday']){
              //print "<br>---<br>";
              //print strip_tags($value['cn']);
              //print " ->".strip_tags($value['birthday']);
              //print "<br>";
              $name = strip_tags($value['cn']);
              $dArr = explode('.', strip_tags($value['birthday']));
              $date = $dArr[2]."-".$dArr[1]."-".$dArr[0];

              $query = "INSERT INTO `tbl_events` (`id`, `type_id`, `img_id`, `name`, `desc`, `date`, `author`, `enable`) 
              VALUES (NULL, '1', '1', ' День рождение ".$name."', '', '".$date."', 'AD', 'Y')";
              print $query."<br>";
              $res = mysqli_query($mysqli, $query);
            }
          }

        
        }

       
        
}

function outDates(){
  $mysqli = connect();
    if($_REQUEST['q']!='month'){
      $query = outQueryWeek();
    }
    elseif ($_REQUEST['q']=='month') {
      $query = outQueryMonth();
    }


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
    
    //print "<pre>";
    
    return $r;
      


}

function queryEvent(){
  $mysqli = connect();
    
$query = "SELECT e.id as id, img.img as img, e.name as name, t.id as type, DATE_FORMAT(e.date, '".date("Y").", %m, %d') as edate, t.name as tnam, e.desc as description FROM tbl_events as e, tbl_events_type as t, tbl_events_img as img 
          WHERE e.type_id = t.id AND e.img_id = img.id AND e.enable='Y' AND e.id = ".$_REQUEST['id']."";

       

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
    
    //print "<pre>";
    
    return $r;
      


}

function addEvent(){
  $mysqli = connect();

      $dArr = explode('/', strip_tags($_REQUEST['date']));
     // $date = $dArr[2]."-".$dArr[0]."-".$dArr[1];

      $query = "INSERT INTO `tbl_events` (`id`, `type_id`, `img_id`, `name`, `desc`, `date`, `author`, `enable`) 
      VALUES (NULL, '".$_REQUEST['type']."', '".$_REQUEST['img']."', '".$_REQUEST['title']."', '".$_REQUEST['desc']."', '".$_REQUEST['date']."', 'AD', 'Y')";
      //print $query."<br>";
      $res = mysqli_query($mysqli, $query);

       $query = outQueryMonth();
    
      $res = mysqli_query($mysqli, $query);
      $rows = mysqli_num_rows($res);

      $r = '';
      $dbdata = '';
      
      while ($row = $res->fetch_assoc()){
              $dbdata[]=$row;
      }
      $r = json_encode($dbdata, JSON_PRETTY_PRINT);
      return $r;

}

function editEvent($id){
  $mysqli = connect();

  $query = "UPDATE `tbl_events` SET `type_id` = '".$_REQUEST['type']."', `img_id` = '".$_REQUEST['img']."', `name` = '".$_REQUEST['title']."', `desc` = '".$_REQUEST['desc']."',  `date` = '".$_REQUEST['date']."' WHERE `id` = ".$_REQUEST['id']."";
  
  
  //print $query;
  $res = mysqli_query($mysqli, $query);

  $query = outQueryMonth();

  
    
      $res = mysqli_query($mysqli, $query);
      $rows = mysqli_num_rows($res);

      $r = '';
      $dbdata = '';
      
      while ($row = $res->fetch_assoc()){
              $dbdata[]=$row;
      }
      $r = json_encode($dbdata, JSON_PRETTY_PRINT);
      return $r;
}

function bookRoom($id){

  $dbdata = array(
          'status' => 'ok', 
          'result' => 'book'
      );
  
      $r = json_encode($dbdata, JSON_PRETTY_PRINT);
      return $r;

}
function sendPropusk($id){

  $dbdata = array(
          'status' => 'ok', 
          'result' => 'send'
      );
  
      $r = json_encode($dbdata, JSON_PRETTY_PRINT);
      return $r;

}

function delEvent($id){
  $mysqli = connect();

  $query = "UPDATE `tbl_events` SET `enable` = 'N' WHERE `id` = ".$id."";
  $res = mysqli_query($mysqli, $query);

  $query = outQueryMonth();

  
    
      $res = mysqli_query($mysqli, $query);
      $rows = mysqli_num_rows($res);

      $r = '';
      $dbdata = '';
      
      while ($row = $res->fetch_assoc()){
              $dbdata[]=$row;
      }
      $r = json_encode($dbdata, JSON_PRETTY_PRINT);
      return $r;

}
function outQueryWeek(){
  $query = "SELECT e.id as id, img.img as img, e.name as name, DATE_FORMAT(e.date, '".date("Y")."-%m-%d') as edate, t.name as tname FROM tbl_events as e, tbl_events_type as t, tbl_events_img as img 
          WHERE e.type_id = t.id AND e.img_id = img.id AND e.enable='Y'
          AND DATE(e.date + INTERVAL (YEAR(NOW()) - YEAR(e.date)) YEAR)
          BETWEEN
          DATE(NOW() - INTERVAL WEEKDAY(NOW()) DAY)
          AND
          DATE(NOW() + INTERVAL 6 - WEEKDAY(NOW()) DAY) 
          ORDER BY e.date ASC";

          return $query;
}
function outQueryMonth(){
   $query = "SELECT e.id as id, img.img as img, e.name as name, DATE_FORMAT(e.date, '".date("Y")."-%m-%d') as edate, t.name as tname FROM tbl_events as e, tbl_events_type as t, tbl_events_img as img 
          WHERE e.type_id = t.id AND e.img_id = img.id AND e.enable='Y'
          ORDER BY MONTH(e.date), DAYOFMONTH(e.date) ASC";

          return $query;
}


