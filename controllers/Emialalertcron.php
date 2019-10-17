<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Emialalertcron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function alert()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data        = $this->Model_connectdb->getupdatedtimeofreturns();
            $currenttime = date('d-m-Y h:i:s A');
            for ($i = 0; $i < sizeof($data); $i++) {
                $status = $data[$i]['status'];
                $count = $data[$i]['alert_count'];
                $approval_code = $data[$i]['approval_code'];

                if ($status == 'Open') {
                    $insertingtime = $data[$i]['dateof_initiated'];
                    $difftime      = (strtotime($currenttime) - strtotime($insertingtime)) / 60;
                    if ($difftime > 5) {
                        $storecode        = $data[$i]['receiving_strwhr_code'];
                        $storedata        = $this->Model_connectdb->storecode($storecode);
                        $nearby_warehouse = $storedata[0]['nearby_warehouse'];
                        if ($nearby_warehouse != "") {
                            $emaildata = $this->Model_connectdb->selectstoreemail($nearby_warehouse);
                            $email     = $emaildata[0]['user_email'];
                            $this->emailtemplate($to,$subject,$txt,$headers,$count,$approval_code);
                            
                        } else {
                            $emaildata = $this->Model_connectdb->selectstoreemail($storecode);
                            $email     = $emaildata[0]['user_email'];
                           $this->emailtemplate($to,$subject,$txt,$headers,$count,$approval_code);
                        }
                    }
                } else {
                    $updatedtime = $data[$i]['updated_at'];
                    $difftime    = (strtotime($currenttime) - strtotime($updatedtime)) / 60;
                    if ($difftime > 5) {
                        $storecode        = $data[$i]['receiving_strwhr_code'];
                        $storedata        = $this->Model_connectdb->storecode($storecode);
                        $nearby_warehouse = $storedata[0]['nearby_warehouse'];
                        if ($nearby_warehouse != "") {
                            $emaildata = $this->Model_connectdb->selectstoreemail($nearby_warehouse);
                            $email     = $emaildata[0]['user_email'];
                            $this->emailtemplate($to,$subject,$txt,$headers,$count,$approval_code);
                            
                        } else {
                            $emaildata = $this->Model_connectdb->selectstoreemail($storecode);
                            $email     = $emaildata[0]['user_email'];
                            $this->emailtemplate($to,$subject,$txt,$headers,$count,$approval_code);
                        }
                    }
                }
                
            }
        }
        
    }
    function emailtemplate($to,$subject,$txt,$headers,$count,$approval_code)
    {
                $to      = "$email";
                $subject = "Email alert";
                $txt = "<html>
    <head>
    <title>Your tansaction is still pending in $status status</title>
    </head>
    <body>
    <p>Please login and check your trasaction status</p>
    </body>
    </html>
    ";
                $headers = "From: divakar@theretailinsights.com"; // . "\r\n" .
                // "CC: somebodyelse@example.com";
                // "CC: somebodyelse@example.com";
                $headers .= "MIME-Version: 1.0";
                $headers .= "Content-type:text/html;charset=UTF-8";
                
                mail($to, $subject, $txt, $headers);
                $result = $this->Model_connectdb->mailtrigertime($currenttime,$count,$approval_code);
                echo $result;
    }
}