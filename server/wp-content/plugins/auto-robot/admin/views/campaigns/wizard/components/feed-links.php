<div class="robot-box-settings-row">

    <div class="robot-box-settings-col-1">
        <span class="robot-settings-label"><?php esc_html_e( 'Feed Link', Auto_Robot::DOMAIN ); ?></span>
    </div>

    <div class="robot-box-settings-col-2">

        <label class="robot-settings-label"><?php esc_html_e( 'Feed Source Link', Auto_Robot::DOMAIN ); ?></label>


        <div class="robot-form-field">
            <input
                type="text"
                name="robot_feed_link"
                placeholder="<?php esc_html_e( 'Enter your feed url here', Auto_Robot::DOMAIN ); ?>"
                value="<?php if(isset($settings['robot_feed_link'])){echo $settings['robot_feed_link'];}?>"
                id="robot_feed_link"
                class="robot-form-control"
            />
        </div>

        <p>
           <span class="instagram-description">
              <?php
              printf(
                  esc_html__( 'Note: Please enter the rss url like %1$s', Auto_Robot::DOMAIN ),
                  'https://www.wpbeginner.com/feed'
              );
              ?>
           </span>
        </p>


    </div>

</div>
