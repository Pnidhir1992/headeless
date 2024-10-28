<div class="robot-box" role="document">

    <div class="robot-box-header robot-block-content-center">

        <div class="robot-dialog-image" aria-hidden="true">

            <img src="<?php echo esc_url(AUTO_ROBOT_URL.'/assets/images/soundcloud.png'); ?>" alt="<?php esc_attr_e( 'Twitter API', Auto_Robot::DOMAIN ); ?>">

        </div>

        <div class="robot-box-content integration-header">

            <h3 class="robot-box-title" id="dialogTitle2"><?php esc_html_e( 'Setup Twitter API', Auto_Robot::DOMAIN ); ?></h3>

			<span class="robot-description">
                <?php esc_html_e( 'Setup Twitter API to be used by Auto Robot to display tweets on your blog.', Auto_Robot::DOMAIN ); ?>
			</span>

        </div>

    </div>

    <div class="robot-box-body">
        <form>

            <div class="robot-form-field">
                <label class="robot-label"><?php esc_html_e( 'Client ID', Auto_Robot::DOMAIN ); ?></label>
                <div class="robot-control-with-icon">
                    <ion-icon class="robot-icon-person" name="person"></ion-icon>
                    <input name="client_id" placeholder="<?php esc_html_e( 'Client ID', Auto_Robot::DOMAIN ); ?>" value="" class="robot-form-control">
                </div>
            </div>

            <div class="robot-form-field">
                <label class="robot-label"><?php esc_html_e( 'Client Secret', Auto_Robot::DOMAIN ); ?></label>
                <div class="robot-control-with-icon">
                    <ion-icon class="robot-icon-key" name="key"></ion-icon>
                    <input name="client_secret" placeholder="<?php esc_html_e( 'Client Secret', Auto_Robot::DOMAIN ); ?>" value="" class="robot-form-control">
                </div>
            </div>

            <div class="robot-border-frame robot-description">

                <span>
                    <?php esc_html_e( 'Follow these instructions to retrieve your Client ID and Secret.', Auto_Robot::DOMAIN ); ?>
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