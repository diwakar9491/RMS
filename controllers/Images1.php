<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Images extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function img()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $image_no="2";
                $params  = (array) json_decode(file_get_contents('php://input'), true);
                $image = $params[0]['image'];
                print_r($params);die;
                $path = "IMG/".$image_no.".jpeg";
                $size_in_bytes = (int) (strlen(rtrim($image, '=')) * 3 / 4);
                $status = file_put_contents($path,$image);
                if($status){
                 echo "Successfully Uploaded";
                }else{
                 echo "Upload failed";
                }
        }
    }
}
