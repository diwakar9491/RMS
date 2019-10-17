<?php
// ini_set('upload_max_filesize', '50M');
// ini_set('post_max_size', '50M');
// ini_set('memory_limit','200M');
// ini_set('max_execution_time','1000');

defined('BASEPATH') OR exit('No direct script access allowed');
class Xceltest extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model_connectdb');
    }
    function xceldata()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-llow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        //     include 'SimpleXLSX.php';

        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     $name = $_FILES["xcel"]["name"];
        //      $data = SimpleXLSX::parse($name);
        //     echo json_encode($data);
        // }
$file = $_FILES['xcel']['name'];
    $tmp_name = $_FILES['xcel']['tmp_name'];
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    $microtime = substr(microtime(),15);
    $newFile = "FILES/IMG/'$microtime'.$extension";
    move_uploaded_file( $tmp_name, $newFile);
//load the excel library
$path = $newFile;
 $this->load->library('Excel');
// include 'libraries/Excel.php';
//read file from path
$objPHPExcel = PHPExcel_IOFactory::load($path);
//get only the Cell Colection
$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
// print_r($cell_collection);die;
//extract to a PHP readable array format
foreach ($cell_collection as $cell) {
    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
 
    //The header will/should be in row 1 only. of course, this can be modified to suit your need.
    if ($row == 1) {
        $header[$row][$column] = $data_value;
    } else {
        $arr_data[$row][$column] = $data_value;
    }
}
 
//send the data in an array format
$data['header'] = $header;
$data['values'] = $arr_data;
$headers = $data['header'][1];
$keys = array_keys($data['header'][1]);
$val = $data['values'];
$params = array();
$j = 0;
 for ($i=2; $i <sizeof($data['values']) ; $i++) {
    for ($j=0; $j < sizeof($headers); $j++) { 
$params[$i-2][$headers[$keys[$j]]] = $data['values'][$i][$keys[$j]];
    }
    // $params[$i-2] = $array[$i-2];
}
    echo json_encode($params);
    }
}
