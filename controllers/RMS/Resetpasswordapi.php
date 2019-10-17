<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Resetpasswordapi extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
function resetpassword()
{
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: access"); 
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 

$params = (array) json_decode(file_get_contents('php://input'), TRUE);
$postemail = isset($params['email_address']) ? $params['email_address'] : "";
$posttoken = isset($params['token']) ? $params['token'] : "";
if($posttoken != "" && $postemail != "")
{
    $dbAccess_token = $this->Model_connectdb->gettoken($posttoken);
    $Access_token = $dbAccess_token[0]['token'];
    if($posttoken == $Access_token)
    {

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $params = (array) json_decode(file_get_contents('php://input'), TRUE);
    if (isset($params['email']) != "" && isset($params['password']) != "") {
        $postemail    = $params['email'];
        $postpassword = md5($params['password']);
        $data         = $this->Model_connectdb->selectpwd($postemail);
        $email        = $data[0]['user_email'];
        $password     = $data[0]['user_password'];
        $array        = array();
        if ($email != "") {
            if ($postpassword == $password) {
                $array['message'] = "OLd password and new password should not be same.";
                echo json_encode($array);
            } else {
                $this->Model_connectdb->resetpwd($postpassword,$postemail);
                $array['message'] = 'Password reset successfull';
                $array['email']   = "$email";
                echo json_encode($array);
            }
        } else {
            $array['message'] = "Not regestered user";
            echo json_encode($array);
        }
    } else {
        $array["message"] = 'either email id or password is missing';
        echo json_encode($array);
    }
}
    }
    else{
        $array['message'] = "Invalid Access token";
        echo json_encode($array);
    }
}
else{
    $array['message'] = "Either email id or token is missing";
    echo json_encode($array);
}
}
}

?>