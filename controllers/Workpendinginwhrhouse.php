<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Workpendinginwhrhouse extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function whrhousework()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
           $issuing_store_code = isset($_GET['issuing_store_code']) ? $_GET['issuing_store_code'] : "";
           
           $resultarr = array();
           $data = $this->Model_connectdb->getworkpendinginwhrhouse($email,$brand,$issuing_store_code);
           if(sizeof($data) != 0)
           {
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
            $array['total_qty']                = $data[$i]['qtyof_approvalcode'];
            $array['updated_at']                = $data[$i]['updated_at'];
            
            $statusdisc = $data[$i]['status'];
            $stscount = $this->Model_connectdb->getstatuscount($statusdisc);
            $array['status_count'] = $stscount[0]['status_count'];
            $resultarr[$i] = $array;
        }
        echo json_encode($resultarr);
    }
    else
    {
        $array['message'] = "No work initiated yet";
        $array['satus_code'] = 304;
        echo json_encode($array);
        }
    }
    }
}