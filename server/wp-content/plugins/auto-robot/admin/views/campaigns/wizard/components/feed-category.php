<?php
$categories = array(
    "health" => "Health",
    "business" => "Business",
    "technology" => "Technology",
    "entertainment" => "Entertainment",
    "sports" => "Sports",
    "science" => "Science",
);
?>
<div class="robot-box-settings-row">

    <div class="robot-box-settings-col-1">
        <span class="robot-settings-label"><?php esc_html_e( 'Feed Category', Auto_Robot::DOMAIN ); ?></span>
    </div>

    <div class="robot-box-settings-col-2">

        <div class="robot-form-field">
            <span class="dropdown-el robot-init-category-selector">
                <?php foreach ( $categories as $key => $value ) : ?>
                    <input type="radio" name="robot_init_category" value="<?php echo esc_attr( $key ); ?>" <?php if(isset($settings['robot_init_category']) && $key == $settings['robot_init_category']){echo 'checked="checked"';}elseif($key == 'health'){echo 'checked="checked"';} ?> id="<?php echo esc_attr( $key ); ?>">
                    <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></label>
                <?php endforeach; ?>
            </span>
        </div>
    </div>

</div>
