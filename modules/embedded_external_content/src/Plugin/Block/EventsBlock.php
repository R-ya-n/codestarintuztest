<?php
/**
 * @file
 * Contains \Drupal\embedded_external_content\Plugin\Block\EventsBlock
 */

namespace Drupal\embedded_external_content\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\embedded_external_content\RestfulWebserviceClient;
/**
 * Provides events content in a block
 *
 * @Block(
 *   id = "events_block",
 *   admin_label = @Translation("Events"),
 * )
 */

class EventsBlock extends BlockBase {
   /**
    * {@inheritdoc}
    */ 
    public function build() {
        $node_title = \Drupal::request()->attributes->get('node')->title->value;
        $events_url = 'https://9uvl4xq5xh.execute-api.eu-west-2.amazonaws.com/live/events?q=' . urlencode('start-date:[' . date('Y-m-d') . ' TO *] AND tags:' . str_replace(' ', '', $node_title)) . '&sort=start-date:asc';
        $events_html = '';
        $count = 0;
        $events = false;
        
        try{
            //Hit webservice with title parameter
            $events_data = (new RestfulWebserviceClient())->getDataFromURL($events_url);
            if($events_data->hits && $events_data->hits->total && $events_data->hits->total > 0) {
                $events = true;
                //Go through results picking out the fields we want
                foreach ($events_data->hits->hits as $hit) {
                    $count++;
                    $events_html .= 
                    '<div class="dp_pageContainer"><div class="dp_eventCard">'
                        . '<a href="' . $hit->_source->{'event-page-url'} . '">'
                            . '<img src="' . $hit->_source->{'banner-image-url'} . '" alt="Event image" class="dp_eventCardImage">'
                        . '</a>'
                        . '<a href="' . $hit->_source->{'event-page-url'} . '" tabindex="-1" class="dp_eventCardContent">'
                            . '<div class="dp_eventCardDateTime">' . date('H:i d F Y', strtotime($hit->_source->{'start-date'})) . '</div>'
                            //. '<div class="dp_eventCardShortLocation">' . strip_tags($hit->_source->{'location-short'}) . '</div>'
                            . '<div class="dp_eventCardTitle">' . strip_tags($hit->_source->title) . '</div>'
                            . '<div class="dp_eventCardTag">' . strip_tags(substr($hit->_source->description, 0, 50)) . '...</div>'
                        . '</a>'
                        . '<div class="dp_eventCardTagContainer">'
                            . '<a class="dp_eventCardTag"></a>'
                        . '</div>'
                    . '</div></div>';
                    if ($count == 4) break;
                }

            }
        }catch(Exception $e){
                //To do: error logging and email
        }
        if(!$events)
            $events_html .= 
                    '<div class="dp_pageContainer"><div class="dp_eventCard">'
                        . '<a href="http://iop-events-live.eu-west-2.elasticbeanstalk.com/d/f/worldwide/page.html">'
                            . '<img src="http://iop-events-live.eu-west-2.elasticbeanstalk.com/img/ui_placeholder_header_1.png" alt="Event image" class="dp_eventCardImage">'
                        . '</a>'
                        . '<a href="http://iop-events-live.eu-west-2.elasticbeanstalk.com/d/f/worldwide/page.html" tabindex="-1" class="dp_eventCardContent">'
                            . '<div class="dp_eventCardTitle">No upcoming events for ' . $node_title . '</div>'
                            . '<div class="dp_eventCardTag">'
                                . 'See all IOP upcoming events'
                            . '</div>'
                        . '</a>'
                    . '</div></div>';

        return array(
            '#markup' => $this->t($events_html),
        );
    }
}