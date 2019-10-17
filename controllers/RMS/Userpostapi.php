<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Userpostapi extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
function userpost()
{
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$params = (array) json_decode(file_get_contents('php://input'), TRUE);
$postemail = isset($params[0]['email_address']) ? $params[0]['email_address'] : "";
$posttoken = isset($params[0]['token']) ? $params[0]['token'] : "";
if($posttoken != "" && $postemail != "")
{
    $dbAccess_token = $this->Model_connectdb->gettoken($posttoken);
    $Access_token = $dbAccess_token[0]['token'];
    if($posttoken == $Access_token)
    {
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $array  = array();
    $params = (array) json_decode(file_get_contents('php://input'), true);
    if (isset($params[1]['name']) != "" && isset($params[1]['code']) != "") {
        if (isset($params[1]['email']) != ""&& isset($params[1]['role']) != "") {
            if (isset($params[1]['password']) != "" && isset($params[1]['insertedby']) != "") {
                
                $user_name       = $params['name'];
                $user_code       = $params['code'];
                $user_email      = $params['email'];
                $user_role       = $params['role'];
                $user_password   = md5($params['password']);
                $user_insertedby = $params['insertedby'];
                $insrtusrdata 	 =$this->Model_connectdb->insertuser($user_name,$user_code,$user_role,$user_password,$user_insertedby,$user_email);
                if($insrtusrdata)
                {
                	$array['message'] = "User regestered successfully.";
                	echo json_encode($array);
                }
                else
                {
                	$array['message'] = "User not regestered, try with another email id or user code.";
                	echo json_encode($array);
                }
             } else {
                $array['message'] = "Either password or inserted by is missing.";
                echo json_encode($array);
            }
        } else {
            $array['message'] = "either email or role of user is missing.";
            echo json_encode($array);
        }
    } else {
        $array['message'] = "Either name or code is missing.";
        echo json_encode($array);
    }
    
}
else
{
	$array['message'] = "Method not allowed.";
	echo json_encode($array);
}
    }
    else{
        $array['message'] = "Invalid Access token";
        echo json_encode($array);
    }
}
else{
    $array['message'] = "Email id or Access token is missing";
    echo json_encode($array);
}
}
}
?>