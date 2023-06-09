<?php
/**
 * Plugin Name: Gift to Wallet
 * Description: Woocommerce Gift card system plugin. #Designed, Developed, Maintained & Supported by wooXperto on behalf of Leikbreytir.
 * Plugin URI: https://wooxperto.com/plugins/gifttowallet
 * Version: 1.0.1
 * Author: wooxperto
 * Author URI: https://wooxperto.com/
 * License: GPLv2
 * textdomain: gtw
 */

// Exit if accessed directly wc_gift_card | gtw
if ( ! defined('ABSPATH')) {
    // return; 
    exit;
}

include 'gtw-setting.php';
include 'gtw-image.php';

// include 'class.contact-table.php';

define("VERSION", "1.0.0");

/*
* WP Admin Dashboard Script Reg.
*/
function gtw_wp_admin_plugin_js(){ // gtw_wp_admin_plugin_js | wc_gift_card_wp_admin_plugin_js

    wp_enqueue_style( 'gtw_wp_admin_style', plugins_url( 'assets/css/wp_admin_style.css', __FILE__ ), false, "1.0.0");
    
    // wp_enqueue_script('jquery');
    wp_enqueue_script('gtw_wp_admin_script', plugin_dir_url(__FILE__ ) . 'assets/js/wp_admin_js.js?v='.time(), array('jquery'));
    wp_localize_script("gtw_wp_admin_script","ajax_object",array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ));

    wp_enqueue_script('jquery');
    wp_enqueue_script('gtw_wp_admin_script');


}
add_action('admin_enqueue_scripts', 'gtw_wp_admin_plugin_js');







register_activation_hook( __FILE__, 'gtw_activation' );
 
function gtw_activation() {
	// $args = array( $args_1, $args_2 );
	// $args = array( $args_1 );
	if (! wp_next_scheduled ( 'gtw_img_search_which_uncheckout' )) {
    	wp_schedule_event( time(), 'daily', 'gtw_img_search_which_uncheckout' ); // 30_seconds | hourly | daily
    }
}


// 30 seconds interval schedules
/*
add_filter( 'cron_schedules', 'add_thirty_second_interval' );
function add_thirty_second_interval( $schedules ) {
    $schedules['30_seconds'] = array(
        'interval' => 30,
        'display' => __( 'Every 30 seconds' )
    );
    return $schedules;
}
*/



register_deactivation_hook( __FILE__, 'gtw_deactivation' );
function gtw_deactivation() {
    wp_clear_scheduled_hook( 'gtw_img_search_which_uncheckout' );
}


add_action('gtw_img_search_which_uncheckout', 'gtw_delete_img_from_wp_media_which_uncheckout');
function wdelete_img_from_wp_media_which_uncheckout(){


    gtw_token();
    gtw_send_cron();

    global $wpdb;
            
    // Get the uncheckout attachment IDs # MINUTE | HOUR | DAY
    $uncheckout_attach_ids = $wpdb->get_results(
        "
        SELECT b.ID FROM 
        {$wpdb->prefix}postmeta a, {$wpdb->prefix}posts b 
        WHERE a.meta_key='gift_card_processing' 
        and a.meta_value=1 and a.post_id=b.ID 
        and HOUR(TIMEDIFF(NOW(), b.post_date))>24; 
        ", ARRAY_A
    );

    foreach ($uncheckout_attach_ids as $key => $value) {


        // gtw_token();


        // Delete the attachment
        $result = wp_delete_attachment($value['ID']);
    }


}


$urlLogo      = plugin_dir_url(__FILE__ ).'assets/images/gifttowallet-logo.svg';
$urlQRcode    = plugin_dir_url(__FILE__ ).'assets/images/qrCode-gifttowallet.com.png';
$urlBarcode   = plugin_dir_url(__FILE__ ).'assets/images/barcode-gifttowallet.gif';

$logo         = get_option('logo') ? get_option('logo') : $urlLogo;
$qrCode       = get_option('qr') ? get_option('qr') : $urlQRcode;
$barcode      = get_option('bar') ? get_option('bar') : $urlBarcode;
$btnText      = get_option('btn_text') ? get_option('btn_text') :__('KAUPA RAFRÆNT', 'gtw');
$btnTextColor = get_option('btn_t_color') ? get_option('btn_t_color') :__('#000', 'gtw');
$btnBg        = get_option('btn_bg_color') ? get_option('btn_bg_color') :__('#00b9f9', 'gtw');
$btnBgHover   = get_option('btn_h_bg_color') ? get_option('btn_h_bg_color') :__('#33e54c', 'gtw');
$bottomText   = get_option('buttom_text') ? get_option('buttom_text') :__('Til hamingju með gjafakort GiftToWallet. Skannaðu QR kóðann og fáðu gjafakortið beint í rafrænt veski í símanum þínum. Eins og er er því miður ekki hægt að greiða með gjafakorti í vefverslun GiftToWallet.', 'gtw');

$leb1 = get_option('l_img') ? get_option('l_img') :__('Veldu mynd', 'gtw');
$leb2 = get_option('l_price') ? get_option('l_price') :__('Velja upphæð', 'gtw');
$leb3 = get_option('l_delivery') ? get_option('l_delivery') :__('Upplýsingar um afhendingu', 'gtw');
$leb4 = get_option('l_info') ? get_option('l_info') :__('Upplýsingar um viðtakanda', 'gtw');
$leb5 = get_option('l_info_text') ? get_option('l_info_text') :__('Viðtakandi fær gjafakortið sent í tölvupósti og/eða SMS. Ef þú vilt prenta gjafakortið út sjálf/ur skaltu skrá þitt netfang sem viðtakanda og þá færðu PDF gjafakort sent tilbúið til prentunar', 'gtw');
$leb6 = get_option('l_you') ? get_option('l_you') :__('Upplýsingar um þig', 'gtw');
$leb7 = get_option('l_u_text') ? get_option('l_u_text') :__('Mikilvægt að setja nafn þitt hér svo að viðtakandi viti frá hverjum gjöfin er.', 'gtw');

define('LOGO', $logo);
define('QR', $qrCode);
define('BAR_CODE', $barcode);
define('BTN_TEXT', $btnText);
define('BTN_TC', $btnTextColor);
define('BTN_BG', $btnBg);
define('BTN_BG_H', $btnBgHover);
define('BOTTOM_TEXT', $bottomText);

define('LABEL_IMG', $leb1);
define('LABEL_PRICE', $leb2);
define('LABEL_DELIVERY', $leb3);
define('LABEL_INFO', $leb4);
define('LABEL_INFO_T', $leb5);
define('LABEL_YOU', $leb6);
define('LABEL_YOU_T', $leb7);

class WC_Product_Pass_Gift_Card_Plugin {

    /**
     * Build the instance
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'gtw_wc_product_type_enqueue_script'));
        add_filter('product_type_options', array($this, 'gtw_wc_product_option'));
        add_action('woocommerce_process_product_meta_simple', array($this, 'gtw_save_giftcard_option_fields'));
        add_action('woocommerce_product_options_pricing', array($this, 'gtw_add_pricing'));
        add_action('woocommerce_product_options_general_product_data', array($this, 'gtw_add_brand_name')); // GTW Brand name

        add_action('admin_footer', array($this, 'gtw_enable_js_on_wc_product'));

        //add_filter( 'woocommerce_product_get_price', array($this,'custom_dynamic_regular_price'), 30, 2 );
        add_action('woocommerce_before_add_to_cart_quantity', array($this, 'gtw_before_add_to_cart_qty'), 10);
        add_filter('wc_get_template', array($this, 'gtw_modify_product_gallery_template'), 10, 5);

    }

    public function gtw_wc_product_type_enqueue_script() {
        wp_enqueue_style('wc_product_type_custom_css', plugin_dir_url(__FILE__ ) . 'assets/css/css.css?v='.time());
        wp_enqueue_script('wc_product_type_accounting_js_script', plugin_dir_url(__FILE__ ) . 'assets/js/accounting.js');
        wp_enqueue_style('jquery-uri-accordion-css', '//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css', array('astra-theme-css'), '1.0.0', 'all');

        // wp_enqueue_style( 'wc_product_type_crop_css', plugin_dir_url( __FILE__ ) . 'assets/css/crop.css');

        wp_enqueue_script('jquery-accordion-js', 'https://code.jquery.com/jquery-3.6.0.js', array('jquery'));
        wp_enqueue_script('crop_js_script', plugin_dir_url(__FILE__ ) . 'assets/js/crop.js', array('jquery'), true);

        wp_enqueue_script('jquery-uri-accordion-js', 'https://code.jquery.com/ui/1.13.2/jquery-ui.js', array('jquery'));

        wp_enqueue_script('wc_product_type_custom_script', plugin_dir_url(__FILE__ ) . 'assets/js/js.js?v='.time(), array('jquery'));
        global $post;
        $pageId = $post->ID;
        wp_localize_script('wc_product_type_custom_script', 'gtwAjax', array(
            'ajaxurl'=> admin_url('admin-ajax.php'),
            'localStVal' => 'myUploadCropImages' . $pageId
        ));

    }


    /**
     * Add 'Gift Card' product option
     */
    public function gtw_wc_product_option($product_type_options) {

        $product_type_options['gift_card']=array('id'=> '_gift_card',
            'wrapper_class'=> 'show_if_simple',
            'label'=> __('Gift Card', 'gtw'),
            'description'=> __('Gift Cards allow users to put in personalised messages.', 'gtw'),
            'default'=> 'no'
        );
        return $product_type_options;
    }

