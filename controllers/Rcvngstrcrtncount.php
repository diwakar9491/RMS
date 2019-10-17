<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rcvngstrcrtncount extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function crtnpodupdate()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $params = (array) json_decode(file_get_contents('php://input'), true);
               // for ($i = 0; $i < sizeof($params); $i++) {
                $apprlcode       = isset($params['approval_code']) ? $params['approval_code'] : "";
                $email       = isset($params['email_address']) ? $params['email_address'] : "";
                $comment       = isset($params['comment']) ? $params['comment'] : "";


                $no_corton_boxes = isset($params['no_of_carton_boxes']) ? $params['no_of_carton_boxes'] : "";
                $pod_date        = isset($params['POD_date']) ? $params['POD_date'] : "";
                $status_count    = isset($params['status_count']) ? $params['status_count'] : "";
                    $podupdate = $this->Model_connectdb->updatewithpod($apprlcode, $no_corton_boxes, $pod_date,$comment);
                    if ($podupdate) {
                        $status     = $this->Model_connectdb->getstsmsg($status_count + 1);
                        $status_msg = $status[0]['short_form'];
                        if ($status_msg) {
                            $stsupdate = $this->Model_connectdb->stsupdate($email,$apprlcode, $status_msg);
                        }
                    }
                //}
            // }
            if ($stsupdate) {
                $array['message']     = "corton count uploaded";
                $array['status_code'] = 200;
                $array['status'] = $status_msg;
                echo json_encode($array);
            }
            else
            {
                $array['message'] = "failed to upload";
                $array['status_code'] = 304;
                echo json_encode($array);
            }
        }
    }
}