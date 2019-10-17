<?php
class Model_connectdb extends CI_Model{

function selectuserdata($postemail,$postpwd)
	{
	$query=$this->db->query("SELECT user_name,user_code,user_role,str_whr_code,user_email,user_brand from user_master where user_email='$postemail' and user_password='$postpwd'");
   return $query->result_array();
    }
    function selectstoreemail($storecode)
    {
        $query=$this->db->query("SELECT user_email from user_master where str_whr_code = '$storecode'");
   return $query->result_array();
    }
    function selectreturnsexcelno($email)
    {
        $query = $this->db->query("SELECT max(excel_no) FROM Returns where initiated_by = '$email'");
        return $query->result_array();
    }
    function selectstorenm($strcode)
    {
        $query = $this->db->query("SELECT store_name,store_status,store_type FROM store_master where store_code = '$strcode'");
        return $query->result_array();
    }
    function selectwrhsnm($strcode)
    {
        $query = $this->db->query("SELECT warehouse_name,warehouse_status FROM warehouse_master where warehouse_code = '$strcode'");
        return $query->result_array();
    }
    function selecterrorlogexcelno($email)
    {
        $query = $this->db->query("SELECT max(error_excelno) FROM error_log  where inserted_by = '$email'");
        return $query->result_array();
    }
    function selecterr_returnexcelno($email)
    {
        $query = $this->db->query("SELECT max(excel_no) FROM error_log  where inserted_by = '$email'");
        return $query->result_array();
    }
    function selectapprovalcode($email)
    {
        $query = $this->db->query("SELECT approval_code from returns where approval_code not in ('') 
        and initiated_by = '$email' order by approval_code desc limit 1");
        return $query->result_array();
    }
    function selecterrorcode()
    {
        $query = $this->db->query("SELECT error_code from error_code_master where error_description = 'empty column'");
        return $query->result_array();
    }
    // function insrterrorlog($errorcode,$fromstrcode,$fromstrname,$barcode,$line,$stylecode,$size,$qty,$rcvstrcode,$rcvstrname,$trantype,$errormsg,$excell_no,$email,$errorexcell_no)
    // {
    //     $query = "INSERT INTO error_log(
    //         error_code, issuing_store_code, issuing_store_name, barcode, line, style_code, size, qty, receiving_strwhr_code, receiving_store_name, transaction_type,error_description,excel_no,inserted_by,error_excelno)
    //         VALUES ('$errorcode','$fromstrcode','$fromstrname','$barcode','$line','$stylecode','$size','$qty','$rcvstrcode','$rcvstrname','$trantype','$errormsg','$excell_no','$email','$errorexcell_no')";
    //     return $this->db->query($query);
    // }
    function insrterrorlog($valuesstr)
    {
        $query = "INSERT INTO error_log(
            error_code, issuing_store_code, issuing_store_name, barcode, line, style_code, size, qty, receiving_strwhr_code, receiving_store_name, transaction_type,error_description,excel_no,inserted_by,error_excelno)
            VALUES $valuesstr";
        return $this->db->query($query);
    }
    // function insrtreturns($fromstrcode,$fromstrname,$barcode,$line,$stylecode,$size,$qty,$rcvstrcode,$rcvstrname,$trantype,$excell_no,$apprlcode,$email,$brand)
    // {
        
