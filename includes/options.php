<?php

function wp_unload_user() {
	add_options_page( 'WP Unload Users', 'Unload users', 'manage_options', 'wp-unload-options', 'my_plugin_options' );
}

function register_unloadsettings() { // whitelist options
    register_setting( 'unload_options', 'unload_waiting_time', [
        'default' => 60,
    ]);
    register_setting( 'unload_options', 'unload_admins', [
        'default' => 1,
    ] );
}
if ( is_admin() ){ // admin actions
    add_action( 'admin_init', 'register_unloadsettings' );
    add_action( 'admin_menu', 'wp_unload_user' );
} else {
    // non-admin enqueues, actions, and filters
}

  function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    ?>
	<div class="wrap">
    
    <h1>WP Unload Users</h1>
        <p>
        This plugins keep track of users online status and triggers a function to forcibly loggout them after some time of inactivity.
        </p><p>
        It works by setting a user meta that updates everytime an user load a page and deleting said meta when the unload event is triggered.
        You can setup how this plugin works bellow.
        </p><p>
        For waiting time the ideal input is a number between 5 and 60 seconds, to keep your website RAM consuption regular avoiding too many concurrent delayed functions.
        </p>
        <p>
            You can check if admins are affected by this rule.
        </p>
        <form method="post" action="options.php"> 
        <?php 
            settings_fields( 'unload_options' ); 
            do_settings_sections( 'unload_options' );
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Waiting time</th>
                <td><input type="number" name="unload_waiting_time" min="5" max="60" value="<?php echo esc_attr( get_option('unload_waiting_time') ); ?>" />
            seconds
            </td>
            </tr>
            
            <tr valign="top">
            <th scope="row">Keep admins logged.</th>
            <td><input type="checkbox" name="unload_admins" <?php echo get_option('unload_admins') ? 'checked' : '' ?> /></td>
            </tr>
            
        </table>
            <?php submit_button(); ?>
        </form>
	</div>
    <?php
}