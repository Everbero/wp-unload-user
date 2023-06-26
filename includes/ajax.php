<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

function setUserLogoffTimeout(){
    //error_log('setUserLogoffTimeout started at '. time());

    //if not receiving a post ends
    if (!isset($_POST)) {
		wp_die();
	}

    if ( current_user_can( 'manage_options' ) ) {
        //Something in the case of admin
        if(get_option( 'unload_admins' )){
            wp_die();
        }
    }

    $user = get_current_user_id();
    //if there is no logged in user ends
    if($user === 0) wp_die();

    delete_user_meta($user, 'unload_status'); 

    //set this function to wait a bit
    sleep(intval(get_option('unload_waiting_time')));


    //if i still can't find an logged in flag after a minute
    if(!get_user_meta($user, 'unload_status', true)){
        wp_logout();
        wp_die();
    }

    wp_die();
    
}

function setUserOnline(){
    //error_log('setUserOnline started at '. time());
    //if not receiving a post ends
    if (!isset($_POST)) {
		wp_die();
	}

    $user = get_current_user_id();
    //if there is no logged in user ends
    if($user === 0) wp_die();
    
    //flag this user as logged in
    update_user_meta($user, 'unload_status', true); 
    
}

//called onver an unload event
add_action('wp_ajax_setUserLogoffTimeout', 'setUserLogoffTimeout');
add_action('wp_ajax_setUserOnline', 'setUserOnline');