    //     return $this->db->query("INSERT INTO Returns
    //     ( issuing_store_code, issuing_store_name, barcode, line, style_code, size, qty, receiving_strwhr_code, receiving_store_name, transaction_type, excel_no, status, approval_code,created_at,initiated_by,brand,tranhistory)
    //      VALUES ('$fromstrcode','$fromstrname','$barcode','$line','$stylecode','$size','$qty','$rcvstrcode','$rcvstrname','$trantype','$excell_no','Open','$apprlcode ','$datetime','$email','$brand','$tranhistory')");
    // }
    function insrtreturns($valuesstr)
    {
        
        return $this->db->query("INSERT INTO Returns
        ( issuing_store_code, issuing_store_name, barcode, line, style_code, size, qty, receiving_strwhr_code, receiving_store_name, transaction_type, excel_no, status, approval_code,created_at,initiated_by,brand,tranhistory,apprcode)
         VALUES $valuesstr");
    }
    function selectreturns($excell_no,$email)
    {
        $query = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        barcode, line, style_code, size, qty, receiving_strwhr_code, receiving_store_name,
         transaction_type, status, approval_code,created_at
         from returns where initiated_by = '$email' and excel_no = $excell_no");
        return $query->result_array();
    }
    function selectarrcodereturns($excell_no,$email)
    {
        $query = $this->db->query("SELECT distinct issuing_store_code,issuing_store_name,receiving_store_name,transaction_type,
        receiving_strwhr_code,status, approval_code
         from returns where excel_no " . "='$excell_no' and initiated_by = '$email'");
        return $query->result_array();
    }

    function selectallreturns($email,$excell_no)
    {
            $query = $this->db->query("SELECT distinct issuing_store_code,issuing_store_name,receiving_store_name,transaction_type,
            receiving_strwhr_code,status, approval_code,excel_no
             from returns where initiated_by = '$email'and excel_no = '$excell_no' order by excel_no desc");
            return $query->result_array();

    }
    function proqty($approvalcode)
    {
            $data = $this->db->query("SELECT  qty from returns where approval_code = '$approvalcode'");
            return $data->result_array();
    }

    function selectwf($returntype,$brand)
    {
        if($brand == 'Tommy hilfiger')
        {
        $query = $this->db->query("SELECT return_type,return_workflow from return_type_master where return_type = '$returntype' and tommy_hilfiger = '$brand'");
        return $query->result_array();
        }
        elseif($brand == 'Calvin klein')
        {
        $query = $this->db->query("SELECT return_type,return_workflow from return_type_master where return_type = '$returntype' and calvin_klein = '$brand'");
        return $query->result_array();
        }
    }
    function selectalladminreturns($brand)
    {
        $query = $this->db->query("SELECT issuing_store_code, issuing_store_name, barcode, line, style_code, size, qty, receiving_strwhr_code, receiving_store_name, transaction_type, excel_no, status, approval_code,created_at
        FROM Returns where brand = '$brand'");
        return $query->result_array();
    }
    function selectallerror_log($excelno,$email)
    {
        $query = $this->db->query("SELECT * from error_log where error_excelno = '$excelno' and inserted_by = '$email'");
        return $query->result_array();
    }
    function userdata($postemail)
    {
        $query = $this->db->query("SELECT * from user_master where user_email = '$postemail'");
        return $query->result_array();
    }
    function selectotptble($postemail)
    {
        $query = $this->db->query("SELECT otp,count from otp where useremail='$postemail'");
        return $query->result_array();   
    }
    function insrtotp($otp,$postemail)
    {
        return $this->db->query("INSERT into otp(otp, useremail) values ('$otp','$postemail')");
    }
    function updateotp($postemail,$otp,$updtcount)
    {
        return $this->db->query("UPDATE otp set otp='$otp',count='$updtcount' where useremail='$postemail'");
    }
    function stsupdate($email,$apprlcode,$status_msg)
    {
        // $data = $this->selectreturnsexcelno($email);
        // $excell_no = $data[0]['max'];
        // $stswithdate = $status_msg . "," .date('d-m-Y h:i:s A');
        $data = $this->db->query("SELECT tranhistory from aprvlcoe_returns where duplicate_approvalcode in ('$apprlcode')");
        $prevtranhistoryarr = $data->result_array();
        $prestranhistory = $prevtranhistoryarr[0]['tranhistory'] .",".$status_msg. " ".date('Y-m-d h:i:s') . " " . $email;
        $date = date('Y-m-d h:i:s A');

        $result = $this->db->query("UPDATE returns set status = '$status_msg',tranhistory = '$prestranhistory' where duplicate_approvalcode in('$apprlcode') or approval_code in('$apprlcode')");
        if($result)
        {
        return $this->db->query("UPDATE aprvlcoe_returns set status = '$status_msg',tranhistory = '$prestranhistory',updated_at ='$date' where duplicate_approvalcode in('$apprlcode') or approval_code in ('$apprlcode')");
//         $to = "divakarparuchuri123@gmail.com";
//         $subject = "Email alert";
//         $txt = "<html>
// <head>
// <title>HTML email</title>
// </head>
// <body>
// <p>This email contains HTML Tags!</p>
// <table>
// <tr>
// <th>Firstname</th>
// <th>Lastname</th>
// </tr>
// <tr>
// <td>John</td>
// <td>Doe</td>
// </tr>
// </table>
// </body>
// </html>";
//          $headers = "From: divakar@theretailinsights.com";// . "\r\n" .
//         // "CC: somebodyelse@example.com";

//         mail($to,$subject,$txt,$headers);
        }

    }
    // function proqtystsupdate($apprlcode,$status_msg)
    // {
    //     // $data = $this->selectreturnsexcelno($email);
    //     // $excell_no = $data[0]['max'];
    //     $result = $this->db->query("UPDATE returns set status = '$status_msg' where approval_code = '$apprlcode'");
    //     if($result)
    //     {
    //     return $this->db->query("UPDATE aprvlcoe_returns set status = '$status_msg' where approval_code = '$apprlcode'");
    //     }

    // }
    function getstatuscount($statusdisc)
    {
        $data = $this->db->query("SELECT status_count from status_master where short_form = '$statusdisc'");
        return $data->result_array();
    }
    function getstsmsg($statuscount)
    {
        $data = $this->db->query("SELECT short_form, status_description from status_master where status_count = '$statuscount'");
        return $data->result_array();
    }
    function selectotp($postemail)
    {
        $query = $this->db->query("SELECT otp from otp where useremail='$postemail'");
        return $query->result_array();
    }
    function deleteotp($postemail)
    {
        $query = $this->db->query("DELETE from otp where useremail = '$postemail'");
       
    }
    function selectpwd($postemail)
    {
        $query = $this->db->query("SELECT user_password,user_email from user_master where user_email='$postemail'");
        return $query->result_array();

    }
    function updatetpwd($postemail,$encpwd)
    {
        return $this->db->query("UPDATE user_master set user_password = '$encpwd' where user_email = '$postemail'");
    }
    function insertuser($user_name,$user_code,$user_role,$user_password,$user_insertedby,$user_email)
    {
        return  $this->db->query("INSERT into user_master(user_name, user_code, user_role, user_password, user_insertedby,user_email) values('$user_name','$user_code','$user_role','$user_password','$user_insertedby','$user_email')");
    }
    function resetpwd($postpassword,$postemail)
    {
       return $this->db->query("UPDATE user_master set user_password='$postpassword' where user_email='$postemail'");
    }
    function user_log($email,$token)
    {
        return $this->db->query("INSERT into user_log(user_email,token) values ('$email','$token')");
    }
    function gettoken($posttoken)
    {
        $token = $this->db->query("SELECT token from user_log where token ='$posttoken'");
        return $token->result_array();
    }
    function delettoken($postemail)
    {
        if($this->db->query("DELETE from user_log where user_email = '$postemail'"))
        {
            return true;
        }
        else{
            return false;
        }
    }
    function deletereturn($str)
    {
        if($this->db->query("DELETE from returns where barcode in ($str)"))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function storecode($storecode)
    {
        $storecode = $this->db->query("SELECT store_code,store_status,nearby_warehouse from store_master where store_code = '$storecode'");
        return $storecode->result_array();
    }
    function insrtstoredata($store_code,$store_status,$tommy_hilfiger,$calvin_klein,$email,$storename)
    {
        return $this->db->query("INSERT into store_master(store_code,store_status,tommy_hilfiger,calvin_klein,store_insertedby,store_name) values('$store_code','$store_status','$tommy_hilfiger','$calvin_klein','$email','$storename')");
    }
    function dltstoredata($email)
    {
        return $this->db->query("DELETE from store_master where store_insertedby='$email'");
    }
    function insrtwarehouse($whrcode,$whrstatus,$tommy_hilfiger,$calvin_klein,$email,$whrname)
    {
        return $this->db->query("INSERT into warehouse_master(warehouse_code,warehouse_status,tommy_hilfiger,calvin_klein,warehouse_insertedby,warehouse_name) values ('$whrcode','$whrstatus','$tommy_hilfiger','$calvin_klein','$email','$whrname')");
    }
    function insrtreturntype($returntype,$returndesc,$returnwf,$tommy_hilfiger,$calvin_klein,$email)
    {
        return $this->db->query("INSERT into return_type_master(return_type,return_type_description,return_workflow,tommy_hilfiger,calvin_klein,return_type_insertedby) values ('$returntype','$returndesc','$returnwf','$tommy_hilfiger','$calvin_klein','$email')");
    }
    function dltwarehousedata($email)
    {
        return $this->db->query("DELETE from warehouse_master where warehouse_insertedby='$email'");
    }
    function dltreturntypedata($email)
    {
        return $this->db->query("DELETE from return_type_master where return_type_insertedby='$email'");
    }
    function selectwarehouse($rcvstrcode)
    {
        $warehousedata = $this->db->query("SELECT warehouse_code,warehouse_status from warehouse_master where warehouse_code = '$rcvstrcode'");
        return $warehousedata->result_array();   
    }
    function selectwhrdata($brand)
    {
        if($brand == 'Tommy hilfiger')
        {
        $warehousedata = $this->db->query("SELECT warehouse_code,warehouse_status,tommy_hilfiger,warehouse_name from warehouse_master where tommy_hilfiger='$brand'");
        return $warehousedata->result_array();   
        }
        elseif($brand == 'Calvin klein')
        {
            $warehousedata = $this->db->query("SELECT warehouse_code,warehouse_status,calvin_klein,warehouse_name from warehouse_master where calvin_klein='$brand'");
            return $warehousedata->result_array(); 
        }
    }

    function selectreturntype($brand)
    {
        if($brand == 'Tommy hilfiger')
        {
        $warehousedata = $this->db->query("select return_type,return_type_description,return_workflow from return_type_master where tommy_hilfiger ='$brand'");
        return $warehousedata->result_array();   
        }
        elseif($brand == 'Calvin klein')
        {
            $warehousedata = $this->db->query("select return_type,return_type_description,return_workflow from return_type_master where calvin_klein ='$brand'");
            return $warehousedata->result_array(); 
        }
    }

    function selectstore($brand)
    {
        if($brand == 'Tommy hilfiger')
        {
        $storedata = $this->db->query("SELECT store_code,store_status,tommy_hilfiger,store_name from store_master where tommy_hilfiger='$brand'");
        return $storedata->result_array();
        }
        elseif($brand == 'Calvin klein')
        {
        $storedata = $this->db->query("SELECT store_code,store_status,calvin_klein,store_name from store_master where calvin_klein='$brand'");
        return $storedata->result_array();
        }
       }
       function selectallwarehouse($brand)
    {
        if($brand == 'Tommy hilfiger')
        {
        $storedata = $this->db->query("SELECT store_code,store_status,tommy_hilfiger from store_master where tommy_hilfiger='tommy hilfiger' ");
        return $storedata->result_array();
        }
        elseif($brand == 'Calvin klein')
        {
        $storedata = $this->db->query("SELECT store_code,store_status,calvin_klein from store_master where calvin_klein='calvin klein' ");
        return $storedata->result_array();
        }
       }
 function updateapprcode($email,$apprcode,$fromstrcode,$rcvstrcode,$excel_no,$dbtrantype)
 {
     return $this->db->query("UPDATE returns set approval_code = '$apprcode' where initiated_by = '$email' and issuing_store_code = '$fromstrcode' and receiving_strwhr_code = '$rcvstrcode' and excel_no = '$excel_no' and transaction_type = '$dbtrantype'");
 }
 // function returntype($sendcode,$receivecode,$email,$excell_no)
 // {
 //     $trantype = $this->db->query("SELECT transaction_type from returns where issuing_store_code = '$sendcode' and receiving_strwhr_code = '$receivecode' and initiated_by = '$email' and excel_no = '$excell_no'");
 //     if(!$trantype)
 //     {
 //         return "";
 //     }
 //     else{
 //     return $trantype->result_array();
 //     }
 //    }
    function apprreturns($email,$approval_code)
    {
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        barcode, line, style_code, size, qty, receiving_strwhr_code, receiving_store_name,
         transaction_type, excel_no, status, approval_code,created_at
         from returns where  approval_code = '$approval_code'");
         return $data->result_array();
    }

    function apprstrreturns($store_code,$approval_code)
    {
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        barcode, line, style_code, size, qty, receiving_strwhr_code, receiving_store_name,
         transaction_type, excel_no, status, approval_code,created_at
         from returns where  approval_code = '$approval_code'");
         return $data->result_array();
    }
    function ebildata($store_code,$approval_code)
    {
        $ebildata = $this->db->query("SELECT transporter,ebill_no,lr_no,credit_note from aprvlcoe_returns where approval_code = '$approval_code' ");/*and issuing_store_code = '$store_code'*/
        return $ebildata->result_array();
    }
    function getstoreexcel($approval_code)
    {
        $ebildata = $this->db->query("SELECT issuing_store_code,receiving_strwhr_code,store_returns_doc from aprvlcoe_returns where approval_code = '$approval_code'");/*and issuing_store_code = '$store_code'*/
        return $ebildata->result_array();
    }
    // function getstorelocs($approval_code);
    // {
    //     $ebildata = $this->db->query("SELECT issuing_store_code,receiving_strwhr_code from aprvlcoe_returns where approval_code = '$approval_code'");/*and issuing_store_code = '$store_code'*/
    //     return $ebildata->result_array();
    // }

    function OdnData($store_code,$approvalcode)
    {
        $OdnData = $this->db->query("SELECT corton_count,stock_count,outwarddocno_sno from aprvlcoe_returns where approval_code = '$approvalcode'");
        return $OdnData->result_array();
    }

    function apprwhrreturns($store_code,$approval_code)
    {
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        barcode, line, style_code, size, qty, receiving_strwhr_code, receiving_store_name,
         transaction_type, excel_no, status, approval_code,created_at
         from returns where  approval_code = '$approval_code' and receiving_strwhr_code = '$store_code'");
         return $data->result_array();
    }

    function updateapprnum($onlycode,$sendcode,$receivecode)
    {
        return $this->db->query("UPDATE returns set apprcode = $onlycode where issuing_store_code = '$sendcode' and receiving_strwhr_code = '$receivecode'");
    }
    function onlycode($email)
    {
        $data = $this->db->query("SELECT max(apprcode) from returns");
        return $data->result_array();
    }
    function insrtstatus($statuscode,$statusdescription,$statusinsertedby,$stscount)
    {
        return $this->db->query("INSERT into status_master(status_code,status_description,status_insertedby,status_count) values ('$statuscode','$statusdescription','$statusinsertedby','$stscount')");
    }
    function dltstatus()
    {
        return $this->db->query("DELETE  from status_master");
    }
    function distapprl($rcvstrcode)
    {
        $data = $this->db->query("SELECT distinct approval_code from returns where issuing_store_code ='$rcvstrcode'");
        return $data->result_array();
    }
    function storeapprlget($issustrcode)//,$straprvlcode)
    {
        $data = $this->db->query("SELECT distinct issuing_store_code,issuing_store_name,receiving_store_name,transaction_type,dateof_initiated,qtyof_approvalcode,
        receiving_strwhr_code,status, approval_code,ebill_image,lr_copy_image,credit_note_image,invoice_pdf,pod_image,returns_sno,transporter,corton_count,stock_count,outwarddocno_sno,transaction_report_path,updated_at from aprvlcoe_returns where issuing_store_code ='$issustrcode' 
        and status SIMILAR to 'SP%|PI%|EWB%|TAC%' /*and  approval_code in ($straprvlcode) */
		order by returns_sno desc");
        return $data->result_array();
    }
    function insrtform($corton_count,$stock_qty,$ODN_no,$email,$approval_code,$comment,$excel)
    {
        return $this->db->query("UPDATE aprvlcoe_returns set corton_count ='$corton_count',stock_count = '$stock_qty',outwarddocno_sno = '$ODN_no',odn_comment = '$comment',odn_excel = '$excel' where approval_code = '$approval_code'");
        // if($result)
        // {
        // return $this->db->query("UPDATE aprvlcoe_returns set transporter = '$transporter' where approval_code = '$approval_code'");
        // }
    }
    function getODN_no($approval_code)
    {
        $data = $this->db->query("SELECT corton_count,stock_count,outwarddocno_sno,inserted_at from aprvlcoe_returns where
           approval_code = '$approval_code' order by inserted_at desc");
        return $data->result_array();
    }
    function getapprlwrhouse($wrhousecode,$rcvingstrstring,$vendorcodes)
    {
        $data = $this->db->query("SELECT issuing_store_code,issuing_store_name,receiving_store_name,
        transaction_type,receiving_strwhr_code,status, approval_code,dateof_initiated,qtyof_approvalcode,corton_count,stock_count,outwarddocno_sno,transporter,transaction_report_path,store_returns_doc,updated_at from aprvlcoe_returns where
         (receiving_strwhr_code = '$wrhousecode' and  status SIMILAR to 'DC%|DESP%|SR%|SC%') or (receiving_strwhr_code in ($vendorcodes) and  status SIMILAR to 'DC%') or (receiving_strwhr_code in ($rcvingstrstring) and  status SIMILAR to 'DC%|DESP%|SR%|SC%')order by dateof_initiated desc");
        return $data->result_array();
    }
    function getnearstoresofwarehouse($warehousecode) 
    {
        $istreturns = $this->db->query("SELECT store_code  from store_master where nearby_warehouse = '$warehousecode'");
        return $istreturns->result_array();
    }
    function getvendorsforwarehouse($wrcode)
    {
        $istreturns = $this->db->query("SELECT warehouse_code  from warehouse_master where vendorlinkedwarehouse = '$wrcode'");
        return $istreturns->result_array();
    }
    function getreceivaerreturns($apprvlcode,$store_code)
    {
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        barcode, line, style_code, size, qty, receiving_strwhr_code, receiving_store_name,
        transaction_type, excel_no, status, approval_code,created_at
        from returns where  approval_code = '$apprvlcode' order by created_at desc");
        return $data->result_array();
    }
        function stscount()
    {
        $count = $this->db->query("SELECT max(status_count) from status_master");
        return $count->result_array();
    }
    function selectaprvlreturns($email,$rtvreturntypestr)
    {
        $query = $this->db->query("SELECT issuing_store_code,issuing_store_name,receiving_store_name,transaction_type,
        receiving_strwhr_code,status, approval_code,qtyof_approvalcode,dateof_initiated,excel_no,duplicate_approvalcode,ebill_image,lr_copy_image,credit_note_image,invoice_pdf,pod_image,updated_at
         from aprvlcoe_returns where initiated_by = '$email' and status similar to 'Ope%' order by excel_no desc");
        $plannerinitiateddata =  $query->result_array();
        $rtvreturnsquery = $this->db->query("SELECT issuing_store_code,issuing_store_name,receiving_store_name,transaction_type,
        receiving_strwhr_code,status, approval_code,qtyof_approvalcode,dateof_initiated,excel_no,duplicate_approvalcode,ebill_image,lr_copy_image,credit_note_image,invoice_pdf,pod_image,updated_at
         from aprvlcoe_returns where initiated_by = '$email' and status similar to 'DES%' and transaction_type in ($rtvreturntypestr) order by excel_no desc");
        $result = array_merge($plannerinitiateddata,$rtvreturnsquery->result_array());
        return $result; 
    }
    function getrtvreturntypes($brand)
    {
        if ($brand == 'Tommy hilfiger') {
        $data = $this->db->query("SELECT return_type from return_type_master where return_workflow = 'WF2' and tommy_hilfiger = '$brand'");
        return $data->result_array();
        }
        else
        {
        $data = $this->db->query("SELECT return_type from return_type_master where return_workflow = 'WF2' and calvin_klein = '$brand'");
        return $data->result_array();
        }
    }
    function returnsfor_masterplanner($brand)
    {
        $query = $this->db->query("SELECT issuing_store_code,issuing_store_name,receiving_store_name,
        transaction_type,receiving_strwhr_code,status, approval_code,dateof_initiated,qtyof_approvalcode,corton_count,stock_count,outwarddocno_sno,transporter,transaction_report_path,duplicate_approvalcode,ebill_image,lr_copy_image,credit_note_image,invoice_pdf,pod_image from aprvlcoe_returns where
         brand = '$brand' order by dateof_initiated desc");
        return $query->result_array();
    }
    function pendingonothers($email,$rtvreturntypes)
    {
        $query = $this->db->query("SELECT issuing_store_code,issuing_store_name,receiving_store_name,transaction_type,
        receiving_strwhr_code,status, approval_code,qtyof_approvalcode,dateof_initiated,excel_no,duplicate_approvalcode,updated_at
         from aprvlcoe_returns where initiated_by = '$email' and status not SIMILAR TO 'Ope%|CAN%|ICD|IC' and transaction_type not in ($rtvreturntypes) order by excel_no desc");
        return $query->result_array();    
    }
    // function insrtaprvlreturns($frmstr,$frmstrnm,$tostr,$tostrnm,$transtype,$apprvlcde,$sts,$xl_no,$email,$brand,$tranhistory,$totqty)
    // {
    //     $date = date('d-m-Y h:i:s A');
    //     $tranhis = $sts ." ". date('d-m-Y h:i:s A'). " " . $email;
    //     return $this->db->query("INSERT into aprvlcoe_returns(issuing_store_code,issuing_store_name,
    //     receiving_strwhr_code,receiving_store_name,transaction_type,excel_no,status,approval_code,initiated_by,brand,tranhistory,qtyof_approvalcode,dateof_initiated,duplicate_approvalcode)
    //     values('$frmstr','$frmstrnm','$tostr','$tostrnm','$transtype','$xl_no','$sts','','$email','$brand','$tranhis','$totqty','$date','$apprvlcde')");
    // }
    function insrtaprvlreturns($valuesstr)
    {
        return $this->db->query("INSERT into aprvlcoe_returns(issuing_store_code,issuing_store_name,
        receiving_strwhr_code,receiving_store_name,transaction_type,excel_no,status,approval_code,initiated_by,brand,tranhistory,qtyof_approvalcode,dateof_initiated,duplicate_approvalcode,apprcode)
        values $valuesstr");
    }
    function ebillupload($transporter,$ebill_no,$approval_code,$comment)
    {
        if($transporter != "" && $ebill_no != ""){  //for COCO in warehouse
        return $this->db->query("UPDATE aprvlcoe_returns set transporter = '$transporter',ebill_no = '$ebill_no',ta_ewaybill_comment = '$comment' where approval_code = '$approval_code'");
            }
        elseif($transporter != "" && $ebill_no == ""){ //for FOCO in warehouse
          return $this->db->query("UPDATE aprvlcoe_returns set transporter = '$transporter' ,ta_comment = 'comment' where approval_code = '$approval_code'");
        }
        elseif ($transporter == "" && $ebill_no != "") { //for FOCO in store
        return $this->db->query("UPDATE aprvlcoe_returns set ebill_no = '$ebill_no' ,ebill_comment = '$comment' where approval_code = '$approval_code'");
        }
    }
    function updatelr_no($lr_no,$approval_code,$doc,$comment)
    {
     return $this->db->query("UPDATE aprvlcoe_returns set lr_no = '$lr_no',store_returns_doc = '$doc' ,lr_comment = '$comment' where approval_code = '$approval_code'");   
    }
        function getODNdata($approval_code)
    {
     $data = $this->db->query("SELECT corton_count,stock_count,outwarddocno_sno from aprvlcoe_returns where approval_code = '$approval_code'");
     return $data->result_array();
    }
    function updatewithpod($apprlcode,$no_corton_boxes,$pod_date,$comment)
    {
    return $this->db->query("UPDATE aprvlcoe_returns set no_crtns_received = '$no_corton_boxes', pod_date = '$pod_date', pod_comment = '$comment' where approval_code = '$apprlcode'");
    }
    function insrtproqty($product_count,$date,$comment,$approval_code)
    {
     return $this->db->query("UPDATE aprvlcoe_returns set quantity_of_products = '$product_count',date_of_upload = '$date',sc_comment = '$comment' where approval_code = '$approval_code'"); 
    }
    function whrupdateCN($CNno,$doc,$invoice,$approval_code,$excess,$shortage,$comment,$path,$date)
    {
       return $this->db->query("UPDATE aprvlcoe_returns set credit_note = '$CNno',warehouse_returns_doc = '$doc',excess = '$excess',shortage = '$shortage' ,cn_comment = '$comment',transaction_report_path = '$path',invoice_pdf = '$invoice',cn_date = '$date' where approval_code = '$approval_code'");
    }
    function transporterget($brand)
    {
       $data = $this->db->query("SELECT transporter_type from transporter_master where brand = '$brand'");
       return $data->result_array();
    }
    function getworkpendinginstore($receiving_strwhr_code,$nearbystoretring,$vendorcodes)
    {
            $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
            receiving_strwhr_code, receiving_store_name,
            transaction_type, status, approval_code,returns_sno,dateof_initiated,qtyof_approvalcode,updated_at
            from aprvlcoe_returns where  (receiving_strwhr_code = '$receiving_strwhr_code' and status SIMILAR TO 'EWB%|TAC%') or (receiving_strwhr_code in ($nearbystoretring) and status SIMILAR TO 'EWB%|TAC%') or(receiving_strwhr_code in ($vendorcodes) and status SIMILAR TO 'EWB%|TAC%') order by returns_sno desc");
            return $data->result_array(); 
    }
    function getworkpendinginwhrhouse($email,$brand,$issuing_store_code)
    {
        // if($email != "" && $brand != "" && $issuing_store_code == "")
        //    {
        // $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        // receiving_strwhr_code, receiving_store_name,
        // transaction_type, status, approval_code,returns_sno,dateof_initiated,qtyof_approvalcode
        // from aprvlcoe_returns where  initiated_by = '$email' and brand = '$brand' and status SIMILAR to 'DC%|DESP%|TAC%|SR%|SC%' order by returns_sno desc ");
        // return $data->result_array();
        //     }
            // elseif($email == "" && $brand == "" && $issuing_store_code != "")
            // {
            $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
             receiving_strwhr_code, receiving_store_name,
            transaction_type, status, approval_code,returns_sno,dateof_initiated,qtyof_approvalcode,updated_at
            from aprvlcoe_returns where  issuing_store_code = '$issuing_store_code' and status SIMILAR to 'DC%|DESP%|SR%|SC%' order by returns_sno desc ");
        return $data->result_array();            
            //}
    }
    function gettrnclosedreturns($email,$brand,$store_code,$role,$linkedstorecodes,$vendorcodes)
    {
        if($store_code == "")//if planner
        {
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        receiving_strwhr_code, receiving_store_name,
        transaction_type, status, approval_code,returns_sno,updated_at,qtyof_approvalcode
        from aprvlcoe_returns where  initiated_by = '$email' and brand = '$brand' and status SIMILAR TO 'IC%|ICD%' order by returns_sno desc");
        return $data->result_array();
        }
        else //if store or warehouse
        {
            if ($role == 'store') { //if store
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        receiving_strwhr_code, receiving_store_name,
        transaction_type, status, approval_code,returns_sno,updated_at,qtyof_approvalcode
        from aprvlcoe_returns where  issuing_store_code = '$store_code' and status SIMILAR TO 'IC%|ICD%' order by returns_sno desc");
        return $data->result_array();            
            }
            else { // if warehouse
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        receiving_strwhr_code, receiving_store_name,
        transaction_type, status, approval_code,returns_sno,updated_at,qtyof_approvalcode
        from aprvlcoe_returns where (receiving_strwhr_code = '$store_code' and status SIMILAR TO 'IC%|ICD%') or (receiving_strwhr_code in ($linkedstorecodes) and status SIMILAR TO 'IC%|ICD%') or (receiving_strwhr_code in ($vendorcodes) and status SIMILAR TO 'IC%|ICD%') order by returns_sno desc");
        return $data->result_array();            
    }
        }
    }
    function gettrncancledreturns($email,$brand)
    {
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        receiving_strwhr_code, receiving_store_name,
        transaction_type, status, approval_code,returns_sno,qtyof_approvalcode,dateof_initiated
        from aprvlcoe_returns where  initiated_by = '$email' and brand = '$brand' and status in ('CAN') order by returns_sno desc ");
        return $data->result_array();
    }
    function transactiontype($rtntype)
    {
        $data = $this->db->query("SELECT return_workflow from return_type_master where return_type = '$rtntype'");
        return $data->result_array();
    }
    function updatefiles($lr_copy_image,$ebill_image,$credit_note_image,$pod_image,$odn_image,$invoice,$approvalcode)
    {
        if($lr_copy_image != "")
        {
        return $this->db->query("UPDATE aprvlcoe_returns set lr_copy_image = '$lr_copy_image' where approval_code = '$approvalcode'");
        }
        elseif($ebill_image != "")
        {
        return $this->db->query("UPDATE aprvlcoe_returns set ebill_image = '$ebill_image' where approval_code = '$approvalcode'");
        }
        elseif($credit_note_image != "" || $invoice != "")
        {
        return $this->db->query("UPDATE aprvlcoe_returns set credit_note_image = '$credit_note_image',invoice_pdf = '$invoice' where approval_code = '$approvalcode'");
        }
        elseif ($pod_image != "") {
      return $this->db->query("UPDATE aprvlcoe_returns set pod_image = '$pod_image' where approval_code = '$approvalcode'");
        }
        elseif ($odn_image != "") {
      return $this->db->query("UPDATE aprvlcoe_returns set odn_image = '$odn_image' where approval_code = '$approvalcode'");
              }

    }
    function getfiles($ebill_image,$lr_copy_image,$credit_note_image,$invoice_pdf,$approval_code)
    {
        if($ebill_image != "")
        {
        $data = $this->db->query("SELECT ebill_image from aprvlcoe_returns where approval_code = '$approval_code'");
        return $data->result_array();
        }
        elseif($lr_copy_image != "")
        {
        $data = $this->db->query("SELECT lr_copy_image from aprvlcoe_returns where approval_code = '$approval_code'");
        return $data->result_array();
        }
        elseif($credit_note_image != "")
        {
        $data = $this->db->query("SELECT credit_note_image from aprvlcoe_returns where approval_code = '$approval_code'");
        return $data->result_array();
        }
        elseif($invoice_pdf != "")
        {
        $data = $this->db->query("SELECT invoice_pdf from aprvlcoe_returns where approval_code = '$approval_code'");
        return $data->result_array();
        }
    }
    function getfilepaths($approval_code)
    {
        $data = $this->db->query("SELECT ebill_image,lr_copy_image,credit_note_image,invoice_pdf,pod_image from aprvlcoe_returns where approval_code = '$approval_code'");
        return $data->result_array();    
    }
    function workcpmpletedinstore($store_code)
    {
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        receiving_strwhr_code, receiving_store_name,
        transaction_type, status, approval_code,returns_sno
        from aprvlcoe_returns where status SIMILAR to 'IC%' and issuing_store_code = '$store_code'");
        return $data->result_array();
    }
        function workcpmpletedinstorewithdisc($store_code)
    {
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        receiving_strwhr_code, receiving_store_name,
        transaction_type, status, approval_code,returns_sno
        from aprvlcoe_returns where status SIMILAR to 'IC%' and issuing_store_code = '$store_code'");
        return $data->result_array();
    }
    function workcpmpletedinwhr($store_code)
    {
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        receiving_strwhr_code, receiving_store_name,
        transaction_type, status, approval_code,returns_sno
        from aprvlcoe_returns where status SIMILAR to 'IC%|ICD%' and receiving_strwhr_code = '$store_code'");
        return $data->result_array();
    }
    function returnsofstatus($status_msg,$role,$store_code,$email,$rcvingstrstring,$transaction_type,$vendorcodes,$rtvreturntypestr)
    {
        if($role == 'store')
        {
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        receiving_strwhr_code, receiving_store_name,
        transaction_type, status, approval_code,excel_no,dateof_initiated,qtyof_approvalcode,duplicate_approvalcode,ebill_image,lr_copy_image,credit_note_image,invoice_pdf,pod_image,corton_count,stock_count,outwarddocno_sno,transporter,transaction_report_path,updated_at
        from aprvlcoe_returns where status like '$status_msg%' and issuing_store_code = '$store_code' order by excel_no desc");
        return $data->result_array();
        }
        elseif ($role == 'planner') 
        {
            if ($transaction_type != "") 
            {
            $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        receiving_strwhr_code, receiving_store_name,
        transaction_type, status, approval_code,excel_no,dateof_initiated,qtyof_approvalcode,duplicate_approvalcode,ebill_image,lr_copy_image,credit_note_image,invoice_pdf,pod_image,corton_count,stock_count,outwarddocno_sno,transporter,transaction_report_path,updated_at
        from aprvlcoe_returns  where status = '$status_msg' and initiated_by = '$email' and transaction_type in ($rtvreturntypestr) order by excel_no desc");
        return $data->result_array(); 
            }
            else
            {
            if ($status_msg == 'IC' || $status_msg == 'ICD') {
                $opendataqry = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        receiving_strwhr_code, receiving_store_name,
        transaction_type, status, approval_code,excel_no,dateof_initiated,qtyof_approvalcode,duplicate_approvalcode,ebill_image,lr_copy_image,credit_note_image,invoice_pdf,pod_image,corton_count,stock_count,outwarddocno_sno,transporter,transaction_report_path,updated_at
        from aprvlcoe_returns  where status = '$status_msg' and initiated_by = '$email' order by excel_no desc");
        return $opendataqry->result_array();
                }
                else{
        $opendataqry = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        receiving_strwhr_code, receiving_store_name,
        transaction_type, status, approval_code,excel_no,dateof_initiated,qtyof_approvalcode,duplicate_approvalcode,ebill_image,lr_copy_image,credit_note_image,invoice_pdf,pod_image,corton_count,stock_count,outwarddocno_sno,transporter,transaction_report_path,updated_at
        from aprvlcoe_returns  where status = '$status_msg' and initiated_by = '$email' order by excel_no desc");
        return $opendataqry->result_array();
                }
            }
            //}
        //     else{
        // $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        // receiving_strwhr_code, receiving_store_name,
        // transaction_type, status, approval_code,excel_no,dateof_initiated,qtyof_approvalcode,duplicate_approvalcode,ebill_image,lr_copy_image,credit_note_image,invoice_pdf,pod_image,corton_count,stock_count,outwarddocno_sno,transporter,transaction_report_path
        // from aprvlcoe_returns  where status = '$status_msg' and initiated_by = '$email' order by excel_no desc");
        // return $data->result_array();
        // }        
         }
        else
        {
            if ($status_msg == 'DESP') {
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        receiving_strwhr_code, receiving_store_name,
        transaction_type, status, approval_code,excel_no,dateof_initiated,qtyof_approvalcode,duplicate_approvalcode,ebill_image,lr_copy_image,credit_note_image,invoice_pdf,pod_image,corton_count,stock_count,outwarddocno_sno,transporter,transaction_report_path,store_returns_doc,updated_at
        from aprvlcoe_returns  where (status = '$status_msg' and receiving_strwhr_code = '$store_code') or (status = '$status_msg' and receiving_strwhr_code in ($rcvingstrstring))  order by excel_no desc");
        return $data->result_array();
            }
            else
            {
        $data = $this->db->query("SELECT issuing_store_code, issuing_store_name,
        receiving_strwhr_code, receiving_store_name,
        transaction_type, status, approval_code,excel_no,dateof_initiated,qtyof_approvalcode,duplicate_approvalcode,ebill_image,lr_copy_image,credit_note_image,invoice_pdf,pod_image,corton_count,stock_count,outwarddocno_sno,transporter,transaction_report_path,store_returns_doc,updated_at
        from aprvlcoe_returns  where
           (status = '$status_msg' and receiving_strwhr_code = '$store_code') or (status = '$status_msg'
         and receiving_strwhr_code in ($rcvingstrstring))
            or (status = '$status_msg' and receiving_strwhr_code in ($vendorcodes))  order by excel_no desc");
        return $data->result_array();
            }
        }
    }
    function getstatusmaster()
    {
        $data = $this->db->query("SELECT status_description from status_master");
        return $data->result_array();
    }
    function gettranhistory($approval_code)
    {
        $data = $this->db->query("SELECT tranhistory,issuing_store_code from aprvlcoe_returns where approval_code = '$approval_code'");
        return $data->result_array();
    }
    function generateapprovalcode($duplicate_approvalcode)
    {
        return $this->db->query("UPDATE aprvlcoe_returns set approval_code = '$duplicate_approvalcode' where duplicate_approvalcode = '$duplicate_approvalcode'");
    }
    function getcountofstatus($email,$store_code,$warehousecode)
    {
        if ($email !="") {
        $data=  $this->db->query("SELECT count(status) as count, status from aprvlcoe_returns where initiated_by = '$email' group by status");
        return $data->result_array();       
         }
         elseif ($store_code != "") {
        $data=  $this->db->query("SELECT count(status) as count, status from aprvlcoe_returns where initiated_by = '$email' group by status");
        return $data->result_array();
         }
         elseif ($warehousecode != "") {
        $data=  $this->db->query("SELECT count(status) as count, status from aprvlcoe_returns where initiated_by = '$email' group by status");
        return $data->result_array();       
          }
    }
    function getupdatedtimeofreturns()
    {
        $data = $this->db->query("SELECT duplicate_approvalcode,receiving_strwhr_code,dateof_initiated,status,updated_at,alert_count from aprvlcoe_returns where status not SIMILAR to 'IC|ICD|CAN'");
        return $data->result_array();
    }
    function mailtrigertime($currenttime,$count,$approval_code)
    {
        return $this->db->query("UPDATE aprvlcoe_returns set alert_count = '$count',alerted_time = '$currenttime' where approval_code = '$approval_code'");
    }
}

?>