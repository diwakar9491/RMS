<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ApprvlcodeRCreturns extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function receiverget()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $receivercode = $_GET['store_code'];
            $approvalcode = $_GET['approval_code'];
            $data = $this->Model_connectdb->getreceivaerreturns($approvalcode,$receivercode);
            $ebildata = $this->Model_connectdb->ebildata($store_code,$approvalcode);
		    $odndata = $this->Model_connectdb->OdnData($store_code,$approvalcode);
		    $transporter = $ebildata[0]['transporter'];
		    $ebill_no = $ebildata[0]['ebill_no'];
		    $lr_no = $ebildata[0]['lr_no'];
		    $creditnote = $ebildata[0]['credit_note'];
		    $corton_count = $odndata[0]['corton_count'];
		    $stock_count = $odndata[0]['stock_count'];
		    $outwarddocno_sno = $odndata[0]['outwarddocno_sno'];
            $result = array();

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
            $array['status'] = $data[$i]['status'];
            
            $statusdisc =$data[$i]['status'];
            $stscount = $this->Model_connectdb->getstatuscount($statusdisc);
            $array['status_count'] = $stscount[0]['status_count'];

            $array['approval_code'] = $data[$i]['approval_code'];
            $array['created_at'] = $data[$i]['created_at'];

            $array['transporter'] = $transporter;
            $array['ebill_no'] = $ebill_no;
            $array['lr_no'] = $lr_no;
            $array['corton_count'] = $corton_count;
            $array['stock_count'] = $stock_count;
            $array['outwarddocno_sno'] = $outwarddocno_sno;
            $result[$i] = $array;
            }
            echo json_encode($result);
        }
    }
}