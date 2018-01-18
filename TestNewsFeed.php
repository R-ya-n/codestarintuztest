<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
   <head>
       <meta charset="UTF-8">
       <title></title>
   </head>
   <body>
       <?php
            //$url = 'http://feeds.feedburner.com/iopblog/Eked?format=xml';
            $url = 'http://www.iopblog.org/feed/';
            $ch = curl_init($url);
            $data = "";

            //This setting lets us get by the proxy, remove for live
            //curl_setopt($ch, CURLOPT_PROXY, "194.200.94.5:8080");

            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //$data = curl_exec($ch);
            
            
            // transform XML 
            $xslDoc = new DOMDocument();
            $xslDoc->load("transform_news.xsl");

            $xmlDoc = new DOMDocument();
            //$xmlDoc->loadxml($data);
            $xmlDoc->load("news.xml");

            $proc = new XSLTProcessor();
            $proc->importStylesheet($xslDoc);
            echo $proc->transformToXML($xmlDoc);

            
            curl_close($ch);
       
            
            //echo '<pre>';
            //echo $data;
            //echo '</pre>';   
            
       //echo "Here is the length of the array ", count($data),"<br/><br/>";

       //foreach ($data as $datum){
       //    echo "Counter ", $i++, "<br/>";
       //    echo "userId ", $datum->userId, "<br/>";
       //    echo "id ", $datum->id, "<br/>";
       //   echo "title ", $datum->title, "<br/>";
       //    echo "body ", $datum->body, "<br/>";
       //    echo "<br/>";
       //}
       ?>
   </body>
</html>