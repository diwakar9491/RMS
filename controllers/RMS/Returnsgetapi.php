<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Returnsgetapi extends CI_Controller {
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
header("Access-Control-Allow-Headers: access"); 
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 

$params = (array) json_decode(file_get_contents('php://input'), TRUE);
$postemail = isset($params['email_address']) ? $params['email_address'] : "";
$posttoken = isset($params['token']) ? $params['token'] : "";
if($posttoken != "" && $postemail != "")
{
    $dbAccess_token = $this->Model_connectdb->gettoken($posttoken);
    $Access_token = $dbAccess_token[0]['token'];
    if($posttoken == $Access_token)
    {

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
$array = array();
$returnsarr = array();
$j = 0;
$getreturnsqry =$this->Model_connectdb->selectallreturns();
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
$returnsarr[$j-1] = $array;
}
echo json_encode($returnsarr);
}
else{
    $array['message'] = "Method not allowed";
    echo json_encode($array);
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