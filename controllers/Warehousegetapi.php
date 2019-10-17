<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Warehousegetapi extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
function getwarehousedetails()
{
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: access"); 
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $array = array();
    $warehousedataarr = array();
    $brand = $_GET['brand'];
    // $params = (array) json_decode(file_get_contents('php://input'),true);
    // $email       =  isset($params['email_address']) ? $params['email_address'] : "";
    $warehousedata = $this->Model_connectdb->selectwhrdata($brand);
    if($brand == 'Tommy hilfiger')
    {
    if($warehousedata != "")
    {
        for($i=0;$i<sizeof($warehousedata);$i++)
        {
        $array['warehouse_code'] = $warehousedata[$i]['warehouse_code'];
        $array['warehouse_status'] = $warehousedata[$i]['warehouse_status'];
        $array['warehouse_brand']  = $warehousedata[$i]['tommy_hilfiger'];
        $array['warehouse_name']  = $warehousedata[$i]['warehouse_name'];
        $warehousedataarr[$i] = $array;
        }
        echo json_encode($warehousedataarr);
    }
    else{
        $array['message'] = "unable to get data";
        echo json_encode($array);
    }
}else{
    if($warehousedata != "")
    {
        for($i=0;$i<sizeof($warehousedata);$i++)
        {
        $array['warehouse_code'] = $warehousedata[$i]['warehouse_code'];
        $array['warehouse_status'] = $warehousedata[$i]['warehouse_status'];
        $array['warehouse_brand']  = $warehousedata[$i]['calvin_klein'];
        $array['warehouse_name']  = $warehousedata[$i]['warehouse_name'];
        $warehousedataarr[$i] = $array;
        }
        echo json_encode($warehousedataarr);
    }
    else{
        $array['message'] = "unable to get data";
        echo json_encode($array);
    }

}

}
}
}

?>