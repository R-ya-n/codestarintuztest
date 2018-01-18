<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * @Shortcode(
 *   id = "nd_node",
 *   title = @Translation("Node"),
 *   description = @Translation("Render node"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-file-o",
 *   description = true
 * )
 */
class NodeShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    if (isset($attrs['admin_url']) && strpos($attrs['admin_url'], 'node/') !== FALSE) {
      $node_name = substr($attrs['admin_url'], strpos($attrs['admin_url'], 'node/') + 5);
      $parts = explode('/', $node_name);
      $node = node_load($parts[0]);
      if(isset($node->nid) && $node->nid) {
        $node = node_view($node);
        $output = render($node);
        $attrs = _rhythm_shortcodes_shortcode_attributes($attrs);
        $text = $attrs ? '<div ' . $attrs  . '>' . $output . '</div>' : $output;
        return $text;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function settings($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $form['admin_url'] = array(
      '#title' => t('Node Title'),
      '#type' => 'textfield',
      '#autocomplete_path' => 'admin/ajax/nd_visualshortcodes/node_autocomplete',
      '#default_value' => isset($attrs['admin_url']) ? $attrs['admin_url'] : '',
      '#attributes' => array('class' => array('form-control'))
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function description($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    return 'TEST TEXT';
    if (strpos($attrs['admin_url'], 'node/') !== FALSE) {
      $node_name = substr($attrs['admin_url'], strpos($attrs['admin_url'], 'node/') + 5);
      $parts = explode('/', $node_name);
      $nid = $parts[0];
      if(is_numeric($nid)) {
        $node = node_load($nid);
        return l($node->title, 'node/' . $nid . '/edit', array('attributes' => array('target' => '_blank')));
      }
    }
  }
}