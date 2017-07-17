<?php

add_action('admin_init', 'upme_inc_upgrade_routine');

function upme_inc_upgrade_routine() {

 
    $stored_version = get_option('upme_inc_version');
    $current_version = upme_inc_get_plugin_version();

    if (!$stored_version && $current_version) {
        upme_inc_initialize_regular_tasks();
        update_option('upme_inc_version', $current_version);
    }

    if (version_compare($current_version, $stored_version) == 0) {
        return;
    }

    update_option('upme_inc_version', $current_version);
}

function upme_inc_initialize_regular_tasks(){
    $current_option = get_option('upme_options');

    $current_option['invitation_code_activation_status']  = '0';
    update_option('upme_options', $current_option);
}