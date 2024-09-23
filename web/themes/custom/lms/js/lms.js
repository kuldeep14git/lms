/**
 * @file
 * LMS behaviors.
 */
(function (Drupal) {

  'use strict';

  Drupal.behaviors.lms = {
    attach (context, settings) {

      console.log('It works!');

    }
  };

  document.querySelectorAll('.accordion-header').forEach(header => {
    header.addEventListener('click', () => {
        const body = header.nextElementSibling;
        const isVisible = body.classList.contains('show');

        // Hide all bodies
        document.querySelectorAll('.accordion-body').forEach(b => b.classList.remove('show'));
        // Toggle the clicked body
        if (!isVisible) {
            body.classList.add('show');
        }
    });
});

} (Drupal));
