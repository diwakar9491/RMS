<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Trnclosedreturns extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function trnclosed()
    {


//same api for planner,store,warehouse completed returns---------------------

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
           $email = isset($_GET['email_address']) ? $_GET['email_address'] : "";
           $brand = isset($_GET['brand']) ? $_GET['brand'] : "";
           $store_code = isset($_GET['store_code']) ? $_GET['store_code'] : "";
           $role = isset($_GET['role']) ? $_GET['role'] : "";

           $resultarr = array();
            
            $nearbystores = $this->Model_connectdb->getnearstoresofwarehouse($store_code);
            for ($i=0; $i < sizeof($nearbystores); $i++) 
            { 
                $rcvingstrstring = $rcvingstrstring.",'".$nearbystores[$i]['store_code']."'";
            }

            $vendorcodes = $this->Model_connectdb->getvendorsforwarehouse($store_code);
            for ($i=0; $i < sizeof($vendorcodes); $i++) { 
                $vendorcodesstr = $vendorcodesstr.",'".$vendorcodes[$i]['warehouse_code']."'";
            }

            $storecodes = isset($rcvingstrstring) ? substr($rcvingstrstring,1) : "''";
            $vendorcodes = isset($vendorcodesstr) ? substr($vendorcodesstr,1) : "''";

           $data = $this->Model_connectdb->gettrnclosedreturns($email,$brand,$store_code,$role,$storecodes,$vendorcodes);
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
        $array['message'] = "Nothing closed";
        $array['satus_code'] = 304;
        echo json_encode($array);
        }
    }
    }
}