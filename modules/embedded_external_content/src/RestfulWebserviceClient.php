<?php
/**
 * @file
 * Contains \Drupal\embedded_external_content\RestfulWebserviceClient
 */

namespace Drupal\embedded_external_content;

/**
 * This takes in a URL and takes care of hitting that URL and returning the data
 * 
 */
class RestfulWebserviceClient {
    
        public function getDataFromURL($url) {
            $ch = curl_init($url);
            //curl_setopt($ch, CURLOPT_PROXY, "194.200.94.5:8080");         //This setting lets us get by the proxy, remove for live
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = json_decode(curl_exec($ch));  
            curl_close($ch);  
            return $data;
 	}
        
        public function getStuff() {
            return 'stuff';
        }
}