    /**
     * Save the custom fields.
     */
    public function gtw_save_giftcard_option_fields($post_id) {

        $is_gift_card = isset($_POST['_gift_card']) ? 'yes': 'no';
                        update_post_meta($post_id, '_gift_card', $is_gift_card);

        $price        = isset($_POST['_giftcard_min_price']) ? sanitize_text_field($_POST['_giftcard_min_price']): '';
                        update_post_meta($post_id, '_giftcard_min_price', $price);

        $gtwBrandName = isset($_POST['_gtw_brand_name']) ? sanitize_text_field($_POST['_gtw_brand_name']): '';
                        update_post_meta($post_id, '_gtw_brand_name', $gtwBrandName);

    }

    public function gtw_add_brand_name() {
        global $product_object;
        ?>
        <div id="_gtw_brand_name_box" class='options_group show_if_advanced'>
        <?php 
            woocommerce_wp_text_input(array(
                'id'=> '_gtw_brand_name',
                'label'=> __('GTW Brand Name', 'gtw'),
                'value'=> $product_object->get_meta('_gtw_brand_name', true),
                'default'=> '',
                'placeholder' => 'Enter Brand Name',
                'desc_tip'    => 'true',
                'description' => __('Enter Gift To Wallet Brand Name.','gtw'),
                // 'data_type'=> 'price',
            ));

        ?>
        </div>
        <?php
    }

    public function gtw_add_pricing() {
        global $product_object;
        ?>
        <div id="_giftcard_min_price_box" class='options_group show_if_advanced'>
        <?php 
            woocommerce_wp_text_input(array(
                'id'=> '_giftcard_min_price',
                'label'=> __('Gift Card Prices ('.get_woocommerce_currency_symbol().')', 'gtw'),
                'value'=> $product_object->get_meta('_giftcard_min_price', true),
                'default'=> '',
                'placeholder'=> '5, 10, 15, 20, 30',
                'desc_tip'    => 'true',
                'description' => __('Enter price for gift price btn.','gtw'),
                // 'data_type'=> 'price',
            ));
        ?>
        </div>
    <?php
    }

    public function gtw_enable_js_on_wc_product() {
        global $post,
        $product_object;

        if ( ! $post) {
            return;
        }

        if ('product' !=$post->post_type) : return;
        endif;

        $is_gift=false;

        if(($product_object && 'simple'===$product_object->get_type()) && get_post_meta($post->ID, '_gift_card', true)==='yes') {
            $is_gift=true;
        }

        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function () {
                //alert("ok");
                //for Price tab
                jQuery('#_giftcard_min_price_box').hide();

                <?php if ($is_gift) { ?> 
                    jQuery('#_giftcard_min_price_box').show(); 
                <?php } ?> 
                jQuery(document).change("input[name='_gift_card']", function () {

                    if (jQuery("input[name='_gift_card']").is(":checked")) {
                        jQuery('#_giftcard_min_price_box').show();
                    } else {
                        jQuery('#_giftcard_min_price_box').hide();
                    }
                })
            });
        </script>
        <?php
    }

    public function gtw_before_add_to_cart_qty() {
        global $post;
        $product = wc_get_product($post->ID);
        $is_gift = get_post_meta($post->ID, '_gift_card', true);
     
        $currency= get_woocommerce_currency_symbol();

        if($is_gift=='yes') {
           
            $image_id=$product->get_image_id();

            if(wp_get_attachment_image_url($image_id,'full')) {
                $default_image=wp_get_attachment_image_url($image_id,'full');
            } else {
                $default_image=plugin_dir_url(__FILE__ ).'assets/images/3.jpg';
            }


            // Show all Category
            $taxonomy='gift_card_category';
            $terms=get_terms(array(
                    'taxonomy'  => $taxonomy,
                    'hide_empty'=> false,
                ));

            $buttons='';
            $tab_contents='';
            $i=0;

            if ( ! empty($terms) && ! is_wp_error($terms)) {
                foreach ($terms as $term) {

                    // $active = ($i=0) ? 'active' : '';
                    if($i==0) {
                        $active='active';
                        $showImg='display: block';
                    } else {
                        $active='';
                        $showImg=' ';
                    }

                    $buttons.='<button type="button" class="tablinks '.$active.'" onclick="openImgCat(this,event, \'Cat'.$term->term_id.'\')">'.$term->name.'</button>';


                    $args=array(
                        'post_type'=> 'gift_card',
                        'tax_query'=> array(array(
                                'taxonomy'=> 'gift_card_category',
                                'field'=> 'term_id',
                                'terms'=> $term->term_id,
                            ),
                        ),
                        'posts_per_page'=> -1 // Set to -1 to display all posts
                    );
                    $posts_query=new WP_Query($args);

                    if ($posts_query->have_posts()) {

                        $tab_contents.='
                        <div class="Cat'.$term->term_id.' tabcontent" style="'.$showImg.'">
                            <ul>';
                                $j=0;
                                while ($posts_query->have_posts()):
                                    $posts_query->the_post();
                                // Display post content here

                                if (has_post_thumbnail()) {
                                    // Display featured image if available
                                    $tab_contents.='
                                    <li>
                                        <img class="'.(($i==0&&$j==0)?'active':'').'" onclick="selectThisGiftCardImage(this)" data-class="image1" src="'.get_the_post_thumbnail_url().'" width="60">
                                    </li>';
                                }

                                $j++;
                                endwhile;

                                $tab_contents.='
                            </ul>
                        </div>';  

                        wp_reset_postdata();
                    }
                    $i++;
                }
            }

            echo'
            <div id="price_area">
                <h2>'.LABEL_IMG.'</h2>
                <div class="img_thumbnail">';

                if(get_option('enable_product_gallery_img')==='true') {

                   $imgUpload = get_option('gtw_upload_img_btn_text') ? get_option('gtw_upload_img_btn_text') : "Hlaða upp mynd";
                   $imgChoose = get_option('gtw_choose_img_btn_text') ? get_option('gtw_choose_img_btn_text') : "Veldu mynd";

                echo '
                    <div class="tab">
                        '.$buttons.'
                        <button type="button" class="tablinks upload_my_img" onclick="openImgCat(this,event, \'uploadGiftImg\')">'.$imgUpload.'</button>
                    </div>'.$tab_contents.'

                    <div class="uploadGiftImg tabcontent">
                        <ul>
                            <li>
                                <input id="selectedFile" onchange="gtw_file_change(this)" class="disp-none selectedFile" type="file" accept=".png, .jpg, .jpeg, .svg">
                                <button id="upload-aphoto" class="upload-aphoto" type="button">'.$imgChoose.'</button>
                                <img id="confirm-img" class="confirm-img" src=""onclick="selectThisGiftCardImage(this)" data-class="image1" width="60">
                            </li>
                        </ul>
                    </div>';

                } else {
                echo'
                    <div id="Cat1"class="Cat1 tabcontent"style="display: block;">
                        <ul>
                            <li style="margin-right: 0px;">
                                <img class="active"onclick="selectThisGiftCardImage(this)"data-class="image1"src="'.$default_image.'"width="60">
                            </li>';
                            $gallery_image_ids=$product->get_gallery_image_ids();

                        foreach ($gallery_image_ids as $image_id) {
                            $image_url=wp_get_attachment_image_src($image_id, 'full');

                            echo '
                            <li style="margin-right: 0px;">
                                <img class=""onclick="selectThisGiftCardImage(this)"data-class="image1"src="'.$image_url[0].'"width="60">
                            </li>';
                        }
                        echo '
                        </ul>
                    </div>';
                }
                echo '
                </div>

                <input type="hidden"name="gift_card_image"value="'.$default_image.'">
                <h2>'.LABEL_PRICE.'</h2>';

                global $post;
                $product=wc_get_product($post->ID);
                $is_gift=get_post_meta($post->ID, '_gift_card', true);
                $regularPrice=$product->get_regular_price();
                $giftcardPrices=0;

                if($is_gift=='yes') {
                    $giftcardPrices=get_post_meta($post->ID, '_giftcard_min_price', true);

                    $prices_array=explode(',', $giftcardPrices);
                    sort($prices_array);

                    if(is_array($prices_array) && $giftcardPrices) {
                        // count($prices_array)>1
                        $pricesBTN='';
                        $btnPrice=0;

                        foreach ($prices_array as $key=> $value) {

                            if($btnPrice>0) {
                                $pricesBTN .='<label><input class="gift_card_default_amount" onchange="giftCardDefaultPrice(this)" type="radio" name="gift_card_default_price" value="'.$value.'">'.wc_price($value).'</label>';
                            }
                            $btnPrice++;
                        }
                        $hiddenPrice=$prices_array[0];
                    } else {
                        $hiddenPrice=$regularPrice;
                    }

                } else {
                    $giftcardPrices=$product->get_regular_price();
                }

                echo '
                <input type="hidden"name="gift_card_amount"value="'.$hiddenPrice.'">
                <div class="radio_btn">
                    <label><input class="gift_card_default_amount"onchange="giftCardDefaultPrice(this)" checked type="radio"name="gift_card_default_price"value="'.$hiddenPrice.'">'.wc_price($hiddenPrice).'</label>'.$pricesBTN.'
                </div>';

            echo'<div class="pform_area"> 
                    <div class="custom_price">
                        <span class="currency">'.$currency.'</span>
                        <input placeholder="Önnur upphæð?" type="text" name="custom_price" readonly data-min="'.$hiddenPrice.'" onclick="giftCardCustomPriceEnable(this)" onfocusout="giftCardCustomAmountFocus(this)" onkeyup="giftCardCustomPriceChange(this)">
                        <div id="gift-card-amount-error" class="gift-card-amount-error"></div>
                    </div>

                    <h2>'.LABEL_DELIVERY.'</h2>
                    <div class="toggleDateDivClass"id="toggleDateDiv"class="form_row">
                        <div class="col_2">
                            <input class="ckBox"onchange="toggleDateDiv(this);"type="checkbox">
                        </div>
                        <div class="col_4">
                            <h2 style="margin-top:4px;">Senda síðar</h2>
                        </div>
                    </div>
                    <div class="dateDivClass"id="dateDiv"style="display:none">
                        <p>Viðtakandi mun fá gjafakortið sent á þeim degi og tíma sem þú velur hér</p>
                        <div class="form_row">
                            <div class="col_4">
                                <div class="form_group">
                                    <label>Dagsetning:</label>
                                    <input type="date"id="dDate"name="gift_card_date"onchange="deliveryDate(this)"autocomplete="off">
                                </div>
                            </div>
                            <div class="col_4">
                                <div class="form_group">
                                    <label>Klukkustund:</label><br/>
                                    <select name="gift_card_time_hour"id="gift_card_time_hour"class="form-select"aria-label="">';
                                for($i=0; $i<24; $i++) {
                                    echo'<option value="'.sprintf("%02d", $i).'">'.sprintf("%02d", $i).'</option>';
                                }

                                echo'</select>
                                </div>
                            </div>

                            <div class="col_4">
                                <div class="form_group">
                                    <label>Mínúta:</label><br/>
                                    <select name="gift_card_time_minute"id="gift_card_time_minute"class="form-select"aria-label="">';
                                        for($i=0; $i<60; $i++) {
                                            echo'<option value="'.sprintf("%02d", $i).'">'.sprintf("%02d", $i).'</option>';
                                        }
                                        echo'
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <h2>'.LABEL_INFO.'</h2>
                    <div class="form_row">
                        <div class="col_6">
                            <div class="form_group">
                                <label>Nafn: <span class="require">*</span></label>
                                <input value="'.(isset($_POST["gift_card_recipient_name"])?$_POST["gift_card_recipient_name"]:'').'"autocomplete="off"type="text"name="gift_card_recipient_name"onkeyup="document.getElementById(\'gift_card_recipient_name_text\').innerHTML=this.value"required>
                                <span class="gift_card_recipient_name_span giftcardformError"></span>
                            </div>
                        </div>
                        <div class="col_6">
                            <div class="form_group">
                                <label>Netfang: <span class="require email_required email_required">*</span></label>
                                <input value="'.(isset($_POST["gift_card_recipient_email"])?$_POST["gift_card_recipient_email"]:'').'"autocomplete="off"type="text"name="gift_card_recipient_email"required>
                                <span class="gift_card_recipient_email_span giftcardformError"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form_row"id="">
                        <div class="col_6">
                            <div class="form_group">
                                <label>Símanúmer: <span class="require requirePhone phone_required">*</span></label>
                                <input value="'.(isset($_POST["gift_card_phone"])?$_POST["gift_card_phone"]:'').'"autocomplete="off"type="text"name="gift_card_phone"maxlength="7"minlength="7"required>
                                <span class="gift_card_phone_span giftcardformError"></span>
                            </div>
                        </div>
                    </div>
                    <p style="margin-top:5px;margin-bottom:10px">'.LABEL_INFO_T.'</p>
                    <div class="form_group">
                        <label>Skilaboð sem birtast á gjafabréf og í SMS:</label>
                        <textarea rows="3"name="gift_card_custome_message"onkeyup="giftCardMessagePreview(this)">'.(isset($_POST["gift_card_custome_message"])?$_POST["gift_card_custome_message"]:'').'</textarea>
                    </div>
                    <h2>'.LABEL_YOU.'</h2>
                    <p>'.LABEL_YOU_T.'</p>
                    <div class="form_group name_field">
                        <label>Nafn: <span class="require">*</span></label>
                        <input value="'.(isset($_POST["gift_card_sender_name"])?$_POST["gift_card_sender_name"]:'').'"autocomplete="off"type="text"name="gift_card_sender_name"onkeyup="document.getElementById(\'gift_card_recipient_email_text\').innerHTML=this.value"required>
                        <span class="gift_card_sender_name_span giftcardformError"></span>
                    </div>
                    <div class="form_group radio-buttob-form">
                        <label class="send_mail_sms">
                            <input type="radio"name="send_mail_to_recipient"value="1">Sendu tölvupóst til viðtakanda 
                        </label>
                        <label class="send_mail_sms">
                            <input type="radio"name="send_mail_to_recipient"value="2">Sendu sms til viðtakanda 
                        </label>
                        <label class="send_mail_sms">
                            <input type="radio"name="send_mail_to_recipient"value="3"checked>Sendu sms & tölvupóst til viðtakanda
                        </label>
                    </div>
                    <input type="hidden"name="product_id"value="'.$post->ID.'"/>
                </div>
            
            </div>';


        }


    }

    public function gtw_modify_product_gallery_template($located, $template_name, $args, $template_path, $default_path) {
        global $post;
        $is_gift=get_post_meta($post->ID, '_gift_card', true);

        if($is_gift=='yes') {
            if ('single-product/product-image.php'==$template_name) {
                $located=$plugin_path=untrailingslashit(plugin_dir_path(__FILE__)) . '/product-image.php';
            }

        }

        return $located;
    }
}

