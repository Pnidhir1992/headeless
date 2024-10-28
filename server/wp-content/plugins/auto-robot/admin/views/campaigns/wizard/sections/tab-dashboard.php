<?php
$source = isset( $_GET['source'] ) ? sanitize_text_field( $_GET['source'] ) : 'dashboard';
$components = auto_robot_get_components($source);
?>

<div id="dashboard" class="robot-box-tab active" data-nav="dashboard" >

	<div class="robot-box-header">
		<h2 class="robot-box-title"><?php esc_html_e( 'General', Auto_Robot::DOMAIN ); ?></h2>
	</div>

    <div class="robot-box-body">
            <?php
				foreach ($components as $key => $value) {
					$this->template($value, $settings);
			    }
 			?>
   </div>


</div>
