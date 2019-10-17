<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Insertstatus extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
function insertstatusmaster()
{
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: access"); 
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$params = (array) json_decode(file_get_contents('php://input'), true);
$flag = array();
$array = array();
$error = array();
        for($i=0;$i<sizeof($params);$i++)
            {
                $status_code = isset($params[$i]['status_code']) ? $params[$i]['status_code'] : "";
                $statusmsg = isset($params[$i]['status_description']) ? $params[$i]['status_description'] : "";
                $insrtby = isset($params[$i]['status_insertedby']) ? $params[$i]['status_insertedby'] : "";
                if($statusmsg != "" && $status_code != "")
                    {
                    }
                    else{
                    $flag[$i] = 0;
                    $row = $i+1;
                    $error[$i] = $error[$i]."status description or status code is missing in $row row";
                    }
                    if($insrtby != "")
                    {
                    }
                    else{
                        $flag[$i] = 0;
                        $row = $i+1;
                        $error[$i] = $error[$i]."Inserting persion name is missing in $row row";
                    }
            }

    if(in_array(0,$flag))
    {
        $array['status_code'] = 422;
        $array['message'] = "upload unsuccessfull";
        $array['error'] = implode($error,','); 
        echo json_encode($array);   
    }
    else{
        $dltsts = $this->Model_connectdb->dltstatus();
        if($dltsts)
        {
            for($i=0;$i<sizeof($params);$i++)
            {   
                $count = $this->Model_connectdb->stscount();
                $stscount = $count[0]['max'];
                $stsno = $stscount+1;
                $status_code = isset($params[$i]['status_code']) ? $params[$i]['status_code'] : "";
                $statusmsg = isset($params[$i]['status_description']) ? $params[$i]['status_description'] : "";
                $insrtby = isset($params[$i]['status_insertedby']) ? $params[$i]['status_insertedby'] : "";
                $data = $this->Model_connectdb->insrtstatus($status_code,$statusmsg,$insrtby,$stsno);
            }
        if($data)
        { 
            $array['status_code'] = 200;
            $array['message'] = "upload successfull";
            echo json_encode($array);
        }
        else{
            echo "else";die;
        }
        }
    }
}
else{
    $array['status_code'] = 405;
    $array['message'] = "method not allowed";
    echo json_encode($array);
}
}
}
?>