new WC_Product_Pass_Gift_Card_Plugin();



// test add to cart
function gtw_is_session_started() {
    if (php_sapi_name() !=='cli') {
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            return session_status()===PHP_SESSION_ACTIVE ? TRUE: FALSE;
        }

        else {
            return session_id()===''? FALSE: TRUE;
        }
    }

    return FALSE;
}

// Example


add_action('init', function() {
        if(isset($_POST["add-to-cart"]) && 1==2) {
            $product_id=$_POST['add-to-cart']; //This is product ID

            if(get_post_meta($product_id, '_gift_card', true)==='yes') {
                $gift_card_time=$_POST["gift_card_time_hour"].':'.$_POST["gift_card_time_minute"];
                $gift_card_item_add_to_cart_meta=array('gift_card_image'=>$_POST["gift_card_image"],
                    'gift_card_amount'=>$_POST["gift_card_amount"],
                    'gift_card_date'=>(empty($_POST["gift_card_date"])?date('d/m/Y'):$_POST["gift_card_date"]),
                    'gift_card_time'=>$gift_card_time,
                    'gift_card_recipient_name'=>$_POST["gift_card_recipient_name"],
                    'gift_card_recipient_email'=>$_POST["gift_card_recipient_email"],
                    'gift_card_phone'=>$_POST["gift_card_phone"],
                    'gift_card_sender_name'=>$_POST["gift_card_sender_name"],
                    'gift_card_custome_message'=>$_POST["gift_card_custome_message"],
                    'send_mail_to_recipient'=>(isset($_POST["send_mail_to_recipient"])?$_POST["send_mail_to_recipient"]:0),

                );
                if (gtw_is_session_started()===FALSE) session_start();
                $_SESSION['lb_gift_card_add_to_cart_'.$product_id]=$gift_card_item_add_to_cart_meta;
            }
        }
    }

);


add_filter('woocommerce_add_cart_item_data', 'gtw_add_item_data', 1, 2);

if( !function_exists('gtw_add_item_data')) {
    function gtw_add_item_data($cart_item_data, $product_id) {
        $unique_cart_item_key=md5(microtime().rand());
        $cart_item_data['unique_key']=$unique_cart_item_key;

        /*Here, We are adding item in WooCommerce session with, gtw_user_custom_data_value name*/
        global $woocommerce;
        $option='';
        if (gtw_is_session_started()===FALSE) session_start();

        if (isset($_SESSION['lb_gift_card_add_to_cart_'.$product_id])) {
            $option=$_SESSION['lb_gift_card_add_to_cart_'.$product_id];
            unset($_SESSION['lb_gift_card_add_to_cart_'.$product_id]);
            $new_value=array('gtw_user_custom_data_value'=> $option);

        }

        if(empty($option)) return $cart_item_data;

        else {
            if(empty($cart_item_data)) {
                return $new_value;
            } else {
                return array_merge($cart_item_data, $new_value);
            }
        }

        //Unset our custom session variable, as it is no longer needed.

    }
}


