<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

session_start();

error_reporting(E_ALL);
ini_set("display_errors", 0);
//include "function.php";
function getAddress(){

        
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

        $json_res = json_decode($responseBody);
        //print var_dump($error);
        // if(@$json_res->{'name'}!='Unauthorized'){
        //         $userid = $json_res->{'displayname'};
        //         $_SESSION['username'] = $username;
        //         $_SESSION['user_id'] = $userid; 
        //         $_SESSION['user_profile'] = $responseBody;
                
        //         //print $_SESSION['user_profile'];
        // }

        

        return $result;
        
        
}
$body = file_get_contents('php://input');
//print var_dump($_POST);
$result = getAddress();
//echo json_encode(["sent" => true, "message" => "Something went "]);

print $result;
