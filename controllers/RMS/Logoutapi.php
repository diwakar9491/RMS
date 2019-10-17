
<?php
defined('BASEPATH') OR exit ('No direct script access allowed');
class Logoutapi extends CI_Controller
 {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->Model('Model_connectdb');
    }
    function logout()
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
                $dlttoken = $this->Model_connectdb->delettoken($postemail);
                if($dlttoken)
                {
                    echo $dlttoken;die;
                    $array['message'] = "Log out successfull";
                    echo json_encode($array);
                }
            }
            else{
                $array['message'] = "Invalid Access token";
                echo json_encode($array);
            }
        }
        else{
            $array['message'] = "email id or Access tocken is missing";
            echo json_encode($array);
        }
    }
}

?>