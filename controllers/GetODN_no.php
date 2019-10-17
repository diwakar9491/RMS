<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class GetODN_no extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function getodn()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $params = (array) json_decode(file_get_contents('php://input'), true);
            $email  = $_GET['email_address'];//isset($params['email_address']) ? $params['email_address'] : "";
            $approval_code = $_GET['approval_code'];//isset($params['approval_code']) ? $params['approval_code'] : "";
            $data   = $this->Model_connectdb->getODN_no($email,$approval_code);
            $array  = array();
            $result = array();
            if ($email) {
                if ($data) {
                    for ($i = 0; $i < sizeof($data); $i++) {
                        $array['date']             = isset($data[$i]['date']) ? $data[$i]['date'] : "";
                        $array['corton_count']     = isset($data[$i]['corton_count']) ? $data[$i]['corton_count'] : "";
                        $array['stock_count']      = isset($data[$i]['stock_count']) ? $data[$i]['stock_count'] : "";
                        $array['outwarddocno_sno'] = isset($data[$i]['outwarddocno_sno']) ? $data[$i]['outwarddocno_sno'] : "";
                        $array['inserted_at']      = isset($data[$i]['inserted_at']) ? $data[$i]['inserted_at'] : "";
                        $result[$i]                = $array;
                    }
                    echo json_encode($result);
                } else {
                    $array['message']     = "Unable to get data";
                    $array['status_code'] = 304;
                    echo json_encode($array);
                }
            } else {
                $array['message']     = "Email is missing";
                $array['status_code'] = 422;
                echo json_encode($array);
            }
        } else {
            $array['message']     = "Method not allowed";
            $array['status_code'] = 405;
        }
    }
}