add_filter('woocommerce_get_cart_item_from_session', 'gtw_get_cart_items_from_session', 1, 3);

if( !function_exists('gtw_get_cart_items_from_session')) {
    function gtw_get_cart_items_from_session($item, $values, $key) {
        if (array_key_exists('gtw_user_custom_data_value', $values)) {
            $item['gtw_user_custom_data_value']=$values['gtw_user_custom_data_value'];
        }

        return $item;
    }
}


add_filter('woocommerce_checkout_cart_item_quantity', 'gtw_add_user_custom_option_from_session_into_cart', 1, 3);
add_filter('woocommerce_cart_item_price', 'gtw_add_user_custom_option_from_session_into_cart', 1, 3);

if( !function_exists('gtw_add_user_custom_option_from_session_into_cart')) {
    function gtw_add_user_custom_option_from_session_into_cart($product_name, $values, $cart_item_key) {
        if(isset($values['gtw_user_custom_data_value'])) {

            /*code to add custom data on Cart & checkout Page*/
            if(count($values['gtw_user_custom_data_value']) > 0) {
                $return_string=$product_name;
                $return_string .="<table class='gtw_options_table giftCardWallet' id='". $values['product_id'] . "'>";
                $return_string .="<tr><td>Nafn viðtakanda: ". $values['gtw_user_custom_data_value']['gift_card_recipient_name'] . "</td></tr>";
                $return_string .="<tr><td>Netfang viðtakanda: ". $values['gtw_user_custom_data_value']['gift_card_recipient_email'] . "</td></tr>";
                $return_string .="<tr><td>Símanúmer: ". $values['gtw_user_custom_data_value']['gift_card_phone'] . "</td></tr>";
                $return_string .="<tr><td>Nafn sendanda: ". $values['gtw_user_custom_data_value']['gift_card_sender_name'] . "</td></tr>";
                $return_string .="<tr><td>Skilaboð: ". $values['gtw_user_custom_data_value']['gift_card_custome_message'] . "</td></tr>";


                if($values['gtw_user_custom_data_value']['gift_card_time'] !='00:00:00') {
                    $return_string .="<tr><td>Dagsetning: ". $values['gtw_user_custom_data_value']['gift_card_date'] . " @ ". $values['gtw_user_custom_data_value']['gift_card_time'] . "</td></tr>";
                }

                else {
                    $return_string .="<tr><td>Dagsetning: ". $values['gtw_user_custom_data_value']['gift_card_date'] . "</td></tr>";
                }

                $return_string .="</table>";





                $editCard = get_option('enable_gift_card_edit');
                if ( is_cart() && $editCard === 'true' ) {
                    $return_string .= "<button type='button' class='Click-here' onclick='cart_popup_open_action(\"".$cart_item_key."\")'>Breyta</button>"; 

                    // Popup Start Now
                    $return_string .='
                        <div class="popup-wrap">

                            <div class="popup-model-main" id="'.$cart_item_key.'">
                                <div class="popup-model-inner">
                                    <div class="close-btn">&times;</div>
                                    <div class="popup-model-wrap">
                                        <div class="pop-up-content-wrap">
                                            <div class="form_group">
                                                <label>Nafn: <span class="require">*</span></label>
                                                <input value="'.$values['gtw_user_custom_data_value']['gift_card_recipient_name'].'" type="text" name="gift_card_name_'.$cart_item_key.'">
                                                <span class="gift_card_name_span giftcardformError"></span>
                                                <input value="'.$cart_item_key.'" type="hidden" name="gift_card_item_key">
                                            </div>
                                            <div class="form_group">
                                                <label>Netfang: <span class="email_required require">*</span></label>
                                                <input value="'.$values['gtw_user_custom_data_value']['gift_card_recipient_email'].'" type="email" name="gift_card_email_'.$cart_item_key.'">
                                                <span class="gift_card_email_span giftcardformError"></span>
                                            </div>
                                            <div class="form_group">
                                                <label>Símanúmer: <span class="phone_required require">*</span></label>
                                                <input value="'.$values['gtw_user_custom_data_value']['gift_card_phone'].'" type="number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="gift_card_phone_'.$cart_item_key.'" maxlength="7" minlength="7">
                                                <span class="gift_card_phone_span giftcardformError"></span>
                                            </div>
                                            <!-- <div class="form_group">
                                                <label>Samtals:</label>
                                                <input value="" type="number" name="gift_card_price">
                                            </div>
                                            <div class="form_group">
                                                <label>Image:</label>
                                                <input value="" type="file" name="gift_card_img">
                                            </div> -->
                                            <div class="form_group">
                                                <label>Nafn sendanda: <span class="require">*</span></label>
                                                <input value="'.$values['gtw_user_custom_data_value']['gift_card_sender_name'].'" type="text" name="gift_card_sender_name_'.$cart_item_key.'" >
                                                <span class="gift_card_sender_name_span giftcardformError"></span>
                                            </div>
                                            
                                            <div class="date_time">
                                                <div class="form_group date-input">
                                                    <label>Dagsetning: </label>';

                                                        $selectedDate = $values['gtw_user_custom_data_value']['gift_card_date'];
                                                        $splitDate    = explode("/",$selectedDate);
                                                        $g_date       = $splitDate[0];
                                                        $g_month      = $splitDate[1];
                                                        $g_year       = $splitDate[2];
                                                        $finalDate    = $g_year.'-'.$g_month.'-'.$g_date; 
                                                    
                                $return_string .='<input value="'.$finalDate.'" type="date" id="dDate" name="gift_card_date_'.$cart_item_key.'">
                                                    
                                                </div>

                                                <div class="form_group">
                                                    <label>Klukkustund: </label>
                                                    <select name="gift_card_time_hour_'.$cart_item_key.'" id="gift_card_time_hour" class="form-select" aria-label="">';

                                                        $time = $values['gtw_user_custom_data_value']['gift_card_time'];
                                                        $split = explode(":",$time);
                                                        $hour = $split[0];
                                                        $minit = $split[1];

                                                        for($i=0;$i<24;$i++){
                                                            $value = sprintf("%02d", $i);
                                                            $selected = ($value === $hour) ? "selected" : "";
                                                            $return_string .='<option value="'.$value.'" '.$selected.' >'.$value.'</option>';
                                                        }
                                                        
                                $return_string .='
                                                    </select>
                                                </div>

                                                <div class="form_group">
                                                    <label>Mínúta:</label>
                                                    <select name="gift_card_time_minit_'.$cart_item_key.'" id="gift_card_time_minit" class="form-select" aria-label="">';
                                                        
                                                        for($i=0;$i<60;$i++){
                                                            $value = sprintf("%02d", $i);
                                                            $selected = ($value === $minit) ? "selected" : "";
                                                            $return_string .='<option value="'.$value.'" '.$selected.' >'.$value.'</option>';
                                                        }
                                $return_string .='
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="form_group message-aria">
                                                <label>Skilaboð (Hámark 250 stafir):</label>
                                                <textarea rows="3" class="text_area_msg" name="gift_card_custome_message_'.$cart_item_key.'" onkeyup="giftCardMessagePreview(this)" maxlength="250" spellcheck="false">'.$values['gtw_user_custom_data_value']['gift_card_custome_message'].'</textarea>
                                            </div>';

                                // $send_mail_to_recipient=wc_get_order_item_meta( $item_id, 'send_mail_to_recipient', true)[0];
                                $send_recipient=$values['gtw_user_custom_data_value']['send_mail_to_recipient'];
                                $save_text     = get_option('gift_popup_save_btn') ? get_option('gift_popup_save_btn') : "Vista";
                                $closeText     = get_option('gift_popup_cancel_btn') ? get_option('gift_popup_cancel_btn') : "Hætta við";

                                $return_string .='
                                                        
                                            <div class="radio-buttob-form popup-checkbox">
                                                <label class="send_mail_sms sms_email">
                                                    <input type="radio" '.($send_recipient == 1 ? "checked":"").' name="send_recipient_'.$cart_item_key.'" value="1"> Sendu tölvupóst til viðtakanda 
                                                </label>

                                                <label class="send_mail_sms sms_email">
                                                    <input type="radio" '.($send_recipient == 2 ? "checked":"").' name="send_recipient_'.$cart_item_key.'" value="2">
                                                     Sendu sms til viðtakanda </label>
                                                <label class="send_mail_sms sms_email">
                                                    <input type="radio" '.($send_recipient == 3 ? "checked":"").' name="send_recipient_'.$cart_item_key.'" value="3">
                                                     Sendu sms &amp; tölvupóst til viðtakanda</label>
                                            </div>


                                            <div class="update-btn">

                                                <div id="sms"></div>

                                                <button type="button" class="cancel-btn" >'.$closeText.'</button>
                                                <button type="button" class="cart_popup_update_btn" onclick="cart_popup_update_btn(\''.$cart_item_key.'\')" >'.$save_text.'</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-overlay"></div>
                            </div>

                        </div>';
                    // Popup The end
                }
              







                return $return_string;
            }

            else {
                return $product_name;
            }
        }

        else {
            return $product_name;
        }
    }
}

// modify cart item image

