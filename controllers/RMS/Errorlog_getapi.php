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
    $returnsdataarr = array();
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
                $j            = 0;
                $excelnoqry   = $this->Model_connectdb->selecterrorlogexcelno();
                $excelno      = isset($excelnoqry[0]['max']) ? $excelnoqry[0]['max'] : 0;
                if($excelno > 0)
                {
                $errordataqry =$this->Model_connectdb->selectallerror_log($excelno);
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
                    $array['planner_id']            = $errordataqry[$i]['planner_id'];
                    $array['planner_name']          = $errordataqry[$i]['planner_name'];
                    $array['error_message']         = $errordataqry[$i]['error_description'];
                    $array['excel_no']              = $errordataqry[$i]['excel_no'];
                    $array['quantity']              = $errordataqry[$i]['qty'];
                    $returnsdataarr[$j - 1]         = $array;
                }
            }
            else
            {
                $array['message'] = "No data in error log.";
                echo json_encode($array);
            }
            }
            else{
                $array['message'] = "Method not allowed.";
            }
                echo json_encode($returnsdataarr);
            }
            else{
                $array['message']  = "Invalid Access token";
                echo json_encode($array);
            }
                    }
            else{
                $array['message'] = "Either Access Token or email id is missing.";
                echo json_encode($array);
                }
    }
}

?>