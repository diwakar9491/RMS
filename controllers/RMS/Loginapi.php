<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Loginapi extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
	public function index()
	{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: access"); 
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $params = (array) json_decode(file_get_contents('php://input'), TRUE);
    if (isset($params['email_address']) != "" && isset($params['password']) != "") {
        $postemail = $params['email_address'];
        $postpwd   = md5($params['password']);
        $data      = $this->Model_connectdb->selectuserdata($postemail,$postpwd);
        $email     = $data[0]['user_email'];
        $role = $data[0]['user_role'];
        $array     = array();
        $randtoken = rand(100000,1000000);
        $token = md5($randtoken);
        if ($email != "") {
            $this->Model_connectdb->user_log($email,$token);
            $array['message'] = 'Authorised user';
            $array['email'] = "$email";
            $array['role'] = "$role";
            $array['Access Token'] = $token;
            echo json_encode($array);
        }
         else {
            $array['message'] = "email id or password is wrong.";
            echo json_encode($array);
        }
    } else {
        $array['message'] = "either email id or password is missing.";
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