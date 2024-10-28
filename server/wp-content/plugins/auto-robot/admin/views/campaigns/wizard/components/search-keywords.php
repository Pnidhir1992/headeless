<div class="robot-box-settings-row">

    <div class="robot-box-settings-col-1">
        <span class="robot-settings-label"><?php esc_html_e( 'Keywords', Auto_Robot::DOMAIN ); ?></span>
        <span class="robot-description"><?php esc_html_e( 'Customize the dashboard as per your liking.', Auto_Robot::DOMAIN ); ?></span>
    </div>

    <div class="robot-box-settings-col-2">

        <label class="robot-settings-label"><?php esc_html_e( 'Campaign keywords', Auto_Robot::DOMAIN ); ?></label>

        <span class="robot-description"><?php esc_html_e( 'Campaign keywords (search for these keywords) (comma separated).', Auto_Robot::DOMAIN ); ?></span>

        <div class="robot-form-field">
            <label for="robot_search" id="robot-feed-link" class="robot-label"><?php esc_html_e( 'search keyword', Auto_Robot::DOMAIN ); ?></label>
            <input
                type="text"
                name="robot_search"
                placeholder="<?php esc_html_e( 'Enter your search keyword here', Auto_Robot::DOMAIN ); ?>"
                value=""
                id="robot-search"
                class="robot-form-control"
                aria-labelledby="robot_search"
            />
        </div>

        <div class="robot-search-results-wrapper">
            <ul class="search-result-list">
            </ul>
        </div>

        <div class="robot-form-field">
            <label for="robot_selected_keywords" id="robot-feed-link" class="robot-label"><?php esc_html_e( 'Selected Keywords', Auto_Robot::DOMAIN ); ?></label>
            <textarea class="robot-form-control" id="robot-selected-keywords" rows="5" cols="20" name="robot_selected_keywords" required="required"><?php if(isset($settings['robot_selected_keywords'])){echo $settings['robot_selected_keywords'];}?></textarea>
        </div>


    </div>

</div>
