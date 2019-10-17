<?php
// ini_set('upload_max_filesize', '50M');
// ini_set('post_max_size', '50M');
// ini_set('memory_limit','4096M');
// ini_set('max_execution_time','10000');
defined('BASEPATH') OR exit('No direct script access allowed');
class Returnspostapi5 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb1');
    }
    function Returnspost()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $error          = "";
            $errorarr       = array();
            $flagarr        = array();
            $approvalcode   = 0;
            $returnsdataarr = array();
            $flage          = 1;
            $openreturnsarr = array();
            $plnrreturnsarr = array();
            $row            = 0;
            $apprlcodearr   = array();
            $apprlcodearr2  = array();
            $exceldata      = array();
            $code           = array();
            $wf1arr         = array();
            $wf2arr         = array();
            $distinctarr    = array();
            $dummyarr       = array();

            try{
                $starttime = date("h-i-s");
            $file           = $_FILES['xcel']['name'];
            $tmp_name       = $_FILES['xcel']['tmp_name'];
            $extension      = pathinfo($file, PATHINFO_EXTENSION);
            $microtime      = substr(microtime(), 15);
            $newFile        = "FILES/Returns_excel/$microtime.$extension";
            move_uploaded_file($tmp_name, $newFile);
            $fileupload = date("h-i-s");
            //load the excel library
            $path = $newFile;
            $this->load->library('Excel');
            // include 'libraries/Excel.php';
            //read file from path
            $objPHPExcel     = PHPExcel_IOFactory::load($path);
            //get only the Cell Colection
            $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
            // print_r($cell_collection);die;
            //extract to a PHP readable array format
            foreach ($cell_collection as $cell) {
                $column     = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                $row        = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                
                //The header will/should be in row 1 only. of course, this can be modified to suit your need.
                if ($row == 1) {
                    $header[$row][$column] = $data_value;
                } else {
                    $arr_data[$row][$column] = $data_value;
                }
            }
            
            //send the data in an array format
            $data['header'] = $header;
            $data['values'] = $arr_data;
            $headers        = $data['header'][1];
            $keys           = array_keys($data['header'][1]);
            $val            = $data['values'];
            $exceldata      = array();
            $j              = 0;
            for ($i = 0; $i < sizeof($val); $i++) {
                for ($j = 0; $j < sizeof($headers); $j++) {
                    $exceldata[$i][$headers[$keys[$j]]] = $val[$i + 2][$keys[$j]];
                    // $trandata[$i][$headers[$keys[$j]]] = $val[$i+2][$keys[$j]]['']
                }
            }
            $excelreadtime     = date("h-i-s");
            //$exceldata = $params[1];
            $email             = isset($_GET['email_address']) ? $_GET['email_address'] : "";
            $brand             = isset($_GET['brand']) ? $_GET['brand'] : "";
            $excell_noqry      = $this->Model_connectdb1->selectreturnsexcelno($email);
            $excell_no         = $excell_noqry[0]['max'] + 1;
            $initiatedat       = date('Y-m-d H:i:s'); //$params['initiatedat'];
            $errorexcell_noqry = $this->Model_connectdb1->selecterrorlogexcelno($email);
            $errorexcell_no    = $errorexcell_noqry[0]['max'] + 1;
            
            //validating ----------------------------------------------------------------
            
            
            for ($i = 0; $i < sizeof($exceldata); $i++) {
            $fromstrcode = isset($exceldata[$i]['ISSUING STORE CODE']) ? $exceldata[$i]['ISSUING STORE CODE'] : "";
                $fromstrname = isset($exceldata[$i]['ISSUING STORE NAME']) ? $exceldata[$i]['ISSUING STORE NAME'] : "";
                $barcode     = isset($exceldata[$i]['BARCODE']) ? $exceldata[$i]['BARCODE'] : "";
                $line        = isset($exceldata[$i]['LINE']) ? $exceldata[$i]['LINE'] : "";
                $stylecode   = isset($exceldata[$i]['STYLE CODE']) ? $exceldata[$i]['STYLE CODE'] : "";
                $size        = isset($exceldata[$i]['SIZE']) ? $exceldata[$i]['SIZE'] : "";
                $qty         = isset($exceldata[$i]['QTY']) ? $exceldata[$i]['QTY'] : "";
                $rcvstrcode  = isset($exceldata[$i]['RECEIVING STORE CODE']) ? $exceldata[$i]['RECEIVING STORE CODE'] : "";
                $rcvstrname  = isset($exceldata[$i]['RECEIVING STORE NAME']) ? $exceldata[$i]['RECEIVING STORE NAME'] : "";
                $trantype    = isset($exceldata[$i]['TRANSACTION TYPE']) ? $exceldata[$i]['TRANSACTION TYPE'] : "";
                if ($fromstrcode != "" && $rcvstrcode != "") {
                    if ($fromstrcode == $rcvstrcode) {
                            $flage = '0';
                            $error = "issuing and receiving location should not be same";
                            $errorarr[$i] = $errorarr[$i] . ',' .$error;
                    }
                }
                if ($fromstrcode != "") {
                        $storedata = $this->Model_connectdb1->storecode($fromstrcode);
                        $storecode = $storedata[0]['store_code'];
                        if($storecode != "")
                        {
                            $storestatus = $storedata[0]['store_status'];
                        if($storestatus == 'Active')
                        {
                        }
                        else{
                            $flage = '0';
                            $error = "issuing store code is inactive now";
                            $errorarr[$i] = $errorarr[$i] . ',' .$error;  
                        }
                        }
                        else{
                            $flage = '0';
                            $error = "incorrect issuing store code ";
                            $errorarr[$i] = $errorarr[$i] . ',' .$error; 
                        }
                } else {
                    $flage = '0';
                    $error = "issuing store code is missing ";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error;
                }
                if ($trantype != "") {
                    $dbrtntype = $this->Model_connectdb1->selectwf($trantype,$brand);
                    if(sizeof($dbrtntype) != 0)
                    {
                    }
                    else
                    {
                    $flage = '0';
                    $error = "Transaction type is wrong";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error; 
                    }
                    if ($rcvstrcode != "") {
                    // if(in_array($trantype,$wf1arr))
                    // {
                        $storedata = $this->Model_connectdb1->storecode($rcvstrcode);
                        $warehousedata = $this->Model_connectdb1->selectwarehouse($rcvstrcode);
                        if(sizeof($storedata) != 0 && sizeof($warehousedata) == 0){
                        $storecode = $storedata[0]['store_code'];
                         if($storecode != "")
                        {
                            $storestatus = $storedata[0]['store_status'];
                        if($storestatus == 'Active')
                        {
                        }
                        else{
                            $flage = '0';
                            $error = "store is inactive now";
                            $errorarr[$i] = $errorarr[$i] . ',' .$error;  
                        }
                        }
                        
                    }
                    //}
                    elseif(sizeof($storedata) == 0 && sizeof($warehousedata) != 0)
                    {
                        $warehousecode = $warehousedata[0]['warehouse_code'];
                        $warehousestatus = $warehousedata[0]['warehouse_status'];
                        if($warehousecode != "")
                        {
                        if($warehousestatus == 'Active')
                        {
                        }
                        else{
                            $flage = '0';
                            $error = "warehouse is inactive now";
                            $errorarr[$i] = $errorarr[$i] . ',' .$error;  
                        }
                        }
                        else{
                            $flage = '0';
                            $error = "incorrect warehouse code ";
                            $errorarr[$i] = $errorarr[$i] . ',' .$error; 
                        }
                    }
                    else{
                            $flage = '0';
                            $error = "incorrect receiving store code ";
                            $errorarr[$i] = $errorarr[$i] . ',' .$error; 
                        }

                    // else
                    // {
                    //     $flage = '0';
                    //     $error = "transaction type is wrong ";
                    //     $errorarr[$i] = $errorarr[$i] . ',' .$error;
                    // }
                }
                else
                {
                    $flage = '0';
                    $error = "receiving store code is missing ";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error;
                }
                } else {
                    $flage = '0';
                    $error = "transactoin type is missing ";
                    $errorarr[$i] = $errorarr[$i] . ',' .$error;
                }
                $flagarr[$i]  = $flage;
                $errorarr[$i] = $errorarr[$i];
            }
            $validatetime = date("h-i-s");
            //inserting into database---------------------------------------------------------------
            if (!in_array(0, $flagarr)) {
                $dbapprcode      = $this->Model_connectdb1->onlycode(null);
                $apprlgen        = $dbapprcode[0]['max'];
                $approvalcodearr = array();
                
                for ($i = 0; $i < sizeof($exceldata); $i++) {
                    
                    $fromstrcode = isset($exceldata[$i]['ISSUING STORE CODE']) ? $exceldata[$i]['ISSUING STORE CODE'] : "";
                    $fromstrname = isset($exceldata[$i]['ISSUING STORE NAME']) ? $exceldata[$i]['ISSUING STORE NAME'] : "";
                    $barcode     = isset($exceldata[$i]['BARCODE']) ? $exceldata[$i]['BARCODE'] : "";
                    $line        = isset($exceldata[$i]['LINE']) ? $exceldata[$i]['LINE'] : "";
                    $stylecode   = isset($exceldata[$i]['STYLE CODE']) ? $exceldata[$i]['STYLE CODE'] : "";
                    $size        = isset($exceldata[$i]['SIZE']) ? $exceldata[$i]['SIZE'] : "";
                    $qty         = isset($exceldata[$i]['QTY']) ? $exceldata[$i]['QTY'] : "";
                    $rcvstrcode  = isset($exceldata[$i]['RECEIVING STORE CODE']) ? $exceldata[$i]['RECEIVING STORE CODE'] : "";
                    $rcvstrname  = isset($exceldata[$i]['RECEIVING STORE NAME']) ? $exceldata[$i]['RECEIVING STORE NAME'] : "";
                    $trantype    = isset($exceldata[$i]['TRANSACTION TYPE']) ? $exceldata[$i]['TRANSACTION TYPE'] : "";
                    
                    $strarr = $exceldata[$i]['ISSUING STORE CODE'] . " " . $exceldata[$i]['RECEIVING STORE CODE'] . " " . $exceldata[$i]['TRANSACTION TYPE'];
                    
                    if (!array_key_exists($strarr, $approvalcodearr)) {
                        $apprlgen                 = $apprlgen + 1;
                        $approvalcodearr[$strarr] = array(
                            'appr' => $trantype . $apprlgen,
                            'totqty' => $qty
                        );
                        // $approvalcodearr[$strarr] = $trantype . $apprlgen;
                        $approvalcode             = $trantype . $apprlgen;
                        $date                     = date('d-m-Y h:i:s A');
                        $tranhistory              = "Open " . $date ." ".$email;
                        $valuesstr1[$i]           = ",(" . "'$fromstrcode','$fromstrname','$rcvstrcode','$rcvstrname','$trantype','$excell_no','Open','','$email','$brand','$tranhistory','totqty','$date','$approvalcode','$apprlgen'" . ")"; //for unique product vise returns
                    } else {
                        $apprcode = $approvalcodearr[$strarr]['appr'];
                        
                        $approvalcodearr[$strarr]['totqty'] = $approvalcodearr[$strarr]['totqty'] + $qty;
                    }
                    $tranhistory                  = "Open " . $date;

                    $valuesstr                    = $valuesstr . ",(" . "'$fromstrcode','$fromstrname','$barcode','$line','$stylecode','$size','$qty','$rcvstrcode','$rcvstrname','$trantype','$excell_no','Open','$approvalcode','$date','$email','$brand','$tranhistory','$apprlgen'" . ")"; //for product vise returns
                }
                $keys = array_keys($approvalcodearr);
                $strkeys = array_keys($valuesstr1);
                for ($i = 0; $i < sizeof($keys); $i++) {
                    $unicinsrtstr = $unicinsrtstr . str_replace('totqty', $approvalcodearr[$keys[$i]]['totqty'], $valuesstr1[$strkeys[$i]]);
                }
                $strform = date("h-i-s");
             $qry = $this->Model_connectdb1->insrtreturns(substr($valuesstr,1));          
             $insrtaprvlreturns = $this->Model_connectdb1->insrtaprvlreturns(substr($unicinsrtstr,1));
             $inserttime = date("h-i-s");
            }
            
            //inserting into error log------------------------------------------------
            else {
                
                for ($i = 0; $i < sizeof($exceldata); $i++) {
                    $approvalcode++;
                    $fromstrcode = isset($exceldata[$i]['ISSUING STORE CODE']) ? $exceldata[$i]['ISSUING STORE CODE'] : "";
                    $fromstrname = isset($exceldata[$i]['ISSUING STORE NAME']) ? $exceldata[$i]['ISSUING STORE NAME'] : "";
                    $barcode     = isset($exceldata[$i]['BARCODE']) ? $exceldata[$i]['BARCODE'] : "";
                    $line        = isset($exceldata[$i]['LINE']) ? $exceldata[$i]['LINE'] : "";
                    $stylecode   = isset($exceldata[$i]['STYLE CODE']) ? $exceldata[$i]['STYLE CODE'] : "";
                    $size        = isset($exceldata[$i]['SIZE']) ? $exceldata[$i]['SIZE'] : "";
                    $qty         = isset($exceldata[$i]['QTY']) ? $exceldata[$i]['QTY'] : "";
                    $rcvstrcode  = isset($exceldata[$i]['RECEIVING STORE CODE']) ? $exceldata[$i]['RECEIVING STORE CODE'] : "";
                    $rcvstrname  = isset($exceldata[$i]['RECEIVING STORE NAME']) ? $exceldata[$i]['RECEIVING STORE NAME'] : "";
                    $trantype    = isset($exceldata[$i]['TRANSACTION TYPE']) ? $exceldata[$i]['TRANSACTION TYPE'] : "";
                    if ($flagarr[$i] == '1') {
                        $errormsg = "";
                    } elseif ($flagarr[$i] == '0') {
                        $errormsg = substr($errorarr[$i], 1);
                    }
                    $errorcodegetqry = $this->Model_connectdb1->selecterrorcode();
                    $errorcode       = $errorcodegetqry[0]['error_code'];
                    
                    
                    $valuesstr = $valuesstr . ",(" . "'$errorcode','$fromstrcode','$fromstrname','$barcode','$line','$stylecode','$size','$qty','$rcvstrcode','$rcvstrname','$trantype','$errormsg','$excell_no','$email','$errorexcell_no'" . ")";
                    
                }
                $inserterrorqry  = $this->Model_connectdb1->insrterrorlog(substr($valuesstr,1));

            }
            if ($qry) {
                $data = $this->Model_connectdb1->selectaprvlreturns($email);
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
            $array['date']                = $data[$i]['dateof_initiated'];
            $array['total_qty']                = $data[$i]['qtyof_approvalcode'];
            $array['duplicate_approvalcode']                = $data[$i]['duplicate_approvalcode'];
            $statusdisc = $data[$i]['status'];
            $stscount = $this->Model_connectdb1->getstatuscount($statusdisc);
            $array['status_count'] = $stscount[0]['status_count'];
            $openreturnsarr[$i] = $array;
            }
                // echo json_encode($openreturnsarr);
                echo json_encode($openreturnsarr);
                // $times['starttime'] = $starttime;
                // $times['fileupload'] = $fileupload;
                // $times['excelreadtime'] = $excelreadtime;
                // $times['validatetime'] = $validatetime;
                // $times['stringform'] = $strform;
                // $times['inserttimee'] = $inserttime;
                // echo json_encode($times);

                    }
            elseif ($inserterrorqry) {
                $array['message']     = "error while uploading excel please reffer error log for errors.";
                $array['status_code'] = 304;
                echo json_encode($array);
            }
          }
          catch(Exception $e)
          {
            var_dump($e->getMessage());
          }  
        }
    }
}
?>