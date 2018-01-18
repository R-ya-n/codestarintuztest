<?php
/**
 * @file
 * Contains \Drupal\embedded_external_content\RestfulWebserviceClientV2
 */

namespace Drupal\embedded_external_content;

/**
 * This takes in a URL and takes care of hitting that URL and returning the data
 * 
 */
class RestfulWebserviceClientV2 {
    
        public function getDataFromURL($url) {
//            $ch = curl_init($url);
//            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
//            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            $data = curl_exec($ch);
//            curl_close($ch);  
            
            
            $xmlDoc = new DOMDocument();
            $xmlDoc->load("news.xml");
            
            return $xmlDoc;
 	}
        
        public function getStuff() {
            return 'stuff';
        }
}
