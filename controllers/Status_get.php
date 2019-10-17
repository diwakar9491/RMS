<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Status_get extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function statuses()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $role = isset($_GET['role']) ? $_GET['role'] : "";
            $type = isset($_GET['store_type']) ? $_GET['store_type'] : "";
            $status = $this->Model_connectdb->getstatusmaster();
            switch ($role) { 
                case 'store': //getting status for store
                if($type == 'COCO')
                {
                $array[0] = $status[2];
                $array[1] = $status[3];
                $array[2] = $status[5];
                $array[3] = $status[6];
                echo json_encode($array);
                }
                elseif($type == 'FOCO')
                {
                $array[0] = $status[4];
                $array[1] = $status[5];
                $array[2] = $status[6];
                }
                    break;
                case 'warehouse':   //getting status for warehouse
                if($type == 'COCO')
                {
                $array[0] = $status[3];
                $array[1] = $status[5];
                $array[2] = $status[6];
                $array[3] = $status[7];
                $array[4] = $status[8];
                $array[5] = $status[9];
                echo json_encode($array);
                }
                elseif ($type == 'FOCO') {
                $array[0] = $status[4];
                $array[1] = $status[5];
                $array[2] = $status[6];
                $array[3] = $status[7];
                $array[4] = $status[8];
                $array[5] = $status[9];
                }
                break;
                case 'planner':     //getting status for planner
                $array[0] = $status[0];
                $array[1] = $status[1];
                $array[2] = $status[2];
                $array[3] = $status[3];
                $array[4] = $status[4];
                $array[5] = $status[5];
                $array[5] = $status[6];
                $array[5] = $status[7];
                $array[5] = $status[8];
                $array[5] = $status[9];
                $array[5] = $status[10];
                echo json_encode($array);
                    break;
                default:
                $array['message'] = "please pass role of user";
                $array['status_code'] = 304;
                echo json_encode($array);
                    break;
            }
        }
    }
}