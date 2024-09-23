<?php

namespace Drupal\dlp\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class generates PDF of completed courses.
 */
class CertificateController extends ControllerBase {

  /**
   * Generate certificate.
   *
   * @param int $nid
   *   The node ID for the course.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response containing the PDF certificate.
   */
  public function generateCertificate($nid) {

    // Load the course node.
    $course = Node::load($nid);
    if (!$course || $course->getType() !== 'lessons') {
      throw new NotFoundHttpException();
    }

    // Retrieve user progress and other necessary data here.
    $progress = dlp_get_user_progress($nid);

    // Check if progress indicates completion (e.g., JSON structure).
    $progress_data = !empty($progress) ? json_decode($progress, TRUE) : [];
    $completed_count = count($progress_data);

    // Define completion criteria (for example, if all lessons are completed).
    $total_lessons = $this->getTotalLessons($nid);

    if ($completed_count >= $total_lessons) {
      // Generate the PDF using PDF Generator.
      $pdf_content = $this->buildCertificateContent($course);

      // Get the PDF generator service.
      $pdf_generator = \Drupal::service('pdf_generator.dompdf_generator');

      // Generate the PDF response.
      return $pdf_generator->getResponse($course->getTitle(), ['#markup' => $pdf_content]);
    }
    else {
      return new JsonResponse(['status' => 'error', 'message' => 'Course not completed.'], 400);
    }
  }

  /**
   * Get the total number of lessons for a given course.
   *
   * @param int $nid
   *   The node ID of the course.
   *
   * @return int
   *   The total number of lessons.
   */
  private function getTotalLessons($nid) {
    $course = Node::load($nid);
    // Assuming lessons are stored in a field called field_add_lesson.
    return count($course->get('field_add_lesson')->referencedEntities());
  }

  /**
   * Build the content for the certificate.
   *
   * @param \Drupal\node\Entity\Node $course
   *   The course node entity.
   *
   * @return string
   *   The HTML content for the certificate.
   */
  private function buildCertificateContent(Node $course) {
    $username = $this->getUserName();
    $course_title = dlp_get_course_referencing_lesson($course->id());
    return '<h1>Certificate of Completion</h1>
            <p>This certifies that</p>
            <h2>' . $username . '</h2>
            <p>has completed the course:</p>
            <h3>' . $course_title . '</h3>
            <p>Congratulations!</p>';
  }

  /**
   * Get the current user's name.
   *
   * @return string
   *   The user's name.
   */
  private function getUserName() {
    $user = \Drupal::currentUser();
    return $user->getDisplayName();
  }

}
