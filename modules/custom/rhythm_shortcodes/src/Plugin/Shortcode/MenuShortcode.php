<?php

namespace Drupal\rhythm_shortcodes\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;
use Drupal\search\Form\SearchBlockForm;

/**
 * @Shortcode(
 *   id = "nd_menu",
 *   title = @Translation("Menu"),
 *   description = @Translation("Render menu"),
 *   process_backend_callback = "nd_visualshortcodes_backend_nochilds",
 *   icon = "fa fa-bars"
 * )
 */
class MenuShortcode extends ShortcodeBase {

  /**
   * {@inheritdoc}
   */
  public function process($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $color = isset($attrs['type']) ? $attrs['type'] : '';
    $transparent = isset($attrs['transparent']) ? $attrs['transparent'] : FALSE;
    $color .= $transparent ? ' transparent stick-fixed' : ' js-stick';
    // Search block
    $search = isset($attrs['search']) ? $attrs['search'] : TRUE;
    if($search && \Drupal::moduleHandler()->moduleExists('search')) {
      $form = \Drupal::formBuilder()->getForm(\Drupal\search\Form\SearchBlockForm::class);
      $search = render($form);
    }

    $menu_type = isset($attrs['menu_type']) ? $attrs['menu_type'] : '';

    $cart = isset($attrs['cart']) ? $attrs['cart'] : FALSE;
    // Render Menu
    $menu = isset($attrs['menu']) ? $attrs['menu'] : 'main';
    $class = $menu_type == 'popup_menu' ? 'fm' : 'mn';
    if($menu_type != 'popup_menu' && \Drupal::moduleHandler()->moduleExists('tb_megamenu')) {
      $menu_array = array(
        '#theme' => 'tb_megamenu',
        '#menu_name' => $menu,
        '#post_render' => array('tb_megamenu_attach_number_columns')
      );
      $menu = render($menu_array);
    }
    else {
      $menu = render_menu($menu, $class);
    }
    // Render Language
    $language = isset($attrs['language']) ? $attrs['language'] : FALSE;
    $lang_code = '';
    if($language && \Drupal::moduleHandler()->moduleExists('language')) {
      $block = entity_load('block', 'languageswitcher')->getPlugin()->build();
      $language = render($block);
      $lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
    }
    $file = isset($attrs['fid']) && !empty($attrs['fid']) ? file_load($attrs['fid']) : '';
    $logo = isset($file->uri) ? file_create_url($file->getFileUri()) : theme_get_setting('logo.url');


    $config = \Drupal::config('system.site');
    $site_name = $config->get('name');

    switch ($menu_type) {
      case 'popup_menu':
        $output = [
          '#theme' => 'rhythm_cms_menu_popup',
          '#menu' => $menu,
          '#logo' => $logo
        ];
        break;
      default:
        $output = [
          '#theme' => 'rhythm_cms_menu',
          '#menu' => $menu,
          '#logo' => $logo,
          '#color' => $color,
          '#search' => $search,
          '#cart' => $cart,
          '#language' => $language,
          '#lang_code' => $lang_code,
          '#site_name' => $site_name
        ];
    }

    $output = $this->render($output);
    $attrs_output = _rhythm_shortcodes_shortcode_attributes($attrs);
    if ($attrs_output) {
      $output = '<div ' . $attrs_output . '>' . $output . '</div>';
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function settings($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $menu_type = array('' => t('Default'), 'popup_menu' => t('Popup Menu'));
    $form['menu_type'] = array(
      '#type' => 'select',
      '#title' => t('Menu Type'),
      '#default_value' => isset($attrs['menu_type']) ? $attrs['menu_type'] : '',
      '#options' => $menu_type,
      '#attributes' => array('class' => array('form-control menu-select')),
      '#prefix' => '<div class = "row"><div class = "col-sm-4">',
      '#suffix' => '</div>'
    );
    $menus = menu_ui_get_menus();
    $form['menu'] = array(
      '#type' => 'select',
      '#title' => t('Menu'),
      '#default_value' => isset($attrs['menu']) ? $attrs['menu'] : '',
      '#options' => $menus,
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "col-sm-4">',
      '#suffix' => '</div></div>'
    );

    $form['fid'] = array(
      '#type' => 'textfield',
      '#title' => t('Image'),
      '#default_value' => isset($attrs['fid']) ? $attrs['fid'] : '',
      '#prefix' => '<div class = "row"><div class = "col-sm-6"><div class="image-gallery-upload ">',   
      '#suffix' => '</div></div></div>',
      '#attributes' => array('class' => array('image-gallery-upload hidden')),
      '#field_suffix' => '<div class = "preview-image"></div><a href = "#" class = "vc-gallery-images-select button">' . t('Upload Image') .'</a><a href = "#" class = "gallery-remove button">' . t('Remove Image') .'</a>'
    );

    if(isset($attrs['fid']) && !empty($attrs['fid'])) {
      $file = isset($attrs['fid']) && !empty($attrs['fid']) ? file_load($attrs['fid']) : '';
      if($file) {
        $filename = $file->getFileUri();
        $filename=\Drupal\image\Entity\ImageStyle::load('medium')->buildUrl($filename);
        $form['fid']['#prefix'] = '<div class = "row"><div class = "col-sm-6"><div class="image-gallery-upload has_image">';
        $form['fid']['#field_suffix']=  '<div class = "preview-image"><img src="'.$filename.'"></div><a href = "#" class = "vc-gallery-images-select button">' . t('Upload Image') .'</a><a href = "#" class = "gallery-remove button">' . t('Remove Image') .'</a>';
      }
    }

    $states =  array(
      'visible' => array(
        '.menu-select' => array('!value' => 'popup_menu'),
      ),
    );
    $types = array('white' => t('White'), 'dark' => t('Dark'));
    $form['type'] = array(
      '#type' => 'select',
      '#title' => t('Background Type'),
      '#options' => $types,
      '#default_value' => isset($attrs['type']) ? $attrs['type'] : 'white',
      '#attributes' => array('class' => array('form-control')),
      '#prefix' => '<div class = "row"><div class = "col-sm-4">',
      '#states' => $states,
    );
    $form['transparent'] = array(
      '#type' => 'checkbox',
      '#title' => t('Transparent'),
      '#default_value' => isset($attrs['transparent']) ? $attrs['transparent'] : TRUE,
      '#prefix' => '</div><div class = "col-sm-4">',
      '#suffix' => '</div></div>',
      '#states' => $states,
    );
    $form['search'] = array(
      '#type' => 'checkbox',
      '#title' => t('Search Box'),
      '#default_value' => isset($attrs['search']) ? $attrs['search'] : TRUE,
      '#prefix' => '<div class = "row"><div class = "col-sm-3">',
      '#states' => $states,
    );
    $form['cart'] = array(
      '#type' => 'checkbox',
      '#title' => t('Cart'),
      '#default_value' => isset($attrs['cart']) ? $attrs['cart'] : FALSE,
      '#prefix' => '</div><div class = "col-sm-3">',
      '#states' => $states,
    );
    $form['language'] = array(
      '#type' => 'checkbox',
      '#title' => t('Language Swticher'),
      '#default_value' => isset($attrs['language']) ? $attrs['language'] : FALSE,
      '#prefix' => '</div><div class = "col-sm-3">',
      '#suffix' => '</div></div>',
      '#states' => $states,
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function description($attrs, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    if (isset($attrs['admin_url']) && strpos($attrs['admin_url'], 'admin/structure/views/view') !== FALSE) {
      $form = jango_shortcodes_shortcode_view_settings($attrs, $text);
      $value = l($form['admin_url']['#options'][$attrs['admin_url']], $attrs['admin_url'], array('attributes' => array('target' => '_blank')));
      return $value;
    }
  }
}