<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Creditnote_insert extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function uploadcn()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $params        = (array) json_decode(file_get_contents('php://input'), true);
            $CNno          = isset($params[0]['credit_note_no']) ? $params[0]['credit_note_no'] : "";
            $approval_code = isset($params[0]['approval_code']) ? $params[0]['approval_code'] : "";
            $date = isset($params[0]['date']) ? $params[0]['date'] : "";

            

            $status_count  = isset($params[0]['status_count']) ? $params[0]['status_count'] : "";
            $comment       = isset($params[0]['comment']) ? $params[0]['comment'] : "";
            
            $email = isset($params[0]['email_address']) ? $params[0]['email_address'] : "";
            
            $doc  = isset($params[1]) ? $params[1] : "";
            $invoicedata  = isset($params[2]) ? $params[2] : "";
            $invoice = json_encode($invoicedata);

            $doc1 = json_encode($doc);
            
            //getting store excel
            
            $storedata = $this->Model_connectdb->getstoreexcel($approval_code);
            $sendloc = $storedata[0]['issuing_store_code'];
            $receivingloc = $storedata[0]['receiving_strwhr_code'];
            $qtystr    = (array) $storedata[0]['store_returns_doc'];
            $arr       = json_decode($qtystr[0], true);
            
            if (sizeof($arr) > sizeof($doc)) {
                for ($i = 0; $i < sizeof($arr); $i++) {
                    $storeexcelarr[$arr[$i]['LR_copy_no'] . " " . $arr[$i]['doc_no'] . " " . $arr[$i]['EAN_no']]     = $arr[$i]['QTY'];
                    $warehouseexceldoc[$doc[$i]['LR_copy_no'] . " " . $doc[$i]['doc_no'] . " " . $doc[$i]['EAN_no']] = $doc[$i]['QTY'];
                }
                $storekeys     = array_keys($storeexcelarr);
                $warehousekeys = array_keys($warehouseexceldoc);
                
                for ($i = 0; $i < sizeof($arr); $i++) {
                    if ($storeexcelarr[$storekeys[$i]] == $warehouseexceldoc[$storekeys[$i]]) {
                        $array['LR NO'] = $storeexcelarr[$arr[$i]['LR_copy_no']];
                        $array['APPROVAL CODE']     = $approval_code;
                        $array['SENDING LOCATION']     = $sendloc;
                        $array['RECEIVING LOCATION']     = $receivingloc;
                        
                        $array['DOC NO']     = $arr[$i]['doc_no'];
                        // $array['WAREHOUSE DOC NO'] = $doc[$i]['doc_no'];
                        
                        $array['EAN NO']     = $arr[$i]['EAN_no'];
                        // $array['WAREHOUSE EAN NO'] = $doc[$i]['EAN_no'];
                        
                        $array['SEND QTY']     = $arr[$i]['QTY'];
                        $array['RECEIVED QTY'] = $doc[$i]['QTY'];
                        $array['EXCESS']        = "$excess";
                        $array['SHORTAGE']      = "$shortage";
                    } elseif ($storeexcelarr[$storekeys[$i]] != $warehouseexceldoc[$storekeys[$i]]) {
                        $shortage                  = "";
                        $excess                    = "";
                        $array['LR NO'] = $storeexcelarr[$arr[$i]['LR_copy_no']];
                        $array['APPROVAL CODE']     = $approval_code;
                        $array['SENDING LOCATION']     = $sendloc;
                        $array['RECEIVING LOCATION']     = $receivingloc;
                        
                        $array['DOC NO']     = $arr[$i]['doc_no'];
                        // $array['WAREHOUSE DOC NO'] = $doc[$i]['doc_no'];
                        
                        $array['EAN NO']     = $arr[$i]['EAN_no'];
                        // $array['WAREHOUSE EAN NO'] = $doc[$i]['EAN_no'];
                        
                        $array['SEND QTY']     = $arr[$i]['QTY'];
                        $array['RECEIVED QTY'] = $doc[$i]['QTY'];
                        if ($arr[$i]['QTY'] > $doc[$i]['QTY']) {
                            $shortage          = $arr[$i]['QTY'] - $doc[$i]['QTY'];
                            $array['EXCESS']   = "$excess";
                            $array['SHORTAGE'] = "$shortage";
                        } elseif ($arr[$i]['QTY'] < $doc[$i]['QTY']) {
                            $excess            = $doc[$i]['QTY'] - $arr[$i]['QTY'];
                            $array['EXCESS']   = "$excess";
                            $array['SHORTAGE'] = "$shortage";
                        }
                    }
                    $report[$i] = $array;
                }
                $fh = fopen($path, 'w+');
                fputcsv($fh, array(
                    'LR NO',
                    'APPROVAL CODE',
                    'SENDING LOCATION',
                    'RECEIVING LOCATION',
                    'DOC NO',
                    'EAN NO',
                    'SEND QTY',
                    'RECEIVED QTY',
                    'EXCESS',
                    'SHORTAGE'
                ));
                foreach ($report as $line) {
                    foreach ($line as $key => $value) {
                        $data[] = $value;
                    }
                    fputcsv($fh, $data);
                    $data = array();
                }
                
                fclose($fh);
               $path = "FILES/Transaction_report/".$approval_code.substr(microtime(),15).".csv";
                 $result = $this->Model_connectdb->whrupdateCN($CNno, $doc1,$invoice, $approval_code, $excess, $shortage, $comment,$path,$date);
                if ($result) {
                    $status     = $this->Model_connectdb->getstsmsg($status_count + 2);
                    $status_msg = $status[0]['short_form'];
                    $data1      = $this->Model_connectdb->stsupdate($email, $approval_code, $status_msg);
                    if ($data1) {
                        $response['message']     = "Credit note uploaded";
                        $response['status_code'] = 200;
                        
                        echo json_encode($response);
                    }
                    
                }
            } elseif (sizeof($arr) < sizeof($doc)) {
                for ($i = 0; $i < sizeof($doc); $i++) {
                    $storeexcelarr[$arr[$i]['LR_copy_no'] . " " . $arr[$i]['doc_no'] . " " . $arr[$i]['EAN_no']]     = $arr[$i]['QTY'];
                    $warehouseexceldoc[$doc[$i]['LR_copy_no'] . " " . $doc[$i]['doc_no'] . " " . $doc[$i]['EAN_no']] = $doc[$i]['QTY'];
                }
                $storekeys     = array_keys($storeexcelarr);
                $warehousekeys = array_keys($warehouseexceldoc);
                
                for ($i = 0; $i < sizeof($doc); $i++) {
                    if ($warehouseexceldoc[$warehousekeys[$i]] == $storeexcelarr[$warehousekeys[$i]]) {
                        $array['LR NO'] = $storeexcelarr[$arr[$i]['LR_copy_no']];
                        $array['APPROVAL CODE']     = $approval_code;
                        $array['SENDING LOCATION']     = $sendloc;
                        $array['RECEIVING LOCATION']     = $receivingloc;
                        
                        $array['DOC NO']     = $arr[$i]['doc_no'];
                        // $array['WAREHOUSE DOC NO'] = $doc[$i]['doc_no'];
                        
                        $array['EAN NO']     = $arr[$i]['EAN_no'];
                        // $array['WAREHOUSE EAN NO'] = $doc[$i]['EAN_no'];
                        
                        $array['SEND QTY']     = $arr[$i]['QTY'];
                        $array['RECEIVED QTY'] = $doc[$i]['QTY'];
                        $array['EXCESS']       = "$excess";
                        $array['SHORTAGE']     = "$shortage";
                    } elseif ($storeexcelarr[$warehousekeys[$i]] != $warehouseexceldoc[$warehousekeys[$i]]) {
                        $shortage                  = "";
                        $excess                    = "";
                        $array['LR NO'] = $storeexcelarr[$arr[$i]['LR_copy_no']];
                        $array['APPROVAL CODE']     = $approval_code;
                        $array['SENDING LOCATION']     = $sendloc;
                        $array['RECEIVING LOCATION']     = $receivingloc;
                        
                        $array['DOC NO']     = $arr[$i]['doc_no'];
                        // $array['WAREHOUSE DOC NO'] = $doc[$i]['doc_no'];
                        
                        $array['EAN NO']     = $arr[$i]['EAN_no'];
                        // $array['WAREHOUSE EAN NO'] = $doc[$i]['EAN_no'];
                        
                        $array['SEND QTY']     = $arr[$i]['QTY'];
                        $array['RECEIVED QTY'] = $doc[$i]['QTY'];
                        if ($arr[$i]['QTY'] > $doc[$i]['QTY']) {
                            $shortage          = $arr[$i]['QTY'] - $doc[$i]['QTY'];
                            $array['EXCESS']   = "$excess";
                            $array['SHORTAGE'] = "$shortage";
                        } elseif ($arr[$i]['QTY'] < $doc[$i]['QTY']) {
                            $excess            = $doc[$i]['QTY'] - $arr[$i]['QTY'];
                            $array['EXCESS']   = "$excess";
                            $array['SHORTAGE'] = "$shortage";
                        }
                    }
                    $report[$i] = $array;
                }
                $path = "FILES/Transaction_report/".$approval_code.substr(microtime(),15).".csv";
                $result = $this->Model_connectdb->whrupdateCN($CNno, $doc1,$invoice, $approval_code, $excess, $shortage, $comment,$path,$date);
                if ($result) {
                    $status     = $this->Model_connectdb->getstsmsg($status_count + 2);
                    $status_msg = $status[0]['short_form'];
                    $data1      = $this->Model_connectdb->stsupdate($email, $approval_code, $status_msg);
                    if ($data1) {
                        $response['message']     = "Credit note uploaded";
                        $response['status_code'] = 200;
                        $fh                   = fopen($path, 'w+');
                        fputcsv($fh, array(
                            'LR NO',
                            'APPROVAL CODE',
                    'SENDING LOCATION',
                    'RECEIVING LOCATION',
                    'DOC NO',
                    'EAN NO',
                    'SEND QTY',
                    'RECEIVED QTY',
                    'EXCESS',
                    'SHORTAGE'
                        ));
                        foreach ($report as $line) {
                            // with this foreach, if value is array, replace it with first array value
                            foreach ($line as $key => $value) {
                                //$line[$key] = $value;
                                $data[] = $value;
                            }
                            fputcsv($fh, $data);
                            $data = array();
                            
                            // no need for foreach, as fputcsv expects array, which we already have
                        }
                        
                        fclose($fh);
                        echo json_encode($response);
                    }
                    
                }
            } elseif (sizeof($arr) == sizeof($doc)) {
                for ($i = 0; $i < sizeof($doc); $i++) {
                   $storeexcelarr[$arr[$i]['LR_copy_no'] . " " . $arr[$i]['doc_no'] . " " . $arr[$i]['EAN_no']]     = $arr[$i]['QTY'];
                    $warehouseexceldoc[$doc[$i]['LR_copy_no'] . " " . $doc[$i]['doc_no'] . " " . $doc[$i]['EAN_no']] = $doc[$i]['QTY'];
                }
                $storekeys     = array_keys($storeexcelarr);
                $warehousekeys = array_keys($warehouseexceldoc);
                
                for ($i = 0; $i < sizeof($doc); $i++) {
                    if ($warehouseexceldoc[$storekeys[$i]] == $storeexcelarr[$storekeys[$i]]) {
                        $array['LR NO'] = $storeexcelarr[$arr[$i]['LR_copy_no']];
                        $array['APPROVAL CODE']     = $approval_code;
                        $array['SENDING LOCATION']     = $sendloc;
                        $array['RECEIVING LOCATION']     = $receivingloc;
                        
                        $array['DOC NO']     = $arr[$i]['doc_no'];
                        // $array['WAREHOUSE DOC NO'] = $doc[$i]['doc_no'];
                        
                        $array['EAN NO']     = $arr[$i]['EAN_no'];
                        // $array['WAREHOUSE EAN NO'] = $doc[$i]['EAN_no'];
                        
                        $array['SEND QTY']     = $arr[$i]['QTY'];
                        $array['RECEIVED QTY'] = $doc[$i]['QTY'];
                        $array['EXCESS']       = "$excess";
                        $array['SHORTAGE']     = "$shortage";
                        $qtyflag               = true;
                    } elseif ($storeexcelarr[$storekeys[$i]] != $warehouseexceldoc[$storekeys[$i]]) {
                        $array['LR NO'] = $storeexcelarr[$arr[$i]['LR_copy_no']];
                        $array['APPROVAL CODE']     = $approval_code;
                        $array['SENDING LOCATION']     = $sendloc;
                        $array['RECEIVING LOCATION']     = $receivingloc;
                        $array['DOC NO']     = $arr[$i]['doc_no'];
                        // $array['WAREHOUSE DOC NO'] = $doc[$i]['doc_no'];
                        
                        $array['EAN NO']     = $arr[$i]['EAN_no'];
                        // $array['WAREHOUSE EAN NO'] = $doc[$i]['EAN_no'];
                        
                        $array['SEND QTY']     = $arr[$i]['QTY'];
                        $array['RECEIVED QTY'] = $doc[$i]['QTY'];
                        
                        if ($arr[$i]['QTY'] > $doc[$i]['QTY']) {
                            $shortage          = $arr[$i]['QTY'] - $doc[$i]['QTY'];
                            $array['EXCESS']   = "$excess";
                            $array['SHORTAGE'] = "$shortage";
                        } elseif ($arr[$i]['QTY'] < $doc[$i]['QTY']) {
                            $excess            = $doc[$i]['QTY'] - $arr[$i]['QTY'];
                            $array['EXCESS']   = "$excess";
                            $array['SHORTAGE'] = "$shortage";
                        }
                        $qtyflag = false;
                    }
                    $report[$i] = $array;
                }
                if ($qtyflag) {
                    $path = "FILES/Transaction_report/".$approval_code.substr(microtime(),15).".csv";
                    $result = $this->Model_connectdb->whrupdateCN($CNno, $doc1,$invoice, $approval_code, $excess, $shortage, $comment,$path,$date);
                    if ($result) {
                        $status     = $this->Model_connectdb->getstsmsg($status_count + 1);
                        $status_msg = $status[0]['short_form'];
                        $data1      = $this->Model_connectdb->stsupdate($email, $approval_code, $status_msg);
                        if ($data1) {
                            $response['message']     = "Credit note uploaded";
                            $response['status_code'] = 200;
                            $fh                   = fopen($path, 'w+');
                            fputcsv($fh, array(
                    'LR NO',
                    'APPROVAL CODE',
                    'SENDING LOCATION',
                    'RECEIVING LOCATION',
                    'DOC NO',
                    'EAN NO',
                    'SEND QTY',
                    'RECEIVED QTY',
                    'EXCESS',
                    'SHORTAGE'
                            ));
                            foreach ($report as $line) {
                                // with this foreach, if value is array, replace it with first array value
                                foreach ($line as $key => $value) {
                                    //$line[$key] = $value;
                                    $data[] = $value;
                                }
                                fputcsv($fh, $data);
                                $data = array();
                                
                                // no need for foreach, as fputcsv expects array, which we already have
                            }
                            
                            fclose($fh);
                            echo json_encode($response);
                        }
                    }
                } elseif (!$qtyflag) {
                    $path = "FILES/Transaction_report/".$approval_code.substr(microtime(),15).".csv";
                    $result = $this->Model_connectdb->whrupdateCN($CNno, $doc1, $invoice,$approval_code, $excess, $shortage, $comment,$path,$date);
                    if ($result) {
                        $status     = $this->Model_connectdb->getstsmsg($status_count + 2);
                        $status_msg = $status[0]['short_form'];
                        $data1      = $this->Model_connectdb->stsupdate($email, $approval_code, $status_msg);
                        if ($data1) {
                            $responsce['message']     = "Credit note uploaded";
                            $response['status_code'] = 200;
                            $fh                   = fopen($path, 'w+');
                            fputcsv($fh, array(
                                'LR NO',
                                'APPROVAL CODE',
                    'SENDING LOCATION',
                    'RECEIVING LOCATION',
                    'DOC NO',
                    'EAN NO',
                    'SEND QTY',
                    'RECEIVED QTY',
                    'EXCESS',
                    'SHORTAGE'
                            ));
                            foreach ($report as $line) {
                                // with this foreach, if value is array, replace it with first array value
                                foreach ($line as $key => $value) {
                                    //$line[$key] = $value;
                                    $data[] = $value;
                                }
                                fputcsv($fh, $data);
                                $data = array();
                                
                                // no need for foreach, as fputcsv expects array, which we already have
                            }
                            
                            fclose($fh);
                            echo json_encode($response);
                        }
                    }
                }
                
                
                
            }
        }
    }
}