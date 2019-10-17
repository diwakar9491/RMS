<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Returntypeget extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
function getreturntypes()
{
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: access"); 
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $array = array();
    $returntypedataarr = array();
    $brand = $_GET['brand'];
    // $params = (array) json_decode(file_get_contents('php://input'),true);
    // $email       =  isset($params['email_address']) ? $params['email_address'] : "";
    $returntypedata = $this->Model_connectdb->selectreturntype($brand);
    if($brand == 'tommy_hilfiger')
    {
    if($returntypedata != "")
    {
        for($i=0;$i<sizeof($returntypedata);$i++)
        {
        $array['return_type'] = $returntypedata[$i]['return_type'];
        $array['return_description'] = $returntypedata[$i]['return_type_description'];
        $array['return_workflow']  = $returntypedata[$i]['return_workflow'];
        $returntypedataarr[$i] = $array;
        }
        echo json_encode($returntypedataarr);
    }
    else{
        $array['message'] = "unable to get data";
        echo json_encode($array);
    }
}else{
    if($returntypedata != "")
    {
        for($i=0;$i<sizeof($returntypedata);$i++)
        {
            $array['return_type'] = $returntypedata[$i]['return_type'];
            $array['return_description'] = $returntypedata[$i]['return_type_description'];
            $array['return_workflow']  = $returntypedata[$i]['return_workflow'];
        $returntypedataarr[$i] = $array;
        }
        echo json_encode($returntypedataarr);
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