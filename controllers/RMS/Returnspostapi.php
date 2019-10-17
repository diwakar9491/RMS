<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Returnspostapi extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
function Returnspost()
{
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$params = (array) json_decode(file_get_contents('php://input'), TRUE);
$postemail = isset($params[0]['email_address']) ? $params[0]['email_address'] : "";
$posttoken = isset($params[0]['token']) ? $params[0]['token'] : "";
if($posttoken != "" && $postemail != "")
{
    $dbAccess_token = $this->Model_connectdb->gettoken($posttoken);
    $Access_token = $dbAccess_token[0]['token'];
    if($posttoken == $Access_token)
    {

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = "";
    $flagarr = array();
    $approvalcode = 0;
    $returnsdataarr = array();
    $flage        = "";
    $excell_noqry = $this->Model_connectdb->selectreturnsexcelno();
    $excell_no = $excell_noqry[0]['max']+1;
    $initiatedat  = date('Y-m-d H:i:s'); //$params['initiatedat'];
    $errorexcell_noqry = $this->Model_connectdb->selecterrorlogexcelno();
    $errorexcell_no = $errorexcell_noqry[0]['max']+1;
    
    $params = (array) json_decode(file_get_contents('php://input'), true);
    // foreach($params  as $row){
    //     foreach($row as $value){
    //         if($value == ""){
    //             $j++;
    //             $flage[$j] = 0;
    //         }
    //         else{

    //         }
    //     }
    // }
    // die;
            for ($i = 1; $i < sizeof($params); $i++) 
        {
            $fromstrcode = isset($params[$i]['ISSUING STORE CODE']) ? $params[$i]['ISSUING STORE CODE'] : "";
            $fromstrname = isset($params[$i]['ISSUING STORE NAME']) ? $params[$i]['ISSUING STORE NAME'] : "";
            $barcode     = isset($params[$i]['BARCODE']) ? $params[$i]['BARCODE'] : "";
            $line        = isset($params[$i]['LINE']) ? $params[$i]['LINE'] : "";
            $stylecode   = isset($params[$i]['STYLE CODE']) ? $params[$i]['STYLE CODE'] : "";
            $size        = isset($params[$i]['SIZE']) ? $params[$i]['SIZE'] : "";
            $qty         = isset($params[$i]['QTY']) ? $params[$i]['QTY'] : "";
            $rcvstrcode  = isset($params[$i]['RECEIVING STORE CODE']) ? $params[$i]['RECEIVING STORE CODE'] : "";
            $rcvstrname  = isset($params[$i]['RECEIVING STORE NAME']) ? $params[$i]['RECEIVING STORE NAME'] : "";
            $trantype    = isset($params[$i]['TRANSACTION TYPE']) ? $params[$i]['TRANSACTION TYPE'] : "";
            $plannerid    = isset($params[$i]['PLANNER ID']) ? $params[$i]['PLANNER ID'] : "";
            $plannername    = isset($params[$i]['PLANNER NAME']) ? $params[$i]['PLANNER NAME'] : "";
            if($plannername != "")
            {
            if($plannerid != "")
            {
           if ($fromstrcode != "") 
            {
                if ($fromstrname != "") 
                {
                    if ($barcode != "") 
                    {
                        if ($line != "") 
                        {
                            if ($stylecode != "") 
                            {
                                if ($size != "") 
                                {
                                    if ($qty != "") 
                                    {
                                        if ($rcvstrcode != "") 
                                        {
                                            if ($rcvstrname != "") 
                                            {
                                                if ($trantype != "") 
                                                {

                                                    $flage = '1';

                                                } 
                                                else {
                                                    $flage            = '0';
                                                    $error = "some columns are empty";
                                                }
                                            }
                                             else {
                                                $flage            = '0';
                                                $error = "some columns are empty";
                                            }
                                        } 
                                        else {
                                            $flage            = '0';
                                            $error = "some columns are empty";
                                        }
                                    } 
                                    else {
                                        $flage            = '0';
                                        $error = "some columns are empty";
                                    }
                                } 
                                else {
                                    $flage            = '0';
                                    $error = "some columns are empty";                                }
                            } 
                            else {
                                $flage            = '0';
                                $error = "some columns are empty";
                            }
                        }
                         else {
                            $flage            = '0';
                            $error = "some columns are empty";                        }
                    } 
                    else {
                        $flage            = '0';
                        $error = "some columns are empty";
                    }
                } 
                else {
                    $flage            = '0';
                    $error = "some columns are empty";                }
            } 
            else {
                $flage            = '0';
                $error = "some columns are empty";
            }
        }
        else {
            $flage            = '0';
            $error = "some columns are empty";
        }
    }
    else {
        $flage            = '0';
        $error = "some columns are empty";
    }
    $flagarr[$i] = $flage;
        }
if(!in_array("0",$flagarr))
{
$approvalcodeqry =$this->Model_connectdb->selectapprovalcode();
$dbapprovalcode = $approvalcodeqry[0]['max'];
$trmcode = substr($dbapprovalcode,3);
$approvalcode = (int)$trmcode;
}
        for ($i = 0; $i < sizeof($params); $i++) 
        {
            $approvalcode++;
            $fromstrcode = isset($params[$i]['ISSUING STORE CODE']) ? $params[$i]['ISSUING STORE CODE'] : "";
            $fromstrname = isset($params[$i]['ISSUING STORE NAME']) ? $params[$i]['ISSUING STORE NAME'] : "";
            $barcode     = isset($params[$i]['BARCODE']) ? $params[$i]['BARCODE'] : "";
            $line        = isset($params[$i]['LINE']) ? $params[$i]['LINE'] : "";
            $stylecode   = isset($params[$i]['STYLE CODE']) ? $params[$i]['STYLE CODE'] : "";
            $size        = isset($params[$i]['SIZE']) ? $params[$i]['SIZE'] : "";
            $qty         = isset($params[$i]['QTY']) ? $params[$i]['QTY'] : "";
            $rcvstrcode  = isset($params[$i]['RECEIVING STORE CODE']) ? $params[$i]['RECEIVING STORE CODE'] : "";
            $rcvstrname  = isset($params[$i]['RECEIVING STORE NAME']) ? $params[$i]['RECEIVING STORE NAME'] : "";
            $trantype    = isset($params[$i]['TRANSACTION TYPE']) ? $params[$i]['TRANSACTION TYPE'] : "";
            $plannerid    = isset($params[$i]['PLANNER ID']) ? $params[$i]['PLANNER ID'] : "";
            $plannername    = isset($params[$i]['PLANNER NAME']) ? $params[$i]['PLANNER NAME'] : "";
            $apprlcode = $trantype.$approvalcode;
            if(in_array("0", $flagarr))
            {
                if($flagarr[$i] == '1')
                {
                    $errormsg = "";
                }
                elseif($flagarr[$i] == '0'){
                    $errormsg = "Some columns are empty";
                }
                    $errorcodegetqry = $this->Model_connectdb->selecterrorcode();
                    $errorcode = $errorcodegetqry[0]['error_code'];
                    $inserterrorqry = $this->Model_connectdb->insrterrorlog($errorcode,$fromstrcode,$fromstrname,$barcode,$line,$stylecode,$size,$qty,$rcvstrcode,$rcvstrname,$trantype,$plannerid,$plannername,$errormsg,$errorexcell_no);
            }
            else   
            {
                    $qry =$this->Model_connectdb->insrtreturns($fromstrcode,$fromstrname,$barcode,$line,$stylecode,$size,$qty,$rcvstrcode,$rcvstrname,$trantype,$excell_no,$apprlcode,$plannerid,$plannername);
                    if ($qry) {
                        $getreturnsqry =$this->Model_connectdb->selectreturns($excell_no);
                    }
                }
            }
            for ($i=0;$i<sizeof($getreturnsqry);$i++) {
                $j++;
                $array['message']           = "excell uploaded successfully";
                $array['issuing_store_code']        = $getreturnsqry[$i]['issuing_store_code'];
                $array['issuing_store_name']    = $getreturnsqry[$i]['issuing_store_name'];
                $array['barcoded']          = $getreturnsqry[$i]['barcode'];
                $array['line']      = $getreturnsqry[$i]['line'];
                $array['style_code'] = $getreturnsqry[$i]['style_code'];
                $array['size']      = $getreturnsqry[$i]['size'];
                $array['qty']     = $getreturnsqry[$i]['qty'];
                $array['receiving_strwhr_code']          = $getreturnsqry[$i]['receiving_strwhr_code'];
                $array['receiving_store_name']      = $getreturnsqry[$i]['receiving_store_name'];
                $array['transaction_type']            = $getreturnsqry[$i]['transaction_type'];
                $array['excel_no']     = $getreturnsqry[$i]['excel_no'];
                $array['status']     = $getreturnsqry[$i]['status'];
                $array['approval_code']     = $getreturnsqry[$i]['approval_code'];
                $array['planner_id']     = $getreturnsqry[$i]['planner_id'];
                $array['planner_name']     = $getreturnsqry[$i]['planner_name'];
                $returnsdataarr[$j-1] = $array;
            }
            if($returnsdataarr)
            {
            echo json_encode($returnsdataarr);
            }
            else{
                $array['message'] = "error while uploading excel please reffer error log for errors.";
                echo json_encode($array);
            }
        }
    }
    else{
        $array['message'] = "Invalid Access token";
        echo json_encode($array);
    }
}
else{
    $array['message'] = "Email id or Access token is missing";
    echo json_encode($array);
}
    } 
}


?>