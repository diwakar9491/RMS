<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Generateapproval_code extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function approval_codegen()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $params = (array) json_decode(file_get_contents('php://input'), true);


        for($i=0;$i<sizeof($params);$i++)
        {
            $result = $this->Model_connectdb->generateapprovalcode($params[$i]['duplicate_approvalcode']);
            // $apprvlcodestring =$apprvlcodestring . ",'".$params[$i]['duplicate_approvalcode']."'";
        // if (!array_key_exists($params[$i]['store_code'], $storecodes) {
        //     $storeemail[$i] =  $this->Model_connectdb->selectstoreemail($params[$i]['store_code']);         
        //     $storecodes[$params[$i]['store_code']] = 1;
        // }
        // else
        // {
        // $storecodes[$params[$i]['store_code']] = $storecodes[$params[$i]['store_code']] + 1;
        // }

        }


    
//      $keys = array_keys($storecodes);
//         for ($i=0; $i < sizeof($storeemail); $i++) { 
//             $to = "$storeemail[$i]";
//         $subject = "Returns Initiated";
//         $txt = "<html>
// <head>
// <title>Planner Initiated</title>
// </head>
// <body>
// <p style='color:blue;font-size:50px;font-family:Helvetica;'><b>$storecodes[$params[$i]['store_code']]</b> Returns Initiated from your store ($storecodes[$keys[$i]]) please login and check</p>
// </body>
// </html>";
//          $headers = 'From: divakar@theretailinsights.com';// . "\r\n" .
//         // "CC: somebodyelse@example.com";
//          $headers = 'MIME-Version: 1.0' . '\r\n';
//         $headers .= 'Content-type:text/html;charset=UTF-8' . '\r\n';

//         mail($to,$subject,$txt,$headers);

//         }
        if($result)
        {
            $array['status_code'] = 200;
            echo json_encode($array);
        }
        else
        {
            $array['status_code'] = 304;
            echo json_encode($array);
        }               
        }
    }
}