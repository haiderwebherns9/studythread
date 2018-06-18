<?php
class ServicesCafe{
   public $sms_url = "http://sslsms.cafe24.com/sms_sender.php";
   public $user_id;
   public $secure;

    public function __construct($user_id,$secure){
        $this->user_id = $user_id;
        $this->secure = $secure;
    }
    function send($msg='',$to,$from){
        $sms['user_id'] = base64_encode($this->user_id); //SMS id.
        $sms['secure'] = base64_encode($this->secure);
        $sms['msg'] = base64_encode(stripslashes($msg));
        $sms['rphone'] = base64_encode($to);
        $from = explode("-", $from);
        $sms['sphone1'] = base64_encode($from[0]);
        $sms['sphone2'] = base64_encode($from[1]);
        $sms['sphone3'] = base64_encode($from[2]);
        $sms['rdate'] = base64_encode("");
        $sms['rtime'] = base64_encode("");
        $sms['mode'] = base64_encode("1"); // When using base64, you must assign 1 to mode value.
        $sms['returnurl'] = base64_encode("");
        $sms['testflag'] = base64_encode("");
        $sms['destination'] = urlencode("");
        $sms['repeatFlag'] = base64_encode("");
        $sms['repeatNum'] = base64_encode("");
        $sms['repeatTime'] = base64_encode("");
        $sms['smsType'] = base64_encode("S");
        return $this->setupsms($sms);
    }
    function setupsms($conf){
        $host_info = explode("/", $this->sms_url);
        $host = $host_info[2];
        $path = $host_info[3]."/".$host_info[4];
        srand((double)microtime()*1000000);
        $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
    // creating header
        $header = "POST /".$path ." HTTP/1.0\r\n";
        $header .= "Host: ".$host."\r\n";
        $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

    // creating body
        $data = "";
        foreach($conf AS $index => $value){
            $data .="--$boundary\r\n";
            $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
            $data .= "\r\n".$value."\r\n";
            $data .="--$boundary\r\n";
        }
        $header .= "Content-length: " . strlen($data) . "\r\n\r\n";

        $fp = fsockopen($host, 80);

        if ($fp) {
            fputs($fp, $header.$data);
            $rsp = '';
            while(!feof($fp)) {
                $rsp .= fgets($fp,8192);
            }
            fclose($fp);
            $msg = explode("\r\n\r\n",trim($rsp));
            $rMsg = explode(",", $msg[1]);
            $Result= $rMsg[0];
            $Count= $rMsg[1];
            return "Result ".$Result.", Count ".$Count;
        }
    }
}
?>