<?php
    global $upme_admin;
?>

<div class="upme-tab-content" id="upme-invitation-codes-settings-content" style="display:none;">
    <h3><?php _e('Manage Invitation Codes','upmeinc');?>
        </h3>
        <?php echo UPME_Html::button('button', array('name'=>'upme-display-list-res-rule', 'id'=>'upme-display-inc-settings'
            , 'value'=> __('Settings','upmeinc'), 'class'=>'button button-primary')); ?>
        <?php echo UPME_Html::button('button', array('name'=>'upme-display-list-res-rule', 'id'=>'upme-display-inc-codes'
            , 'value'=> __('Manage Codes','upmeinc'), 'class'=>'button button-primary')); ?>
        <?php echo UPME_Html::button('button', array('name'=>'upme-display-list-res-rule', 'id'=>'upme-display-inc-notifications'
            , 'value'=> __('Send Invitation Codes','upmeinc'), 'class'=>'button button-primary')); ?>
    
    <div id="upme-invitation-codes-settings" class="upme-inc-screens" style="display:block">

        <form id="upme-invitation-codes-settings-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php

                        $upme_admin->add_plugin_module_setting(
                            'checkbox',
                            'invitation_code_activation_status',
                            'invitation_code_activation_status',
                            __('Enable/Disable Invitation Codes', 'upmeinc'),
                            '1',
                            __('If checked, invitation codes will be enabled for registration.', 'upmeinc'),
                            __('Checking this option will enable invitation codes for new user registrations.', 'upmeinc')
                        );

                    ?>

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 
                                echo UPME_Html::button('button', array('name'=>'save-upme-invitation-codes-settings', 'id'=>'save-upme-invitation-codes-settings', 'value'=> __('Save Changes','upmeinc'), 'class'=>'button button-primary upme-save-module-options'));
                                echo '&nbsp;&nbsp;';
                                echo UPME_Html::button('button', array('name'=>'reset-upme-invitation-codes-settings', 'id'=>'reset-upme-invitation-codes-settings', 'value'=>__('Reset Options','upmeinc'), 'class'=>'button button-secondary upme-reset-module-options'));
                            ?>
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        
        </form>
        
    </div>
    
    <div id="upme-invitation-codes-create" class="upme-inc-screens" style="display:none">


        <div class="updated" id="upme-add-invitation-codes-settings-msg" style="display:none;">

        </div>
        <form id="upme-invitation-codes-create-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label for="<?php _e('Invitation Code', 'upmeinc'); ?>"><?php _e('Invitation Code', 'upmeinc'); ?></label></th>
                        <td>
                            <?php 
                                echo UPME_Html::text_box(array(
                                    'class' => '',
                                    'name' => 'upme_inc_code_id',
                                    'id'  =>  'upme_inc_code_id',
                                    'placeholder' => '',
                                    'value' => ''
                                ));
                                    
                            ?><i class="upme-icon upme-icon-question-circle upme-tooltip2 option-help" original-title="<?php _e('Enter unique invitation code.', 'upme') ?>"></i>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><label for="<?php _e('Quota', 'upmeinc'); ?>"><?php _e('Quota', 'upmeinc'); ?></label></th>
                        <td>
                            <?php 
                                echo UPME_Html::text_box(array(
                                    'class' => '',
                                    'name' => 'upme_inc_code_quota',
                                    'id'  =>  'upme_inc_code_quota',
                                    'placeholder' => '',
                                    'value' => ''
                                ));
                                    
                            ?><i class="upme-icon upme-icon-question-circle upme-tooltip2 option-help" original-title="<?php _e('Number of occurences for invitation code.', 'upmeinc') ?>"></i>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 

                                echo UPME_Html::button('button', array('name'=>'add-upme-invitation-code', 'id'=>'add-upme-invitation-code', 'value'=>'Add Invitation Code', 'class'=>'button button-primary '));
                                echo '&nbsp;&nbsp;';
                            ?>
                        </td>
                    </tr>

                </tbody>
            </table>
        </form>
        
        <form id="upme-invitation-codes-list-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>                                

                    <tr valign="top">
                        <td colspan="2">
                            <table id="upme_inc_codes">
                                <tr id="upme_inc_codes_titles">
                                    <th><?php echo __('Invitation Code','upmeinc'); ?></th>
                                    <th><?php echo __('Quota','upmeinc'); ?></th>
                                    <th><?php echo __('Delete','upmeinc'); ?></th>
                                </tr>
                                <?php
                                    global $upme_invitation_codes;
                                    echo $upme_invitation_codes->manager->invitation_codes_list();
                                ?>
                            </table>
                        </td>
                    </tr>

                </tbody>
            </table>
        </form>
    </div>
    
    <div id="upme-invitation-codes-notifications" class="upme-inc-screens" style="display:none">
        
        <div class="updated" id="upme-send-invitation-code-settings-msg" style="display:none;">

        </div>
        <form id="upme-invitation-codes-notification-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label for="<?php _e('Invitation Code', 'upmeinc'); ?>"><?php _e('Invitation Code', 'upmeinc'); ?></label></th>
                        <td>
                            <?php
                                global $upme_invitation_codes;
                                $saved_inc_codes = $upme_invitation_codes->manager->invitation_codes_list_select();

                                echo UPME_Html::drop_down(array(
                                    'name'=>'upme_inc_send_code_id',
                                    'id'=>'upme_inc_send_code_id', 
                                    'class' => 'chosen-admin_setting',
                                    ), $saved_inc_codes , '0');
                                    
                            ?><i class="upme-icon upme-icon-question-circle upme-tooltip2 option-help" original-title="<?php _e('Select available invitation code.', 'upme') ?>"></i>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><label for="<?php _e('Emails', 'upmeinc'); ?>"><?php _e('Emails', 'upmeinc'); ?></label></th>
                        <td>
                            <?php 
                                echo UPME_Html::text_area(array(
                                            'name' => 'upme_inc_notify_emails', 
                                            'id' => 'upme_inc_notify_emails', 
                                            'class' => 'large-text code text-area', 
                                            'value' => '', 
                                            'cols' => '50', 
                                            'style' => 'min-height:300px;width:90% !important;'));
            
                            
                            ?><i class="upme-icon upme-icon-question-circle upme-tooltip2 option-help" original-title="<?php _e('Email addresses for sending invitation codes.', 'upmeinc') ?>"></i>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 

                                echo UPME_Html::button('button', array('name'=>'send-upme-invitation-code', 'id'=>'send-upme-invitation-code', 'value'=>'Send Invitation Code', 'class'=>'button button-primary '));
                                echo '&nbsp;&nbsp;';
                            ?>
                        </td>
                    </tr>

                </tbody>
            </table>
        </form>
        
    </div>
</div>