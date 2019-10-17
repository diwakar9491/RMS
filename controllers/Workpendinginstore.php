<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Workpendinginstore extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function storework()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
           // $params = (array) json_decode(file_get_contents('php://input'), true);
        $receiving_strwhr_code = isset($_GET['receiving_strwhr_code']) ? $_GET['receiving_strwhr_code'] : "";
           $resultarr = array();
           // if($email != "" && $brand != "" && $approval_code == "" && $issuing_store_code == "")
           // {
           $nearbystores = $this->Model_connectdb->getnearstoresofwarehouse($receiving_strwhr_code);
           for ($i=0; $i < sizeof($nearbystores); $i++) { 
            $nearbystoresstring = $nearbystoresstring .",'".$nearbystores[$i]['store_code'] . "'";
           }

            $vendorcodes = $this->Model_connectdb->getvendorsforwarehouse($receiving_strwhr_code);
            for ($i=0; $i < sizeof($vendorcodes); $i++) { 
                $vendorcodesstr = $$vendorcodesstr.",'".$vendorcodes[$i]['warehouse_code']."'";
            }
           
           $vendorcodes = isset($vendorcodesstr) ? substr($vendorcodesstr,1) : "''";
           $string = isset($nearbystoresstring) ? substr($nearbystoresstring, 1) : "''";
           $data = $this->Model_connectdb->getworkpendinginstore($receiving_strwhr_code,$string,$vendorcodes);
            
            for($i=0;$i<sizeof($data);$i++)
            {
            $array['status_code'] = 200;
            $array['issuing_store_code']    = $data[$i]['issuing_store_code'];
            $array['issuing_store_name']    = $data[$i]['issuing_store_name'];
            $array['receiving_strwhr_code'] = $data[$i]['receiving_strwhr_code'];
            $array['receiving_store_name']  = $data[$i]['receiving_store_name'];
            $array['transaction_type']      = $data[$i]['transaction_type'];
            $array['approval_code']         = $data[$i]['approval_code'];
            $array['status']                = $data[$i]['status'];
            // $array['date']                = $data[$i]['dateof_initiated'];
            $array['updated_at']                = $data[$i]['updated_at'];

            $array['total_qty']                = $data[$i]['qtyof_approvalcode'];
            $statusdisc = $data[$i]['status'];
            $stscount = $this->Model_connectdb->getstatuscount($statusdisc);
            $array['status_count'] = $stscount[0]['status_count'];
            $resultarr[$i] = $array;
        }
        if($resultarr)
        {
        echo json_encode($resultarr);
        }
        else
        {
            $array['message'] = "Nothing pending yet";
            $array['status_code'] = 405;
            echo json_encode($array);
        }
    //}
        }
    }
}