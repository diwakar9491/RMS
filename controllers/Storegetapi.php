<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Storegetapi extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
function getstoredetails()
{
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: access"); 
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $array = array();
    $storedataarr = array();
    $brand = $_GET['brand'];
    // $params = (array) json_decode(file_get_contents('php://input'),true);
    // $email       =  isset($params['email_address']) ? $params['email_address'] : "";
    $storedata = $this->Model_connectdb->selectstore($brand);
    if($brand == 'Tommy hilfiger')
    {
    if($storedata != "")
    {
        for($i=0;$i<sizeof($storedata);$i++)
        {
        $array['store_code'] = $storedata[$i]['store_code'];
        $array['store_status'] = $storedata[$i]['store_status'];
        $array['store_brand']  = $storedata[$i]['tommy_hilfiger'];
        $array['store_name']  = $storedata[$i]['store_name'];
        $storedataarr[$i] = $array;
        }
        echo json_encode($storedataarr);
    }
    else{
        $array['message'] = "unable to get data";
        echo json_encode($array);
    }
}else{
    if($storedata != "")
    {
        for($i=0;$i<sizeof($storedata);$i++)
        {
        $array['store_code'] = $storedata[$i]['store_code'];
        $array['store_status'] = $storedata[$i]['store_status'];
        $array['store_brand']  = $storedata[$i]['calvin_klein'];
        $array['store_name']  = $storedata[$i]['store_name'];
        $storedataarr[$i] = $array;
        }
        echo json_encode($storedataarr);
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