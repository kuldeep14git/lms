<?php

declare(strict_types=1);

/**
 * @file
 * Theme settings form for LMS theme.
 */

use Drupal\Core\Form\FormState;

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function lms_form_system_theme_settings_alter(array &$form, FormState $form_state): void {

  $form['lms'] = [
    '#type' => 'details',
    '#title' => t('LMS'),
    '#open' => TRUE,
  ];

  $form['lms']['example'] = [
    '#type' => 'textfield',
    '#title' => t('Example'),
    '#default_value' => theme_get_setting('example'),
  ];

}
