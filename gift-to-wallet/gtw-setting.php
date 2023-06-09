<?php

// === >>>> Dashboard Left side Gift Card Sub menu <<<< === \\
add_action( 'admin_menu', 'gtw_setting_submenu' );
function gtw_setting_submenu() {
  add_submenu_page(
      'edit.php?post_type=gift_card',
      __('Gift Card Setting','gtw'),
      'Setting',
      'manage_options',
      'setting',
      'gtw_setting_fun'
  );
}


function gtw_setting_fun(){
    // current_user_can('administrator');
    ?>
    
    <h2 class="admin-from-title"><?=__('Gift card site setup','gtw')?></h2>
    <p><?php 
    
    // $checkToken  = get_option('gift_to_wallet_token'); // gtw_token();
    // echo $checkToken;
    
    ?></p>
    <div class="gift-wrap">
        <div class="wc-gift-card-setting-left">

        <table>
            <tbody class="gift-card-form">
                <tr>
                    <td><?=__('Logo url (Storefront & pdf)','gtw')?></td>
                    <td><input placeholder="https://leikbreytir.com/dev/wallet/wp-content/plugins/gift-to-wallet/assets/images/gifttowallet-logo.svg" class="gift-input" type="text" name="logo" value="<?php echo get_option('logo');?>"></td>
                    
                    <td class="col-right"><?=__('Image Section Title','gtw')?></td>
                    <td><input placeholder="Veldu mynd" class="gift-input" type="text" name="l_img" value="<?php echo get_option('l_img');?>"></td>
                </tr>
                <tr>
                    <td><?=__('QR Code url','gtw')?> </td>
                    <td><input placeholder="https://leikbreytir.com/dev/wallet/wp-content/plugins/gift-to-wallet/assets/images/qrCode-gifttowallet.com.png" class="gift-input" type="text" name="qr" value="<?php echo get_option('qr'); ?>"></td>

                    <td class="col-right"><?=__('Price Section Title','gtw')?></td>
                    <td><input placeholder="Velja upphæð" class="gift-input" type="text" name="l_price" value="<?php echo get_option('l_price');?>"></td>
                </tr>
                <tr>
                    <td><?=__('Barcode url','gtw')?> </td>
                    <td><input placeholder="https://leikbreytir.com/dev/wallet/wp-content/plugins/gift-to-wallet/assets/images/barcode-gifttowallet.gif" class="gift-input" type="text" name="bar" value="<?php echo get_option('bar'); ?>"></td>

                    <td class="col-right"><?=__('Date-Time Section Title','gtw')?></td>
                    <td><input placeholder="Upplýsingar um afhendingu" class="gift-input" type="text" name="l_delivery" value="<?php echo get_option('l_delivery');?>"></td>
                </tr>
                <tr>
                    <td><?=__('PDF form bottom text.','gtw')?> </td>
                    <td><input placeholder="Til hamingju með gjafakort GiftToWallet. Skannaðu QR kóðann og fáðu gjafakortið beint í rafrænt veski í símanum þínum. Eins og er er því miður ekki hægt að greiða með gjafakorti í vefverslun GiftToWallet." class="gift-input" type="text" name="buttom_text" value="<?php echo get_option('buttom_text'); ?>"></td>

                    <td class="col-right"><?=__('Sender Section Title','gtw')?></td>
                    <td><input placeholder="Upplýsingar um þig" class="gift-input" type="text" name="l_you" value="<?php echo get_option('l_you');?>"></td>
                </tr>
                <tr>
                    <td><?=__('Add to Cart Button Text','gtw')?> </td>
                    <td><input placeholder="KAUPA RAFRÆNT" class="gift-input" type="text" name="btn_text" value="<?php echo get_option('btn_text'); ?>"></td>

                    <td class="col-right"><?=__('Sender Section Note','gtw')?></td>
                    <td><input placeholder="Mikilvægt að setja nafn þitt hér svo að viðtakandi viti frá hverjum gjöfin er." class="gift-input" type="text" name="l_u_text" value="<?php echo get_option('l_u_text');?>"></td>
                </tr>

                <tr>
                    <td><?=__('User upload img btn text','gtw')?> </td>
                    <td><input placeholder="Upload Image" class="gift-input" type="text" name="gtw_upload_img_btn_text" value="<?php echo get_option('gtw_upload_img_btn_text'); ?>"></td>

                    <td class="col-right"><?=__('User choose img btn text','gtw')?></td>
                    <td><input placeholder="Choose image" class="gift-input" type="text" name="gtw_choose_img_btn_text" value="<?php echo get_option('gtw_choose_img_btn_text');?>"></td>
                </tr>


                <tr>
                    <td><?=__('Add to Cart Button Text Color')?> </td>
                    <td><input placeholder="" class="gift-input" type="color" name="btn_t_color" value="<?php echo get_option('btn_t_color'); ?>" ></td>

                    <td class="col-right"><?=__('Recipient Info. Sec. Title','gtw')?></td>
                    <td><input placeholder="Upplýsingar um viðtakanda" class="gift-input" type="text" name="l_info" value="<?php echo get_option('l_info');?>"></td>
                </tr>
               
                <tr>
                    <td><?=__('Add to Cart Button bg Color')?> </td>
                    <td><input placeholder="" class="gift-input" type="color" name="btn_bg_color" value="<?php echo get_option('btn_bg_color'); ?>" ></td>

                    <td class="col-right"><?=__('SMS/Email Section Note.','gtw')?></td>
                    <td><input placeholder="Viðtakandi fær gjafakortið sent í tölvupósti og/eða SMS. Ef þú vilt prenta gjafakortið út sjálf/ur skaltu skrá þitt netfang sem viðtakanda og þá færðu PDF gjafakort sent tilbúið til prentunar" class="gift-input" type="text" name="l_info_text" value="<?php echo get_option('l_info_text');?>"></td>
                </tr>
                
                <tr>
                    <td><?=__('Add to Cart Button hover bg Color')?> </td>
                    <td><input placeholder="" class="gift-input" type="color" name="btn_h_bg_color" value="<?php echo get_option('btn_h_bg_color'); ?>" ></td>
                    
                    <td><?=__('PDF Date Formate')?> </td>
                    <td><input placeholder="d/M/Y" class="gift-input" type="text" name="pdf_date_formate" value="<?php echo get_option('pdf_date_formate'); ?>" ></td>
                </tr>

                <tr>
                    <td>
                        <?=__('Enable Gift Card Edit','gtw')?> <b title="if this is unchecked, it will show edit button in cart page."> <span class="dashicons dashicons-editor-help"></span> <span class="woocommerce-help-tip"></span> </b> 
                    </td>
                        <?php 
                            $cardEdit = get_option('enable_gift_card_edit');
                            if($cardEdit === 'true'){
                                $check = 'checked';
                            }else{
                                $check = '';
                            }
                        ?>
                    <td>
                        <input placeholder="" class="gift-input" type="checkbox" name="enable_gift_card_edit" value="<?php echo $cardEdit; ?>" <?=$check?> >
                    </td>

                    <td><?=__('Save Button Text')?> <b title="This is Upload image & Edit giftCard info in cart page popup seve button "> <span class="dashicons dashicons-editor-help"></span> <span class="woocommerce-help-tip"></span> </b>
                    </td>
                    <td><input placeholder="Save" class="gift-input" type="text" name="gift_popup_save_btn" value="<?php echo get_option('gift_popup_save_btn'); ?>" ></td>

                </tr>

                <tr>
                    <td><?=__('Enable demo mode','gtw')?> <?php // echo get_option('enable_demo_mode'); ?> </td>
                    <?php 
                        $edm = get_option('enable_demo_mode');
                        if($edm === 'true'){
                            $check = 'checked';
                        }else{
                            $check = '';
                        }
                    ?>
                    <td><input placeholder="" class="gift-input" type="checkbox" name="enable_demo_mode" value="<?php echo get_option('enable_demo_mode'); ?>" <?=$check?> ></td>

                    <td><?=__('Cancel Button Text')?> <b title="This is Upload image & Edit giftCard info in cart page popup cancel button "> <span class="dashicons dashicons-editor-help"></span> <span class="woocommerce-help-tip"></span> </b>
                    </td>
                    <td><input placeholder="Cancel" class="gift-input" type="text" name="gift_popup_cancel_btn" value="<?php echo get_option('gift_popup_cancel_btn'); ?>" ></td>

                </tr>

                <tr>
                    <td><?=__('Show Tabs image','gtw')?> <b title="if this is unchecked product gallery image will be shown."> <span class="dashicons dashicons-editor-help"></span> <span class="woocommerce-help-tip"></span> </b> <?php // echo get_option('enable_product_gallery_img'); ?> </td>
                    <?php 
                        $epgi = get_option('enable_product_gallery_img');
                        if($epgi === 'true'){
                            $check = 'checked';
                        }else{
                            $check = '';
                        }
                    ?>
                    <td>
                        <input placeholder="" class="gift-input" type="checkbox" name="enable_product_gallery_img" value="<?php echo $epgi; ?>" <?=$check?> >
                    </td>

                    <td class="col-right" colspan="2">
                        <h3>GiftToWallet pos customer admin login details</h3>
                    </td>
                </tr>
                <tr>
                    <td>Gift Card Expiry Years</td>
                    <td>
                        <select name="gtw_expiry_date" id="gtw_expiry_date" >
                            <option value="">Select Years</option> 
                            <?php 
                            $value    = get_option('gtw_expiry_date');
                            for($i=1;$i<11;$i++){
                                $selected = ($value == $i) ? "selected" : "";
                                echo '<option value="'.$i.'" '.$selected.' >'.$i.' year'.($i>1?'s':'').'</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td class="col-right"><?=__('Use Email','gtw')?></td>
                    <td><input placeholder="" class="gift-input" type="email" name="gtw_c_eamil" value="<?php echo get_option('gtw_c_eamil');?>"></td>
                </tr>
                <tr>
                    <td>  </td>
                    <td>  </td>

                    <td class="col-right"><?=__('Use Pass','gtw')?></td>
                    <td><input class="gift-input" type="password" name="gtw_c_password" value="<?php echo get_option('gtw_c_password');?>"></td>
                </tr>
                <tr>
                    <td>  </td>
                    <td>  </td>

                    <td class="col-right"><button type="button" class="btn-wc-gift-card gift-btn" onclick="gtwGenerateTokenSubmit()">Generate Token</button></td>
                    <td><p id="smsToken"></p></td>
                </tr>
                
                <tr>
                    <td colspan="4"> 
                    <h2 class="admin-from-title">Layout of SMS, Email & PDF</h2>
                    </td>
                </tr>


                
                <tr>
                    <td>
                        <p><?=__('SMS Body')?></p>
                        
                    </td>
                    <td colspan="3">
                        <p>{sender_name},{pass_link}, {recipient_name}, {gift_card_message}</p>
                        <textarea placeholder="{recipient_name}, þú varst að fá sent gjafakort frá Kringlan. Sendandi er {sender_name} með kveðjunni: {gift_card_message} Smelltu á hlekkinn til að bæta gjafakortinu í Wallet hjá þér: {pass_link}" id="txtid" name="use_send_sms" rows="4" cols="66" ><?php echo get_option('use_send_sms');?></textarea>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="4">
                       <h3 style="text-align:center;"> Email Body html</h3>
                    </td>
                </tr>
                <tr>
                    <td><?=__('Email Subject','gtw')?></td>
                    <td colspan="3">
                        <input style="width: 100%;" placeholder="Til hamingju með gjafakortið þitt!" class="gift-input" type="text" name="gtw_email_subject" value="<?php echo get_option('gtw_email_subject');?>" >
                    </td>
                    
                </tr>

                <tr>
                    <td>
                        <h3>Use html in email </h3>
                        <p>{pass_link}</p>
                        <p>{recipient_name}</p>
                        <p>{gift_card_message}</p>
                    </td>
                    <td colspan="3"> 
                        <?php 
                        
                            $email_send_user =   get_option('email_send_user');

                            // echo stripslashes($email_send_user);

                            $content =  $email_send_user ? stripslashes($email_send_user) : "Enter Email html";
                            
                            // wp_editor( $content, $editor_id, $settings );
                            wp_editor( $content, 'email_send_user', array(
                                'wpautop'       => true,
                                'media_buttons' => false, // true | false
                                'textarea_name' => 'email_send_user',
                                'editor_class'  => 'email_send_user',
                                'textarea_rows' => 10
                            ));
                        
                        ?>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <h3>Use html in PDF</h3>
                        <p>{logo}</p>
                        <p>{gift_card_img}</p>
                        <p>{gift_card_send_date}</p>
                        <p>{gift_card_expiry_date}</p>
                        <p>{gift_card_message}</p>
                        <p>{gift_card_amount}</p>
                        <p>{bar_code}</p>
                        <p>{gift_card_no}</p>
                        <p>{qr_code}</p>
                        <p>{buttom_text}</p>
                    </td>
                    <td colspan="3"> 
                        <h3> Gift card pdf body html </h3>
                        
                        <?php 
                        
                            $pdfhtml=get_option('pdfhtml');

                            // echo stripslashes($pdfhtml);

                            $content =  $pdfhtml? stripslashes($pdfhtml) : "Enter PDF html";
                            
                            // wp_editor( $content, $editor_id, $settings );
                            wp_editor( $content, 'pdfhtml', array(
                                'wpautop'       => true,
                                'media_buttons' => false, // true | false
                                'textarea_name' => 'pdfhtml',
                                'editor_class'  => 'pdfhtml',
                                'textarea_rows' => 10
                            ));
                        
                        ?>
                    </td>
                </tr>


                <!-- start massage -->
                <tr>
                    <td colspan="4" id="smsGift"></td>
                </tr>
                <!-- the end massage -->

                <tr>
                    <td><input type="button" class="btn-wc-gift-card gift-btn" value="SAVE" onclick="wc_gift_card_setting()" ></td>
                </tr>
            </tbody>
        </table>
        </div>

        <div class="wc-gift-card-setting-right">
            <?php if( is_user_logged_in() ) { ?>

            <?php } ?>
        </div>

    </div>
<?php
}


// Ajax process for save data to option table.
function gtw_setting_save() {
    $logo       = sanitize_text_field($_POST['urlLogo']);
    $qr         = sanitize_text_field($_POST['urlQR']);
    $bar        = sanitize_text_field($_POST['urlBar']);
    $btnText    = sanitize_text_field($_POST['btnText']);
    $btnTC      = sanitize_text_field($_POST['btnTextColor']);
    $btnBGC     = sanitize_text_field($_POST['btnBGColor']);
    $btnHBGC    = sanitize_text_field($_POST['btnHoverBGC']);
    $bottomText = sanitize_text_field($_POST['bottomText']);
    $demoModeCh = sanitize_text_field($_POST['enableDemoMode']);
    $editCard   = sanitize_text_field($_POST['enable_gift_card_edit']);
    $pdfDateFormat= sanitize_text_field($_POST['pdf_date_formate']);
    $showGalImg = sanitize_text_field($_POST['showGalleryImages']);
    // $giftCardPr = sanitize_text_field($_POST['giftCardPrices']);

    $l1 = sanitize_text_field($_POST['l_img']);
    $l2 = sanitize_text_field($_POST['l_price']);
    $l3 = sanitize_text_field($_POST['l_delivery']);
    $l4 = sanitize_text_field($_POST['l_info']);
    $l5 = sanitize_text_field($_POST['l_info_text']);
    $l6 = sanitize_text_field($_POST['l_you']);
    $l7 = sanitize_text_field($_POST['l_u_text']);

    $l8 = sanitize_text_field($_POST['l_u_email']);
    $l9 = sanitize_text_field($_POST['l_u_pass']);

    $l10 = $_POST['l_u_pdf']; // pdfhtml
    $l11 = $_POST['l_user_email']; // email_send_user
    $l12 = $_POST['l_user_email_sub']; // email_send_sub
    $l13 = $_POST['l_u_sms']; // use_send_sms
    $l14 = sanitize_text_field($_POST['gtw_expiry_date']);

    $l15 = sanitize_text_field($_POST['gift_popup_cancel_btn']);
    $l16 = sanitize_text_field($_POST['gift_popup_save_btn']);
    $l17 = sanitize_text_field($_POST['gtw_upload_img_btn_text']);
    $l18 = sanitize_text_field($_POST['gtw_choose_img_btn_text']);
    
    update_option('logo',$logo);
    update_option('qr',$qr);
    update_option('bar',$bar);
    update_option('btn_text',$btnText);
    update_option('btn_t_color',$btnTC);
    update_option('btn_bg_color',$btnBGC);
    update_option('btn_h_bg_color',$btnHBGC);
    update_option('buttom_text',$bottomText);
    update_option('enable_demo_mode',$demoModeCh);
    update_option('enable_gift_card_edit',$editCard);
    update_option('pdf_date_formate',$pdfDateFormat);
    update_option('enable_product_gallery_img',$showGalImg);
    // update_option('gift_card_prices',$giftCardPr);

    update_option('l_img',$l1);
    update_option('l_price',$l2);
    update_option('l_delivery',$l3);
    update_option('l_info',$l4);
    update_option('l_info_text',$l5);
    update_option('l_you',$l6);
    update_option('l_u_text',$l7);

    update_option('gtw_c_eamil',$l8);
    update_option('gtw_c_password',$l9);

    update_option('pdfhtml',$l10);
    update_option('email_send_user',$l11);
    update_option('gtw_email_subject',$l12);
    update_option('use_send_sms',$l13);
    update_option('gtw_expiry_date',$l14);

    update_option('gift_popup_cancel_btn',$l15);
    update_option('gift_popup_save_btn',$l16);
    update_option('gtw_upload_img_btn_text',$l17);
    update_option('gtw_choose_img_btn_text',$l18);

    echo json_encode(['status'=>'ok', 'message' => 'Gift Card Item Setting information save done' ]);
    // wp_redirect(admin_url('admin.php?page=setting'));

    exit();

}
add_action('wp_ajax_gtw_setting_save', 'gtw_setting_save');
add_action('wp_ajax_nopriv_gtw_setting_save', 'gtw_setting_save');




// ajax process for gtw_generate_token
function gtw_generate_token() {

    // $token = gtw_token();

    $customerAdminEmail = sanitize_email($_POST['email']); // get_option('gtw_c_eamil'); // kringlan@kringlan.is
    $customerAdminPass  = sanitize_text_field($_POST['pass']); // get_option('gtw_c_password');
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://admin.gifttowallet.com/api/login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('email' => $customerAdminEmail, 'password' => $customerAdminPass),
    ));

    $response = curl_exec($curl);

    curl_close($curl);


    $response=json_decode($response);
    if($response->success){
        $token=$response->result->token;
        // update_option('gift_to_wallet_token', $token,false);
        update_option('gift_to_wallet_token', $token);

        $sms = 'Successfully Token Generated.';
        echo json_encode(['status'=>'ok', 'message' => $sms ]);
    } else {

        $sms = 'Token Generate Fail! Check your Credential.';
        echo json_encode(['status'=>'notok', 'message' => $sms ]);

    }


    exit(); // wp_die();

}

add_action('wp_ajax_gtw_generate_token', 'gtw_generate_token');
add_action('wp_ajax_nopriv_gtw_generate_token', 'gtw_generate_token');



