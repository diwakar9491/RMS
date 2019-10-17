<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Returntypepostapi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function returnpost()
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
            $brand     = $params[0]['brand'];
            $exceldata = $params[1];
            for ($i = 0; $i < sizeof($exceldata); $i++) {
                $returntype = isset($exceldata[$i]['RETURN TYPE']) ? $exceldata[$i]['RETURN TYPE'] : "";
                $returndesc = isset($exceldata[$i]['RETURN DESCRIPTION']) ? $exceldata[$i]['RETURN DESCRIPTION'] : "";
                $returnwf   = isset($exceldata[$i]['RETURN WORKFLOW']) ? $exceldata[$i]['RETURN WORKFLOW'] : "";
                if ($returntype != "") {
                } else {
                    $flag         = 0;
                    $row          = $i + 1;
                    $error        = "retutn type is missing in row $row";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($returndesc != "") {
                } else {
                    $flag         = 0;
                    $row          = $i + 1;
                    $error        = " return description is missing in row $row";
                    $errorarr[$i] = $errorarr[$i] . ',' . $error;
                }
                if ($returnwf != "") {
                } else {
                    $flag         = 0;
                    $row          = $i + 1;
                    $error        = "return workflow is missing in row $row";
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
                if ($this->Model_connectdb->dltreturntypedata($email)) {
                    for ($i = 0; $i < sizeof($exceldata); $i++) {
                        $returntype = isset($exceldata[$i]['RETURN TYPE']) ? $exceldata[$i]['RETURN TYPE'] : "";
                        $returndesc = isset($exceldata[$i]['RETURN DESCRIPTION']) ? $exceldata[$i]['RETURN DESCRIPTION'] : "";
                        $returnwf   = isset($exceldata[$i]['RETURN WORKFLOW']) ? $exceldata[$i]['RETURN WORKFLOW'] : "";
                        if ($brand == 'Tommy hilfiger') {
                            $insrtreturntype = $this->Model_connectdb->insrtreturntype($returntype, $returndesc, $returnwf, $brand, '', $email);
                        } elseif ($brand == 'Calvin klein') {
                            $insrtreturntype = $this->Model_connectdb->insrtreturntype($returntype, $returndesc, $returnwf, '', $brand, $email);
                        }
                    }
                }
                if ($insrtreturntype) {
                    $array['message']     = "upladed successfully";
                    $array['status_code'] = 200;
                    $returntypedata       = $this->Model_connectdb->selectreturntype($brand);
                    if ($brand == 'Tommy hilfiger') {
                        if ($returntypedata != "") {
                            for ($i = 0; $i < sizeof($returntypedata); $i++) {
                                $array['return_type']        = $returntypedata[$i]['return_type'];
                                $array['return_description'] = $returntypedata[$i]['return_type_description'];
                                $array['return_workflow']    = $returntypedata[$i]['return_workflow'];
                                $returntypedataarr[$i]       = $array;
                            }
                            echo json_encode($returntypedataarr);
                        } else {
                            $array['message'] = "unable to get data";
                            echo json_encode($array);
                        }
                    } else {
                        if ($returntypedata != "") {
                            for ($i = 0; $i < sizeof($returntypedata); $i++) {
                                $array['return_type']        = $returntypedata[$i]['return_type'];
                                $array['return_description'] = $returntypedata[$i]['return_type_description'];
                                $array['return_workflow']    = $returntypedata[$i]['return_workflow'];
                                $returntypedataarr[$i]       = $array;
                            }
                            echo json_encode($returntypedataarr);
                        } else {
                            $array['message'] = "unable to get data";
                            echo json_encode($array);
                        }
                        
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