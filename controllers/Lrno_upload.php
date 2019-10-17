<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lrno_upload extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function lrupload()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $params      = (array) json_decode(file_get_contents('php://input'), true);
        // $date = isset($params[0]['date']) ? $params[0]['date'] : "";
        $lr_no = isset($params[0]['lorry_receipt_no']) ? $params[0]['lorry_receipt_no'] : "";
        $comment = isset($params[0]['comment']) ? $params[0]['comment'] : "";

        // $imgname = isset($params[0]['image_url']) ? $params[0]['image_url'] : "";
        // $e_waybill = isset($params[0]['e_waybill']) ? $params[0]['e_waybill'] : "";
        $email            = isset($params[0]['email_address']) ? $params[0]['email_address'] : "";
        $apprl_code       = isset($params[0]['approval_code']) ? $params[0]['approval_code'] : "";
        $status_count       = isset($params[0]['status_count']) ? $params[0]['status_count'] : "";
        $doc       = isset($params[1]) ? $params[1] : "";
        $doc1 = json_encode($doc);

        if ($lr_no != "") {
            $result = $this->Model_connectdb->updatelr_no($lr_no,$apprl_code,$doc1,$comment);
            if($result){
                $status     = $this->Model_connectdb->getstsmsg($status_count + 1);
                $status_msg = $status[0]['short_form'];
                if($status_msg){
                $data       = $this->Model_connectdb->stsupdate($email,$apprl_code, $status_msg);
                }
                if($data){
                    $array['status_code'] = 200;
                    $array['message'] = "LR number uploaded";
                    echo json_encode($array);
                }

            }
            else
            {
                echo "else";
            }
        
    }
    else{
        $array['message'] = "LR number missing";
        $array['status_code'] = 304;
        echo json_encode($array);
    }
        



        }   
    }
}
?>