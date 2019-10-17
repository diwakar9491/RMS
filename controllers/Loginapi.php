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
        $user_code = $data[0]['user_code'];
        $store_wr_code = $data[0]['str_whr_code'];
        $email     = $data[0]['user_email'];
        $role = $data[0]['user_role'];
        $brand = $data[0]['user_brand'];
        $user_name = $data[0]['user_name'];


        $array     = array();
        $randtoken = rand(100000,1000000);
        $token = md5($randtoken);
        if($email != "")
        {
            if($role == 'planner')
            {
                $array['message'] = 'Authorised user';
                $array['status_code'] = 200;
                $array['email_address'] = $email;
                $array['user_name'] = $user_name;
                $array['user_code'] = $user_code;
                $array['role'] = $role;
                $array['Access Token'] = $token;
                $array['brand'] = $brand;
                echo json_encode($array);
            }
        elseif ($role == 'store') 
        {
            // $this->Model_connectdb->user_log($email,$token);
            $array['message'] = 'Authorised user';
            $array['status_code'] = 200;
            $array['email_address'] = $email;
            $array['user_code'] = $user_code;
            $array['store_code'] = $store_wr_code;
            $array['user_name'] = $user_name;

            $strnm = $this->Model_connectdb->selectstorenm($store_wr_code);
            $store_name = $strnm[0]['store_name'];
            $str_status = $strnm[0]['store_status'];
            $str_type = $strnm[0]['store_type'];
            
            $array['store_name'] = $store_name;
            $array['store_type'] = $str_type;
            $array['role'] = $role;
            $array['store_status'] = $str_status;
            $array['Access Token'] = $token;
            $array['brand'] = $brand;
            echo json_encode($array);
        }
        elseif($role == 'warehouse')
        {
            $array['message'] = 'Authorised user';
            $array['status_code'] = 200;
            $array['email_address'] = $email;
            $array['user_code'] = $user_code;
            $array['store_code'] = $store_wr_code;
            $array['user_name'] = $user_name;

            $wrhsnm = $this->Model_connectdb->selectwrhsnm($store_wr_code);
            $warehouse_name = $wrhsnm[0]['warehouse_name'];
            $warehouse_status = $wrhsnm[0]['warehouse_status'];

            $array['warehouse_name'] = $warehouse_name;
            $array['role'] = $role;
            $array['warehouse_status'] = $warehouse_status;
            $array['Access Token'] = $token;
            $array['brand'] = $brand;
            echo json_encode($array);
        }
        elseif($role == 'ADMIN')
        {
            $array['message'] = 'Authorised user';
            $array['status_code'] = 200;
            $array['email_address'] = $email;
            $array['user_name'] = $user_name;

            $array['user_code'] = $user_code;
            $array['role'] = $role;
            $array['Access Token'] = $token;
            $array['brand'] = $brand;
            echo json_encode($array);
        }
        else {
           $array['message'] = 'Authorised user';
            $array['status_code'] = 200;
            $array['email_address'] = $email;
            $array['user_name'] = $user_name;

            $array['user_code'] = $user_code;
            $array['role'] = $role;
            $array['Access Token'] = $token;
            $array['brand'] = $brand;
            echo json_encode($array);        
        }
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
    $array['status_code'] = 405;
    echo json_encode($array); 
}


         
        }
    }