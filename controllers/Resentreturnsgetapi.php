<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Resentreturnsgetapi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function Resentreturnsget()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        
//isset($params['email_address']) ? $params['email_address'] : "";
        // $posttoken = isset($params['token']) ? $params['token'] : "";
            // $dbAccess_token = $this->Model_connectdb->gettoken($posttoken);
            // $Access_token   = $dbAccess_token[0]['token'];
            // if ($posttoken == $Access_token) {
                
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                    $params    = (array) json_decode(file_get_contents('php://input'), TRUE);
                    $postemail = $_GET['email_address'];
                    $returnsdataarr = array();
                    $array          = array();
                    if (/*$posttoken != "" &&*/ $postemail != "") {
                    $excell_noqry = $this->Model_connectdb->selectreturnsexcelno($postemail);
                    $dbexcell_no  = $excell_noqry[0]['max'];

                    $getreturnsqry = $this->Model_connectdb->selectreturns($dbexcell_no,$postemail);
                    for ($i = 0; $i < sizeof($getreturnsqry); $i++) {
                        $array['staus_code']               = "200";
                        $array['issuing_store_code']    = $getreturnsqry[$i]['issuing_store_code'];
                        $array['issuing_store_name']    = $getreturnsqry[$i]['issuing_store_name'];
                        $array['barcode']              = $getreturnsqry[$i]['barcode'];
                        $array['line']                  = $getreturnsqry[$i]['line'];
                        $array['style_code']            = $getreturnsqry[$i]['style_code'];
                        $array['size']                  = $getreturnsqry[$i]['size'];
                        $array['qty']                   = $getreturnsqry[$i]['qty'];
                        $array['receiving_strwhr_code'] = $getreturnsqry[$i]['receiving_strwhr_code'];
                        $array['receiving_store_name']  = $getreturnsqry[$i]['receiving_store_name'];
                        $array['transaction_type']      = $getreturnsqry[$i]['transaction_type'];
                        $array['status']                = $getreturnsqry[$i]['status'];
                        $array['approval_code']         = $getreturnsqry[$i]['approval_code'];
                        $returnsdataarr[$i]         = $array;
                    }
                    echo json_encode($returnsdataarr);
                    }
        else{
            $array['message'] = "Email id missing";
            echo json_encode($array);
        }
                }
            // }
            // else{
            //     $array['message'] = "Invalid access token";
            //     echo json_encode($array);
            // }
        
    }
}
?>