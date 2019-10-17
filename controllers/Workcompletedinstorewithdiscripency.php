<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Workcompletedinstorewithdiscripency extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }

    function completed()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $store_code = isset($_GET['issuing_store_code']) ? $_GET['issuing_store_code'] : "";
        if($store_code != "")
        {
             $data = $this->Model_connectdb->workcpmpletedinstorewithdisc($store_code);
            // echo json_encode($data);
            for($i = 0; $i<sizeof($data);$i++)
                {
            $returns[$i]['issuing_store_code'] = $data[$i]['issuing_store_code'];
                    $returns[$i]['issuing_store_name'] = $data[$i]['issuing_store_name'];
                    $returns[$i]['receiving_strwhr_code'] = $data[$i]['receiving_strwhr_code'];
                    $returns[$i]['receiving_store_name'] = $data[$i]['receiving_store_name'];
                    $returns[$i]['transaction_type'] = $data[$i]['transaction_type'];
                    $trntype = $data[$i]['transaction_type'];

                    $trndata = $this->Model_connectdb->transactiontype($trntype);
                    // $wf = $trndata[0]['return_workflow'];
                    $returns[$i]['work_flow'] =  $trndata[0]['return_workflow'];

                    $strtype = $this->Model_connectdb->selectstorenm($data[$i]['issuing_store_code']);
                    $returns[$i]['store_type'] = $strtype[0]['store_type'];

                    $returns[$i]['status'] = $data[$i]['status'];
                    
                    $statusdisc =$data[$i]['status'];
                    $stscount = $this->Model_connectdb->getstatuscount($statusdisc);
                    $returns[$i]['status_count'] = $stscount[0]['status_count'];
                    $returns[$i]['approval_code'] = $data[$i]['approval_code'];
                    $returns[$i]['date'] = $data[$i]['dateof_initiated'];
                    $returns[$i]['total_qty'] = $data[$i]['qtyof_approvalcode'];
                
            }
            if($returns){
                $array[0]['status_code'] = 200;
                $array[1] = $returns;
            echo json_encode($array);
                        }
                        else
                        {
                            $array['status_code'] = 304;
                            $array['message'] = 'Nothing pending in this status';
                            echo json_encode($array);
                        }
        }
        }
    }
}