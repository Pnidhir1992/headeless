<div class="robot-box-settings-row">

    <div class="robot-box-settings-col-1">
        <span class="robot-settings-label"><?php esc_html_e( 'Feed Search Keywords', Auto_Robot::DOMAIN ); ?></span>
    </div>

    <div class="robot-box-settings-col-2">
        <div class="robot-form-field">
            <textarea class="robot-form-control" id="rss-selected-keywords" rows="5" cols="20" name="rss_selected_keywords" required="required"><?php if(isset($settings['rss_selected_keywords'])){echo $settings['rss_selected_keywords'];}?></textarea>
        </div>
    </div>

</div>
