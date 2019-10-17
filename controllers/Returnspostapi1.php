<?php
// include 'excel_reader.php';
// ini_set('upload_max_filesize', '50M');
// ini_set('post_max_size', '50M');
// ini_set('memory_limit','4096M');
// ini_set('max_execution_time','10000');
defined('BASEPATH') OR exit('No direct script access allowed');
class Returnspostapi1 extends CI_Controller
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
            $n              = 0;
            $insertvar      = 0;
            $apprvlvarinc   = 0;
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
            
            $file      = $_FILES['xcel']['name'];
            $tmp_name  = $_FILES['xcel']['tmp_name'];
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $microtime = substr(microtime(), 15);
            $newFile   = "FILES/IMG/'$microtime'.$extension";
            move_uploaded_file($tmp_name, $newFile);
            
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
                    $exceldata[$i ][$headers[$keys[$j]]] = $val[$i+2][$keys[$j]];
                }
            }

            
            // $params = (array) json_decode(file_get_contents('php://input'), true);
            // $exceldata = $params;
            $email             = isset($_POST['email_address']) ? $_POST['email_address'] : "";
            $brand             = isset($_POST['brand']) ? $_POST['brand'] : "";
            $excell_noqry      = $this->Model_connectdb->selectreturnsexcelno($email);
            $excell_no         = $excell_noqry[0]['max'] + 1;
            $initiatedat       = date('Y-m-d H:i:s'); //$params['initiatedat'];
            $errorexcell_noqry = $this->Model_connectdb->selecterrorlogexcelno($email);
            $errorexcell_no    = $errorexcell_noqry[0]['max'] + 1;
            //$rtntypedata = $this->Model_connectdb->selectwf('WF1',$brand);
            // for($i = 0; $i<sizeof($rtntypedata); $i++)
            // {
            //     $wf1arr[$i] = $rtntypedata[$i]['return_type'];
            // }
            // $rtntypedata2 = $this->Model_connectdb->selectwf('WF2',$brand);
            // for($i = 0; $i<sizeof($rtntypedata2); $i++)
            // {
            //     $wf2arr[$i] = $rtntypedata2[$i]['return_type'];
            // }
            
            for ($i = 0; $i < sizeof($exceldata); $i++) {
                $fromstrcode = $exceldata[$i]['ISSUING STORE CODE'];
                $fromstrname = $exceldata[$i]['ISSUING STORE NAME'];
                $barcode     = $exceldata[$i]['BARCODE'];
                $line        = $exceldata[$i]['LINE'];
                $stylecode   = $exceldata[$i]['STYLE CODE'];
                $size        = $exceldata[$i]['SIZE'];
                $qty         = $exceldata[$i]['QTY'];
                $rcvstrcode  = $exceldata[$i]['RECEIVING STORE CODE'];
                $rcvstrname  = $exceldata[$i]['RECEIVING STORE NAME'];
                $trantype    = $exceldata[$i]['TRANSACTION TYPE'];
                if ($fromstrcode != "") {
                    $storedata = $this->Model_connectdb->storecode($fromstrcode);
                    $storecode = $storedata[0]['store_code'];
                    if ($storecode != "") {
                        $storestatus = $storedata[0]['store_status'];
                        if ($storestatus == 'Active') {
                        } else {
                            $flage        = '0';
                            $error        = "issuing store code is inactive now";
                            $errorarr[$i] = $errorarr[$i] . ',' . $error;
                        }
                    } else {
                        $flage        = '0';
                        $error        = "incorrect issuing store code ";
                        $errorarr[$i] = $errorarr[$i] . ',' . $error;
                    }
                } else {
                    $flage        = '0';
                    $error        = "issuing store code is missing ";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($fromstrname != "") {
                } else {
                    $flage        = '0';
                    $error        = "issuing store name is missing ";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                
                if ($barcode != "") {
                } else {
                    $flage        = '0';
                    $error        = "barcode is missing ";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($line != "") {
                } else {
                    $flage        = '0';
                    $error        = "line is missing ";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($stylecode != "") {
                } else {
                    $flage        = '0';
                    $error        = "style code is missing ";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($size != "") {
                } else {
                    $flage        = '0';
                    $error        = "size is missing ";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($qty != "") {
                } else {
                    $flage        = '0';
                    $error        = "quantity is missing ";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($rcvstrname != "") {
                } else {
                    $flage        = '0';
                    $error        = "receiving store name is missing ";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($trantype != "") {
                    $dbrtntype = $this->Model_connectdb->selectwf($trantype, $brand);
                    if (sizeof($dbrtntype) != 0) {
                    } else {
                        $flage        = '0';
                        $error        = "Transaction type is wrong";
                        $errorarr[$i] = $errorarr[$i] . ',' . $error;
                    }
                    if ($rcvstrcode != "") {
                        // if(in_array($trantype,$wf1arr))
                        // {
                        $storedata     = $this->Model_connectdb->storecode($rcvstrcode);
                        $warehousedata = $this->Model_connectdb->selectwarehouse($rcvstrcode);
                        if (sizeof($storedata) != 0 && sizeof($warehousedata) == 0) {
                            $storecode = $storedata[0]['store_code'];
                            if ($storecode != "") {
                                $storestatus = $storedata[0]['store_status'];
                                if ($storestatus == 'Active') {
                                } else {
                                    $flage        = '0';
                                    $error        = "store is inactive now";
                                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                                }
                            }
                            
                        }
                        //}
                        elseif (sizeof($storedata) == 0 && sizeof($warehousedata) != 0) {
                            $warehousecode   = $warehousedata[0]['warehouse_code'];
                            $warehousestatus = $warehousedata[0]['warehouse_status'];
                            if ($warehousecode != "") {
                                if ($warehousestatus == 'Active') {
                                } else {
                                    $flage        = '0';
                                    $error        = "warehouse is inactive now";
                                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                                }
                            } else {
                                $flage        = '0';
                                $error        = "incorrect warehouse code ";
                                $errorarr[$i] = $errorarr[$i] . ',' . $error;
                            }
                        } else {
                            $flage        = '0';
                            $error        = "incorrect receiving store code ";
                            $errorarr[$i] = $errorarr[$i] . ',' . $error;
                        }
                        
                        // else
                        // {
                        //     $flage = '0';
                        //     $error = "transaction type is wrong ";
                        //     $errorarr[$i] = $errorarr[$i] . ',' .$error;
                        // }
                    } else {
                        $flage        = '0';
                        $error        = "receiving store code is missing ";
                        $errorarr[$i] = $errorarr[$i] . ',' . $error;
                    }
                } else {
                    $flage        = '0';
                    $error        = "transactoin type is missing ";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                $flagarr[$i]  = $flage;
                $errorarr[$i] = $errorarr[$i];
            }
            // print_r($errorarr);
            // print_r($flagarr);die;  
            // $starttime = microtime(true);
            if (in_array("0", $flagarr)) {
                for ($i = 0; $i < sizeof($exceldata); $i++) {
                    $approvalcode++;
                    $fromstrcode = $exceldata[$i]['ISSUING STORE CODE'];
                    $fromstrname = $exceldata[$i]['ISSUING STORE NAME'];
                    $barcode     = $exceldata[$i]['BARCODE'];
                    $line        = $exceldata[$i]['LINE'];
                    $stylecode   = $exceldata[$i]['STYLE CODE'];
                    $size        = $exceldata[$i]['SIZE'];
                    $qty         = $exceldata[$i]['QTY'];
                    $rcvstrcode  = $exceldata[$i]['RECEIVING STORE CODE'];
                    $rcvstrname  = $exceldata[$i]['RECEIVING STORE NAME'];
                    $trantype    = $exceldata[$i]['TRANSACTION TYPE'];
                    $apprlcode   = "";
                    
                    if ($flagarr[$i] == '1') {
                        $errormsg = "";
                    } elseif ($flagarr[$i] == '0') {
                        $errormsg = substr($errorarr[$i], 1);
                    }
                    $errorcodegetqry = $this->Model_connectdb->selecterrorcode();
                    $errorcode       = $errorcodegetqry[0]['error_code'];
                    // $inserterrorqry  = $this->Model_connectdb->insrterrorlog($errorcode,$fromstrcode,$fromstrname,$barcode,$line,$stylecode,$size,$qty,$rcvstrcode,$rcvstrname,$trantype,$errormsg,$excell_no,$email,$errorexcell_no);
                    
                    $valuesstr = $valuesstr . ",(" . "'$errorcode','$fromstrcode','$fromstrname','$barcode','$line','$stylecode','$size','$qty','$rcvstrcode','$rcvstrname','$trantype','$errormsg','$excell_no','$email','$errorexcell_no'" . ")";

                }
                $inserterrorqry = $this->Model_connectdb->insrterrorlog(substr($valuesstr, 1));

            } else {
                for ($i = 0; $i < sizeof($exceldata); $i++) {
                $approvalcode++;
                $fromstrcode = $exceldata[$i]['ISSUING STORE CODE'];
                $fromstrname = $exceldata[$i]['ISSUING STORE NAME'];
                $barcode     = $exceldata[$i]['BARCODE'];
                $line        = $exceldata[$i]['LINE'];
                $stylecode   = $exceldata[$i]['STYLE CODE'];
                $size        = $exceldata[$i]['SIZE'];
                $qty         = $exceldata[$i]['QTY'];
                $rcvstrcode  = $exceldata[$i]['RECEIVING STORE CODE'];
                $rcvstrname  = $exceldata[$i]['RECEIVING STORE NAME'];
                $trantype    = $exceldata[$i]['TRANSACTION TYPE'];
                $apprlcode   = "";
                $insertvar = 1;
                $date        = date('d-m-Y h:i:s A');
                $tranhistory = "Open " . date('d-m-Y h:i:s A');
                $valuesstr   = $valuesstr . ",(" . "'$fromstrcode','$fromstrname','$barcode','$line','$stylecode','$size','$qty','$rcvstrcode','$rcvstrname','$trantype','$excell_no','Open','','$date','$email','$brand','$tranhistory'" . ")";
                //$qry = $this->Model_connectdb->insrtreturns($fromstrcode,$fromstrname,$barcode,$line,$stylecode,$size,$qty,$rcvstrcode,$rcvstrname,$trantype,$excell_no,$apprlcode,$email,$brand);
            }
             $qry = $this->Model_connectdb->insrtreturns(substr($valuesstr, 1));
            }
            //}
            // $endtime = microtime(true);
            // echo $endtime - $starttime;
            // die;
            if ($inserterrorqry) {
                $array['message']     = "error while uploading excel please reffer error log for errors.";
                $array['status_code'] = 304;
                echo json_encode($array);
            }
            
            if ($qry) {
                for ($i = 0; $i < sizeof($exceldata); $i++) {
                    $fromstrcode = $exceldata[$i]['ISSUING STORE CODE'];
                    $rcvstrname  = $exceldata[$i]['RECEIVING STORE CODE'];
                    $code[$i]    = $fromstrcode . "," . $rcvstrname;
                }
                $distinctarr        = array_unique($code);
                $keys               = array_keys($distinctarr);
                $returnexcell_noqry = $this->Model_connectdb->selectreturnsexcelno($email);
                $returnexcell_no    = $returnexcell_noqry[0]['max'];
                for ($i = 0; $i < sizeof($distinctarr); $i++) {
                    $dbapprcode    = $this->Model_connectdb->onlycode($email);
                    // $dbapprcode = $this->Model_connectdb->selectapprovalcode($email);
                    $maxapprcode   = $dbapprcode[0]['max'];
                    // $onlynum = filter_var($maxapprcode, FILTER_SANITIZE_NUMBER_INT);
                    $onlycode      = $maxapprcode + 1;
                    $codes         = explode(",", $distinctarr[$keys[$i]]);
                    $sendcode      = $codes[0];
                    $receivecode   = $codes[1];
                    $trnreturntype = $this->Model_connectdb->returntype($sendcode, $receivecode, $email, $excell_no);
                    for ($j = 0; $j < sizeof($trnreturntype); $j++) {
                        $dbtrantype     = $trnreturntype[$j]['transaction_type'];
                        $updatedcode    = $dbtrantype . $onlycode;
                        // $apprlcodearr[$j] = $updatedcode;
                        $updatecode     = $this->Model_connectdb->updateapprnum($onlycode, $sendcode, $receivecode);
                        $updateapprcode = $this->Model_connectdb->updateapprcode($email, $updatedcode, $sendcode, $receivecode, $excell_no, $dbtrantype);
                    }
                    // $apprlcodearr2[$i] = implode(",",array_unique($apprlcodearr));
                }
            }
            if ($updateapprcode) {
                $rtnexcell_noqry   = $this->Model_connectdb->selectreturnsexcelno($email);
                $rtnexcell_no      = $rtnexcell_noqry[0]['max'];
                $getopenreturnsqry = $this->Model_connectdb->selectallreturns($email, $rtnexcell_no);
                for ($i = 0; $i < sizeof($getopenreturnsqry); $i++) {
                    $apprvlvarinc++;
                    $totqty    = "";
                    $frmstr    = $getopenreturnsqry[$i]['issuing_store_code'];
                    $frmstrnm  = $getopenreturnsqry[$i]['issuing_store_name'];
                    $tostr     = $getopenreturnsqry[$i]['receiving_strwhr_code'];
                    $tostrnm   = $getopenreturnsqry[$i]['receiving_store_name'];
                    $transtype = $getopenreturnsqry[$i]['transaction_type'];
                    $apprvlcde = $getopenreturnsqry[$i]['approval_code'];
                    $proqty    = $this->Model_connectdb->proqty($apprvlcde);
                    for ($j = 0; $j < sizeof($proqty); $j++) {
                        $totqty = $totqty + $proqty[$j]['qty'];
                    }
                    
                    $sts         = $getopenreturnsqry[$i]['status'];
                    $xl_no       = $getopenreturnsqry[$i]['excel_no'];
                    $tranhistory = $getopenreturnsqry[$i]['tranhistory'];
                    
                    
                    // $openreturnsarr[$i] = $array;
                    $tranhis                = $sts . " " . date('d-m-Y h:i:s A') . " " . $email;
                    $apprvldate             = date('d-m-Y h:i:s A');
                    $valuseosapprvcodeinsrt = $valuseosapprvcodeinsrt . ",(" . "'$frmstr','$frmstrnm','$tostr','$tostrnm','$transtype','$xl_no','$sts','','$email','$brand','$tranhis','$totqty','$apprvldate','$apprvlcde'" . ")";
                    // $insrtaprvlreturns = $this->Model_connectdb->insrtaprvlreturns($frmstr,$frmstrnm,$tostr,$tostrnm,$transtype,$apprvlcde,$sts,$xl_no,$email,$brand,$tranhistory,$totqty);
                    
                    
                }
                if ($apprvlvarinc > 0) {
                    $insrtaprvlreturns = $this->Model_connectdb->insrtaprvlreturns(substr($valuseosapprvcodeinsrt, 1));
                    $array['message'] = "Upload successfull";
                    $array['status_code'] = 200;
                    echo json_encode($array);
                }
                // if ($insrtaprvlreturns) {
                //     $data = $this->Model_connectdb->selectaprvlreturns($email, $brand);
                //     for ($i = 0; $i < sizeof($data); $i++) {
                //         $array['status_code']            = 200;
                //         $array['issuing_store_code']     = $data[$i]['issuing_store_code'];
                //         $array['issuing_store_name']     = $data[$i]['issuing_store_name'];
                //         $array['receiving_strwhr_code']  = $data[$i]['receiving_strwhr_code'];
                //         $array['receiving_store_name']   = $data[$i]['receiving_store_name'];
                //         $array['transaction_type']       = $data[$i]['transaction_type'];
                //         $array['approval_code']          = $data[$i]['approval_code'];
                //         $array['status']                 = $data[$i]['status'];
                //         $array['date']                   = $data[$i]['dateof_initiated'];
                //         $array['total_qty']              = $data[$i]['qtyof_approvalcode'];
                //         $array['duplicate_approvalcode'] = $data[$i]['duplicate_approvalcode'];
                //         $statusdisc                      = $data[$i]['status'];
                //         $stscount                        = $this->Model_connectdb->getstatuscount($statusdisc);
                //         $array['status_count']           = $stscount[0]['status_count'];
                //         $openreturnsarr[$i]              = $array;
                //     }
                // }
                
            }
            //    $resultarr = array_merge($openreturnsarr,$plnrreturnsarr);
            // if ($openreturnsarr != "") {
            //     echo json_encode($openreturnsarr);
            // }
            
        }
    }
}


?>