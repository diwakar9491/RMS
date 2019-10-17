<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Warehousepostapi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function warehousepost()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errorarr  = array();
            $array     = array();
            $error     = "";
            $flag      = 1;
            $flagarr   = array();
            $params    = (array) json_decode(file_get_contents('php://input'), true);
            $email     = $params[0]['email_address'];
            $exceldata = $params[1];
            for ($i = 0; $i < sizeof($exceldata); $i++) {
                $whrcode   = isset($exceldata[$i]['WAREHOUSE CODE']) ? $exceldata[$i]['WAREHOUSE CODE'] : "";
                $whrstatus = isset($exceldata[$i]['WAREHOUSE STATUS']) ? $exceldata[$i]['WAREHOUSE STATUS'] : "";
                $whrbrand  = isset($exceldata[$i]['WAREHOUSE BRAND']) ? $exceldata[$i]['WAREHOUSE BRAND'] : "";
                $whrname = isset($exceldata[$i]['WAREHOUSE NAME']) ? $exceldata[$i]['WAREHOUSE NAME'] : "";

                if ($whrcode != "") {
                } else {
                    $flag         = 0;
                    $row          = $i + 1;
                    $error        = "warehouse code is missing in row $row";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($whrstatus != "") {
                } else {
                    $flag         = 0;
                    $row          = $i + 1;
                    $error        = "warehouse status is missing in row $row";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($whrbrand != "") {
                } else {
                    $flag         = 0;
                    $row          = $i + 1;
                    $error        = "warehouse brand is missing in row $row";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($whrname != "") {
                } else {
                    $flag         = 0;
                    $row          = $i + 1;
                    $error        = "warehouse name is missing in row $row";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                $flagarr[$i]  = $flag;
                $errorarr[$i] = $errorarr[$i];
            }
            if (in_array("0", $flagarr)) {
                $array['message']     = "upload unsuccessfull";
                $array['status_code'] = 304;
                $array['error']       = substr(implode("", $errorarr), 1);
                echo json_encode($array);
            } else {
                if ($this->Model_connectdb->dltwarehousedata($email)) {
                    for ($i = 0; $i < sizeof($exceldata); $i++) {
                        $whrcode   = isset($exceldata[$i]['WAREHOUSE CODE']) ? $exceldata[$i]['WAREHOUSE CODE'] : "";
                        $whrstatus = isset($exceldata[$i]['WAREHOUSE STATUS']) ? $exceldata[$i]['WAREHOUSE STATUS'] : "";
                        $whrbrand  = isset($exceldata[$i]['WAREHOUSE BRAND']) ? $exceldata[$i]['WAREHOUSE BRAND'] : "";
                        $whrname = isset($exceldata[$i]['WAREHOUSE NAME']) ? $exceldata[$i]['WAREHOUSE NAME'] : "";
                        if ($whrbrand == 'Tommy hilfiger') {
                            $insrtwarehouse = $this->Model_connectdb->insrtwarehouse($whrcode, $whrstatus, $whrbrand, '', $email,$whrname);
                        } elseif ($whrbrand == 'Calvin klein') {
                            $insrtwarehouse = $this->Model_connectdb->insrtwarehouse($whrcode, $whrstatus, '', $whrbrand, $email,$whrname);
                        }
                    }
                }
                if ($insrtwarehouse) {
                    $warehousedata = $this->Model_connectdb->selectwhrdata($whrbrand);
                    $array['message']     = "upladed successfully";
                    $array['status_code'] = 200;
                    if ($warehousedata) {
                        if ($whrbrand == 'Tommy hilfiger') {
                            if ($warehousedata != "") {
                                for ($i = 0; $i < sizeof($warehousedata); $i++) {
                                    $array['warehouse_code']   = $warehousedata[$i]['warehouse_code'];
                                    $array['warehouse_status'] = $warehousedata[$i]['warehouse_status'];
                                    $array['warehouse_brand']  = $warehousedata[$i]['tommy_hilfiger'];
                                    $array['warehouse_name']  = $warehousedata[$i]['warehouse_name'];
                                    $warehousedataarr[$i]      = $array;
                                }
                                echo json_encode($warehousedataarr);
                            } else {
                                $array['message'] = "unable to get data";
                                echo json_encode($array);
                            }
                        } else {
                            if ($warehousedata != "") {
                                for ($i = 0; $i < sizeof($warehousedata); $i++) {
                                    $array['warehouse_code']   = $warehousedata[$i]['warehouse_code'];
                                    $array['warehouse_status'] = $warehousedata[$i]['warehouse_status'];
                                    $array['warehouse_brand']  = $warehousedata[$i]['calvin_klein'];
                                    $array['warehouse_name']  = $warehousedata[$i]['warehouse_name'];
                                    $warehousedataarr[$i]      = $array;
                                }
                                echo json_encode($warehousedataarr);
                            } else {
                                $array['message'] = "unable to get data";
                                echo json_encode($array);
                            }
                            
                        }
                    } else {
                        $array['message']     = "upladed successfully but unable to get data";
                        $array['status_code'] = 200;
                        echo json_encode($array);
                    }
                } else {
                    $array['message']     = "upload unsuccessfull";
                    $array['status_code'] = 304;
                    echo json_encode($array);
                }
            }
        }
    }
}
?>