add_filter('woocommerce_cart_item_thumbnail', 'gtw_wc_pro_cust_field_modify_cart_item_thumbnail', 99, 3); //$_product->get_image(), $cart_item, $cart_item_key );

function gtw_wc_pro_cust_field_modify_cart_item_thumbnail($product_image, $cart_item, $cart_item_key) {

    if(isset($cart_item["gtw_user_custom_data_value"])) {
        $product_image='<img src="'.$cart_item["gtw_user_custom_data_value"]["gift_card_image"].'" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy" width="300" height="300">';
    }

    return $product_image;
}


add_action('woocommerce_before_cart_item_quantity_zero', 'gtw_remove_user_custom_data_options_from_cart', 1, 1);

if( !function_exists('gtw_remove_user_custom_data_options_from_cart')) {
    function gtw_remove_user_custom_data_options_from_cart($cart_item_key) {
        global $woocommerce;
        // Get cart
        $cart=$woocommerce->cart->get_cart();

        // For each item in cart, if item is upsell of deleted product, delete it
        foreach($cart as $key=> $values) {
            if ($values['gtw_user_custom_data_value']==$cart_item_key) unset($woocommerce->cart->cart_contents[ $key]);
        }
    }
}

add_action('woocommerce_add_order_item_meta', 'gtw_add_values_to_order_item_meta', 1, 2);

if( !function_exists('gtw_add_values_to_order_item_meta')) {
    function gtw_add_values_to_order_item_meta($item_id, $values) {
        global $woocommerce,
        $wpdb;
        $user_custom_values=$values['gtw_user_custom_data_value'];

        if( !empty($user_custom_values)) {
            wc_add_order_item_meta($item_id, 'Dagsetning', $user_custom_values['gift_card_date']);
            wc_add_order_item_meta($item_id, 'Tími', $user_custom_values['gift_card_time']);
            wc_add_order_item_meta($item_id, 'Nafn viðtakanda', $user_custom_values['gift_card_recipient_name']);
            wc_add_order_item_meta($item_id, 'recipient_name', [$user_custom_values['gift_card_recipient_name']]);
            wc_add_order_item_meta($item_id, 'recipient_email', [$user_custom_values['gift_card_recipient_email']]);
            wc_add_order_item_meta($item_id, 'Netfang viðtakanda', $user_custom_values['gift_card_recipient_email']);
            wc_add_order_item_meta($item_id, 'Símanúmer', $user_custom_values['gift_card_phone']);
            wc_add_order_item_meta($item_id, 'Nafn sendanda', $user_custom_values['gift_card_sender_name']);
            wc_add_order_item_meta($item_id, 'Message', $user_custom_values['gift_card_custome_message']);

            wc_add_order_item_meta($item_id, 'photo_url', array($user_custom_values['gift_card_image']));

            $imagesUrl = $user_custom_values['gift_card_image'];

            // Get the attachment ID
            $attachment_id = attachment_url_to_postid($imagesUrl);

            // Update the post meta value
            update_post_meta( $attachment_id, 'gift_card_processing', false ); // true
        


            wc_add_order_item_meta($item_id, 'send_mail_to_recipient', array($user_custom_values['send_mail_to_recipient']));
        }
    }
}


// set name price / custom price to cart

add_filter('woocommerce_cart_contents_changed', function($cart_contents) {
        $new_contents=[];

        foreach ($cart_contents as $k=> $cart_item) {
            if(isset($cart_item["gtw_user_custom_data_value"])) {
                $price=$cart_item["gtw_user_custom_data_value"]["gift_card_amount"];
                $cart_item['data']->set_price($price);

            }

            $new_contents[$k]=$cart_item;
        }

        return array_merge($cart_contents, $new_contents);
    }, 30, 1
);


add_filter('woocommerce_is_sold_individually', 'gtw_custom_remove_all_quantity_fields', 10, 2);
function gtw_custom_remove_all_quantity_fields($return, $product) {

    $id=$product->get_id();
    $is_gift=get_post_meta($id, '_gift_card', true);

    if($is_gift=='yes') {
        return true;
    } else {
        return $return;
    }

}


// show pdf download link in order success page
add_action('woocommerce_order_item_meta_end', 'gtw_item_pdf_download_link', 10, 3);

function gtw_item_pdf_download_link($item_id, $item, $order) {
    //print_r($item);
    $product_id=$item->get_product_id();

    if(get_post_meta($product_id, '_gift_card', true)==='yes') {
        echo'<a style="clear:both;display:block;" target="_blank" href="'.site_url().'?gift-card-pdf=true&gcpdf='.base64_encode($item_id).'" class="gift-card-pdf-download-link home-back">Sækja gjafakort</a>';
    }

}

// show pdf download in admin order page
add_action('woocommerce_after_order_itemmeta', 'gtw_admin_item_pdf_download_link', 10, 3);

function gtw_admin_item_pdf_download_link($item_id, $item, $product) {

    $product_id=$item->get_product_id();

    if(get_post_meta($product_id, '_gift_card', true)==='yes') {
        echo'<a style="clear:both;display:block;" target="_blank" href="'.site_url().'?gift-card-pdf=true&gcpdf='.base64_encode($item_id).'" class="gift-card-pdf-download-link home-back">Sækja gjafakort</a>';
    }

}

// show custom gift card image at admin order detail page
add_filter('woocommerce_admin_order_item_thumbnail', 'gtw_card_item_custom_thumbnail', 10, 3);

function gtw_card_item_custom_thumbnail($product_get_image_thumbnail_array_title_false, $item_id, $item) {
    $product_id=$item->get_product_id();

    if(get_post_meta($product_id, '_gift_card', true)==='yes') {
        $order_id=$item->get_order_id();
        $custome_image=wc_get_order_item_meta($item_id, 'photo_url', true);

        $product_get_image_thumbnail_array_title_false='<img src="'.$custome_image[0].'" class="attachment-thumbnail size-thumbnail" alt="" width="150" height="150">';

        //return $custome_image[0];
    }

    return $product_get_image_thumbnail_array_title_false;
}

add_action('init', function() {
    //echo get_option('gift_to_wallet_token');
    if(isset($_GET["gift-card-pdf"]) && isset($_GET["gcpdf"])) {
        if($_GET["gift-card-pdf"]=='true'&& !empty($_GET["gcpdf"]) && base64_decode($_GET["gcpdf"])>0) {
            $orderLineItemId=base64_decode($_GET["gcpdf"]);
            $link='aaa';
            //$output=true;
            include('gift-card-pdf.php');
        }
    }
});


function gtw_create_entry($amount, $email, $name, $product_id, $item_id, $city, $post_code) {

    $current_date = strtotime(date('d-m-Y'));
    
    $expiryDate   = get_option('gtw_expiry_date');
    $gtwExpiryDate= '+'.$expiryDate.' years';
    $expiry_date  = date('d-m-Y', strtotime($gtwExpiryDate, $current_date));
    $brand        = get_post_meta( $product_id, '_gtw_brand_name', true );
    // $brand     = $gtwBrandName ? $gtwBrandName : 'Gjafakort';
    // generate token  

    if (is_null($brand) || !$brand) {
        //echo'@@@@@@@@@@@@@';
        return; // exit;
    }
     
    $token = get_option('gift_to_wallet_token');


    if($token){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.gifttowallet.com/api/card/bulkgenerate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'number_of_cards' => '1',
                'card_number_length' => '8',
                'card_number_format' => 'Numeric',
                'card_brand_name' => ''.$brand.'',
                'initial_balance' => ''.$amount.'',
                'current_balance' => ''.$amount.'',
                'expiry_date' => ''.$expiry_date.'',
                'generate_pass' => '1'
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token.''
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response=json_decode($response);
        /*echo'<hr>';
        print_r($response);
        echo'<hr>';*/
        if($response->status==1){
            
            //$card=$response->result->cards;
            $card=$response->result->cards[0]->card_number;
            $pass_link=$response->result->cards[0]->pass_link;
            $pass__identity=$response->result->cards[0]->pass_identifier;

            // update card info
            $phone=wc_get_order_item_meta( $item_id, 'Símanúmer', true);
            wc_add_order_item_meta($item_id, 'giftcard_no', $card);
            wc_add_order_item_meta($item_id, 'pass_link', $pass_link); // $apiResp->linkToPassPage
            wc_add_order_item_meta($item_id, 'pass__identity',$pass__identity); // $passi_id
            wc_add_order_item_meta($item_id, 'expiry_date',$expiry_date); // $passi_id
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://admin.gifttowallet.com/api/card/update-detail',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('card_number' => $card,'phone' => $phone,'email' => $email,'name' => $name),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.$token.''
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

        }
    }// end token checking
    
}

