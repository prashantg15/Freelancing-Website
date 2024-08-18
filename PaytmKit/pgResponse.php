<?php
session_start();
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");
include '../Database/dbcon.php';

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {
	// echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	// echo $_POST['ORDERID'];
	// $getUserDetailsByid = getUserDetailsByid();
	// $name = getUserDetailsByid['name'];
	// $email = getUserDetailsByid['email'];
	// echo $name;
	// echo $email;
	// echo $_POST['MSISDN'];
	// echo $_POST['EMAIL'];
	// $payment_details = "Insert into payment(order_id,mid,txnid,txn_amt,pmode,currency,txn_date,txn_status,resp_code,resp_msg,gateway,bank_txn_id,bank_name,checksum,gid,gtitle,gdelivery,gprice,fname,user_name) values('$_POST[ORDERID]','$_POST[MID]','$_POST[TXNID]','$_POST[ORDERID]','$_POST[TXNAMOUNT]','$_POST[PAYMENTMODE]','$_POST[CURRENCY]','$_POST[TXNDATE]','$_POST[STATUS]','$_POST[RESPCODE]','$_POST[RESPMSG]','$_POST[GATEWAYNAME]','$_POST[BANKTXNID]','$_POST[BANKNAME]','$_POST[CHECKSUMHASH]','$_POST[GID]','$_POST[GTITLE]','$_POST[GDELIVERY]','$_POST[GPRICE]','$_POST[FNAME]','$_POST[USERNAME]',)";
	// $insert = mysqli_query($con, $payment_details);

	// // $payment_details = "Insert into payment where"
	// if($insert){
	if(isset($_SESSION["username"])){
		if(isset($_SESSION["username"])){
			if ($_POST["STATUS"] == "TXN_SUCCESS") {
				echo "<b>Transaction status is success</b>" . "<br/>";	
				echo $_SESSION["username"];
				echo $_SESSION["gid"];
				$username = $_SESSION["username"];
				$id = $_SESSION["gid"];
				$query = "Select * from freelancer where gid='$id'";
				$query_run = mysqli_query($con, $query);
				$fetch = mysqli_fetch_array($query_run);
				$gid = $fetch['gid'];
				$gtitle = $fetch['gtitle'];
				$gdelivery = $fetch['gdelivery'];
				$gprice = $fetch['gprice'];
				$fname = $fetch['username'];
				$tot = $_SESSION["tot"];
				//Process your transaction here as success transaction.
				//Verify amount & order id received from Payment gateway with your application's order id and amount.
			}
		}
	}
	else {
		echo "<b>Transaction status is failure</b>" . "<br/>";
		$_POST["STATUS"] = "TXN_FAILURE";
		$_POST["RESPMSG"] = "Txn Failure";
	}
	
		// echo $_POST[MSISDN];
		// echo $_POST[EMAIL];
// $order_id = $_POST["ORDERID"];
// $mid = $_POST["MID"];
// $txnid = $_POST["TXNID"];
// $order_id = $_POST["ORDERID"];
// $order_id = $_POST["ORDERID"];
// $order_id = $_POST["ORDERID"];
// $order_id = $_POST["ORDERID"];
// $order_id = $_POST["ORDERID"];
// $order_id = $_POST["ORDERID"];

		if (isset($_POST) && count($_POST)>0 ){
			if(isset($_SESSION["username"])){
				if ($_POST["STATUS"] == "TXN_SUCCESS") {
					$payment_details = "Insert into payment(order_id,mid,txnid,txn_amt,pmode,currency,txn_date,txn_status,resp_code,resp_msg,gateway,bank_txn_id,bank_name,checksum,gid,gtitle,gdelivery,gprice,total_price,fname,user_name) values('$_POST[ORDERID]','$_POST[MID]','$_POST[TXNID]','$_POST[TXNAMOUNT]','$_POST[PAYMENTMODE]','$_POST[CURRENCY]','$_POST[TXNDATE]','$_POST[STATUS]','$_POST[RESPCODE]','$_POST[RESPMSG]','$_POST[GATEWAYNAME]','$_POST[BANKTXNID]','$_POST[BANKNAME]','$_POST[CHECKSUMHASH]','$gid','$gtitle','$gdelivery','$gprice','$tot','$fname','$username')";
					$insert = mysqli_query($con, $payment_details);
					if($insert){
						echo "Successfully Inserted";
					}else{
						echo "Successfully not Inserted";
					}

					foreach($_POST as $paramName => $paramValue) {
						echo "<br/>" . $paramName . " = " . $paramValue;
					}
				}
			}
		}
	// }

}
else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>