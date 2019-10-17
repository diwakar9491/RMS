<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Masterdata_returnsget extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function getreturns()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
$array = array();
$brand = $_GET['brand'];

$data =$this->Model_connectdb->returnsfor_masterplanner($brand);
for($i=0;$i<sizeof($data);$i++)
{
                $array['issuing_store_code']    = $data[$i]['issuing_store_code'];
                $array['issuing_store_name']    = $data[$i]['issuing_store_name'];
                $array['receiving_strwhr_code'] = $data[$i]['receiving_strwhr_code'];
                $array['receiving_store_name']  = $data[$i]['receiving_store_name'];
                $array['transaction_type']      = $data[$i]['transaction_type'];
                $array['approval_code']         = $data[$i]['approval_code'];
                $array['status']                = $data[$i]['status'];
                $array['date']                = $data[$i]['dateof_initiated'];
                $array['total_qty']                = $data[$i]['qtyof_approvalcode'];
                $array['duplicate_approvalcode']    = $data[$i]['duplicate_approvalcode'];
                $array['corton_count']    = $data[$i]['corton_count'];
                $array['stock_count']    = $data[$i]['stock_count'];
                $array['outwarddocno_sno']    = $data[$i]['outwarddocno_sno'];
                $array['transporter']    = $data[$i]['transporter'];
                $array['transaction_report_path']    = $data[$i]['transaction_report_path'];
                $array['ebill_image']    = $data[$i]['ebill_image'];
                $array['lr_copy_image']    = $data[$i]['lr_copy_image'];
                $array['credit_note_image']    = $data[$i]['credit_note_image'];
                $array['invoice_pdf']    = $data[$i]['invoice_pdf'];
                $array['pod_image']    = $data[$i]['pod_image'];

                $statusdisc =$getopenreturnsqry[$i]['status'];
                $stscount = $this->Model_connectdb->getstatuscount($statusdisc);
                $array['status_count'] = $stscount[0]['status_count'];

                $openreturnsarr[$i] = $array;
}
echo json_encode($openreturnsarr);
}
else{
    $array['message'] = "Method not allowed";
    $arrray['status_code'] = 405;
    echo json_encode($array);
}
    }
}