<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mytasks extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function taskofstatus()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $status_count = isset($_GET['status_count']) ? $_GET['status_count'] : "";
            $role = isset($_GET['role']) ? $_GET['role'] : "";
            $store_code = isset($_GET['store_code']) ? $_GET['store_code'] : "";
            $email = isset($_GET['email']) ? $_GET['email'] : "";
            $transaction_type = isset($_GET['transaction_type']) ? $_GET['transaction_type'] : "";


            $nearbystores = $this->Model_connectdb->getnearstoresofwarehouse($store_code);
            for ($i=0; $i < sizeof($nearbystores); $i++) { 
                $rcvingstrstring = $rcvingstrstring.",'".$nearbystores[$i]['store_code']."'";
            }

            $storestring = isset($rcvingstrstring) ? substr($rcvingstrstring, 1) : "''";

            $status     = $this->Model_connectdb->getstsmsg($status_count); //getting status 
            $status_msg = $status[0]['short_form'];

            $rtvreturntypes =$this->Model_connectdb->getrtvreturntypes($brand);
            for ($i=0; $i < sizeof($rtvreturntypes); $i++) { 
                $rtvreturntypestr = $rtvreturntypestr.",'".$rtvreturntypes[$i]['return_type']."'";
            }

            $vendorcodes = $this->Model_connectdb->getvendorsforwarehouse($store_code);
            for ($i=0; $i < sizeof($vendorcodes); $i++) { 
                $vendorcodesstr = $$vendorcodesstr.",'".$vendorcodes[$i]['warehouse_code']."'";
            }

            $vendorcodes = isset($vendorcodesstr) ? substr($vendorcodesstr,1) : "''";
            $data = $this->Model_connectdb->returnsofstatus($status_msg,$role,$store_code,$email,$storestring,$transaction_type,$vendorcodes,substr($rtvreturntypestr,1));

            for($i = 0; $i<sizeof($data);$i++)
                {
                    $returns[$i]['issuing_store_code'] = $data[$i]['issuing_store_code'];
                    $returns[$i]['issuing_store_name'] = $data[$i]['issuing_store_name'];
                    $returns[$i]['duplicate_approvalcode'] = $data[$i]['duplicate_approvalcode'];
                    
                    $returns[$i]['receiving_strwhr_code'] = $data[$i]['receiving_strwhr_code'];
                    $returns[$i]['receiving_store_name'] = $data[$i]['receiving_store_name'];
                    $returns[$i]['transaction_type'] = $data[$i]['transaction_type'];
                    $returns[$i]['updated_at'] = $data[$i]['updated_at'];
                    
                    $trntype = $data[$i]['transaction_type'];


                    $trndata = $this->Model_connectdb->transactiontype($trntype);
                    // $wf = $trndata[0]['return_workflow'];
                    $returns[$i]['work_flow'] =  $trndata[0]['return_workflow'];

                    $strtype = $this->Model_connectdb->selectstorenm($data[$i]['issuing_store_code']);
                    $returns[$i]['store_type'] = $strtype[0]['store_type'];

                    $returns[$i]['status'] = $data[$i]['status'];
                   
                    // $crtncount = $this->Model_connectdb->OdnData(null,$data[$i]['approval_code']);
                    $returns[$i]['corton_count'] = $data[$i]['corton_count'];
                    $returns[$i]['stock_count'] = $data[$i]['stock_count'];
                    $returns[$i]['outwarddocno_sno'] = $data[$i]['outwarddocno_sno'];
                    $returns[$i]['transporter'] = $data[$i]['transporter'];

                    $statusdisc =$data[$i]['status'];
                    $stscount = $this->Model_connectdb->getstatuscount($statusdisc);
                    $returns[$i]['status_count'] = $stscount[0]['status_count'];
                    $returns[$i]['approval_code'] = $data[$i]['approval_code'];
                    $returns[$i]['date'] = $data[$i]['dateof_initiated'];
                    $returns[$i]['total_qty'] = $data[$i]['qtyof_approvalcode'];
                    $returns[$i]['e_waybill'] = $data[$i]['ebill_image'];
                    $returns[$i]['lr_copy'] = $data[$i]['lr_copy_image'];
                    $returns[$i]['credit_note'] = $data[$i]['credit_note_image'];
                    $returns[$i]['invoice'] = $data[$i]['invoice_pdf'];
                    $returns[$i]['pod_image'] = $data[$i]['pod_image'];
                    $returns[$i]['transaction_report_path'] = $data[$i]['transaction_report_path'];
                    $returns[$i]['store_returns_doc'] = json_decode($data[$i]['store_returns_doc']);
                    
                }
            if($returns){
                $array[0]['status_code'] = 200;
                $array[1] = $returns;
            echo json_encode($array);
                        }
                        else
                        {
                            $array['message'] = "Nothing pending in this status";
                            $array['status_code'] = 304;
                            echo json_encode($array);
                        }
        }
        else
        {
            $array['message'] = "Method not allowed";
            $array['status_code'] = 405;
            echo json_encode($array);
        }
    }
}