function gtw_create_card_pass($product_id, $name, $email, $template, $item_id, $card, $city, $post_code, $initial_amount) {

    $initial_amount=number_format($initial_amount, 0, ',', '.');

    $curl=curl_init();

    curl_setopt_array($curl, array(CURLOPT_URL=> 'https://app.passcreator.com/api/pass?passtemplate='.$template.'&zapierStyle=false',
            CURLOPT_RETURNTRANSFER=> true,
            CURLOPT_ENCODING=> '',
            CURLOPT_MAXREDIRS=> 10,
            CURLOPT_TIMEOUT=> 0,
            CURLOPT_FOLLOWLOCATION=> true,
            CURLOPT_HTTP_VERSION=> CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST=> 'POST',
            CURLOPT_POSTFIELDS=>'{


            "passBackFields": [],
            "additionalProperties": {
                "6330398c2f5ec6.08160392":"'.$city.'", "6148846f9a2e01.86392371":"'.$post_code.'", "6148846f9a32a5.27847319":"'.$card.'", "6148846f9a32d2.20923328":"'.$name.'", "636a61d912c1a8.09853505":"'.$initial_amount.'", "636a61d912c251.17724195":"'.$initial_amount.'"
            }

        }

        ',
            CURLOPT_HTTPHEADER=> array('Authorization: .pIhJs9w5vaMSirNHFItAMea7I.8YFLx_Z=pfG=UwkPvPJWzkBySiN1BgncESpzJ.v5QXGYC47Tn9HBY',
            'Content-Type: application/json'
        ),
    ));

    $response=curl_exec($curl);

    curl_close($curl);
    $apiResp=json_decode($response);

    global $wpdb;
    $passi_id=$apiResp->identifier;
    // insert data into database   
    $table_name=$wpdb->prefix . 'pass';
    $wpdb->insert($table_name, 
        array(
            'name'=> $name, 
            'email'=> $email, 
            'card_number'=> $card,
            'passi_id'=>$passi_id, 
            'template'=>$template,
            'city'=>$city,
            'post_code'=>$post_code,
            'initial_amount'=>$initial_amount,
            'current_amount'=>$initial_amount,
            'status'=>1
            )
        );


    wc_add_order_item_meta($item_id, 'giftcard_no', $card);
    wc_add_order_item_meta($item_id, 'pass_link', $apiResp->linkToPassPage);
    wc_add_order_item_meta($item_id, 'pass__identity', $passi_id);


}


function gtw_send_mail_to_user($name, $email, $url, $gift_card_new_mail=0, $attachment=false, $Message) {

    $subject = get_option( 'gtw_email_subject' ) ? get_option( 'gtw_email_subject' ) : 'Til hamingju með gjafakortið þitt!';

    $defaulEmailBody = 'Kæri/kæra {recipient_name}<br/><br/>
    Til hamingju með gjafakortið þitt !<br/><br/>Smelltu á hlekkinn til að sækja og setja gjafakortið í símann þinn ef þú hefur ekki nú þegar sótt það. Það tekur innan við eina mínútu OG þú getur sett sama kortið í fleiri en einn síma.<br/><br/>{pass_link}<br/><br/>Hægt er að nálgast allar upplýsingar um staðinn sem kortið virkar hjá á <a href="https://gifttowallet.com/">gifttowallet.com</a><br/><br/>Einnig er hægt að prenta út meðfylgjandi gjafakort og greiða með því án þess að setja í símann. <br/>{gift_card_message}</br><br/>Kveðja, <br/>gifttowallet';

    $emailBodyCheck =   get_option('email_send_user');
    $emailHtml = $emailBodyCheck ? stripslashes($emailBodyCheck) : $defaulEmailBody;

    $emailUrl  = '<a href="'.$url.'">'.$url.'</a>';

    // $Message	  = nl2br($Message);

    $emailBody = str_replace(["{recipient_name}", "{pass_link}", "{gift_card_message}"], [$name, $emailUrl, $Message ], $emailHtml);

  
    $to=$email;
    $headers=array('Content-Type: text/html;'); //  charset=UTF-8

    wp_mail($to, $subject, $emailBody, $headers, $attachment);

    //wh_log($name.' I am from send mail to user '.$to.' '.$url);

}


add_action('woocommerce_admin_order_data_after_order_details', 'gtw_wc_admin_order_data_after_details_action');

function gtw_wc_admin_order_data_after_details_action($order) {
    $orderid=$order->get_id();
    $show=false;
    $order_items=$order->get_items();

    foreach($order_items as $item_id=> $order_item) {
        $product_id=$order_item->get_product_id();

        if(get_post_meta($product_id, '_gift_card', true)==='yes') {
            $gift_card=wc_get_order_item_meta($item_id, 'giftcard_no', true);

            if($gift_card !=null) {}

            else {
                $show=true;
                break;
            }
        }
    }

    if($show) {
        echo'<a style="margin-top:20px;" href="'.site_url().'/wp-admin/post.php?post='.$orderid.'&action=edit&gererate=true" class="btn btn-primary button button-primary">Generate Missing Card & Pass</a>';
    }
}

add_action('init', function() {
        if(is_admin()) {
            if(isset($_GET["gererate"]) && isset($_GET["action"]) && isset($_GET["post"])) {
                if( !empty($_GET["post"]) && !empty($_GET["gererate"])) {
                    gtw_item_order_payment_complete($_GET["post"]);
                }
            }
        }
    }

);



$demoModeEnable = get_option('enable_demo_mode');
if($demoModeEnable === 'true'){
    add_action('woocommerce_thankyou', 'gtw_item_order_payment_complete', 10, 1);
}


add_action('woocommerce_payment_complete', 'gtw_item_order_payment_complete');

function gtw_item_order_payment_complete($order_id) {
    global $wpdb;

    $order = wc_get_order($order_id);
    $name  = $order->get_billing_first_name();
    $email = $order->get_billing_email();
    $city  = $order->get_billing_city();
    $type  = 1;

    $post_code   = $order->get_billing_postcode();
    $entryType   = 'plastkort';
    $cardType    = 'Gjafakort';
    $order_items = $order->get_items();
    $makeOrderCompleted=false;
    $x=0;

    if ( !is_wp_error($order_items)) {

        foreach($order_items as $item_id=> $order_item) {
            // for products only:
            $product_id=$order_item->get_product_id();

            if(get_post_meta($product_id, '_gift_card', true)==='yes') {

                if(wc_get_order_item_meta($item_id, 'giftcard_no', true) !=null) {
                    continue;
                }

                // create gift card pos entry & create pass
                $giftAmount      = $order_item->get_total();

                // email send to Netfang viðtakanda
                $recepient_email = wc_get_order_item_meta($item_id, 'recipient_email', true)[0];
                $recepient_name  = wc_get_order_item_meta($item_id, 'recipient_name', true)[0];

                $send_mail_to_recipient      = wc_get_order_item_meta($item_id, 'send_mail_to_recipient', true)[0];
                $send_sms_to_recipient_phone = wc_get_order_item_meta($item_id, 'Símanúmer', true);
                
                // process after gift card created at gift to wallet system
                gtw_create_entry($giftAmount, $recepient_email, $recepient_name, $product_id, $item_id, $city, $post_code);

                // insert gift card email queue    
                $date_ = wc_get_order_item_meta($item_id, 'Dagsetning', true);
                $time_ = wc_get_order_item_meta($item_id, 'Tími', true);

                $prefered_date_time = implode("-", array_reverse(explode("/", $date_))).' '.$time_;
                $table_name         = $wpdb->prefix . 'gift_card_email_queue';

                $wpdb->insert($table_name,
                    array(
                        'order_id'          => $order_id,
                        'order_line_item_id'=> $item_id,
                        'status'            => 0,
                        'prefered_date_time'=> $prefered_date_time
                    )
                );

                $makeOrderCompleted=true;
            }

            $x++;
        }

        // end foreach
    }

    if($x==1 && $makeOrderCompleted) {
        $order->update_status('completed');
    }
}


add_filter ('add_to_cart_redirect', 'gtw_redirect_to_checkout');
function gtw_redirect_to_checkout() {
    global $woocommerce;
    $checkout_url=$woocommerce->cart->get_checkout_url();
    return $checkout_url;
}

/*
function wh_log($log_msg) {
    $log_filename="log";

    if ( !file_exists($log_filename)) {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }

    $log_file_data=$log_filename.'/log_'. time() . '.log';
    // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
}
*/

add_filter('woocommerce_product_single_add_to_cart_text', 'gtw_wc_single_page_add_to_cart_callback');

function gtw_wc_single_page_add_to_cart_callback() {
    return __('Áfram í greiðslumáta', 'woocommerce'); // gtw
}

// Hook before calculate fees
//add_action('woocommerce_cart_calculate_fees' , 'add_user_discounts'); 
/*
function add_user_discounts(WC_Cart $cart) {
    
    $discount=$cart->get_subtotal() * 0.11;
    $cart->add_fee('Afsláttur 11%', -$discount);

}
*/

// validate giftcard input on checkout
add_filter('woocommerce_add_to_cart_validation', 'gtw_validate_card_details_in_the_cart', 10, 2);

