<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Countofstatuses extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function count()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
$email = $_GET['email_address'];
$store_code = $_GET['store_code'];
$warehouse_code = $_GET['warehouse_code'];
// $email = $_GET['email_address'];
$counts = $this->Model_connectdb->getcountofstatus($email,$store_code,$warehouse_code);
for ($i=0; $i < sizeof($counts); $i++) { 
    $countofstatuses[$counts[$i]['status']] = $counts[$i]['count'];
}
$array['Open'] = $countofstatuses['Open'];
$array['PI'] = $countofstatuses['PI'];
$array['SP'] = $countofstatuses['SP'];                    
$array['DC'] = $countofstatuses['DC'];          
$array['TAC'] = $countofstatuses['TAC'];          
$array['EWB'] = $countofstatuses['EWB'];          
$array['DESP'] = $countofstatuses['DESP'];          
$array['SC'] = $countofstatuses['SC'];          
$array['SR'] = $countofstatuses['SR'];          
$array['IC'] = $countofstatuses['IC'];          
$array['ICD'] = $countofstatuses['ICD'];          
$array['CAN'] = $countofstatuses['CAN'];      
echo json_encode($array);    
           }

        }
}