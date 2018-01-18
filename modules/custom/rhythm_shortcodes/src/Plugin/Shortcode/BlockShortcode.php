<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;
use Drupal\Core\Block;

/**
 * @Shortcode(
 *   id = "nd_block",
 *   title = @Translation("Block"),
 *   description = @Translation("Render drupal block"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-file"
 * )
 */
class BlockShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    if (strpos($attrs['admin_url'], 'admin/structure/block') !== FALSE) {
      $block_name = substr($attrs['admin_url'], strpos($attrs['admin_url'], '/manage/') + 8);
      $block = entity_load('block', $block_name)->getPlugin()->build();
      $block = render($block);
    }
    $attrs_output = _rhythm_shortcodes_shortcode_attributes($attrs);
    $text = $attrs_output ? '<div ' . $attrs_output  . '>' . $block . '</div>' : $block;
    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function settings($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {

    $form['admin_url'] = array(
      '#title' => t('Block Adminstration page URL'),
      '#type' => 'textfield',
      '#default_value' => isset($attrs['admin_url']) ? $attrs['admin_url'] : '',
      '#attributes' => array('class' => array('form-control')),
    );

    return $form;


    $current_theme = \Drupal::config('system.theme')->get('default');

    $blocks = block_admin_display_prepare_blocks($current_theme);
    usort($blocks, '_sort_blocks');
    $options = array();
    foreach ($blocks as $block) {
      $options['admin/structure/block/manage/' . $block['module']] = check_plain($block['info']);
    }
    asort($options);
    $form['admin_url'] = array(
      '#title' => t('Block'),
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => isset($attrs['admin_url']) ? $attrs['admin_url'] : '',
      '#attributes' => array('class' => array('form-control'))
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function description($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    return '';
    if (strpos($attrs['admin_url'], 'admin/structure/block') !== FALSE) {
      $form = rhythm_shortcodes_shortcode_block_settings($attrs, $text);
      $value = l($form['admin_url']['#options'][$attrs['admin_url']], $attrs['admin_url'], array('attributes' => array('target' => '_blank')));
      return $value;
    }
  }
}