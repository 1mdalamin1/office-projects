<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}


?>

<style>

	/*multi giftcard*/
	.woocommerce div.product form.cart .button.single_add_to_cart_button, .hidden{
		display: none;
	}

	/* Style the buttons inside the tab */
	.img_thumbnail .tab button {
		background-color: inherit;
		float: left;
		outline: none;
		cursor: pointer;
		transition: 0.3s;
		font-size: 14px;
		padding: 10px 10px;
		border-radius: unset;
		font-weight: 500;
		margin-bottom: 5px;
		margin-right: 5px;
		border: 1px solid <?=BTN_BG?>;
		color:<?=BTN_TC?>;
	}
	.radio_btn label:first-child {
		margin-left: 0;
	}
	.radio_btn label:last-child, .img_thumbnail .tab button:last-child  {
		margin-right: -5px;
	}
	.radio_btn label {
		margin-bottom: 5px;
	}

	.woocommerce-Price-amount {
		font-size: 14px !important;
		text-align: center;
	}

	#gift_card_image_box_html .box_content {
		border: 2px solid <?=BTN_BG?>;
	}
	.img_thumbnail ul li img:hover,.img_thumbnail ul li img.active {
		border-color: <?=BTN_BG?>;
	}
	.radio_btn label span.woocommerce-Price-amount {
		border: 1px solid <?=BTN_BG?>;
	}

	.radio_btn label span.woocommerce-Price-amount:hover, .radio_btn input[type="radio"]:checked + span.woocommerce-Price-amount, .img_thumbnail .tab button.active, .img_thumbnail .tab button:hover {
		background: <?=BTN_BG?>;
		color: #fff;
		transition:.3s;
	}

	.ui-state-default{
		color: <?=BTN_TC?> !important;
	}

	.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-default.ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover {
		border: 1px solid <?=BTN_BG?>;
		background: <?=BTN_BG?>;
		font-weight: normal;
		color: #ffffff !important;
		/* font-family: auto !important; */
	}
	button#submitGiftCardFormBtn {
		background: <?=BTN_BG?>;
		color: #fff;
		border: 0;
	}

	button#submitGiftCardFormBtn:hover {
		background: <?=BTN_BG_H?>;
	}
	.giftcardformError {
		color: red;
		font-size: 12px;
	}

	/* Product bradcum, product title, product category, product price */
	#accordion nav.woocommerce-breadcrumb, #accordion span.single-product-category, #accordion .product_title.entry-title, #accordion .price span.woocommerce-Price-amount.amount, #accordion .product_meta span.posted_in  {
		display: none;
	}
	#accordion  form + .product_meta {
		display: none;
	}


