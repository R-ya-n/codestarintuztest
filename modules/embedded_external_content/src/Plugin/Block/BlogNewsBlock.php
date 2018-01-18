<?php
/**
 * @file
 * Contains \Drupal\embedded_external_content\Plugin\Block\BlogNewsBlock
 */

namespace Drupal\embedded_external_content\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\embedded_external_content\RestfulWebserviceClientV2;

/**
 * Provides events content in a block
 *
 * @Block(
 *   id = "blognews_block",
 *   admin_label = @Translation("BlogNews"),
 * )
 */

class BlogNewsBlock extends BlockBase {
   /**
    * {@inheritdoc}
    */ 
    public function build() {
        //$node_title = \Drupal::request()->attributes->get('node')->title->value;
        //$node_title = str_replace(' ', '', $node_title);
        //$blognews_url = 'http://www.iopblog.org/feed/';

        $groupName = \Drupal::request()->attributes->get('node')->title->value;
        
        // transform XML 
        $xslDoc = new \DOMDocument();
        $xslDoc->load("modules/embedded_external_content/src/Plugin/Block/transform_news.xsl");

        $xmlDoc = new \DOMDocument();
        //$xmlDoc->loadxml($data);
        $xmlDoc->load("modules/embedded_external_content/src/Plugin/Block/news.xml");

        $proc = new \XSLTProcessor();
        $proc->importStylesheet($xslDoc);
        
        $proc->setParameter('', 'groupName', $groupName);

        $blognews_html = $proc->transformToXML($xmlDoc);
        
        return array(
            '#markup' => $this->t($blognews_html),
        );
    }
}