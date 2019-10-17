<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Forgotpasswordapi extends CI_Controller {
    public function __construct()
{
parent::__construct();
$this->load->database();
$this->load->model('Model_connectdb');
}
function forgotpassword()
{
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: access"); 
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$params = (array) json_decode(file_get_contents('php://input'),true);
$postemail = isset($params['email']) ?$params['email'] : "";
$postpwd = isset($params['password']) ? $params['password'] : "";
$postotp = isset($params['otp']) ?$params['otp'] : "";
$pgqry = $this->Model_connectdb->userdata($postemail);
$email =$pgqry[0]['user_email'];
$getotpqry="";
$rmngcount = 0;
$otp = rand(1000,5000);
$array = array();
if ($email != "" && $postemail != "" && $postotp == "" && $postpwd == "")
{
// if(mail('divakartheretailinsights.com', 'Your Recovered Password', 'Please use this password to login :1234', 'From : diwakar@codingcyber.com')){
// 	$array['message'] = "Your Password has been sent to your email id";
//  echo json_encode($array);
$getotpqry =$this->Model_connectdb->selectotptble($postemail);
$dbotp = isset($getotpqry[0]['otp']) ? $getotpqry[0]['otp'] : "";
$count = isset($getotpqry[0]['count']) ? $getotpqry[0]['count'] : 0;
if($dbotp != "")
{
    if($count < 3 && $count != 0)
    {
        $updtcount = $count+1;
        $rmngcount = 3 - $updtcount;
        $otpupdate = $this->Model_connectdb->updateotp($postemail,$otp,$updtcount);
        $array['message'] = "resent successfull";
        echo json_encode($array);
    }
    if($rmngcount == 0)
    {
        $array['message'] = "maximum number of resend otp is over please try again later";
        echo json_encode($array);
    }
    else
    {
        $array['message'] = "Otp sent to $postemail and $rmngcount more attempts remaining";
    echo json_encode($array);
    }
    
    
}
else
{
$otpinsert =$this->Model_connectdb->insrtotp($otp,$postemail);
$array['message'] = "otp sent to $postemail";
echo json_encode($array);
}
// }
}
elseif($postemail != "" && $email == "")
{
    $array['message'] = "Unauthorized email id";
    echo json_encode($array);
}
if ($postotp != "")
{
$getotpqry = $this->Model_connectdb->selectotp($postemail);
$sentotp = $getotpqry[0]['otp'];
if($sentotp != "")
{
if($sentotp == $postotp)
{
    $array['message'] = "Correct otp";
    echo json_encode($array);
    $dltotp = $this->Model_connectdb->deleteotp($postemail);
}
else
{
$array['message'] = "Incorrect otp";
echo json_encode($array);
}
}
}

 if($postpwd != "")
{
$getpwdqry = $this->Model_connectdb->selectpwd($postemail);
$pwd = $getpwdqry[0]['user_password'];
if($pwd == md5($postpwd))
{
    $array['message'] = "old password and new password should not be same.";
    echo json_encode($array);
}
else
{
    $encpwd = md5($postpwd);
    $updtpwdqry = $this->Model_connectdb->updatetpwd($postemail,$encpwd);
    $array['message'] = "password updated successfully";
    echo json_encode($array);
}
}

}
}
}
?>