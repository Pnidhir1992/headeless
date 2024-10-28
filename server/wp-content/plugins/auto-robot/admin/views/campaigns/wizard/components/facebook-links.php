<div class="robot-box-settings-row">

    <div class="robot-box-settings-col-1">
        <span class="robot-settings-label"><?php esc_html_e( 'Facebook URL', Auto_Robot::DOMAIN ); ?></span>
    </div>

    <div class="robot-box-settings-col-2">

        <label class="robot-settings-label"><?php esc_html_e( 'Facebook URL', Auto_Robot::DOMAIN ); ?></label>

        <span class="robot-description"><?php esc_html_e( 'You can get feeds from your own managed facebook group us the Graph Facebook API.', Auto_Robot::DOMAIN ); ?></span>

        <div class="robot-form-field">
            <label for="robot_facebook_link" id="robot-facebook-link" class="robot-label"><?php esc_html_e( 'Facebook Group Url', Auto_Robot::DOMAIN ); ?></label>
            <input
                type="text"
                name="robot_facebook_link"
                placeholder="<?php esc_html_e( 'Enter your own managed facebook group url here', Auto_Robot::DOMAIN ); ?>"
                value="<?php if(isset($settings['robot_facebook_link'])){echo $settings['robot_facebook_link'];}?>"
                id="robot_facebook_link"
                class="robot-form-control"
            />
        </div>


    </div>

</div>
