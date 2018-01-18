<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Drupal\embedded_external_content\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\embedded_external_content\RestfulWebserviceClient;

class EventsController extends ControllerBase {
    
    public function content() {
        
        $events_url = 'https://9uvl4xq5xh.execute-api.eu-west-2.amazonaws.com/live/events?q=tags:AstroparticlePhysicsGroup';
        $events_html = '';
        
        //Hit webservice with title parameter
        $events_data = (new RestfulWebserviceClient())->getDataFromURL($events_url);
                                         
        //Go through results picking out the fields we want
        foreach ($events_data->hits->hits as $hit) {
            $events_html .= 
            '<div class="dp_eventCard">'
                . '<a href="' . $hit->_source->{'banner-image-url'} . '">'
                    . '<img src="' . $hit->_source->{'banner-image-url'} . '" alt="Event image" class="dp_eventCardImage">'
                . '</a>'
                . '<a href="' . $hit->_source->{'event-page-url'} . '" tabindex="-1" class="dp_eventCardContent">'
                    . '<div class="dp_eventCardDateTime">' . $hit->_source->{'start-date'} . '</div>'
                    . '<div class="dp_eventCardShortLocation">' . $hit->_source->location . '</div>'
                    . '<div class="dp_eventCardTitle">' . $hit->_source->title . '</div>'
                    . '<div class="dp_eventCardTitle">' . $hit->_source->description . '</div>'
                . '<a/>'
                . '<div class="dp_eventCardTagContainer">'
                    . '<a class="dp_eventCardTag"></a>'
                . '</div>'
            . '<div/>';
        }

        
        return array(
            '#type' => 'markup',
            '#markup' => t("Hello World from events controller" . $events_html),
        );
        
    }
}