<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Email extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function send()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$to = "divakarparuchuri123@gmail.com";
        $subject = "Email alert";
        $txt = "esfcubljjyhfx";
         $headers = "From: divakar@theretailinsights.com";// . "\r\n" .
        // "CC: somebodyelse@example.com";

       echo  mail($to,$subject,$txt,$headers);
    }
}
}