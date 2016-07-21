<?php
function GoogleIndexPage($domain){
    $url="http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=site:".$domain."&filter=0";
    $googleUrl = 'http://www.google.com/search?oe=utf8&ie=utf8&source=uds&start=0&filter=0&hl=en&q=site:'.urlencode($domain);
    $count =0;
    $status ='ERROR';
    $cek =  cari_url($domain);

if($cek=='false'){
    $gets = @file_get_contents($googleUrl);
    if($gets){
        $pattern = '#<div class="sd" id="resultStats">About (.*?) results</div>#';
        $get = $gets;
        $count = 0;

        if (preg_match($pattern, $get, $match)) {
            $count = (int) str_replace(',', '', $match[1]);
        } else{
            $pattern = '#<div class="sd" id="resultStats">(.*?) results</div>#';
            if (preg_match($pattern, $get, $match)) {
                $count = (int) str_replace(',', '', $match[1]);
            }
        }
        if($count > 0 ){
            $status = '<font color="green">Good Index</font>';
        }else{
            $status = '<font color="#F37C17">Deindex</font>';
            $pesan  = 'URL : '.$domain.'</br>';
            $pesan  .= 'status : <a href="'.$googleUrl.'"><font color="#F37C17">Deindex</font></a></br>';
          //  kirimMail($domain, $pesan);
        }

      

    }else{
        sleep(3);
        $ch=curl_init();
        $user_agent=$_SERVER['HTTP_USER_AGENT'];
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $json = curl_exec($ch);
        curl_close($ch);
        $data=json_decode($json,true);
        if($data['responseStatus']==200){
            $count = isset($data['responseData']['cursor']['resultCount'])?$data['responseData']['cursor']['resultCount']:0;
          
            if($count > 0 ){
                $status = '<font color="green">Good Index</font>';
            }else{
                $status = '<font color="#F37C17">Deindex</font>';
                $pesan  = 'URL : '.$domain.'</br>';
                $pesan  .= 'status : <a href="'.$googleUrl.'"><font color="#F37C17">Deindex</font></a></br> ';
              //  kirimMail($domain, $pesan);
            }

        }else{
            $status ='<font color="#4689F7">ERROR</font>';
        }
      
    }

   if($count < 1){
        rmDomain($domain); 
    }

}else{
       $status ='<font color="##DA5326"><b>DeindeX</b></font>';
}



 
    $data =array('count' => $count,
    'url' => $domain,
    'status' => $status,
    'google' => $googleUrl);

    return $data;

}

function addDomain($domain){
  file_put_contents("domain.txt", $domain."\n", FILE_APPEND);
}

function rmDomain($domain){
  file_put_contents("delete.txt", $domain."\n", FILE_APPEND);
}

function cari_url($domain){
    $file ='delete.txt';
    $searchfor = $domain;
    $contents = file_get_contents($file);
    $pattern = preg_quote($searchfor, '/');
    $pattern = "/^.*$pattern.*\$/m";
    preg_match_all($pattern, $contents, $matches);  
       $text = implode("\n", $matches[0]);
       $search = empty($matches[0])? 'false':'true';
       return $search;
}

function kirimMail($domain, $pesan){
         $to = "kampung_hosting@yahoo.com";
         $subject = "Domain $domain disikat google bos";
         
         $message = "<b>Laporan Hari Ini.</b> </br>";
         $message .= $pesan;
         
         $header = "From:info@deindex.com \r\n";
       //  $header .= "Cc:afgh@somedomain.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
         $retval = mail ($to,$subject,$message,$header);
         
         if( $retval == true ) {
            echo "Message sent successfully...";
         }else {
            echo "Message could not be sent...";
         }
}

?>