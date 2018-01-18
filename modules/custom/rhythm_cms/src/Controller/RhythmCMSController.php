<?php

namespace Drupal\rhythm_cms\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Controller routines for page example routes.
 */
class RhythmCMSController extends ControllerBase {

  public function home_variants($type) {
    $node = node_load(97);
    $node = node_view($node);
    return $node;
  }

  public function onepage_variants($type) {
    $node = node_load(102);
    $node = node_view($node);
    return $node;
  }
}

