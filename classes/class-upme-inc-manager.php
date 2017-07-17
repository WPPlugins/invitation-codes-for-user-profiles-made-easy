<?php

class UPME_INC_Manager{
    
    public $invitation_codes;
    
    public function __construct(){
        
        $this->invitation_codes = get_option('upme_inc_codes');
        
        add_filter( 'upme_custom_registration_fields', array($this,'invitation_code_registration_field'),10,2);
        add_action( 'upme_before_registration_restrictions', array($this,'invitation_code_registration_restrictions'), 10 ,2);
        add_action( 'upme_after_registration_field_update', array($this,'invitation_code_after_update'));
        
        add_action('wp_ajax_upme_inc_save_invitation_codes', array($this, 'save_invitation_codes'));
        add_action('wp_ajax_upme_inc_delete_code', array($this, 'delete_invitation_code'));
        add_action('wp_ajax_upme_inc_send_invitation_codes', array($this, 'send_invitation_codes'));
    }
    
    public function invitation_code_registration_field($fields , $params){
        global $invitation_code_settings;
        
        if( $invitation_code_settings['invitation_code_activation_status'] != '0'){
            
            $invlitation_code = array(
	            'type' => 'usermeta',
	            'icon' => 'user',
	            'field' => 'text',
	            'name' => 'Invitation Code',
	            'meta' => 'upmeinc_invitation',
	            'meta_custom' => '',
	            'social' => 0,
	            'can_edit' => 0,
	            'allow_html' => 0,
	            'can_hide' => 2,
	            'private' => 0,
	            'required' => 1,
	            'show_to_user_role' => 0,
	            'edit_by_user_role' => 0,
	            'show_in_register' => 1,
	            'help_text' => __('You need an invitation code to register new account on this site.','upmeinc'),
	            'choices' => '',
	            'predefined_loop' => 0,

								);
			array_push($fields,$invlitation_code);
            
        }
        return $fields;
    }
    
    
    public function invitation_code_registration_restrictions($usermeta, $params){
        global $upme,$upme_register;
        
        $codes = array_keys($this->invitation_codes);
        $num   = isset($this->invitation_codes[$usermeta['upmeinc_invitation']]) ? $this->invitation_codes[$usermeta['upmeinc_invitation']] : 0;
        
        if(isset($usermeta['upmeinc_invitation']) && !in_array($usermeta['upmeinc_invitation'],$codes)){
            $upme_register->errors[] = __('Please enter valid invitation code.','upmeinc');
        }
        if(isset($usermeta['upmeinc_invitation']) && in_array($usermeta['upmeinc_invitation'],$codes) && $num <= 0){
            $upme_register->errors[] = __('Please enter valid invitation code.','upmeinc');
        }
    }
    
    public function invitation_code_after_update($params){
        extract($params);

        if($meta == 'upmeinc_invitation'){
            // Reduce entries for the given code
            $num = isset($this->invitation_codes[$value]) ? $this->invitation_codes[$value] : 0;
            if($num != 0){
                $num--;
            }
            
            $this->invitation_codes[$value] = $num;
            update_option('upme_inc_codes',$this->invitation_codes);
        }
    }
    
    public function save_invitation_codes(){
        parse_str($_POST['data'], $setting_data);
        
        // Create invitation codes in db when its not available
        if ($this->invitation_codes == '') {
            update_option('upme_inc_codes', array());
            $this->invitation_codes = array();
        }
        
        $codes = array_keys($this->invitation_codes);
        $this->invitation_codes[$setting_data['upme_inc_code_id']] = $setting_data['upme_inc_code_quota'];

        if(in_array($setting_data['upme_inc_code_id'],$codes)){
            
            $inc_codes = $this->invitation_codes_list();
            echo json_encode(array('status'=>'fail','codes'=> $inc_codes, 'msg' => __('Invitation code already exists.','upmeinc')));
        }else{
            $res = update_option('upme_inc_codes', $this->invitation_codes);
                
            $inc_codes = $this->invitation_codes_list();
            if($res){
                echo json_encode(array('status'=>'success','codes'=> $inc_codes, 'msg' => __('New invitation code added.','upmeinc')));

            }else{
                echo json_encode(array('status'=>'fail','codes'=> $inc_codes, 'msg' => __('Failed to add new invitation code.','upmeinc')));

            }
        }
        
        
        exit;
    }
    
