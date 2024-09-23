<?php

namespace Drupal\dlp\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'StudentDashboardBlock' block.
 *
 * @Block(
 *   id = "student_dashboard_block",
 *   admin_label = @Translation("Student Dashboard Block"),
 * )
 */
class StudentDashboardBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $query = dlp_get_user_enrolled_courses();

    $enrolled_courses = [];
    foreach ($query as $record) {
      $node = Node::load($record->nid);
      if ($node) {
        $progress_data = !empty($record->progress) ? json_decode($record->progress, TRUE) : [];
        $paragraph_ids = dlp_get_paragraph_ids($node);

        // Calculate progress based on paragraph IDs and TIDs.
        $completed_paragraphs = $progress_data;

        // Calculate percentage completion.
        $total_paragraphs = count($paragraph_ids);
        $completed_count = count($completed_paragraphs);

        $percentage_complete = $total_paragraphs ? ($completed_count / $total_paragraphs) * 100 : 0;

        // If progress is 100%, display certificate generation button.
        $can_generate_certificate = $percentage_complete == 100;
        $getTitle = dlp_get_course_referencing_lesson($node->id());
        $enrolled_courses[] = [
          'nid' => $node->id(),
          'title' => $getTitle,
          'progress' => $percentage_complete,
        // Rounding for visual representation.
          'progress_bar' => round($percentage_complete),
        // Check if 100% complete.
          'can_generate_certificate' => $can_generate_certificate,
        ];
      }
    }

    // Render the enrolled courses in a block.
    return [
      '#theme' => 'student_dashboard_block',
      '#courses' => $enrolled_courses,
      '#cache' => [
    // Disable cache to show real-time progress.
        'max-age' => 0,
      ],
    ];
  }

}
