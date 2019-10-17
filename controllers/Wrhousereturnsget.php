<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Wrhousereturnsget extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function warehouseget()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // $params = (array) json_decode(file_get_contents('php://input'),true);

            $wrcode = $_GET['store_code'];
            $brand = $_GET['brand'];

            $array = array();
            $result = array();
            $nearbystores = $this->Model_connectdb->getnearstoresofwarehouse($wrcode);
            // $rtvreturntypes =$this->Model_connectdb->getrtvreturntypes($brand);
            $vendorcodes = $this->Model_connectdb->getvendorsforwarehouse($wrcode);
            for ($i=0; $i < sizeof($vendorcodes); $i++) { 
                $vendorcodesstr = $vendorcodesstr.",'".$vendorcodes[$i]['warehouse_code']."'";
            }

            for ($i=0; $i < sizeof($nearbystores); $i++) { 
                $rcvingstrstring = $rcvingstrstring.",'".$nearbystores[$i]['store_code']."'";
            }

            // $storecodesstring = isset($rcvingstrstring) ? $rcvingstrstring . ",'".$wrcode."'" : ",''". ",'".$wrcode."'";
            $storecodes = isset($rcvingstrstring) ? substr($rcvingstrstring,1) : "''";
            $vendorcodes = isset($vendorcodesstr) ? substr($vendorcodesstr,1) : "''";

            $data = $this->Model_connectdb->getapprlwrhouse($wrcode,$storecodes,$vendorcodes);
            for($i=0;$i<sizeof($data);$i++)
            {
            $array['issuing_store_code']    = $data[$i]['issuing_store_code'];
            $array['issuing_store_name']    = $data[$i]['issuing_store_name'];
            $array['receiving_strwhr_code'] = $data[$i]['receiving_strwhr_code'];
            $array['receiving_store_name']  = $data[$i]['receiving_store_name'];
            $array['transaction_type']      = $data[$i]['transaction_type'];
            $array['total_qty']      = $data[$i]['qtyof_approvalcode'];
            $array['date']      = $data[$i]['dateof_initiated'];
            $array['updated_at']      = $data[$i]['updated_at'];


            $trntype  = $data[$i]['transaction_type'];

            $trndata = $this->Model_connectdb->transactiontype($trntype);
            // $wf = $trndata[0]['return_workflow'];
            $array['work_flow'] =  $trndata[0]['return_workflow'];
            $array['approval_code']         = $data[$i]['approval_code'];
            $array['status']                = $data[$i]['status'];
            $statusdisc = $data[$i]['status'];
            $stscount = $this->Model_connectdb->getstatuscount($statusdisc);
            $array['status_count'] = $stscount[0]['status_count'];
            $strtype = $this->Model_connectdb->selectstorenm($data[$i]['issuing_store_code']);
                 $apprvlcode    = $data[$i]['approval_code'];

            // $crtncount = $this->Model_connectdb->OdnData(null,$apprvlcode);
            $array['corton_count'] = $data[$i]['corton_count'];
            $array['stock_count'] = $data[$i]['stock_count'];
            $array['outwarddocno_sno'] = $data[$i]['outwarddocno_sno'];
            $array['transporter'] = $data[$i]['transporter'];

            $array['store_type'] = $strtype[0]['store_type'];

            $array['e_waybill'] = $data[$i]['ebill_image'];
            $array['lr_copy'] = $data[$i]['lr_copy_image'];
            $array['credit_note'] = $data[$i]['credit_note_image'];
            $array['invoice'] = $data[$i]['invoice_pdf'];
            $array['pod_image'] = $data[$i]['pod_image'];
            $array['transaction_report_path'] = $data[$i]['transaction_report_path'];
            $array['store_returns_doc'] = json_decode($data[$i]['store_returns_doc']);


            $result[$i] = $array;
            }
            echo json_encode($result);
        }
    }
}