function gtw_validate_card_details_in_the_cart($passed, $product_id) {

    $is_gift_card=get_post_meta($product_id, '_gift_card', true);

    if($is_gift_card=='yes') {

        if(trim($_POST['gift_card_recipient_name'])=='') {
            wc_add_notice(sprintf('nafn er krafist'), 'error');
            $passed=false; // don't add the new product to the cart
        }

        if(trim($_POST['gift_card_recipient_email'])==''|| !is_email(trim($_POST['gift_card_recipient_email']))) {
            wc_add_notice(sprintf('Netfang er krafist'), 'error');
            $passed=false; // don't add the new product to the cart
        }

        if(trim($_POST['gift_card_phone'])==''|| strlen(trim($_POST['gift_card_phone'])) !=7 || !is_numeric(trim($_POST['gift_card_phone']))) {
            wc_add_notice(sprintf('Símanúmer er krafist'), 'error');
            $passed=false; // don't add the new product to the cart
        }

        /*if(trim($_POST['gift_card_custome_message'])==''){
            wc_add_notice( sprintf( 'Skilaboð sem birtast á gjafabréf og í SMS er krafist'), 'error' );
            $passed = false; // don't add the new product to the cart
        }*/
        if(trim($_POST['gift_card_sender_name'])=='') {
            wc_add_notice(sprintf('Nafn sendanda er krafist'), 'error');
            $passed=false; // don't add the new product to the cart
        }
    }

    return $passed;
}


add_action("wp_ajax_gtw_add_to_cart", "gtw_add_to_cart");
add_action("wp_ajax_nopriv_gtw_add_to_cart", "gtw_add_to_cart");

function gtw_add_to_cart() {

    //check error
    for($i=0; $i<count($_POST['product_id']); $i++) {

        $product_id=$_POST['product_id'][$i];
        $gift_card_recipient_name=trim($_POST['gift_card_recipient_name'][$i]);
        $gift_card_recipient_email=trim($_POST['gift_card_recipient_email'][$i]);
        $gift_card_phone=trim($_POST["gift_card_phone"][$i]);
        $gift_card_sender_name=trim($_POST['gift_card_sender_name'][$i]);
        $error_arr=[];

        if(empty($gift_card_recipient_name)) {
            $error_arr[]=array("product_id"=> $product_id, "fieldname"=> 'gift_card_recipient_name', "field_value"=> $gift_card_recipient_name, "errorText"=> 'Viðtakanda Nafn Áskilið.');
        }

        if($send_mail_to_recipient==1 || $send_mail_to_recipient==3) {
            if(empty($gift_card_recipient_email)) {
                $error_arr[]=array("product_id"=> $product_id, "fieldname"=> 'gift_card_recipient_email', "field_value"=> $gift_card_recipient_email, "errorText"=> 'Viðtakanda Netfang Áskilið.');
            }

            if( !is_email($gift_card_recipient_email)) {
                $error_arr[]=array("product_id"=> $product_id, "fieldname"=> 'gift_card_recipient_email', "field_value"=> $gift_card_recipient_email, "errorText"=> 'Viðtakanda Netfang not valid.');
            }
        }

        if($send_mail_to_recipient==2 || $send_mail_to_recipient==3) {
            if(empty($gift_card_phone) || strlen($gift_card_phone) !=7 || !is_numeric($gift_card_phone)) {
                $error_arr[]=array("product_id"=> $product_id, "fieldname"=> 'gift_card_phone', "field_value"=> $gift_card_phone, "errorText"=> 'Viðtakanda Símanúmer Áskilið.');
            }
        }

        if(empty($gift_card_sender_name)) {
            $error_arr[]=array("product_id"=> $product_id, "fieldname"=> 'gift_card_sender_name', "field_value"=> $gift_card_sender_name, "errorText"=> 'Nafn sendanda Áskilið.');
        }

        if(count($error_arr)>0) {
            $response['type']='fail';
            $response['errorMessages']=$error_arr;
            echo json_encode($response);
            exit;
        }
    }

    //check error end
    for($i=0; $i<count($_POST['product_id']); $i++) {
        if($i>0) sleep(1);

        $product_id=$_POST['product_id'][$i];
        $product=wc_get_product($product_id);
        //$gift_card_amount=$product->get_regular_price();

        $hour=empty($_POST["gift_card_time_hour"][$i])?'00': $_POST["gift_card_time_hour"][$i];
        $minute=empty($_POST["gift_card_time_minute"][$i])?'00': $_POST["gift_card_time_minute"][$i];
        $time=$hour.':'.$minute;

        $gift_card_date=(empty($_POST["gift_card_date"][$i])?date('d/m/Y'):date("d/m/Y", strtotime($_POST["gift_card_date"][$i])));
        $gift_card_time=date("H:i:s", strtotime($time));

        $gift_card_recipient_name=trim($_POST['gift_card_recipient_name'][$i]);
        $gift_card_recipient_email=trim($_POST['gift_card_recipient_email'][$i]);
        $gift_card_phone=trim($_POST["gift_card_phone"][$i]);
        $gift_card_custome_message=$_POST['gift_card_custome_message'][$i];
        $gift_card_sender_name=trim($_POST['gift_card_sender_name'][$i]);
        $send_mail_to_recipient=$_POST['send_mail_to_recipient'][$i];

        $gift_card_image=$_POST['gift_card_image'][$i];
        $gift_card_amount=$_POST['gift_card_amount'][$i];

        $gift_card_item_add_to_cart_meta=array('gift_card_amount'=>$gift_card_amount,
            'gift_card_date'=>$gift_card_date,
            'gift_card_time'=>$gift_card_time, // need to join hour & min
            'gift_card_recipient_name'=>$gift_card_recipient_name,
            'gift_card_recipient_email'=>$gift_card_recipient_email,
            'gift_card_sender_name'=>$gift_card_sender_name,
            'gift_card_phone'=>$gift_card_phone,
            'gift_card_custome_message'=>$gift_card_custome_message,
            'send_mail_to_recipient'=>$send_mail_to_recipient,
            'gift_card_image'=>$gift_card_image,
            'gift_card_amount'=>$gift_card_amount,

        );

        if (gtw_is_session_started()===FALSE) session_start();
        $_SESSION['lb_gift_card_add_to_cart_'.$product_id]=$gift_card_item_add_to_cart_meta;
        WC()->cart->add_to_cart($product_id);
    }

    $response['type']='success';
    $response['successMessages']='Gift Cards added to cart successfully';
    echo json_encode($response);
    exit;
}


add_filter('body_class', 'gtw_body_class');

function gtw_body_class($classes) {
    if (is_cart() || is_checkout()) {
        $classes[]='gift_wallet';
    }

    return $classes;
}

add_filter('the_title', 'gtw_title_order_received', 10, 2);

function gtw_title_order_received($title, $id) {
    if (function_exists('is_order_received_page') && is_order_received_page() && get_the_ID()===$id) {
        $title="Takk fyrir.";
    }

    return $title;
}


// upload image in localStorage 
add_action('wp_footer', 'gtw_my_upload_image_in_localstorage');

function gtw_my_upload_image_in_localstorage() {
    global $post;
    $pid=$post->ID;
    
    
    ?>
    <div class=""id="imageModalContainer" style="display:none;">
        <div class="crop-container-bg closeCrop"></div>
        <div class="modal-crop-img-wrap">
            <div class="crop-header">
                <h5 class="crop-title">Crop Image</h5>
                <button type="button"class="crop-btn-close closeCrop closeEdit">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="crop-body">
                <div id='crop-image-container'></div>
            </div>
            <div class="crop-footer">
                <?php 
                   $saveText   = get_option('gift_popup_save_btn') ? get_option('gift_popup_save_btn') : "Vista";
                   $cancelText = get_option('gift_popup_cancel_btn') ? get_option('gift_popup_cancel_btn') : "Hætta við";
                ?>
                <button type="button"class="crop-btn closeCrop cancelEdit"><?php echo $cancelText?></button>
                <button type="button"class="crop-btn save-modal"><?php echo $saveText?></button>
            </div>
        </div>
    </div>
    
    <?php
}



function gtw_my_upload_image_save($base64_img, $title) {

    // Upload dir.
    $upload_dir      = wp_upload_dir();
    $upload_path     = str_replace('/', DIRECTORY_SEPARATOR, $upload_dir['path']) . DIRECTORY_SEPARATOR;

    $img             = str_replace('data:image/jpeg;base64,', '', $base64_img);
    $img             = str_replace(' ', '+', $img);
    $decoded         = base64_decode($img);
    $filename        = $title . '.jpeg';
    $file_type       = 'image/jpeg';
    $hashed_filename = md5($filename . microtime()) . '_'. $filename;

    // Save the image in the uploads directory.
    $upload_file     = file_put_contents($upload_path . $hashed_filename, $decoded);

    $attachment=array(
        'post_mime_type'=> $file_type,
        'post_title'    => preg_replace('/\.[^.]+$/', '', basename($hashed_filename)),
        'post_content'  => '',
        'post_status'   => 'inherit',
        'guid'          => $upload_dir['url'] . '/'. basename($hashed_filename)
    );

    $attach_id = wp_insert_attachment($attachment, $upload_dir['path'] . '/'. $hashed_filename);

    // Update the post meta value
    update_post_meta( $attach_id, 'gift_card_processing', true ); // false

    

    return wp_get_attachment_image_url($attach_id,'full');
}


// ajax process & file upload to wp_media
function gtw_img_upload_wp_media() {


    $image  = $_POST["image"]; // UploadFile
    $title  = time();
    $imgUrl = gtw_my_upload_image_save($image, $title); // upload_users_file($image); 

    // echo $imgUrl;
    wp_send_json_success($imgUrl);

exit();
}

