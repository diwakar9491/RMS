<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mytaskswithfiles extends CI_Controller
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

            $status     = $this->Model_connectdb->getstsmsg($status_count); //getting status 
            $status_msg = $status[0]['short_form'];

            $data = $this->Model_connectdb->returnsofstatus($status_msg,$role,$store_code,$email);
            for($i = 0; $i<sizeof($data);$i++)
                {
                    $returns[$i]['issuing_store_code'] = $data[$i]['issuing_store_code'];
                    $returns[$i]['issuing_store_name'] = $data[$i]['issuing_store_name'];
                    $returns[$i]['duplicate_approvalcode'] = $data[$i]['duplicate_approvalcode'];
                    
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
                   
                    $apprvlcode = $data[$i]['approval_code'];
                    $crtncount = $this->Model_connectdb->OdnData(null,$apprvlcode);
                    $array['corton_count'] = $crtncount[0]['corton_count'];
                    $array['stock_count'] = $crtncount[0]['stock_count'];
                    $array['outwarddocno_sno'] = $crtncount[0]['outwarddocno_sno'];

                    
                    $statusdisc =$data[$i]['status'];
                    $stscount = $this->Model_connectdb->getstatuscount($statusdisc);
                    $returns[$i]['status_count'] = $stscount[0]['status_count'];
                    $returns[$i]['approval_code'] = $data[$i]['approval_code'];
                    $returns[$i]['date'] = $data[$i]['dateof_initiated'];
                    $returns[$i]['total_qty'] = $data[$i]['qtyof_approvalcode'];

                    $approvalcode = $data[$i]['approval_code'];

                    $filepaths = $this->Model_connectdb->getfilepaths($approvalcode);
                    $ebill = json_encode(explode(",",$filepaths[0]['ebill_image']));
                    $lr_copy = json_encode(explode(",",$filepaths[0]['lr_copy_image']));
                    $credit_note_image = json_encode(explode(",", $filepaths[0]['lr_copy_image']));
                    $invoice = json_encode(explode(",", $filepaths[0]['invoice_pdf']));
                    $pod_image = json_encode(explode("," , $filepaths[0]['pod_image']));
                    $returns[$i]['e_waybill'] = $ebill;
                    $returns[$i]['lr_copy'] = $lr_copy;
                    $returns[$i]['credit_note'] = $credit_note_image;
                    $returns[$i]['invoice'] = $invoice;
                    $returns[$i]['pod_image'] = $pod_image;
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