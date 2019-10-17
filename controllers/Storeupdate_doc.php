<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Storeupdate_doc extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function docupdate()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $params           = (array) json_decode(file_get_contents('php://input'), true);
            $corton_box_count = isset($params[0]['boxes']) ? $params[0]['boxes'] : "";
            $stock_qty        = isset($params[0]['stock']) ? $params[0]['stock'] : "";
            $ODN_no           = isset($params[0]['Outward']) ? $params[0]['Outward'] : "";
            $comment      = isset($params[0]['comment']) ? $params[0]['comment'] : "";

            $email            = isset($params[1]['email_address']) ? $params[1]['email_address'] : "";
            $apprl_code       = isset($params[1]['approval_code']) ? $params[1]['approval_code'] : "";
            $stscount         = isset($params[1]['status_count']) ? $params[1]['status_count'] : "";
            $excel         = isset($params[2])? $params[2]: "";
            $encodedexcel =  json_encode($excel);
            if ($corton_box_count != "") {
                if ($stock_qty != "" && $ODN_no != "") {
                    $result = $this->Model_connectdb->insrtform($corton_box_count, $stock_qty, $ODN_no, $email, $apprl_code,$comment,$encodedexcel);
                    //    $this->load->library('stsupdate');
                    //    $this->load->statusupdate($apprl_code);
                    if ($result) {
                        $status     = $this->Model_connectdb->getstsmsg($stscount + 1);
                        $status_msg = $status[0]['short_form'];
                        $data1       = $this->Model_connectdb->stsupdate($email,$apprl_code, $status_msg);
                        if ($data1 != "") {
                            // $data = $this->Model_connectdb->getODN_no($apprl_code);
                                $array['status_code']      = 200;
                                $array['message']          = "Outward document number upload success";
                                // $array['corton_count']     = $data[0]['corton_count'];
                                // $array['stock_count']      = $data[0]['stock_count'];
                                // $array['outwarddocno_sno'] = $data[0]['outwarddocno_sno'];
                                // $array['inserted_at']      = $data[0]['inserted_at'];
                                // $result[$i]                = $array;
                                // $array[sizeof($array)+1]['message'] = "uploaded and email triggered";
                                // $array[sizeof($array)+1]['status_code'] = 200;
                                echo json_encode($array);
                            } 
                        } else {
                            $array['message']     = "data not updated email not triggered";
                            $array['status_code'] = 422;
                            echo json_encode($array);
                        }
                    }
                    else {
                    $array['message']     = "stock quantity or Outward document number is missing";
                    $array['status_code'] = 422;
                    echo json_encode($array);
                }
                }
                else {
                $array['message']     = "corton box count is missing";
                $array['status_code'] = 422;
                echo json_encode($array);
            }
                
            } 
         else {
            $array['message']     = "Method not allowed";
            $array['status_code'] = 422;
            echo json_encode($array);
        }
    }
}
 