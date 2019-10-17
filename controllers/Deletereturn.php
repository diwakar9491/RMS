<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Deletereturn extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
function selecteddlt()
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-llow-Headers: access");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $returnsdataarr = array();
    $params = (array) json_decode(file_get_contents('php://input'), TRUE);
    $postemail = isset($params[0]['email_address']) ? $params[0]['email_address'] : "";
    $posttoken = isset($params[0]['token']) ? $params[0]['token'] : "";
    // $barcode = isset($params['barcode']) ? $params['barcode'] : "";

    if($posttoken != "" && $postemail != "")
    {
        $dbAccess_token = $this->Model_connectdb->gettoken($posttoken);
        $Access_token = $dbAccess_token[0]['token'];
        if($posttoken == $Access_token)
        {
            for($i=1;$i<sizeof($params);$i++)
            {
                $returnsdataarr[$i-1] ="'".$params[$i]['barcode']."'";
            }
        }
    }
   $str = implode($returnsdataarr,",");
   $this->Model_connectdb->deletereturn($str);
}
}
?>