<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Uploadproqty extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function uploadqty()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           $params = (array) json_decode(file_get_contents('php://input'), true);
           // $cn_no = isset($params[0]['cradit_no']) ? $params[0]['cradit_no'] : "";
           $product_countarr = isset($params[1]) ? $params[1] : "";
           $email = isset($params[0]['email_address']) ? $params[0]['email_address'] : "";

           $status_count = isset($params[0]['status_count']) ? $params[0]['status_count'] : "";
           $comment = isset($params[0]['comment']) ? $params['comment'] : "";

           $approval_code = isset($params[0]['approval_code']) ? $params[0]['approval_code'] : "";
           for ($i=0; $i < sizeof($product_countarr); $i++) { 
             $product_countstr = $product_countstr .",". $product_countarr[$i]['total_qty_received'];
           }
           // $apprvlcode = array();
           // for($i=0;$i<sizeof($approval_code);$i++)
           // {
           //  $apprvlcode[$i] = "'".$approval_code[$i]['approval_code']."'";
           // }
           // $apprcode = implode($apprvlcode, ",");
           if($product_countstr != "")
           {
            $date = date('Y-m-d H:i:s');
            $result = $this->Model_connectdb->insrtproqty(substr($product_countstr,1),$date,$comment,$approval_code);
            if($result)
            {
              $status     = $this->Model_connectdb->getstsmsg($status_count + 1);
              $status_msg = $status[0]['short_form'];

              if($status_msg)
              {
              $stsupdate = $this->Model_connectdb->stsupdate($email,$approval_code,$status_msg);
            }
            if($stsupdate)
            {
              $array['message'] = "status updated";
              $array['status_code'] = 200;
              echo json_encode($array);
            }
            else
            {
              $array['message'] = "status not updated";
              echo json_encode($array);
            }
            }
           }
        }
    }
}