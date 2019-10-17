<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Workcompletedinwarehouse extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }

    function completed()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $store_code = isset($_GET['store_code']) ? $_GET['store_code'] : "";
        if($store_code != "")
        {
            $data = $this->Model_connectdb->workcpmpletedinwhr($store_code);
            echo json_encode($data);
        }
        }
    }
}