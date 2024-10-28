<?php
$current_addon_data = auto_robot_get_addon_data('instagram');
?>
<div class="robot-box" role="document">

    <div class="robot-box-header robot-block-content-center">

        <div class="robot-dialog-image" aria-hidden="true">

            <img src="<?php echo esc_url(AUTO_ROBOT_URL.'/assets/images/instagram-sketched.png'); ?>" alt="<?php esc_attr_e( 'Instagram SessionID', Auto_Robot::DOMAIN ); ?>">

        </div>

        <div class="robot-box-content integration-header">

            <h3 class="robot-box-title" id="dialogTitle2"><?php esc_html_e( 'Setup Instagram SessionID', Auto_Robot::DOMAIN ); ?></h3>

			<span class="robot-description">
                <?php esc_html_e( 'Setup Instagram SessionID to be used by Auto Instagram to display feeds on your blog.', Auto_Robot::DOMAIN ); ?>
			</span>

        </div>

    </div>

    <div class="robot-box-body">
        <form class="robot-integration-form" method="post" name="robot-integration-form" action="">

            <div class="robot-form-field">
                <label class="robot-label"><?php esc_html_e( 'Session ID', Auto_Robot::DOMAIN ); ?></label>
                <div class="robot-control-with-icon">
                    <ion-icon class="robot-icon-key" name="key"></ion-icon>
                    <input name="session_id" placeholder="<?php esc_html_e( 'Session ID', Auto_Robot::DOMAIN ); ?>" value="<?php if(!empty($current_addon_data['session_id'])){echo $current_addon_data['session_id'];}?>" class="robot-form-control">
                </div>
            </div>

            <input type="hidden" name="slug" value="<?php echo esc_attr('instagram');?>" >
            <input type="hidden" name="is_connected" value="<?php echo esc_attr($current_addon_data['is_connected']);?>" >

            <div class="robot-border-frame robot-description">

                <span>
                    <?php
                    printf(
                        esc_html__( 'Follow %1$s your Instagram SessionID.', Auto_Robot::DOMAIN ),
                        '<a href="https://wpautorobot.com/pricing">these instructions to retrieve</a>'
                    );
                    ?>
                </span>

            </div>

        </form>

    </div>

    <div class="robot-box-footer robot-box-footer-center">
        <button type="button" class="robot-button robot-addon-connect">
            <span class="robot-loading-text"><?php esc_html_e( 'Connect', Auto_Robot::DOMAIN ); ?></span>
        </button>
    </div>

</div>