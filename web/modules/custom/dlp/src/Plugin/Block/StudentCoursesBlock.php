<?php

namespace Drupal\dlp\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;

/**
 * Provides a 'StudentCoursesBlock' block.
 *
 * @Block(
 *   id = "student_courses_block",
 *   admin_label = @Translation("Student Courses Block"),
 * )
 */
class StudentCoursesBlock extends BlockBase {

  /**
   * Show user course enrollment listing.
   */
  public function build() {

    $results = dlp_get_active_students();

    $student_courses = [];

    foreach ($results as $uid => $user) {
      // Fetch enrolled courses for each student.
      $enrolled_courses = $this->getUserCourses($uid);

      $student_courses[$uid] = [
        'name' => $user->name,
        'courses' => $enrolled_courses,
      ];
    }

    return [
      '#theme' => 'student_courses_block',
      '#students' => $student_courses,
      '#cache' => [
    // Adjust caching as needed.
        'max-age' => 0,
      ],
    ];
  }

  /**
   * Fetches enrolled courses and progress for a given user.
   *
   * @param int $uid
   *   The user ID.
   *
   * @return array
   *   An array of course information and progress.
   */
  private function getUserCourses($uid) {
    $query = Database::getConnection()->select('course_enrollments', 'ce')
      ->fields('ce', ['nid', 'progress'])
      ->condition('ce.uid', $uid);

    $results = $query->execute()->fetchAll();

    $courses = [];
    foreach ($results as $result) {
      $node = Node::load($result->nid);
      $total_paragraphs = count(dlp_get_paragraph_ids($node));
      // var_dump((array)$result->progress);die;.
      $completed_count = count((array) $result->progress);
      $percentage_complete = $total_paragraphs ? ($completed_count / $total_paragraphs) * 100 : 0;
      $courses[] = [
        'title' => get_course_referencing_lesson($result->nid),
        'progress' => $percentage_complete,
      ];
    }

    return $courses;
  }

}
