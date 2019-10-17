<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Aprvlproreturns extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function provisereturns()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $j = 0;
    $returnsdataarr = array();
    $array = array();
    $store_code = $_GET['store_code'];
    $approvalcode = $_GET['approval_code'];
    $data = $this->Model_connectdb->apprwhrreturns($store_code,$approvalcode);
    for($i=0;$i<sizeof($data);$i++)
        {
            $array['issuing_store_code'] = $data[$i]['issuing_store_code'];
            $array['issuing_store_name'] = $data[$i]['issuing_store_name'];
            $array['barcode'] = $data[$i]['barcode'];
            $array['line'] = $data[$i]['line'];
            $array['style_code'] = $data[$i]['style_code'];
            $array['size'] = $data[$i]['size'];
            $array['qty'] = $data[$i]['qty'];
            $array['receiving_strwhr_code'] = $data[$i]['receiving_strwhr_code'];
            $array['receiving_store_name'] = $data[$i]['receiving_store_name'];
            $array['transaction_type'] = $data[$i]['transaction_type'];
            $array['excel_no'] = $data[$i]['excel_no'];
            $array['status'] = $data[$i]['status'];

            $statusdisc =$data[$i]['status'];
            $stscount = $this->Model_connectdb->getstatuscount($statusdisc);
            $array['status_count'] = $stscount[0]['status_count'];

            $array['approval_code'] = $data[$i]['approval_code'];
            $array['created_at'] = $data[$i]['created_at'];
            $returnsdataarr[$i] = $array;
        }
 echo json_encode($returnsdataarr);

}
        }
    }
