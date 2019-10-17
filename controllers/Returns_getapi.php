<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Returns_getapi extends CI_Controller
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
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
$array = array();
$openreturnsarr = array();
$plnrreturnsarr = array();
$resultarr = array();
$openreturnsdata = array();
$plnereturnsdata = array();
$j = 0;
$email = $_GET['email_address'];
$brand = $_GET['brand'];

$rtvreturntypes =$this->Model_connectdb->getrtvreturntypes($brand);
for ($i=0; $i < sizeof($rtvreturntypes); $i++) { 
    $rtvreturntypesstr = $rtvreturntypesstr . ",'" . $rtvreturntypes[$i]['return_type'] ."'";
}

$getopenreturnsqry =$this->Model_connectdb->selectaprvlreturns($email,substr($rtvreturntypesstr, 1));

for($i=0;$i<sizeof($getopenreturnsqry);$i++)
{
                $j++;
                $array['issuing_store_code']    = $getopenreturnsqry[$i]['issuing_store_code'];
                $array['issuing_store_name']    = $getopenreturnsqry[$i]['issuing_store_name'];
                $array['receiving_strwhr_code'] = $getopenreturnsqry[$i]['receiving_strwhr_code'];
                $array['receiving_store_name']  = $getopenreturnsqry[$i]['receiving_store_name'];
                $array['transaction_type']      = $getopenreturnsqry[$i]['transaction_type'];
                $array['approval_code']         = $getopenreturnsqry[$i]['approval_code'];
                $array['status']                = $getopenreturnsqry[$i]['status'];
                $array['date']                = $getopenreturnsqry[$i]['dateof_initiated'];
                $array['total_qty']                = $getopenreturnsqry[$i]['qtyof_approvalcode'];
                $array['duplicate_approvalcode']    = $getopenreturnsqry[$i]['duplicate_approvalcode'];
                $array['updated_at'] = $getopenreturnsqry[$i]['updated_at'];


                $statusdisc =$getopenreturnsqry[$i]['status'];
                $stscount = $this->Model_connectdb->getstatuscount($statusdisc);
                $array['status_count'] = $stscount[0]['status_count'];

                $openreturnsarr[$j-1] = $array;
}
echo json_encode($openreturnsarr);
}
else{
    $array['message'] = "Method not allowed";
    $arrray['status_code'] = 405;
    echo json_encode($array);
}
    }
}