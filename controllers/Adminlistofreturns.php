<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Adminlistofreturns extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function getreturns()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $array = array();
$returnsarr = array();
$msgarr = array();
$j = 0;
// $params = (array) json_decode(file_get_contents('php://input'), true);
// $email       =  isset($params['email_address']) ? $params['email_address'] : "";
$brand = $_GET['brand'];
$getreturnsqry =$this->Model_connectdb->selectalladminreturns($brand);
// $msgarr['status_code'] = 200;
// $returnsarr['status_code'] = 200;
for($i=0;$i<sizeof($getreturnsqry);$i++)
{
    $j++;
//$array['message'] = "excell uploaded successfully";
$array['issuing_store_code'] = $getreturnsqry[$i]['issuing_store_code'];
$array['issuing_store_name'] = $getreturnsqry[$i]['issuing_store_name'];
$array['barcode'] = $getreturnsqry[$i]['barcode'];
$array['line'] = $getreturnsqry[$i]['line'];
$array['style_code'] = $getreturnsqry[$i]['style_code'];
$array['size'] = $getreturnsqry[$i]['size'];
$array['qty'] = $getreturnsqry[$i]['qty'];
$array['receiving_strwhr_code'] = $getreturnsqry[$i]['receiving_strwhr_code'];
$array['receiving_store_name'] = $getreturnsqry[$i]['receiving_store_name'];
$array['transaction_type'] = $getreturnsqry[$i]['transaction_type'];
$array['excel_no'] = $getreturnsqry[$i]['excel_no'];
$array['status'] = $getreturnsqry[$i]['status'];
$array['approval_code'] = $getreturnsqry[$i]['approval_code'];
$array['date_time'] = $getreturnsqry[$i]['created_at'];
$returnsarr[$j-1] = $array;
}
// $returnsarr[0] = $msgarr;
// $returnsarr[] = $returnsarr;
echo json_encode($returnsarr);
}
else{
    $array['message'] = "Method not allowed";
    $array['status_code'] = 405;
    echo json_encode($array);
}
        }
    }

