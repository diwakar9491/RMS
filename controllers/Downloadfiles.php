<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Downloadfiles extends CI_Controller
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
            // $approval_code = isset($_GET['approval_code']) ? $_GET['approval_code'] : "";
            $path = isset($_GET['path']) ? $_GET['path'] : "";
            // $ebill = isset($_GET['e_waybill']) ? $_GET['e_waybill'] : "";
            // $lr_copy = isset($_GET['lr_copy']) ? $_GET['lr_copy'] : "";
            // $credit_note = isset($_GET['credit_note']) ? $_GET['credit_note'] : "";
            // $invoice = isset($_GET['invoice_pdf']) ? $_GET['invoice_pdf'] : "";
            // if($ebill != "")
            // {
                // $ebillpath = $this->Model_connectdb->getfiles($ebill,"","","",$approval_code);
                // $ebillpatharr = explode(",", $ebillpath[0]['ebill_image']);
                $file = base64_encode(file_get_contents($path));
                echo json_encode($file);
            //}
            // elseif($lr_copy != "")
            // {
            //     $lr_copypath = $this->Model_connectdb->getfiles("",$lr_copy,"","",$approval_code);
            //     $lr_copypatharr = explode(",", $lr_copypath[0]['lr_copy_image']);
            //     for($i = 0;sizeof($lr_copypatharr);$i++)
            //     {
            //     $attachment_location = base64_encode(file_get_contents($lr_copypatharr[$i]));
            //     $imgarr[$i] = $attachment_location;
            //     }

            // }
            // elseif($credit_note != "")
            // {
            //     $credit_note_imagepath = $this->Model_connectdb->getfiles("","",$credit_note,"",$approval_code);
            //     $creditnote_imagepatharr = explode(",", $credit_note_imagepath[0]['credit_note_image']);
            //     for($i = 0;sizeof($creditnote_imagepatharr);$i++)
            //     {
            //     $attachment_location = base64_encode(file_get_contents($creditnote_imagepatharr[$i]));
            //     $imgarr[$i] = $attachment_location;
            //     }
            // }
           // elseif($invoice != "")
           //  {
           //      $invoicepath = $this->Model_connectdb->getfiles("","","",$invoice,$approval_code);
           //      $invoicepatharr = explode(",", $invoicepath[0]['invoice_pdf']);
           //      for($i = 0;sizeof($invoicepatharr);$i++)
           //      {
           //      $attachment_location = base64_encode(file_get_contents($invoicepatharr[$i]));
           //      $imgarr[$i] = $attachment_location;
           //      }
           //  }
           //  if($imgarr != "")
           //  {
           //      json_encode($imgarr);
           //  }
           //  else
           //  {
           //      $array['message'] = "No files found";
           //      $array['status_code'] = 404;
           //      echo json_encode($array);
           //  }



       // $f="WRTF323-09-2019.pdf";
       // $file = ("FILES/Invoice/$f");
       // $filetype=filetype($file);
       // $filename=basename($file);
       // echo json_encode(file_get_contents($file));

                // $filename = "WRTF323-09-2019.pdf";
                // $contenttype = "application/force-download";
                // header("Content-Type: " . $contenttype);
                // header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\";");
                // echo readfile("FILES/Invoice/".$filename,true);
                // exit();


        // $attachment_location = base64_encode(file_get_contents("FILES/IMG/2.png"));//. "2.png";
        // if ($attachment_location) {

        //     // header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        //     // header("Cache-Control: public"); // needed for internet explorer
        //     // header("Content-Type: application/zip");
        //     // header("Content-Transfer-Encoding: Binary");
        //     // header("Content-Length:".filesize($attachment_location));
        //     // header("Content-Disposition: attachment; filename=2.png");
        //    echo $attachment_location;
        // } else {
        //     die("Error: File not found.");
        // }
        }
    }
}
