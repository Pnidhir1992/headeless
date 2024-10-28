<div class="robot-box-settings-row">

    <div class="robot-box-settings-col-1">
        <span class="robot-settings-label"><?php esc_html_e( 'Keywords', Auto_Robot::DOMAIN ); ?></span>
        <span class="robot-description"><?php esc_html_e( 'Customize the dashboard as per your liking.', Auto_Robot::DOMAIN ); ?></span>
    </div>

    <div class="robot-box-settings-col-2">

        <div class="sui-side-tabs">
            <div class="sui-tabs-menu">
                <div class="sui-tab-item active" data-nav="default">Specify User</div>
                <div class="sui-tab-item" data-nav="custom">Keywords</div>
            </div>
        </div>

        <div class="sui-tabs-content">
            <div class="sui-tab-content active" id="default">
                <div class="robot-form-field">
                    <label for="instagram_user" id="instagram-user-label" class="robot-label">
                        <span>
                        <?php
                        printf(
                            esc_html__( 'Instagram username(after instagram.com at the user link, for example "bbc" for  %1$s)', Auto_Robot::DOMAIN ),
                            '<a href="https://www.instagram.com/bbc/">this user</a>'
                        );
                        ?>
                        </span>
                    </label>
                    <input
                        type="text"
                        name="instagram_user"
                        placeholder="<?php esc_html_e( 'Enter your instagram username here', Auto_Robot::DOMAIN ); ?>"
                        value="<?php if(isset($settings['instagram_user'])){echo $settings['instagram_user'];}?>"
                        id="instagram-user"
                        class="robot-form-control"
                        aria-labelledby="instagram_user"
                    />
                    <p>
                     <span class="robot-description">
                        <?php
                        printf(
                            esc_html__( 'Note: Follow %1$s your Instagram SessionID before get instagram data.', Auto_Robot::DOMAIN ),
                            '<a href="https://wpautorobot.com/pricing">these instructions to retrieve</a>'
                        );
                        ?>
                     </span>
                    </p>
                </div>
            </div>

            <div class="sui-tab-content" id="custom">
                <div class="robot-form-field">
                    <label for="instagram_search" id="instagram-feed-link" class="robot-label"><?php esc_html_e( 'search keyword', Auto_Robot::DOMAIN ); ?></label>
                    <input
                        type="text"
                        name="instagram_search"
                        placeholder="<?php esc_html_e( 'Enter your search keyword here', Auto_Robot::DOMAIN ); ?>"
                        value=""
                        id="instagram-search"
                        class="robot-form-control"
                        aria-labelledby="instagram_search"
                    />
                </div>

                <div class="robot-search-results-wrapper">
                    <ul class="search-result-list">
                    </ul>
                </div>

                <div class="robot-form-field">
                    <label for="instagram_selected_keywords" id="instagram-feed-link" class="robot-label"><?php esc_html_e( 'Selected Keywords', Auto_Robot::DOMAIN ); ?></label>
                    <textarea class="robot-form-control" id="instagram-selected-keywords" rows="5" cols="20" name="instagram_selected_keywords" required="required"><?php if(isset($settings['instagram_selected_keywords'])){echo $settings['instagram_selected_keywords'];}?></textarea>
                </div>
            </div>
        </div>

    </div>

</div>
