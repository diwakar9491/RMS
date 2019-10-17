<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Getfiles extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function files()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $approval_code = isset($_GET['approval_code']) ? $_GET['approval_code'] : "";
            $filepaths = $this->Model_connectdb->getfilepaths($approval_code);
            $ebill = explode(",",$filepaths[0]['ebill_image']);
            $lr_copy = explode(",",$filepaths[0]['lr_copy_image']);
            $credit_note_image = explode(",", $filepaths[0]['lr_copy_image']);
            $invoice = explode(",", $filepaths[0]['invoice_pdf']);
            $pod_image = explode("," , $filepaths[0]['pod_image']);
            $imagespathsarr = array();
            $imagespathsarr[0]['e_waybill'] = $ebill;
            $imagespathsarr[1]['lr_copy'] = $lr_copy;
            $imagespathsarr[2]['credit_note'] = $credit_note_image;
            $imagespathsarr[3]['invoice'] = $invoice;
            $imagespathsarr[4]['pod_image'] = $pod_image;
            echo json_encode($imagespathsarr);
        }
    }
}