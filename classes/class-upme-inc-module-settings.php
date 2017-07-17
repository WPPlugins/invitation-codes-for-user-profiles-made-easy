<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class UPME_INC_Module_Settings{
    
    public $template_locations;
    
    public function __construct(){
       
        add_action( 'upme_addon_module_tabs',array($this, 'upme_inc_addon_module_tabs') );
        add_action( 'upme_addon_module_settings',array($this, 'upme_inc_addon_module_settings') );
        
        add_filter( 'upme_template_loader_locations', array($this,'template_loader_locations'));
        
        add_action('admin_enqueue_scripts', array($this,'include_scripts'));
        
    }
    
    public function include_scripts(){
        /* Add scripts for invitation code module settings */
        wp_register_style('upme_inc_admin_css', UPME_INC_PLUGIN_URL . 'css/upme-inc-admin.css');
        wp_enqueue_style('upme_inc_admin_css');
        
        wp_register_script('upme_inc_admin_js', UPME_INC_PLUGIN_URL . 'js/upme-inc-admin.js', array('jquery'));
        wp_enqueue_script('upme_inc_admin_js');
        
        $params = array(
                'AdminAjax' => admin_url('admin-ajax.php'),
                'messages' => array(
                    'emptyInvCode' => __('Invitation code is required.','upmeinc'),
                    'emptyInvQuota' => __('Quota is required.','upmeinc'),
                    'emptyInvEmails' => __('Emails is required.','upmeinc'),
                    'numericInvQuota' => __('Quota should be numeric.','upmeinc'),
                    'savingIncCode' => __('Adding Invitation Code', 'upmeinc'),
                    'saveIncCode' => __('Add Invitation Code','upmeinc'),
                    'sendingIncCode' => __('Sending Invitation Code', 'upmeinc'),
                    'sendIncCode' => __('Send Invitation Code','upmeinc'),
                ));
        wp_localize_script('upme_inc_admin_js', 'UPMEINC_ADMIN', $params);
    }
    
    public function upme_inc_addon_module_tabs(){
        
        echo '<li class="upme-tab " id="upme-invitation-codes-settings-tab">'. __('Invitation Codes','upmeinc').'</li>';
    }
    
    public function upme_inc_addon_module_settings(){
        global $upme_template_loader;
        
        
        
        ob_start();
        $upme_template_loader->get_template_part('invitation-code','list');
        $display = ob_get_clean();        
        echo $display;
    }
    
    public function template_loader_locations($locations){
        $location = trailingslashit( UPME_INC_PLUGIN_DIR ) . 'templates/';
        array_push($locations,$location);
        return $locations;
    }
}