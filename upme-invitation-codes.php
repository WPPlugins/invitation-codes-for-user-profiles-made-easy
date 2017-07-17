<?php
/*
  Plugin Name:  Invitation Codes for User Profiles Made Easy
  Plugin URI: http://codecanyon.net/item/user-profiles-plugin-for-wordpress/4109874?ref=ThemeFluent
  Description: An awesome invitation code management addon for User Profiles Made Easy.
  Version: 1.2
  Author: Rakhitha Nimesh
  Author URI: http://www.wpexpertdeveloper.com
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

function upme_inc_get_plugin_version() {
    $default_headers = array('Version' => 'Version');
    $plugin_data = get_file_data(__FILE__, $default_headers, 'plugin');
    return $plugin_data['Version'];
}

/* Validating existence of required plugins */
add_action( 'plugins_loaded', 'upme_inc_plugin_init' );

function upme_inc_plugin_init(){
    if(!class_exists('UPME')){
        add_action( 'admin_notices', 'upme_inc_plugin_admin_notice' );
    }else{        
        UPME_Invitation_Codes();
    }
}

function upme_inc_plugin_admin_notice() {
   $message = __('<strong>UPME Invitation Codes Addon</strong> requires <strong>User Profiles Made Easy</strong> plugin to function properly','upmeinc');
   echo '<div class="error"><p>'.$message.'</p></div>';
}


if( !class_exists( 'UPME_Invitation_Codes' ) ) {
    
    class UPME_Invitation_Codes{
    
        private static $instance;

        public static function instance() {
            
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof UPME_Invitation_Codes ) ) {
                self::$instance = new UPME_Invitation_Codes();
                self::$instance->setup_constants();

                add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
                self::$instance->includes();
                 
                self::$instance->module_settings    = new UPME_INC_Module_Settings();
                self::$instance->manager            = new UPME_INC_Manager();
            }
            return self::$instance;
        }

        private function setup_constants() {
            global $invitation_code_settings;
            
            $invitation_code_settings = get_option('upme_options');
            
            // Plugin version
            if ( ! defined( 'UPME_INC_VERSION' ) ) {
                define( 'UPME_INC_VERSION', '1.0' );
            }

            // Plugin Folder Path
            if ( ! defined( 'UPME_INC_PLUGIN_DIR' ) ) {
                define( 'UPME_INC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            }

            // Plugin Folder URL
            if ( ! defined( 'UPME_INC_PLUGIN_URL' ) ) {
                define( 'UPME_INC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
            }
            
            
            add_filter('upme_module_settings_array_fields', array($this, 'invitation_codes_settings_list'));
            add_filter('upme_init_options', array($this,'invitation_codes_general_settings') );
            add_filter('upme_default_module_settings', array($this,'invitation_codes_default_module_settings') );
            add_filter('upme_option_with_checkbox', array($this,'invitation_codes_option_with_checkbox') );
            
            // load the text domain
            load_plugin_textdomain('upmeinc', false, '/upme-invitation-codes/languages');
            
        }
        
        public function invitation_codes_settings_list($settings){
            $settings['upme-invitation-codes-settings'] = array('invitation_code_activation_status');
            return $settings;
        }
        
        public function invitation_codes_general_settings($settings){
            $settings['invitation_code_activation_status'] = '0';
            return $settings;
        }
        
        public function invitation_codes_default_module_settings($settings){
            $settings['upme-invitation-codes-settings'] = array(
                                                            'invitation_code_activation_status' => '0',
                                                            );
            return $settings;
        }
        
        public function invitation_codes_option_with_checkbox($settings){
            array_push($settings,'invitation_code_activation_status');
            return $settings;
        }

        private function includes() {

            require_once UPME_INC_PLUGIN_DIR . 'classes/class-upme-inc-upgrade.php';
            require_once UPME_INC_PLUGIN_DIR . 'classes/class-upme-inc-module-settings.php';
            require_once UPME_INC_PLUGIN_DIR . 'classes/class-upme-inc-manager.php';

            if ( is_admin() ) {
                //require_once EDD_PLUGIN_DIR . 'includes/admin/add-ons.php';
            }
        }
    
    }
}


function UPME_Invitation_Codes() {
    global $upme_invitation_codes;
    
	$upme_invitation_codes = UPME_Invitation_Codes::instance();
}