add_action('wp_ajax_gtw_img_upload_wp_media', 'gtw_img_upload_wp_media');
add_action('wp_ajax_nopriv_gtw_img_upload_wp_media', 'gtw_img_upload_wp_media');




function gtw_token(){

    $customerAdminEmail = get_option('gtw_c_eamil'); // kringlan@kringlan.is
    $customerAdminPass  = get_option('gtw_c_password'); // Lq185bYXJ!1

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

        // echo $token;
    }

}


/*
function gtw_add_brand_name(){
    global $woocommerce,$post;
    woocommerce_wp_text_input(
        array(
            'id'          => '_barcode',
            'label'       => __('Brand Name','gtw'),
            'placeholder' => 'GTW Brand name',
            'desc_tip'    => 'true',
            'description' => __('GTW Brand name.','gtw')
        ));
}
add_action('woocommerce_product_options_inventory_product_data','gtw_add_brand_name');



add_action('woocommerce_product_options_sku','add_barcode_custom_field' );
function add_barcode_custom_field(){
    woocommerce_wp_text_input( array(
        'id'          => '_barcode',
        'label'       => __('Barcode xyz','woocommerce'),
        'placeholder' => 'Scan Barcode',
        'desc_tip'    => 'true',
        'description' => __('Scan barcode.','woocommerce')
    ) ); 
}

add_action( 'woocommerce_process_product_meta', 'save_barcode_custom_field', 10, 1 );
function save_barcode_custom_field( $post_id ){
    if( isset($_POST['_barcode']) )
        update_post_meta( $post_id, '_barcode', esc_attr( $_POST['_barcode'] ) );
}
*/





// ajax process gtw_in_cart_page_popup_update_data();
function gtw_in_cart_page_popup_update_data() {
    global $woocommerce;
    
    $email    = is_email($_POST['email']);
    $key      = $_POST['cartItemKey'];
    $name     = sanitize_text_field($_POST['name']);
    $phone    = sanitize_text_field($_POST['phone']);
    $sms      = sanitize_text_field($_POST['message']);
    $required = sanitize_text_field($_POST['requireCheck']);
    $sender_name= sanitize_text_field($_POST['sName']);

    $date = empty($_POST["date"]) ? date('Y-m-d') : sanitize_text_field($_POST['date']); // 2023-05-29 | 20/05/2023

    $minit= empty($_POST["minit"]) ? '00': sanitize_text_field($_POST['minit']);
    $hour = empty($_POST["hour"]) ? '00': sanitize_text_field($_POST['hour']);

    $time = $hour.':'.$minit.':'.'00';


    $splitDate= explode("-",$date);
    $g_date   = $splitDate[2];
    $g_month  = $splitDate[1];
    $g_year   = $splitDate[0];
    $gDate    = $g_date.'/'.$g_month.'/'.$g_year; 

    WC()->cart->cart_contents[$key]['gtw_user_custom_data_value']['gift_card_recipient_email']=$email;
    WC()->cart->cart_contents[$key]['gtw_user_custom_data_value']['gift_card_recipient_name']=$name;
    WC()->cart->cart_contents[$key]['gtw_user_custom_data_value']['gift_card_sender_name']=$sender_name;
    WC()->cart->cart_contents[$key]['gtw_user_custom_data_value']['gift_card_phone']=$phone;
    WC()->cart->cart_contents[$key]['gtw_user_custom_data_value']['gift_card_custome_message']=$sms;
    WC()->cart->cart_contents[$key]['gtw_user_custom_data_value']['gift_card_time']=$time;
    WC()->cart->cart_contents[$key]['gtw_user_custom_data_value']['gift_card_date']=$gDate;

    WC()->cart->cart_contents[$key]['gtw_user_custom_data_value']['send_mail_to_recipient']=$required;

    WC()->cart->set_session();



    $message = 'Item Updated Done!';
    echo json_encode(['status'=>'ok', 'messages' => $message ]);

    exit(); // wp_die();
}
add_action('wp_ajax_gtw_in_cart_page_popup_update_data', 'gtw_in_cart_page_popup_update_data');
add_action('wp_ajax_nopriv_gtw_in_cart_page_popup_update_data', 'gtw_in_cart_page_popup_update_data');





function gtw_send_sms($number,$message){
	$message= strip_tags($message);
    $message= str_replace(array("\r", "\n"), '', $message);
    $sender = 'Kringlan';
    $userId = '24';
	$curl   = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL            => 'https://sms.leikbreytir.is/sms/api.php',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING       => '',
	  CURLOPT_MAXREDIRS      => 10,
	  CURLOPT_TIMEOUT        => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST  => 'POST',
	  CURLOPT_POSTFIELDS     => array(
            'token'  => '1q2w3e4r5t6y7uq1w2e3r4t5y6u7',
            'number' => $number,
            'message'=> $message,
            'sender' => $sender,
            'userId' => $userId
        ),
	));

	curl_exec($curl);

	curl_close($curl);

}

// Send Email & SMS for Gift to Wallet
function gtw_send_cron(){

    global $wpdb;
    $table_name = $wpdb->prefix . 'gift_card_email_queue';
    $sql        = "SELECT id,order_id,order_line_item_id FROM $table_name WHERE status=0 and prefered_date_time<now() order by id asc limit 5";
    $result     = $wpdb->get_results($sql);
    //print_r($result);
    $entryType  = 'plastkort';        
    if($result){
        $i=0;
        foreach($result as $row){
            
            $order = wc_get_order( $row->order_id );
            $name  = $order->get_billing_first_name();
            $email = $order->get_billing_email();
    
            $output= true;
            $orderLineItemId = $row->order_line_item_id;
            $recepient_email = wc_get_order_item_meta( $orderLineItemId, 'recipient_email', true)[0];
            $recepient_name  = wc_get_order_item_meta( $orderLineItemId, 'recipient_name', true)[0];
            $pass_link       = wc_get_order_item_meta( $orderLineItemId, 'pass_link', true);
            if($pass_link!=null){
                $card_sender = wc_get_order_item_meta( $orderLineItemId, 'Nafn sendanda', true);
                $Message     = wc_get_order_item_meta( $orderLineItemId, 'Message', true);
    
                $phone       = wc_get_order_item_meta( $orderLineItemId, 'Símanúmer', true);
    
                $_email      = (is_email($recepient_email)?$recepient_email:$email);
                $_name       = ($recepient_name!=NULL?$recepient_name:$name);
                
                $send_mail_to_recipient=wc_get_order_item_meta( $orderLineItemId, 'send_mail_to_recipient', true)[0];
    
                if(in_array($send_mail_to_recipient,[1,3])){
    
                    include('wp-content/plugins/gift-to-wallet/gift-card-pdf.php');
    
                    gtw_send_mail_to_user($_name,$_email,$pass_link,0,$pdf_file_name, $Message);
    
                    @unlink($pdf_file_name);
                }
    
                if(in_array($send_mail_to_recipient,[2,3])){
                    // $message=$_name.', þú varst að fá sent gjafakort frá Kringlan. Sendandi er '.$card_sender.' með kveðjunni: '.$Message.' Smelltu á hlekkinn til að bæta gjafakortinu í Wallet hjá þér: '.$pass_link.'';
    
                    $smsBody = '{recipient_name}, þú varst að fá sent gjafakort frá Kringlan. Sendandi er {sender_name} með kveðjunni: {gift_card_message} Smelltu á hlekkinn til að bæta gjafakortinu í Wallet hjá þér: {pass_link}';
    
                    $smsCheck=   get_option('use_send_sms');
                    $smsHtml = $smsCheck ? stripslashes($smsCheck) : $smsBody;
    
                    $message = str_replace(["{recipient_name}", "{sender_name}", "{gift_card_message}", "{pass_link}"], [$_name, $card_sender, $Message, $pass_link], $smsHtml);
    
                    if(strlen($phone)==7){
                        gtw_send_sms($phone,$message);
                    }
                }
    
                $wpdb->query("update $table_name set status=1, send_at=now() where id=$row->id");
            }else{
                $wpdb->query("delete from $table_name where id=$row->id");
            }
    
        }
    }
    
    echo'ok';
}




/*
add_filter('template_include', 'gift_cart_template', 99);

function gift_cart_template($template) {

    $is_gift=get_post_meta(get_the_ID(), '_gift_card', true);

    if($is_gift=='yes') {
        $template=dirname(__FILE__) . '/content-single-product.php';
        // $template = dirname( __FILE__ ) . '/content-single-product-page.php';
    }

    return $template;
}
*/

/**
 * Gift to Wallet Single product page template part used by WooCommerce.
 */
add_filter( 'wc_get_template_part', 'gtw_single_product_page_wc_template_part', 10, 3 );

function gtw_single_product_page_wc_template_part( $template, $slug, $name ) {
    
    $is_gift = get_post_meta(get_the_ID(), '_gift_card', true); // $is_gift=='yes'

    if ( 'content' === $slug && 'single-product' === $name && $is_gift=='yes') {

        // Remove default "Sale!" label
        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
        

        $template = dirname(__FILE__) . '/gtw-single-product-content.php';

    }

    return $template;
}


