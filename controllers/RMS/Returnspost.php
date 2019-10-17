<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Returnspost extends CI_Controller
{
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $j                 = 0;
            $error             = "";
            $errorarr          = array();
            $flagarr           = array();
            $approvalcode      = 0;
            $returnsdataarr    = array();
            $flage             = 1;
            $excell_noqry      = $this->Model_connectdb->selectreturnsexcelno();
            $excell_no         = $excell_noqry[0]['max'] + 1;
            $initiatedat       = date('Y-m-d H:i:s'); //$params['initiatedat'];
            $errorexcell_noqry = $this->Model_connectdb->selectreturnsexcelno();
            $errorexcell_no    = $errorexcell_noqry[0]['max'] + 1;
            
            $params = (array) json_decode(file_get_contents('php://input'), true);
            
            for ($i = 0; $i < sizeof($params); $i++) {
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
                if ($fromstrcode != "") {
                } else {
                    $flage = '0';
                    $error = "issuing store code is missing";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error;
                }
                if ($fromstrname != "") {
                } else {
                    $flage = '0';
                    $error = "issuing store name is missing";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error;
                }
                
                if ($barcode != "") {
                } else {
                    $flage = '0';
                    $error = "barcode is missing";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error;
                }
                if ($line != "") {
                } else {
                    $flage = '0';
                    $error = "line is missing";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error;
                }
                if ($stylecode != "") {
                } else {
                    $flage = '0';
                    $error = "style code is missing";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error;
                }
                if ($size != "") {
                } else {
                    $flage = '0';
                    $error = "size is missing";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error;
                }
                if ($qty != "") {
                } else {
                    $flage = '0';
                    $error = "quantity is missing";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error;
                }
                if ($rcvstrcode != "") {
                } else {
                    $flage = '0';
                    $error = "receving store code is missing";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error;
                }
                if ($rcvstrname != "") {
                } else {
                    $flage = '0';
                    $error = "receiving store name is missing";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error;
                }
                if ($trantype != "") {                    
                   
                } else {
                    $flage = '0';
                    $error = "transactoin type is missig";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error;
                }
                $flagarr[$i]  = $flage;
                $errorarr[$i] = $errorarr[$i];
            }
            if (!in_array("0", $flagarr)) {
                $approvalcodeqry = $this->Model_connectdb->selectapprovalcode();
                $dbapprovalcode  = $approvalcodeqry[0]['max'];
                $trmcode         = substr($dbapprovalcode, 3);
                $approvalcode    = (int) $trmcode;
            }
            for ($i = 0; $i < sizeof($params); $i++) {
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
                $apprlcode   = $trantype . $approvalcode;
                if (in_array("0", $flagarr)) {
                    if ($flagarr[$i] == '1') {
                        $errormsg = "";
                    } elseif ($flagarr[$i] == '0') {
                        $errormsg = substr($errorarr[$i],1);
                    }
                    $errorcodegetqry = $this->Model_connectdb->selecterrorcode();
                    $errorcode       = $errorcodegetqry[0]['error_code'];
                    $inserterrorqry  = $this->Model_connectdb->insrterrorlog($errorcode, $fromstrcode, $fromstrname, $barcode, $line, $stylecode, $size, $qty, $rcvstrcode, $rcvstrname, $trantype, $errormsg, $errorexcell_no);
                } else {
                    //date('d/m/y');
                    $qry = $this->Model_connectdb->insrtreturns($fromstrcode, $fromstrname, $barcode, $line, $stylecode, $size, $qty, $rcvstrcode, $rcvstrname, $trantype, $excell_no, $apprlcode);
                    if ($qry) {
                        $getreturnsqry = $this->Model_connectdb->selectreturns($excell_no);
                    }
                }
            }
            
            for ($i = 0; $i < sizeof($getreturnsqry); $i++) {
                $j++;
                $array['message']               = "excell uploaded successfully";
                $array['status_code']           = 200;
                $array['issuing_store_code']    = $getreturnsqry[$i]['issuing_store_code'];
                $array['issuing_store_name']    = $getreturnsqry[$i]['issuing_store_name'];
                $array['barcode']               = $getreturnsqry[$i]['barcode'];
                $array['line']                  = $getreturnsqry[$i]['line'];
                $array['style_code']            = $getreturnsqry[$i]['style_code'];
                $array['size']                  = $getreturnsqry[$i]['size'];
                $array['qty']                   = $getreturnsqry[$i]['qty'];
                $array['receiving_strwhr_code'] = $getreturnsqry[$i]['receiving_strwhr_code'];
                $array['receiving_store_name']  = $getreturnsqry[$i]['receiving_store_name'];
                $array['transaction_type']      = $getreturnsqry[$i]['transaction_type'];
                $array['excel_no']              = $getreturnsqry[$i]['excel_no'];
                $array['status']                = $getreturnsqry[$i]['status'];
                $array['approval_code']         = $getreturnsqry[$i]['approval_code'];
                $array['date_time']             = $getreturnsqry[$i]['created_at'];
                $returnsdataarr[$j - 1]         = $array;
            }
            if ($returnsdataarr) {
                echo json_encode($returnsdataarr);
            } else {
                $array['message']     = "error while uploading excel please reffer error log for errors.";
                $array['status_code'] = 304;
                echo json_encode($array);
            }
        }
    }
}


?>