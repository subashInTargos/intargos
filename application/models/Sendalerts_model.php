<?php
class Sendalerts_model extends CI_Model
{
    public function trigger_alerts($event,$alertsdata)
    {
        //Prepare Message Content
        if($event=="user_complete_registration")
        {
            $msg = "Dear ".$alertsdata['fullname'].", Welcome to ParcelX.\r\nLogin to ParcelX portal at app.parcelx.in with\r\nUsername: ".$alertsdata['username']." &\r\nPassword: ".$alertsdata['password'].".\r\nFor more details check your email. Please change your password after login.\r\nRegards\r\nTeam ParcelX\r\nwww.parcelx.in";

            $this->SendSMS($msg,$alertsdata['number']);
            $this->SendEmail(3,$alertsdata);
        }
    }

    public function SendSMS($msg,$to)
    {
        // echo $msg;
        $encoded_msg = urlencode($msg);
        $URL         = 'http://alerts.solutionsinfini.com/api/v4/?api_key=A63e18a1d5118d5cc3bb8f04524a67b42&method=sms&sender=PARCLX&to='.$to.'&message='.$encoded_msg;
        // echo $URL;
        //Send SMS
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $URL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        // echo $err;
        // echo $response;
    }

    public function SendEmail($templateId,$mailData)
    {
        $headers = array
        (
            'api-key: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',
            'Content-Type: application/json',
            'Accept: application/json'
        );

        $emailData = array(
            'templateId' => $templateId,
            'to' => [[
                'name'  =>  $mailData['fullname'],
                'email' =>  $mailData['email']
            ]],
            'params' => [
                'Fullname'      =>  $mailData['fullname'],
                'Businessname'  =>  $mailData['businessname'],
                'username'      =>  $mailData['username'],
                'password'      =>  $mailData['password']
            ]
        );

        $Data = json_encode($emailData);
        // print_r($Data);
        $emailURL = "https://api.sendinblue.com/v3/smtp/email";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $emailURL);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$Data); 
        $result = curl_exec($ch);
        curl_close($ch);
        // print_r($result);
        // die();
    }

}
?>