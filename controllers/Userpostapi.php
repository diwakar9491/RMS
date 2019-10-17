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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $array  = array();
    $params = (array) json_decode(file_get_contents('php://input'), true);
    if (isset($params['name']) != "" && isset($params['code']) != "") {
        if (isset($params['email']) != ""&& isset($params['role']) != "") {
            if (isset($params['password']) != "" && isset($params['insertedby']) != "") {
                
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
}
?>