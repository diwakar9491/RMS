<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transporterget extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function transporters()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // $params = (array) json_decode(file_get_contents('php://input'), true);
            $brand = $_GET['brand'];
            $tarnarr = array();
            $transporters = $this->Model_connectdb->transporterget($brand);
            // for($i=0;$i<sizeof($transporters);$i++)
            // {
            //     $tarnarr[$i] = $transporters[$i]['transporter_type'];
            // }
            echo json_encode($transporters);
        }
    }
}