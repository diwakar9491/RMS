<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Errorlog_getapi extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
function geterror_log()
{
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-llow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $j            = 0;
    // $params = (array) json_decode(file_get_contents('php://input'), true);
    // $email = isset($params['email_address']) ? $params['email_address'] : "";
    $email = $_GET['email_address'];
    $excelnoqry   = $this->Model_connectdb->selecterrorlogexcelno($email);
    $excelno      = $excelnoqry[0]['max'];
    $returnsexcelnoqry   = $this->Model_connectdb->selectreturnsexcelno($email);
    $returnsexcelno      = $returnsexcelnoqry[0]['max'];
    $err_returnsexlnoqry   = $this->Model_connectdb->selecterr_returnexcelno($email);
    $err_returnsexcelno      = $err_returnsexlnoqry[0]['max'];
    if($err_returnsexcelno != $returnsexcelno)
    {
    $errordataqry =$this->Model_connectdb->selectallerror_log($excelno,$email);
    for ($i=0;$i<sizeof($errordataqry);$i++) {
        $j++;
        $array['error_code']            = $errordataqry[$i]['error_code'];
        $array['issuing_store_code']    = $errordataqry[$i]['issuing_store_code'];
        $array['issuing_store_name']    = $errordataqry[$i]['issuing_store_name'];
        $array['barcode']               = $errordataqry[$i]['barcode'];
        $array['line']                  = $errordataqry[$i]['line'];
        $array['style_code']            = $errordataqry[$i]['style_code'];
        $array['size']                  = $errordataqry[$i]['size'];
        $array['receiving_strwhr_code'] = $errordataqry[$i]['receiving_strwhr_code'];
        $array['receiving_store_name']  = $errordataqry[$i]['receiving_store_name'];
        $array['transaction_type']      = $errordataqry[$i]['transaction_type'];
        $array['error_message']         = $errordataqry[$i]['error_description'];
        $array['excel_no']              = $errordataqry[$i]['excel_no'];
        $array['qty']              = $errordataqry[$i]['qty'];
        $returnsdataarr[$j - 1]         = $array;
    }
    echo json_encode($returnsdataarr);
}
else
{
    $array['message'] = "No errors";
    $array['status_code'] = 200;
    echo json_encode($array);
}
}
else{
    $array['message'] = "Method not allowed.";
    echo json_encode($array);
}
}
}
?>