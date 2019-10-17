<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Returnspostapi extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
function Returnspost()
{
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: access"); 
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if($_SERVER(REQUET_METHOD) === 'PUT')
{
$params = (array) json_decode(file_get_contents('php://input'),true);
$array = array();
if(isset($params['email_address']) != "" && isset($params['updatedby']) && (isset($params['password']) != "" || (isset($params['name']) != "")))
{
	$email = $params['email_address'];
	$updatedby = $params['updatedby'];
	$password = isset($params['password']) ? $params['password'] : "";
	//$parampwd = md5($password);
	if($password != "")
	{
		$pwdvrfnqry = pg_query($dbconn,"select user_password from user_master where user_email = '$email'");
		$pwddata = pg_fetch_row($pwdvrfnqry);
		$pwd = $pwdvrfnqry[0];
		if($pwd == md5($password))
		{
			$flag = false;
			$array['message'] = "Old password and new password should not be same.";
			echo json_encode($array);
		}
		else
		{
			$flag = true;
		}
	}
	$name = isset($params['name']) ? $params['name'] : "";
	if($flag)
	{
	if($password != "" && $name != "")
	{
		
		$usrupdtqry = pg_query($dbconn,"update user_master set user_password = '$password',user_name = '$name' where user_email = '$email'");
		if($usrupdtqry)
		{
			$array['message'] = "user name and password updated successfully.";
			echo json_encode($array);
		}
		else
		{
			$array['message'] = "Not updated";
			echo json_encode($array);
		}
	}
	elseif($password == "" && $name != "")
	{
				$usrupdtqry = pg_query($dbconn,"update user_master set user_name = '$name' where user_email = '$email'");

	}
	}
}
}
}
}
?>