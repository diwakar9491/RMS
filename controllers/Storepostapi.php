<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Storepostapi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function storepost()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $array     = array();
            $errorarr  = array();
            $flagarr   = array();
            $flag      = 1;
            $exceldata = array();
            $params    = (array) json_decode(file_get_contents('php://input'), true);
            $email     = $params[0]['email_address'];
            $exceldata = $params[1];
            for ($i = 0; $i < sizeof($exceldata); $i++) {
                $store_code   = isset($exceldata[$i]['STORE CODE']) ? $exceldata[$i]['STORE CODE'] : "";
                $store_status = isset($exceldata[$i]['STORE STATUS']) ? $exceldata[$i]['STORE STATUS'] : "";
                $store_brand  = isset($exceldata[$i]['STORE BRAND']) ? $exceldata[$i]['STORE BRAND'] : "";
                if ($store_code != "") {
                } else {
                    $flag         = 0;
                    $row          = $i + 1;
                    $error        = "store code is missing in row $row";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($store_status != "") {
                } else {
                    $flag         = 0;
                    $row          = $i + 1;
                    $error        = "store status is missing in row $row";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($store_brand != "") {
                } else {
                    $flag         = 0;
                    $row          = $i + 1;
                    $error        = "store brand is missing in row $row";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                $flagarr[$i]  = $flag;
                $errorarr[$i] = $errorarr[$i];
            }
            if (in_array("0", $flagarr)) {
                $error                = implode("", $errorarr);
                $array['message']     = "store data not uploaded";
                $array['error']       = substr($error, 1);
                $array['status_code'] = 304;
                echo json_encode($array);
            } else {
                if ($this->Model_connectdb->dltstoredata($email)) {
                    for ($i = 0; $i < sizeof($exceldata); $i++) {
                        $store_code   = isset($exceldata[$i]['STORE CODE']) ? $exceldata[$i]['STORE CODE'] : "";
                        $store_status = isset($exceldata[$i]['STORE STATUS']) ? $exceldata[$i]['STORE STATUS'] : "";
                        $store_brand  = isset($exceldata[$i]['STORE BRAND']) ? $exceldata[$i]['STORE BRAND'] : "";
                        $storename = isset($exceldata[$i]['STORE NAME']) ? $exceldata[$i]['STORE NAME'] : "";
                        if ($store_brand == 'Tommy hilfiger') {
                            $insrtstoredata = $this->Model_connectdb->insrtstoredata($store_code, $store_status, $store_brand, '', $email,$storename);
                        } elseif ($store_brand == 'Calvin klein') {
                            $insrtstoredata = $this->Model_connectdb->insrtstoredata($store_code, $store_status, '', $store_brand, $email,$storename);
                        }
                    }
                } else {
                    $array['message'] = "upload unsuccessfull";
                    echo json_encode($array);
                }
                if ($insrtstoredata) {
                    $array['message']     = "store data uploaded successfully";
                    $array['status_code'] = 200;
                    $storedata            = $this->Model_connectdb->selectstore($store_brand);
                    if ($brand == 'Tommy hilfiger') {
                        if ($storedata != "") {
                            for ($i = 0; $i < sizeof($storedata); $i++) {
                                $array['store_code']   = $storedata[$i]['store_code'];
                                $array['store_status'] = $storedata[$i]['store_status'];
                                $array['store_brand']  = $storedata[$i]['tommy_hilfiger'];
                                $array['store_name']  = $storedata[$i]['store_name'];
                                $storedataarr[$i]      = $array;
                            }
                            echo json_encode($storedataarr);
                        } else {
                            $array['message'] = "unable to get data";
                            echo json_encode($array);
                        }
                    } else {
                        if ($storedata != "") {
                            for ($i = 0; $i < sizeof($storedata); $i++) {
                                $array['store_code']   = $storedata[$i]['store_code'];
                                $array['store_status'] = $storedata[$i]['store_status'];
                                $array['store_brand']  = $storedata[$i]['calvin_klein'];
                                $array['store_name']  = $storedata[$i]['store_name'];
                                $storedataarr[$i]      = $array;
                            }
                            echo json_encode($storedataarr);
                        } else {
                            $array['message'] = "unable to get data";
                            echo json_encode($array);
                        }
                        
                    }
                } else {
                    $array['message'] = "Upload unsuccessfull";
                    echo json_encode($array);
                }
            }
        } else {
            $array['message']     = "Method not allowed";
            $array['status_code'] = 405;
            echo json_encode($array);
        }
    }
}



?>