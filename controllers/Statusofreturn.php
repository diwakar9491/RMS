<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Statusofreturn extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function status()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $approval_code = isset($_GET['approval_code']) ? $_GET['approval_code'] : "";
            if($approval_code != "")
            {
                $data = $this->Model_connectdb->gettranhistory($approval_code);
                $str = $data[0]['tranhistory'];
                $issuing_store_code = $data[0]['issuing_store_code'];
                $storedata = $this->Model_connectdb->selectstorenm($issuing_store_code);
                $store_type = $storedata[0]['store_type'];
                $tranhistoryarr = explode(",",$str);
                if (!strpos($str, 'CAN')) 
                {
                if ($store_type == 'COCO') {
                    $response['Open'] = $tranhistoryarr[0];
                    $response['PI'] = $tranhistoryarr[1];
                    $response['SP'] = $tranhistoryarr[2];
                    $response['DC'] = $tranhistoryarr[3];
                    $response['TAC'] = null;
                    $response['E-waybill'] = $tranhistoryarr[4];
                    $response['DESP'] = $tranhistoryarr[5];
                    $response['SR'] = $tranhistoryarr[6];
                    $response['SC'] = $tranhistoryarr[7];
                    if (strpos($tranhistoryarr[8], 'IC')) {
                    $response['IC'] = $tranhistoryarr[8];
                    $response['ICD'] = null;
                    }
                    else
                    {
                    $response['ICD'] = $tranhistoryarr[8];
                    $response['IC'] = null;
                    }
                }
                elseif ($store_type == 'FOCO') {
                    $response['Open'] = $tranhistoryarr[0];
                    $response['PI'] = $tranhistoryarr[1];
                    $response['SP'] = $tranhistoryarr[2];
                    $response['DC'] = $tranhistoryarr[3];                  
                    $response['TAC'] = $tranhistoryarr[4];
                    $response['E-waybill'] = $tranhistoryarr[5];
                    $response['DESP'] = $tranhistoryarr[6];
                    $response['SR'] = $tranhistoryarr[7];
                    $response['SC'] = $tranhistoryarr[8];
                    if (strpos($tranhistoryarr[9], 'IC')) {
                    $response['IC'] = $tranhistoryarr[9];
                    $response['ICD'] = null;
                    }
                    else
                    {
                    $response['ICD'] = $tranhistoryarr[9];
                    $response['IC'] = null;
                    }
                }
            }
            else
            {
                    if ($store_type == 'COCO') {
                    $response['Open'] = $tranhistoryarr[0];
                    $response['PI'] = $tranhistoryarr[1];

                    $response['SP'] = $tranhistoryarr[2];

                    $response['DC'] = $tranhistoryarr[3];

                    $response['E-waybill'] = $tranhistoryarr[4];

                    $response['CAN'] = $tranhistoryarr[5];
                }
                elseif ($store_type == 'FOCO') {
                    $response['Open'] = $tranhistoryarr[0];
                    $response['PI'] = $tranhistoryarr[1];

                    $response['SP'] = $tranhistoryarr[2];

                    $response['DC'] = $tranhistoryarr[3];
                    
                    $response['TAC'] = $tranhistoryarr[4];

                    $response['E-waybill'] = $tranhistoryarr[5];
                    $response['CAN'] = $tranhistoryarr[6];
            }
        }

                if(sizeof($response) > 0)
                {
                    echo json_encode($response);
                }
                else
                {
                $array['message'] = "Please provide a valid approval code";
                $array['status_code'] = 304;
                echo json_encode($array);
                }
            }
         }
    }
}

