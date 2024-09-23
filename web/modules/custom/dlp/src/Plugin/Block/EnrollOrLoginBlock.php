<?php

namespace Drupal\dlp\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Provides an 'Enroll or Login' block.
 *
 * @Block(
 *   id = "enroll_or_login_block",
 *   admin_label = @Translation("Enroll or Login Block"),
 * )
 */
class EnrollOrLoginBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = \Drupal::routeMatch()->getParameter('node');
    $current_user = \Drupal::currentUser();

    // Ensure it's a node and of type 'lesson'.
    if ($node instanceof Node && $node->bundle() == 'lessons') {
      // Check if the user is authenticated.
      if ($current_user->isAuthenticated()) {
        // Check if the current user has the 'Student' role.
        if (in_array('student', $current_user->getRoles())) {
          $is_enrolled = dlp_check_course_enrolled($current_user, $node);
          // If not enrolled, show the enroll button.
          if (!$is_enrolled) {
            $url = Url::fromRoute('dlp.enroll_course', ['node' => $node->id()]);
            $link = Link::fromTextAndUrl('Enroll', $url)->toRenderable();
            $link['#attributes'] = ['class' => ['use-ajax'], 'id' => 'enroll-button'];

            return [
              '#type' => 'inline_template',
              '#template' => '<div class="enroll-block">{{ enroll_link }}</div>',
              '#context' => ['enroll_link' => $link],
              '#cache' => [
            // Disable cache for development.
                'max-age' => 0,
              ],
            ];
          }
          else {
            // Optional: Display a message if the user is already enrolled.
            return [
              '#markup' => $this->t('You are already enrolled in this course.'),
              '#cache' => [
            // Disable cache completely.
                'max-age' => 0,
              ],
            ];
          }
        }
      }
      else {
        // User is not authenticated.
        $login_url = Url::fromRoute('user.login');
        $login_link = Link::fromTextAndUrl('Enroll', $login_url)->toRenderable();
        return [
          '#type' => 'inline_template',
          '#template' => '<div class="login-block">{{ login_link }}</div>',
          '#context' => ['login_link' => $login_link],
          '#cache' => [
        // Disable cache completely.
            'max-age' => 0,
          ],
        ];
      }
    }

    return [];
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    // Allow access for all users.
    return AccessResult::allowed();
  }

}
