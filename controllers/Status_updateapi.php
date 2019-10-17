<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Status_updateapi extends CI_Controller
{
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
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $cancelstatuscount = 11;
            $valuesstr = 0;
            $params            = (array) json_decode(file_get_contents('php://input'), true);
            $email = isset($params[0]['email_address']) ? $params[0]['email_address'] : "";
            $indexone = isset($params[1]) ? $params[1] : "";

            for ($i = 0; $i < sizeof($indexone); $i++) {
                $apprlcode   = isset($indexone[$i]['approval_code']) ? $indexone[$i]['approval_code'] : "";
                if($apprlcode == "")
                {
                $apprlcode   = isset($indexone[$i]['duplicate_approvalcode']) ? $indexone[$i]['duplicate_approvalcode'] : "";
                }
                $statuscount = isset($indexone[$i]['status_count']) ? $indexone[$i]['status_count'] : "";
                if ($apprlcode != "" && $statuscount != "" && $email != "") {
                    $status     = $this->Model_connectdb->getstsmsg($statuscount + 1);
                    $status_msg = $status[0]['short_form'];
                    $data       = $this->Model_connectdb->stsupdate($email,$apprlcode, $status_msg);
                    
                }
            }
            if ($data != "") {
                $array['message']            = "$apprlcode Status updated successfully";
                $array['status_code']        = 200;
                $array['status_description'] = $status_msg;
                echo json_encode($array);
            } else {
                $array['message']     = "Status not updated";
                $array['status_code'] = 304;
            }
        } else {
            $array['message']     = "Method not allowed";
            $array['status_code'] = 405;
            
            echo json_encode($array);
        }
    }
}

?>