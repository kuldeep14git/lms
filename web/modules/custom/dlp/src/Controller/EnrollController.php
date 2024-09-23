<?php

namespace Drupal\dlp\Controller;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a controller for course enrollment.
 */
class EnrollController extends ControllerBase {

  /**
   * Handles the enrollment AJAX request.
   */
  public function enroll(Request $request) {

    // Get the current user and node ID from the request.
    $uid = \Drupal::currentUser()->id();

    // Get the current URL path from the server's request URI.
    $url = $_SERVER['REQUEST_URI'];
    // Split the URL into parts using '/' as the delimiter.
    $parts = explode('/', trim($url, '/'));

    // Get the last part (the ID).
    $nid = end($parts);

    // Validate the input (e.g., check if node exists, etc.).
    if (!$nid || !$uid) {
      return new JsonResponse(['status' => 'error', 'message' => 'Invalid request.']);
    }
    // Perform the enrollment logic (e.g., insert into database).
    $result = dlp_enroll_user_in_course($nid);

    // Check if the insertion was successful.
    if ($result) {
      Cache::invalidateTags(['node:' . $nid]);
      return new JsonResponse(['status' => 'success', 'message' => 'Enrolled successfully.']);
    }
    else {
      return new JsonResponse(['status' => 'error', 'message' => 'Enrollment failed.']);
    }
  }

}
