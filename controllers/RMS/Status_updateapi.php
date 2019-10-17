<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Status_updateapi extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
function statusupdate()
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-llow-Headers: access");
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

$this->Model_connectdb->stsupdate();
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