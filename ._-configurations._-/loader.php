<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/New_York'); //http://php.net/manual/en/timezones.php


//## - Site Configurations - ##//
$siteName 	   = "Tibia Market";
$contactEmail  = "nope";
$siteURL 	   = "http://tibiamarket.net/";		// including last slash
$rootDir 	   = "/var/public_html/"; 		    // FULL PATH including last slash


//## - SMTP Configurations - ##//
$fromEmail = "nope";
$smtp_host = "nope";
$smtp_user = "nope";
$smtp_pass = "nope";
$smtp_port = 587;




include('dbc.php');
include('TibiaMarket.Class.php');
$TibiaMarket = new TibiaMarket($odb);


function _echo($str){
	echo nl2br(htmlspecialchars($str));
}

if($TibiaMarket->isLogged()){
  $userData = $TibiaMarket->userData();
}

function createDateRangeArray($strDateFrom,$strDateTo){
    $aryRange = array();

    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom){
        array_push($aryRange, date('Y-m-d', $iDateFrom));
        while ($iDateFrom < $iDateTo){
            $iDateFrom += 86400;
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}

function SendEmail($to, $subject, $message){
    global $smtp_host;
    global $smtp_user;
    global $smtp_pass;
    global $smtp_port;
    global $fromEmail;
    global $siteName;

    require 'PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $smtp_host;                             // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $smtp_user;                         // SMTP username
    $mail->Password = $smtp_pass;                         // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = $smtp_port;                             // TCP port to connect to
    
    $mail->setFrom($fromEmail, $siteName);
    $mail->addAddress($to);
    $mail->isHTML(true);                                  // Set email format to HTML
    
    $mail->Subject = $subject;
    $mail->Body    = nl2br($message);
    //$mail->AltBody = $message;
    
    if(!$mail->send()) {

        die($mail->ErrorInfo);
        return false;
    } else {
        return true;
    }
}


function inputSanitize($str){
    return strip_tags(htmlentities($str));
}
