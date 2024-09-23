(function ($, Drupal) {
    Drupal.behaviors.enrollButton = {
        attach: function (context, settings) {
          // Use jQuery to select elements and check if the handler is already attached.
            $('#enroll-button', context).each(function () {
                if (!$(this).data('enrollButtonAttached')) {
                    $(this).data('enrollButtonAttached', true).on('click', function (event) {
                        event.preventDefault(); // Prevent the default link action.

                        var $link = $(this);
                        var url = $link.attr('href');

                      // Perform the AJAX request.
                        $.ajax({
                            url: url,
                            type: 'POST',
                            dataType: 'json',
                            success: function (response) {
                                if (response.status === 'success') {
                                      alert(response.message);
                                      location.reload();
                                      // Optionally update the button or page content.
                                }
                            },
                            error: function () {
                                alert('An error occurred while processing your request.');
                            }
                        });
                    });
                }
            });


       // Attach the click event to the accordion headers using on().
            $('.accordion-header', context).on('click', function () {
                var lessonId = $(this).attr('id'); // Get the ID from the accordion header.
                var currentPage = window.location.href; // Get the current page URL.

                // Get the associated accordion body
                var accordionBody = $(this).next('.accordion-body');

                // Check if the accordion is being expanded (not already visible)
                if (accordionBody.is(':visible')) {
                  // Perform the AJAX request to store progress.
                    $.ajax({
                        url: Drupal.url('dlp/ajax/progress'), // Custom AJAX endpoint.
                        type: 'POST',
                        data: {
                            lesson_id: lessonId,
                            current_page: currentPage,
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                console.log('Progress saved successfully!');
                            } else {
                                console.log('Failed to save progress.');
                            }
                        },
                        error: function () {
                                console.log('AJAX error occurred.');
                        }
                    });
                }
            });

        // generate certificate code.
            $('.generate-certificate-btn', context).once('certificateClick').click(function () {
                var courseId = $(this).data('course-nid');
                window.location.href = Drupal.url('dlp/certificate/generate/' + courseId);
            });


        }
    };
})(jQuery, Drupal);
