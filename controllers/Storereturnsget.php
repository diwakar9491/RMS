<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Storereturnsget extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
function storeget()
{
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-llow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $issustrcode      =  $_GET['store_code'];
    // $apprlcode = $GET['approval_code'];
    $apprcodearr = array();
    $array = array();
    $response = array();
   // $apprlcodes = $this->Model_connectdb->distapprl($rcvstrcode);
   // for($i=0;$i<sizeof($apprlcodes);$i++)
   // {
   //  $apprcodearr[$i] = "'".$apprlcodes[$i]['approval_code'] ."'"; 
   // }
   // $straprvl = implode($apprcodearr,",");
   $strreturnsdata = $this->Model_connectdb->storeapprlget($issustrcode);//,$straprvl);
   for($i=0;$i<sizeof($strreturnsdata);$i++)
   {
    $array['approval_code'] = $strreturnsdata[$i]['approval_code'];
    $apprvlcode = $strreturnsdata[$i]['approval_code'];
    $array['issuing_store_code'] = $strreturnsdata[$i]['issuing_store_code'];
    $array['issuing_store_name'] = $strreturnsdata[$i]['issuing_store_name'];
    $array['receiving_strwhr_code'] = $strreturnsdata[$i]['receiving_strwhr_code'];
    $array['receiving_store_name'] = $strreturnsdata[$i]['receiving_store_name'];
    $array['status'] = $strreturnsdata[$i]['status'];
    $array['date'] = $strreturnsdata[$i]['dateof_initiated'];
    $array['total_qty'] = $strreturnsdata[$i]['qtyof_approvalcode'];
    $array['updated_at'] = $strreturnsdata[$i]['updated_at'];

    $strtype = $this->Model_connectdb->selectstorenm($strreturnsdata[$i]['issuing_store_code']);
    $array['store_type'] = $strtype[0]['store_type'];

    $array['corton_count'] = $strreturnsdata[$i]['corton_count'];
    $array['stock_count'] = $strreturnsdata[$i]['stock_count'];
    $array['outwarddocno_sno'] = $strreturnsdata[$i]['outwarddocno_sno'];
    $array['transporter'] = $strreturnsdata[$i]['transporter'];


    $statusdisc = $strreturnsdata[$i]['status'];
    $stscount = $this->Model_connectdb->getstatuscount($statusdisc);
    $array['status_count'] = $stscount[0]['status_count'];
    $array['transaction_type'] = $strreturnsdata[$i]['transaction_type'];
    $array['e_waybill'] = $strreturnsdata[$i]['ebill_image'];
    $array['lr_copy'] = $strreturnsdata[$i]['lr_copy_image'];
    $array['credit_note'] = $strreturnsdata[$i]['credit_note_image'];
    $array['invoice'] = $strreturnsdata[$i]['invoice_pdf'];
    $array['pod_image'] = $strreturnsdata[$i]['pod_image'];
    $array['transaction_report_path'] = $strreturnsdata[$i]['transaction_report_path'];

    $response[$i] = $array;
   }
   echo json_encode($response);
}
}
}