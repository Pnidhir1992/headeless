<?php

/**
 * Ask for some love.
 *
 * @since 1.3.2
 */
class Auto_Robot_Review {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.3.2
	 */
	public function __construct() {

		// Admin notice requesting review.
		add_action( 'admin_notices', array( $this, 'review_request' ) );
		add_action( 'wp_ajax_auto_robot_review_dismiss', array( $this, 'review_dismiss' ) );

	}

	/**
	 * Add admin notices as needed for reviews.
	 *
	 * @since 1.3.2
	 */
	public function review_request() {

		// Only consider showing the review request to admin users.
		if ( ! is_super_admin() ) {
			return;
		}

		// Not show review notice on the following pages
		$screen = get_current_screen();
        $where = array(
            'toplevel_page_auto-robot',
            'auto-robot_page_auto-robot-campaign',
            'auto-robot_page_auto-robot-integrations',
            'auto-robot_page_auto-robot-wizard',
			'auto-robot_page_auto-robot-campaign-wizard',
            'auto-robot_page_auto-robot-logs',
            'auto-robot_page_auto-robot-settings',
            'auto-robot_page_auto-robot-upgrade',
            'auto-robot_page_auto-robot-welcome'
        );

        if ( in_array($screen->base, $where) ) {
            return;
        };

		// Verify that we can do a check for reviews.
		$review = get_option( 'auto_robot_review' );
		$time   = time();
		$load   = false;

		if ( ! $review ) {
			$review = array(
				'time'      => $time,
				'dismissed' => false,
			);
			update_option( 'auto_robot_review', $review );
		} else {
			// Check if it has been dismissed or not.
			if ( isset( $review['dismissed'] ) && ! $review['dismissed']  ) {
				$load = true;
			}
		}

		// If we cannot load, return early.
		if ( ! $load ) {
			return;
		}

		$this->review_lite();
	}

	/**
	 * Maybe show Lite review request.
	 *
	 * @since 1.3.9
	 */
	public function review_lite() {

		// Fetch when plugin was initially installed.
		$activated = get_option( 'auto_robot_activated', array() );

		if ( ! empty( $activated['lite'] ) ) {
			// Only continue if plugin has been installed for at least 7 days.
			if ( ( $activated['lite'] + ( DAY_IN_SECONDS * 7 ) ) > time() ) {
				return;
			}
		} else {
			$activated['lite'] = time();
			update_option( 'auto_robot_activated', $activated );

			return;
		}

		// Only proceed with displaying if the user created at least one form.
		$form_count = wp_count_posts( 'auto_robot_forms' );
		if ( empty( $form_count->publish ) ) {
			return;
		}

		// We have a candidate! Output a review message.
		?>
		<div class="notice notice-info is-dismissible auto_robot-review-notice">
			<p><?php esc_html_e( 'Hey, I noticed you created a campaign with Auto Robot - that’s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', Auto_Robot::DOMAIN ); ?></p>
			<p>
				<a href="https://wordpress.org/support/plugin/auto-robot/reviews/?filter=5#new-post" class="auto_robot-dismiss-review-notice auto_robot-review-out" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Ok, you deserve it', Auto_Robot::DOMAIN ); ?></a><br>
				<a href="#" class="auto_robot-dismiss-review-notice" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Nope, maybe later', Auto_Robot::DOMAIN ); ?></a><br>
				<a href="#" class="auto_robot-dismiss-review-notice" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'I already did', Auto_Robot::DOMAIN ); ?></a>
			</p>
		</div>
		<script type="text/javascript">
			jQuery( function ( $ ) {
				$( document ).on( 'click', '.auto_robot-dismiss-review-notice, .auto_robot-review-notice button', function ( event ) {
					if ( ! $( this ).hasClass( 'auto_robot-review-out' ) ) {
						event.preventDefault();
					}
					$.post( ajaxurl, {
						action: 'auto_robot_review_dismiss'
					} );
					$( '.auto_robot-review-notice' ).remove();
				} );
			} );
		</script>
		<?php
	}

	/**
	 * Dismiss the review admin notice
	 *
	 * @since 1.3.2
	 */
	public function review_dismiss() {

		$review              = get_option( 'auto_robot_review', array() );
		$review['time']      = time();
		$review['dismissed'] = true;

		update_option( 'auto_robot_review', $review );
		die;
	}

}

new Auto_Robot_Review;
