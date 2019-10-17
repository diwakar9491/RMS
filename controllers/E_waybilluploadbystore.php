<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class E_waybilluploadbystore extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function billupload()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $params      = (array) json_decode(file_get_contents('php://input'), true);
        $Transporter = isset($params[0]['transporter']) ? $params[0]['transporter'] : ""; //transporter is only for ebillupload method params-----------------
        $e_waybill = isset($params[0]['e_waybill']) ? $params[0]['e_waybill'] : "";
        $comment = isset($params[0]['comment']) ? $params[0]['comment'] : "";

        $email            = isset($params[1]['email_address']) ? $params[1]['email_address'] : "";
        $apprl_code       = isset($params[1]['approval_code']) ? $params[1]['approval_code'] : "";
        $status_count       = isset($params[1]['status_count']) ? $params[1]['status_count'] : "";

        if($e_waybill != "") //for FOCO store will generate e-way bill-------------------
        {
            $result = $this->Model_connectdb->ebillupload($Transporter,$e_waybill,$apprl_code,$comment);
            if($result)
            {
                $status     = $this->Model_connectdb->getstsmsg($status_count + 1);
                $status_msg = $status[0]['short_form'];
                if($status_msg){
                $data       = $this->Model_connectdb->stsupdate($email,$apprl_code, $status_msg);
                }
                if($data){
                    $array['status_code'] = 200;
                    $array['message'] = "E-way bill uploaded";
                    echo json_encode($array);
                }

            }   
}
        }   
    }
}
?>