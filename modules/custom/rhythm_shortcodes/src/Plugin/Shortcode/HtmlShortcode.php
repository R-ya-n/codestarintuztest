<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;

/**
 * The image shortcode.
 *
 * @Shortcode(
 *   id = "html",
 *   title = @Translation("HTML"),
 *   description = @Translation("HTML with CKEDITOR"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-code"
 * )
 */
class HtmlShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {

    // Made a little hack for tables which can't be controled because rendered some WYSIWYG editor, and also list styles
    $text = str_replace(array('<table', '<ul>', '<ol>'), array('<table class = "table table-bordered table-striped"', '<ul class = "list">', '<ol class = "list">'), $text);
    $attrs_output = _rhythm_shortcodes_shortcode_attributes($attrs);
    if($attrs_output) {
      return '<div ' . $attrs_output . '>' . $text . '</div>';
    }
    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function settings($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $config =\Drupal::config('nd_visualshortcodes.settings');
    // Name should be unical because WYSIWYG editor will conflict during multi-fields upload
    $form['body'] = array(
      '#type' => 'text_format',
      '#format' => $config->get('html_default_format'),
      '#default_value' => $text,
    );
    return $form;
  }

}