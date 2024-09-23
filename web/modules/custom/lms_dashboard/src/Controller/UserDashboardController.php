<?php

namespace Drupal\lms_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * UserDashboardController to return Dashboard template.
 */
class UserDashboardController extends ControllerBase {

  /**
   * Prepare Dasshboard view.
   */
  public function view() {
    $current_user = $this->currentUser();
    $roles = $current_user->getRoles();

    // Initialize an empty array for dashboard content.
    $dashboard_content = [];

    // Check user role and build dashboard accordingly.
    if (in_array('student', $roles)) {
      $dashboard_content = $this->getStudentDashboard();
    }
    elseif (in_array('instructor', $roles)) {
      $dashboard_content = $this->getInstructorDashboard();
    }
    elseif (in_array('administrator', $roles)) {
      $dashboard_content = $this->getAdminDashboard();
    }
    else {
      return [
        '#markup' => $this->t('You do not have access to this dashboard.'),
      ];
    }

    return [
      '#theme' => 'user_dashboard',
      '#content' => $dashboard_content,
    ];
  }

  /**
   * Markup of student dashboard.
   */
  private function getStudentDashboard() {
    return [
      '#markup' => $this->t('Student Dashboard Content.'),
    ];
  }

  /**
   * Markup of instructor dashboard.
   */
  private function getInstructorDashboard() {
    return [
      '#markup' => $this->t('Instructor Dashboard Content'),
    ];
  }

  /**
   * Markup of admin dashboard.
   */
  private function getAdminDashboard() {
    return [
      '#markup' => $this->t('Admin Dashboard Content'),
    ];
  }

}
