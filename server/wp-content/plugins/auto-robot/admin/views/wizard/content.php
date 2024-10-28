
<div class="page-container header-wizard">
	<div class="page-content">
		<div class="row row-custom">
			<div class="robot-wizard-text textalign">
				<p><?php echo esc_attr( __( 'Hi admin!', Auto_Robot::DOMAIN ) ); ?></p>
				<p><?php echo esc_attr( __( 'Never ever miss an opportunity to opt in for Email Notifications / Announcements about exciting New Features and Update Releases.', Auto_Robot::DOMAIN ) ); ?></p>
				<p><?php echo esc_attr( __( 'Contribute in helping us making our plugin compatible with most plugins and themes by allowing to share non-sensitive information about your website.', Auto_Robot::DOMAIN ) ); ?></p>
				<p><?php echo esc_attr( __( 'If you opt in, some data about your usage of Auto Robot will be sent to our servers for Compatiblity Testing Purposes and Email Notifications.', Auto_Robot::DOMAIN ) ); ?></p>
		    </div>
		</div>
		<div class="row row-custom">
			<div class="robot-wizard-text">
				<div class="textalign">
					<p><?php echo esc_attr( __( 'If you\'re not ready to Opt-In, that\'s ok too!', Auto_Robot::DOMAIN ) ); ?></p>
					<p><strong><?php echo esc_attr( __( 'Auto Robot Lite will still work fine.', Auto_Robot::DOMAIN ) ); ?></strong></p>
				</div>
			</div>
			<div class="robot-wizard-text">
				<a href="#permissions" class="robot-wizard-permissions"><?php echo esc_attr( __( 'What permissions are being granted?', Auto_Robot::DOMAIN ) ); ?></a>
			</div>
			<div class="robot-wizard-text" style="display:none;" id="robot_wizard_set_up">
				<div class="col-md-6">
					<ul>
						<li>
							<i class="dashicons dashicons-admin-users cpo-dashicons-admin-users"></i>
							<div class="admin">
								<span><strong><?php echo esc_attr( __( 'User Details', Auto_Robot::DOMAIN ) ); ?></strong></span>
								<p><?php echo esc_attr( __( 'Name and Email Address', Auto_Robot::DOMAIN ) ); ?></p>
							</div>
						</li>
					</ul>
				</div>
				<div class="col-md-6 align align2">
					<ul>
						<li>
							<i class="dashicons dashicons-admin-plugins cpo-dashicons-admin-plugins"></i>
							<div class="admin-plugins">
								<span><strong><?php echo esc_attr( __( 'Current Plugin Status', Auto_Robot::DOMAIN ) ); ?></strong></span>
								<p><?php echo esc_attr( __( 'Activation, Deactivation and Uninstall', Auto_Robot::DOMAIN ) ); ?></p>
							</div>
						</li>
					</ul>
				</div>
				<div class="col-md-6">
					<ul>
						<li>
							<i class="dashicons dashicons-testimonial cpo-dashicons-testimonial"></i>
								<div class="testimonial">
									<span><strong><?php echo esc_attr( __( 'Notifications', Auto_Robot::DOMAIN ) ); ?></strong></span>
									<p><?php echo esc_attr( __( 'Updates &amp; Announcements', Auto_Robot::DOMAIN ) ); ?></p>
								</div>
						</li>
					</ul>
				</div>
				<div class="col-md-6 align2">
					<ul>
						<li>
							<i class="dashicons dashicons-welcome-view-site cpo-dashicons-welcome-view-site"></i>
							<div class="settings">
								<span><strong><?php echo esc_attr( __( 'Website Overview', Auto_Robot::DOMAIN ) ); ?></strong></span>
								<p><?php echo esc_attr( __( 'Site URL, WP Version, PHP Info, Plugins &amp; Themes Info', Auto_Robot::DOMAIN ) ); ?></p>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="robot-wizard-text allow">
				<div class="robot-wizard-actions">
					<a href="#robot-opt-in" class="button button-primary-wizard robot-wizard-opt-in">
						<strong><?php echo esc_attr( __( 'Opt-In &amp; Continue', Auto_Robot::DOMAIN ) ); ?> </strong>
						<i class="dashicons dashicons-arrow-right-alt cpo-dashicons-arrow-right-alt"></i>
					</a>
					<a href="#robot-skip" class="button button-secondary-wizard robot-wizard-skip" tabindex="2">
						<strong><?php echo esc_attr( __( 'Skip &amp; Continue', Auto_Robot::DOMAIN ) ); ?> </strong>
						<i class="dashicons dashicons-arrow-right-alt cpo-dashicons-arrow-right-alt"></i>
					</a>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="robot-wizard-text terms">
				<a href="<?php echo esc_url( WPHOBBY_MAIN_URL ) . '/privacy-policy/'; ?>" target="_blank"><?php echo esc_attr( __( 'Privacy Policy', Auto_Robot::DOMAIN ) ); ?></a>
					<span> - </span>
				<a href="<?php echo esc_url( WPHOBBY_MAIN_URL ) . '/terms-and-conditions/'; ?>" target="_blank"><?php echo esc_attr( __( 'Terms &amp; Conditions', Auto_Robot::DOMAIN ) ); ?></a>
			</div>
		</div>
	</div>
</div>
