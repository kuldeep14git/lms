<?php

namespace Drupal\dlp\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProgressController capture progress on click of pid's.
 */
class ProgressController extends ControllerBase {

  /**
   * Save progress callback.
   *
   * This method will handle the AJAX request and save the progress.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The HTTP request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response to indicate success or failure.
   */
  public function saveProgress(Request $request) {
    // Get the data from the AJAX request.
    $lesson_id = $request->request->get('lesson_id');
    $current_page = $request->request->get('current_page');

    // Split the URL into parts using '/' as the delimiter.
    $parts = explode('/', trim($current_page, '/'));
    $nid = end($parts);
    // Get current user ID.
    $uid = \Drupal::currentUser()->id();

    // Check if the user has permission to enroll in the course.
    if (!\Drupal::currentUser()->hasPermission('enroll in course')) {
      return new JsonResponse(['status' => 'error', 'message' => 'permission denied.']);
    }
    // Check if the lesson_id and current_page are present.
    if (!empty($lesson_id) && !empty($current_page)) {
      try {
        // Call the function to save or update progress.
        dlp_save_or_update_progress($uid, $nid, $lesson_id);

        // Return a successful JSON response.
        return new JsonResponse(['status' => 'success']);
      }
      catch (\Exception $e) {
        // Log the error and return a failure response.
        \Drupal::logger('dlp')->error($e->getMessage());
        return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()]);
      }
    }

    // If data is missing, return an error.
    return new JsonResponse(['status' => 'error', 'message' => 'Missing required parameters.']);
  }

}
