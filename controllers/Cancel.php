<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cancel extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function cancelarrvlcode()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $cancelstatuscount = 0;
            $params      = (array) json_decode(file_get_contents('php://input'), true);
            $approvalcode = $params[1];
            // $email       = isset($params['email_address']) ? $params['email_address'] : "";
            $email   = isset($params[0]['email_address']) ? $params[0]['email_address'] : "";
            for ($i=0; $i < sizeof($approvalcode); $i++) { 
            $apprlcode   = isset($approvalcode[$i]['approval_code']) ? $approvalcode[$i]['approval_code'] : "";

            if($apprlcode == "")
            {
            $apprlcode   = isset($approvalcode[$i]['duplicate_approvalcode']) ? $approvalcode[$i]['duplicate_approvalcode'] : "";
            }     
            $statusmsg  = $this->Model_connectdb->getstsmsg($cancelstatuscount);
            $stsmsg = $statusmsg[0]['short_form'];
            $cancel       = $this->Model_connectdb->stsupdate($email,$apprlcode, $stsmsg); $apprvlstr = $apprvlstr . ",".$apprlcode;    
        }
                if($cancel)
                {
                    $array['message']            = substr($apprvlstr,1)." codes are Cancled";
                    $array['status_code']        = 200;
                    $array['status_description'] = $stsmsg;
                    echo json_encode($array);
                }           
        }
    }
}