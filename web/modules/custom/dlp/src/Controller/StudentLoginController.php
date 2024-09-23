<?php

namespace Drupal\dlp\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides a page for student login.
 */
class StudentLoginController extends ControllerBase {

  /**
   * Returns the student login page.
   */
  public function loginPage() {
    // Render the login form or a custom message.
    $response = \Drupal::formBuilder()->getForm('Drupal\user\Form\UserLoginForm');
    return $response;
  }

}
