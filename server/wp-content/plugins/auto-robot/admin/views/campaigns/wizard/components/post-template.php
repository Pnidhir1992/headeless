<div class="robot-box-settings-row">

    <div class="robot-box-settings-col-1">
        <span class="robot-settings-label"><?php esc_html_e( 'Post Template', Auto_Robot::DOMAIN ); ?></span>
        <span class="robot-description"><?php esc_html_e( 'Customize the dashboard as per your liking.', Auto_Robot::DOMAIN ); ?></span>
    </div>

    <div class="robot-box-settings-col-2">

        <label class="robot-settings-label"><?php esc_html_e( 'Campaign keywords', Auto_Robot::DOMAIN ); ?></label>

        <span class="robot-description"><?php esc_html_e( 'Campaign keywords (search for these keywords) (comma separated).', Auto_Robot::DOMAIN ); ?></span>

        <div class="robot-form-field">
            <label for="robot_template" id="robot-feed-link" class="robot-label"><?php esc_html_e( 'Feed Source Link', Auto_Robot::DOMAIN ); ?></label>
            <input
                type="text"
                name="robot_template"
                placeholder="<?php esc_html_e( 'Enter your Feed source link here', Auto_Robot::DOMAIN ); ?>"
                value=""
                id="robot_template"
                class="robot-form-control"
            />
        </div>


    </div>

</div>