    function invitation_codes_list(){

        $display = '';

        if(is_array($this->invitation_codes) && count($this->invitation_codes) != 0){


            foreach ($this->invitation_codes as $code=>$number) {

                $display .= '<tr>
                                <td>'.$code.'</td>
                                <td>'.$number.'</td>
                                <td><input type="hidden" id="upme_inc_code_id" value="'.$code.'" />
                                    <input type="button" id="upme_inc_delete_code" value="'.__('Delete Code','upmeinc').'" 
                                    class="button button-primary upme_inc_delete_code" />
                                </td>
                            </tr>';
            }
        }else{
            $display .= '<tr >
                            <td colspan="6" style="text-align:center;">'.__('Invitation codes are not available.','upmeinc').'</td>
                        </tr>';
        }

        return $display;
    }
    
    public function delete_invitation_code(){
        $code_id = upme_post_value('code_id');

        if ($this->invitation_codes == '') {
            update_option('upme_inc_codes', array());
            $this->invitation_codes = array();
        }

        if(isset($this->invitation_codes[$code_id])){
            unset($this->invitation_codes[$code_id]);
        }

        $res = update_option('upme_inc_codes', $this->invitation_codes);
        
        $codes = $this->invitation_codes_list();
        if($res){
            echo json_encode(array('status'=>'success','codes'=> $codes, 'msg' => __('Invitation code deleted.','upmeinc')));
        
        }else{
            echo json_encode(array('status'=>'fail','codes'=> $codes, 'msg' => __('Failed to delete invitation code.','upmeinc')));
        
        }
        exit;
    }
    
    public function invitation_codes_list_select(){
        $saved_inc_codes = (array) $this->invitation_codes;
        $codes = array();
        $codes[0] = __('Please Select','upmeinc');
        foreach($saved_inc_codes as $code=>$num){
            $codes[$code] = $code;
        }
        return $codes;
    }
    
    public function send_invitation_codes(){
        global $invitation_code_settings;
        
        parse_str($_POST['data'], $setting_data);
        
        $headers = array();
        
        $emails = explode(',', $setting_data['upme_inc_notify_emails'] );
        $email_status = true;
        $to_email = '';
        
        $sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}

		$to_email = 'wordpress@' . $sitename;
        
        foreach($emails as $email){
            if(!is_email(trim($email))){
                $email_status = false;
            }
            $headers[] = "Bcc: ".$email;
        
        }
        
        if(!$email_status){
            echo json_encode(array('status'=>'fail','msg' => __('Invalid email addreses list.','upmeinc')));
        }else{
            // Send Emails
            $blog_name = get_option('blogname');
            
            $current_option = get_option('upme_options');
            $registration_link = get_permalink($invitation_code_settings['registration_page_id'] );
            
            $subject = sprintf( __('Invitation for Registration - %s','upmeinc'), $blog_name );
            
            $message  = sprintf(__('Your are invited to create a new account in %s. ','upme'), $blog_name ) . "\r\n\r\n";      
            
            $message .= sprintf(__('Invitation Code: %s','upme'), $setting_data['upme_inc_send_code_id'] ).  " \r\n\r\n";

            $message .= __('You can now register new account using the following link.','upme') . "\r\n\r\n";
            $message .= $registration_link ."\r\n\r\n";
            $message .= __('Thanks','upme') . "\r\n";
            $message .= sprintf( "%s",$blog_name). "\r\n\r\n";
            
            $send_emails = implode(',',$emails);
            
    
            wp_mail(
                $to_email,                
                $subject,
                $message,
                $headers
            );
  
            echo json_encode(array('status'=>'success','msg' => __('Invitation code sent.','upmeinc')));
        }       
        
        
        exit;
    }
    
}