</style>
<div id="accordion">
	<h3 class="product-title-gift" id="productHeader-<?php the_ID(); ?>"><?php the_title();?> -1</h3>
	<div  id="productGift-<?php the_ID(); ?>" <?php wc_product_class( ' product-single-gift ', $product ); ?>>

		<?php
		/**
		 * Hook: woocommerce_before_single_product_summary.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
		?>

		<div class="summary entry-summary">
			<?php
			/**
			 * Hook: woocommerce_single_product_summary.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */

			do_action( 'woocommerce_single_product_summary' );
			?>
		</div>

		<?php
		/**
		 * Hook: woocommerce_after_single_product_summary.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		//do_action( 'woocommerce_after_single_product_summary' );
		?>
	</div>
</div>
<br>
<div class="formButton form_row" style="">
	<div class="col_6">
		<input type="number" id="qtyGiftCard" style="margin-left: 10px;width: 60px;" value="1" name="qtyGiftCard"/> 
		<button id="submitGiftCardFormBtn" class="submitGiftCardFormBtn" ><?=BTN_TEXT?></button>
		<a class="btn aUpdateCart" style="display:none" href="<?=site_url()?>/cart/">Update Cart</a>
		<!--<button id="cloneProduct">Add More Card</button>-->
	</div>
	
	<div class="col_6" id="errorText">
		
	</div>
</div>


<script language="javascript">
	jQuery(document).on("change","label.send_mail_sms",function(){
		let parent = jQuery(this).parent().parent();
		let value = jQuery(parent).find("input[name=send_mail_to_recipient]:checked").val();
		//console.log(value);
		if(value == 1){ // email required
			jQuery(parent).find("span.phone_required").addClass("hidden");
			jQuery(parent).find("span.email_required").removeClass("hidden");
			jQuery(parent).find('.gift_card_phone_span').html('');
		}else if(value == 2){ // phone required
			jQuery(parent).find("span.phone_required").removeClass("hidden");
			jQuery(parent).find("span.email_required").addClass("hidden");
			jQuery(parent).find('.gift_card_recipient_email_span').html('');
		}else{
			jQuery(parent).find("span.phone_required").removeClass("hidden");
			jQuery(parent).find("span.email_required").removeClass("hidden");
		}
	});

	jQuery(document).ready(function(){

		function validateEmail($email) {
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			return emailReg.test( $email );
		}

		function removeLastWord(str) {
			const lastIndexOfSpace = str.lastIndexOf(' ');
			if (lastIndexOfSpace === -1) {
				return str;
			}
			return str.substring(0, lastIndexOfSpace);
		}

		jQuery( function() {
			jQuery( "#accordion" ).accordion();
		} );

		jQuery('#cloneProduct').click(function(){

			var $divHeader = jQuery('#accordion h3[id^="productHeader-"]:last');
			var $div = jQuery('div[id^="productGift-"]:last');
			var numH3 = parseInt( $divHeader.prop("id").match(/\d+/g), 10 ) +1;
			var $klonH3 = $divHeader.clone().prop('id', 'productHeader-'+numH3 );
			$div.after( $klonH3.html($divHeader.html()) );

			var num = parseInt( $div.prop("id").match(/\d+/g), 10 ) +1;
			var $klon = $div.clone().prop('id', 'productGift-'+num );
			$klonH3.after( $klon.html($div.html()) );


			jQuery( "#accordion" ).accordion( "refresh" );

		});

		jQuery('#qtyGiftCard').change(function(){

			var qtyGiftCard = jQuery(this).val();
			var countProductSingleGift = jQuery('.product-single-gift').length;

			if(qtyGiftCard != countProductSingleGift && qtyGiftCard > 0){
				for(var i=0; i<countProductSingleGift-1;i++){
					
					var countProductSingleGiftTemp = jQuery('.product-single-gift').length;
					if(countProductSingleGiftTemp==1){
						break;
					}
					var $divHeader = jQuery('#accordion h3[id^="productHeader-"]:last');
					console.log($divHeader);
					var $div = jQuery('div[id^="productGift-"]:last');
					console.log($div);
					$divHeader.remove();
					$div.remove();

				}

				for(var i=0; i<qtyGiftCard-1;i++){

					var $divHeader = jQuery('#accordion h3[id^="productHeader-"]:last');
					var $div = jQuery('div[id^="productGift-"]:last');
					var numH3 = parseInt( $divHeader.prop("id").match(/\d+/g), 10 ) +1;
					var $klonH3 = $divHeader.clone().prop('id', 'productHeader-'+numH3 );
					$div.after( $klonH3.html(removeLastWord($divHeader.html())+' -'+(i+2)) );
					$klonH3.removeClass('ui-accordion-header-active ui-state-active');
					$klonH3.css('color','#000');

					

					var num = parseInt( $div.prop("id").match(/\d+/g), 10 ) +1;
					let new_id = 'productGift-'+num;
					var $klon = $div.clone().prop('id', new_id);
					let item_html = $klon.html($div.html());

					// local storage image url change here

					let imgLocalStorage = localStorage.getItem(localStVar);
					if(imgLocalStorage){
						let imageAll = JSON.parse(imgLocalStorage);

						// console.log("Show Image = "+imageAll);
						let singleImg = imageAll.find(u => u.s_no === new_id);
						if(singleImg){
							jQuery(item_html).find("img.confirm-img").attr('src', singleImg.url);
						}else{
							jQuery(item_html).find("img.confirm-img").attr('src','');
						}
						
					}else{
						jQuery(item_html).find("img.confirm-img").attr('src','');
					}


					//var $klon = $div.clone().prop('id', 'productGift-'+num );
					$klonH3.after( item_html );
				}
			}
			
			jQuery( "#accordion" ).accordion( "refresh" );

		});

		jQuery(".submitGiftCardFormBtn").click( function(e) {
			e.preventDefault(); 

			var product_id = [];	
			var gift_card_date = [];
			var gift_card_time_hour  = [];
			var gift_card_time_minute  =  [];
			var gift_card_recipient_name  =  [];
			var gift_card_recipient_email  = [];
			var gift_card_phone  = [];
			var gift_card_custome_message  = [];
			var gift_card_sender_name  =  [];
			var send_mail_to_recipient  = [];

			var gift_card_image  = [];
			var gift_card_amount = [];
			
			var i = 0;
			var formError = 0;
			jQuery(".product-single-gift").each(function() {

				var myID =  jQuery(this).attr('id').match(/\d+/g);

				product_id[i]  =  jQuery(this).find('[name="product_id"]').val();
				gift_card_date[i]  =  jQuery(this).find('[name="gift_card_date"]').val();
				gift_card_time_hour[i]  =  jQuery(this).find('[name="gift_card_time_hour"] option:selected').val();
				gift_card_time_minute[i]  =  jQuery(this).find('[name="gift_card_time_minute"] option:selected').val();
				gift_card_recipient_name[i]  =  jQuery(this).find('[name="gift_card_recipient_name"] ').val();
				gift_card_recipient_email[i]  =  jQuery(this).find('[name="gift_card_recipient_email"]').val();
				gift_card_phone[i]  =  jQuery(this).find('[name="gift_card_phone"]').val();
				gift_card_custome_message[i]  =  jQuery(this).find('[name="gift_card_custome_message"]').val();
				gift_card_sender_name[i]  =  jQuery(this).find('[name="gift_card_sender_name"]').val();
				send_mail_to_recipient[i]  =  jQuery(this).find('[name="send_mail_to_recipient"]:checked').val();

				

				gift_card_image[i]  =  jQuery(this).find('[name="gift_card_image"]').val();
				gift_card_amount[i]  =  jQuery(this).find('[name="gift_card_amount"]').val();
				
				if(gift_card_recipient_name[i].trim()=='' || gift_card_recipient_name[i]==null){
					jQuery(this).find('[name="gift_card_recipient_name"]').css('border-color','red');
					jQuery(this).find('[name="gift_card_recipient_name"]').focus();	
					jQuery(this).find('.gift_card_recipient_name_span').html('Nafn viðtakanda er ekki rétt.');
					formError++;
					jQuery( "#productHeader-"+myID ).trigger( "click" );
					
				}
				else{
					jQuery(this).find('[name="gift_card_recipient_name"]').css('border','0px');
					jQuery(this).find('.gift_card_recipient_name_span').html('');
				}


				if(send_mail_to_recipient[i]==1 || send_mail_to_recipient[i]==3){

					jQuery(this).find('.email_required').html('*');
					if(gift_card_recipient_email[i].trim()=='' || gift_card_recipient_email[i]==null || !validateEmail(gift_card_recipient_email[i])){
						jQuery(this).find('[name="gift_card_recipient_email"]').css('border-color','red');
						jQuery(this).find('[name="gift_card_recipient_email"]').focus();	
						jQuery(this).find('.gift_card_recipient_email_span').html('Netfang viðtakanda er ekki rétt.');
						formError++;
						jQuery( "#productHeader-"+myID ).trigger( "click" );
					}
					else{
						jQuery(this).find('[name="gift_card_recipient_email"]').css('border','0px');
						jQuery(this).find('.gift_card_recipient_email_span').html('');
						jQuery(this).find('.email_required').html('');
					}
				} else {
					jQuery(this).find('.email_required').html('');
					jQuery(this).find('.gift_card_recipient_email_span').html('');
					jQuery( "#productGift-"+myID ).find("span.email_required").addClass("hidden");
				}


				if(send_mail_to_recipient[i]!='1'){
					
					jQuery(this).find('.requirePhone').html('*');
					if(gift_card_phone[i].trim()=='' || gift_card_phone[i]==null || !(jQuery.isNumeric(gift_card_phone[i])) || gift_card_phone[i].length!=7){
						jQuery(this).find('[name="gift_card_phone"]').css('border-color','red');
						jQuery(this).find('[name="gift_card_phone"]').focus();	
						jQuery(this).find('.gift_card_phone_span').html('Sími viðtakanda er ekki réttur.');
						formError++;
						jQuery( "#productHeader-"+myID ).trigger( "click" );
					}
					else{
						jQuery(this).find('[name="gift_card_phone"]').css('border','0px');
						jQuery(this).find('.gift_card_phone_span').html('');
					}
				}
				else{
					jQuery(this).find('.requirePhone').html('');
					jQuery(this).find('[name="gift_card_phone"]').css('border','0px');
					jQuery(this).find('.gift_card_phone_span').html('');
				}
				
				if(gift_card_sender_name[i].trim()=='' || gift_card_sender_name[i]==null){
					jQuery(this).find('[name="gift_card_sender_name"]').css('border-color','red');
					jQuery(this).find('[name="gift_card_sender_name"]').focus();	
					jQuery(this).find('.gift_card_sender_name_span').html('Nafn sendanda er ekki rétt.');
					formError++;
					jQuery( "#productHeader-"+myID ).trigger( "click" );
				}
				else{
					jQuery(this).find('[name="gift_card_sender_name"]').css('border','0px');
					jQuery(this).find('.gift_card_sender_name_span').html('');
				}
		
				i++;

			});

			if(formError>0){
				return false;
			}
			else{
				jQuery("#submitGiftCardFormBtn").html('Í vinnslu').prop("disabled",true).css('background-color','gray');
				jQuery.ajax({
					type : "post",
					dataType : "json",
					url : gtwAjax.ajaxurl,
					data : {
						action: "gtw_add_to_cart", 
						product_id : product_id,
						gift_card_date : gift_card_date,
						gift_card_time_hour : gift_card_time_hour,
						gift_card_time_minute : gift_card_time_minute,
						gift_card_recipient_name : gift_card_recipient_name,
						gift_card_recipient_email : gift_card_recipient_email,
						gift_card_phone : gift_card_phone,
						gift_card_custome_message : gift_card_custome_message,
						gift_card_sender_name : gift_card_sender_name,
						send_mail_to_recipient : send_mail_to_recipient,
						gift_card_image : gift_card_image,
						gift_card_amount : gift_card_amount
					},
					success: function(response) {
						//console.log(response);
						if(response.type == "success") {
							jQuery("#submitGiftCardFormBtn").html('Vara bætt við í körfu').prop("disabled",true).css('background-color','gray');
							//jQuery(".aUpdateCart").show();
							// window.location.replace(WPURLS.siteurl+"/cart/");
							window.location.replace("<?php echo wc_get_cart_url(); ?>");
						}
						else {
							response.errorMessages.forEach(function callback(value, index) {
								console.log(value.errorText);
								jQuery('#errorText').append('<span class="errorSpan">'+value.errorText+'</span><br/>');
							});
						}
					}
				})
			}
		})
	});
</script>

<?php 

do_action( 'woocommerce_after_single_product' ); ?>



