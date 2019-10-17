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
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        // echo $_REQUEST;
        // die();


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $params = (array) json_decode(file_get_contents('php://input'), true);
        $lorry_receipt_no = isset($params[0][0]['lorry_receipt_no']) ? $params[0][0]['lorry_receipt_no'] : "";
        $e_waybill = isset($params[0][0]['e_waybill']) ? $params[0][0]['e_waybill'] : "";
        $approval_code = isset($params[0][0]['approval_code']) ? $params[0][0]['approval_code'] : "";
        $credit_note_no = isset($params[0][0]['credit_note_no']) ? $params[0][0]['credit_note_no'] : "";
        $pod_image = isset($params[0][0]['pod_image']) ? $params[0][0]['pod_image'] : "";
        $odn_image = isset($params[0][0]['odn_image']) ? $params[0][0]['odn_image'] : "";

        $imagearr = isset($params[1]) ? $params[1] : "";
        $invoicearr = isset($params[2]) ? $params[2] : "";
        // print_r($invoicearr);die;
        $imagepatharr = array();
        $invicepatharr = array();
        if($imagearr != "" || $invoicearr != "")
        {
            for($i = 0;$i<sizeof($imagearr); $i++)
                {
                $imgdata = base64_decode($imagearr[$i]['image']);
                $invoicedata = base64_decode($invoicearr[$i]['invoice']);
                $f = finfo_open();
                $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
                $invoice_type = finfo_buffer($f, $invoicedata, FILEINFO_MIME_TYPE);
                if($mime_type == 'image/png' || $mime_type == 'image/jpg' || $mime_type == 'image/jpeg' || $mime_type == 'application/pdf' )
                {
                    if ($mime_type == 'image/png') {
                $path = "FILES/IMG/".$approval_code.$i.substr(microtime(),15).".png";
                $status = file_put_contents($path,$imgdata);
                $imagepatharr[$i] = $path;                    
                }
                elseif ($mime_type == 'image/jpeg') {
                $path = "FILES/IMG/".$approval_code.$i.substr(microtime(),15).".jpeg";
                $status = file_put_contents($path,$imgdata);
                $imagepatharr[$i] = $path;               
                 }
                 elseif ($mime_type == 'image/jpg') {
                $path = "FILES/IMG/".$approval_code.$i.substr(microtime(),15).".jpg";
                $status = file_put_contents($path,$imgdata);
                $imagepatharr[$i] = $path;                 
                    }
                elseif ($mime_type == 'application/pdf') {
                $path = "FILES/IMG/".$approval_code.$i.substr(microtime(),15).".pdf";
                $status = file_put_contents($path,$imgdata);
                $imagepatharr[$i] = $path;                 
                    }
                }
                // if($mime_type == 'image/jpg')
                // {
                // $path = "FILES/IMG/".$image_no.".jpg"; 
                // $status = file_put_contents($path,base64_decode($imgdata));                  
                // }
                if($invoice_type == 'application/xls' || $invoice_type == 'application/zip' || $invoice_type == 'application/xlsx')
                {
                // $invoicedata = base64_decode($invoice);
                // $f = finfo_open();
                // $mime_type = finfo_buffer($f, $invoicedata, FILEINFO_MIME_TYPE);
                    if($invoice_type == 'application/xls'){
                $path = "FILES/Invoice/".$approval_code.$i.substr(microtime(),15).".xls";
                $status = file_put_contents($path,$invoicedata);
                $invicepatharr[$i] = $path;
                    }
                    else
                    {
                $path = "FILES/Invoice/".$approval_code.$i.substr(microtime(),15).".xlsx";
                $status = file_put_contents($path,$invoicedata);
                $invicepatharr[$i] = $path;
                    }
                }
        }
        $imagepathstr = implode(",",$imagepatharr);
        $invoicepathstr = implode(",", $invicepatharr);

        if($status){
                // if($lorry_receipt_no != "" || $e_waybill != "" || $credit_note_no != "")
                // {

                    if($lorry_receipt_no != "")
                    {
                    $result = $this->Model_connectdb->updatefiles($imagepathstr,"","","","","",$approval_code);
                }
                elseif($e_waybill != "")
                {
                    $result = $this->Model_connectdb->updatefiles("",$imagepathstr,"","","","",$approval_code);

                }
                elseif($credit_note_no != "")
                {
                    $result = $this->Model_connectdb->updatefiles("","",$imagepathstr,"","",$invoicepathstr,$approval_code);
                }
                elseif($pod_image != "")
                {
                    $result = $this->Model_connectdb->updatefiles("","","",$imagepathstr,"","",$approval_code);
                }
                elseif($odn_image != "")
                {
                    $result = $this->Model_connectdb->updatefiles("","","","",$imagepathstr,"",$approval_code);
                }
                    if($result)
                    {
                $array['message'] = "Upload success";
                $array['status_code'] = 200;
                echo json_encode($array);    
                    }
                //}
            }
            else
            {
                $array['message'] = "Upload unsuccess";
                $array['status_code'] = 304;
                echo json_encode($array);
            }
            }
        // elseif($image == "" && $invoice != "")
        // {
        //         $invoicedata = base64_decode($invoice);
        //         $f = finfo_open();
        //         $mime_type = finfo_buffer($f, $invoicedata, FILEINFO_MIME_TYPE);
        //     if($mime_type == 'image/pdf')
        //         {
        //         $path = "FILES/Invoice/".$image_no.".pdf";
        //         $status = file_put_contents($path,base64_decode($invoicedata));
        //         }
        // }

        //         // $size_in_bytes = (int) (strlen(rtrim($image, '=')) * 3 / 4);

        //         if($status){
        //          echo json_encode("Successfully Uploaded");
        //         }else{
        //          echo json_encode("Upload failed");
        //         }
    // $data = base64_decode($image);
    // $im = imagecreatefromstring($data);
    // $width = imagesx($im);
    // $height = imagesy($im);
    // $newwidth = $width * $percent;
    // $newheight = $height * $percent;

    // $thumb = imagecreatetruecolor($newwidth, $newheight);

    // // Resize
    // imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                
                // }
                // else
                // {
                //     $array['message'] = "size must less than 500 kb";
                //     $array['status_code'] = 304;
                // }
    //             $percent = 0.3;

    //             // $image = imagecreatefrompng($image);

    // $data = base64_decode($image);
    // $im = imagecreatefromstring($data);
    // $width = imagesx($im);
    // $height = imagesy($im);
    // $newwidth = $width * $percent;
    // $newheight = $height * $percent;
    // $thumb = imagecreatetruecolor($newwidth, $newheight);
    // $immage = imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    // // $size_in_bytes = (int) (strlen(rtrim($thumb, '=')) * 3 / 4);
    // $compressedimage = imagejpg($thumb);
    // // $filesize = $_FILES["$compressedimage"]["size"];
    
    //             $path = "IMG/".$image_no.".jpg";
                

    //             if($status){
    //              echo "Successfully Uploaded";
    //             }else{
    //              echo "Upload failed";
    //             }






                
            // $params = (array) json_decode(file_get_contents('php://input'), true);
            // echo $params[0]['image'];
            // $image = isset($params[0])
            // $target_dir = "IMG/";
            // $url = $SERVER['REQUEST_URI'];
            // $parts = explode('/',$url);
            // $dir = "http://".$SERVER['rms.rstag.xyz'];
            // for ($i=0; $i < count($parts); $i++) { 
            //     $dir.=$parts[$i]."/";   
            // }
            // $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            // $target_file = $target_dir ;//. basename($_FILES["EWAY"]["apprlimgs"]);
            // $uploadOk = 1;
            // $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            // // if(isset($_POST["submit"]))
            // // {
            // //     $check = getimagesize($_FILES["selectFile"]["tmp_name"]);
            // //     if($check !== false)
            // //     {
            // //         echo "File is an image - ".$check["mime"]. ".";
            // //         $uploadOk = 1;
            // //     }
            // //     else
            // //     {
            // //         echo "File is not an image.";
            // //         $uploadOk = 0;
            // //     }
            // // }
            // if(file_exists($target_file))
            // {
            //     echo "sorry, File already exists";
            //     $uploadOk = 0;
            // }
            // if($uploadOk == 0)
            // {
            //     echo "sorry file not uploaded.";

            // }
            // // else
            // // {
            //     if(move_uploaded_file($_FILES["selectFile"]["tmp_name"], $target_file))
            //     {
            //         echo $dir.$target_file;

            //     }
            //     else
            //     {
            //         echo "sorry, there was an error uploading file.";
            //     }
            // //}
        }
    }
}
