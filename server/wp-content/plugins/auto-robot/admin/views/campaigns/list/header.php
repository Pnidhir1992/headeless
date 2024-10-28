<?php
    $count = $this->countModules();
    if($count >= 1){
        $href_link = '#test-popup';
        $create_class = 'open-popup-pro robot-button robot-button-blue robot-button-blue-first';
    }else{
        $href_link = admin_url( 'admin.php?page=auto-robot-welcome' );
        $create_class = 'robot-button robot-button-blue robot-button-blue-first';
    }
 ?>
<h1 class="robot-header-title"><?php esc_html_e( 'Campaigns', Auto_Robot::DOMAIN ); ?></h1>

<div class="robot-actions-left">
        <a href="<?php echo $href_link; ?>" class="<?php echo $create_class; ?>" data-effect="mfp-zoom-in">
            <?php esc_html_e( 'Create', Auto_Robot::DOMAIN ); ?>
          </a>

    <a href="https://www.spinrewriter.com/?ref=50f2e" target="_blank" class="robot-button robot-button-blue robot-button-blue-first">
        <?php esc_html_e( 'Spin Rewriter', Auto_Robot::DOMAIN ); ?>
    </a>

    <a href="https://wpautorobot.com/pricing" target="_blank" class="robot-button robot-button-blue">
        <?php esc_html_e( 'Pro Version', Auto_Robot::DOMAIN ); ?>
    </a>
</div>

<div class="robot-actions-right">
        <a href="https://wpautorobot.com/pricing" target="_blank" class="robot-button robot-button-ghost">
            <ion-icon class="robot-icon-document" name="document-text-sharp"></ion-icon>
            <?php esc_html_e( 'View Documentation', Auto_Robot::DOMAIN ); ?>
        </a>
</div>

<div id="test-popup" class="white-popup mfp-with-anim mfp-hide">

		<div class="robot-box-header robot-block-content-center">
			<h3 class="robot-box-title type-title"><?php esc_html_e( 'Upgrade to Pro', Auto_Robot::DOMAIN ); ?></h3>
		</div>

        <div class="robot-box-body robot-pro-popup-body">
            <div class="robot-getting-started__content">
                <div class="features-list-body">
                    <table id="pricing-table" class="mb0">
                        <tbody>
                        <tr>
                            <td><?php esc_html_e( 'Autoblogging with unlimited keywords and campaigns', Auto_Robot::DOMAIN ); ?></td>
                            <td><i class="dashicons dashicons-yes green" title="Feature is available"></i></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Generate content using OpenAI ChatGPT API', Auto_Robot::DOMAIN ); ?></td>
                            <td><i class="dashicons dashicons-yes green" title="Feature is available"></i></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Create posts from unlimited number of keywords on each blog with long tail keywords supported', Auto_Robot::DOMAIN ); ?></td>
                            <td><i class="dashicons dashicons-yes green" title="Feature is available"></i></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Keyword suggestions using the google suggest api', Auto_Robot::DOMAIN ); ?></td>
                            <td><i class="dashicons dashicons-yes green" title="Feature is available"></i></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Schedule publish posts as your selected', Auto_Robot::DOMAIN ); ?></td>
                            <td><i class="dashicons dashicons-yes green" title="Feature is available"></i></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Use WP Cron Job to run campaigns automatically', Auto_Robot::DOMAIN ); ?></td>
                            <td><i class="dashicons dashicons-yes green" title="Feature is available"></i></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Select post type include post, page, attachment', Auto_Robot::DOMAIN ); ?></td>
                            <td><i class="dashicons dashicons-yes green" title="Feature is available"></i></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Select post status include publish, draft, private, pending', Auto_Robot::DOMAIN ); ?></td>
                            <td><i class="dashicons dashicons-yes green" title="Feature is available"></i></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Select post author', Auto_Robot::DOMAIN ); ?></td>
                            <td><i class="dashicons dashicons-yes green" title="Feature is available"></i></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Insert content before and after post template', Auto_Robot::DOMAIN ); ?></td>
                            <td><i class="dashicons dashicons-yes green" title="Feature is available"></i></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Set rss campaign generated post content words limit', Auto_Robot::DOMAIN ); ?></td>
                            <td><i class="dashicons dashicons-yes green" title="Feature is available"></i></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Generate WordPress posts from RSS Feed Link', Auto_Robot::DOMAIN ); ?></td>
                            <td><i class="dashicons dashicons-yes green" title="Feature is available"></i></td>
                        </tr>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="robot-box-footer robot-box-footer-center robot-pro-popup-footer">

          <a href="<?php echo esc_url( 'https://wpautorobot.com/pricing', Auto_Robot::DOMAIN ); ?>" target="_blank" class="robot-button robot-button-blue">
                    <?php esc_html_e( 'Try pro version today', Auto_Robot::DOMAIN ); ?>
                </a>
        </div>

		<img src="<?php echo esc_url(AUTO_ROBOT_URL.'/assets/images/robot.png'); ?>" class="robot-image robot-image-center" aria-hidden="true" alt="<?php esc_attr_e( 'Auto Robot', Auto_Robot::DOMAIN ); ?>">
</div>