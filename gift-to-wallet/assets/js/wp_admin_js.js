
// console.log("get ajax url  = =  "+ajax_object.ajax_url);

// Ajax Call wc_gift_card_setting()
function wc_gift_card_setting(){
    ///let useGift  = jQuery('.gift-card-form').find(':checkbox:checked').val();
    
    let logo         = jQuery('.gift-card-form').find("input[name='logo']").val();
    let qr           = jQuery('.gift-card-form').find("input[name='qr']").val();
    let bar          = jQuery('.gift-card-form').find("input[name='bar']").val();
    let btnText      = jQuery('.gift-card-form').find("input[name='btn_text']").val();
    let btnTextColor = jQuery('.gift-card-form').find("input[name='btn_t_color']").val();
    let btnBGColor   = jQuery('.gift-card-form').find("input[name='btn_bg_color']").val();
    let btnHoverBGC  = jQuery('.gift-card-form').find("input[name='btn_h_bg_color']").val();
    let bottomText   = jQuery('.gift-card-form').find("input[name='buttom_text']").val();
    let pdfDateFormat= jQuery('.gift-card-form').find("input[name='pdf_date_formate']").val();

    let gftPopupCancl= jQuery('.gift-card-form').find("input[name='gift_popup_cancel_btn']").val();
    let gftPopupSave = jQuery('.gift-card-form').find("input[name='gift_popup_save_btn']").val();
    let gtwUploadIgm = jQuery('.gift-card-form').find("input[name='gtw_upload_img_btn_text']").val();
    let gtwChooseImg = jQuery('.gift-card-form').find("input[name='gtw_choose_img_btn_text']").val();

    let checkboxEn   = jQuery('.gift-card-form').find("input[name='enable_demo_mode']").prop('checked');
    let checkGImgs   = jQuery('.gift-card-form').find("input[name='enable_product_gallery_img']").prop('checked'); // checked
    let giftCardEdit = jQuery('.gift-card-form').find("input[name='enable_gift_card_edit']").prop('checked'); // checked

    // let giftCardPr   = jQuery('.gift-card-form').find("input[name='gift_card_prices']").val();

    // console.log(giftCardPr);

    let label1 = jQuery('.gift-card-form').find("input[name='l_img']").val();
    let label2 = jQuery('.gift-card-form').find("input[name='l_price']").val();
    let label3 = jQuery('.gift-card-form').find("input[name='l_delivery']").val();
    let label4 = jQuery('.gift-card-form').find("input[name='l_info']").val();
    let label5 = jQuery('.gift-card-form').find("input[name='l_info_text']").val();
    let label6 = jQuery('.gift-card-form').find("input[name='l_you']").val();
    let label7 = jQuery('.gift-card-form').find("input[name='l_u_text']").val();


    let label8 = jQuery('.gift-card-form').find("input[name='gtw_c_eamil']").val();
    let label9 = jQuery('.gift-card-form').find("input[name='gtw_c_password']").val();


    // content = tinymce.activeEditor.getContent();

            

    let label10 = tinymce.editors['pdfhtml'].getContent();
    let label11 = tinymce.editors['email_send_user'].getContent();
    let label12 = jQuery('.gift-card-form').find("input[name='gtw_email_subject']").val();
    let label13 = jQuery('.gift-card-form').find("textarea[name='use_send_sms']").val();
    let label14 = jQuery(" #gtw_expiry_date ").find(":selected").val(); //.val(); //.text() .prop('selected')
    
    console.log("Select year = = "+label14);
    

    jQuery('#smsGift').html(`<p class="info">Please wait! we are processing...</p>`);

    // WP Ajax Call echo admin_url('admin-ajax.php');
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajax_object.ajax_url,
        data: {
            action: 'gtw_setting_save',
            urlLogo: logo,
            urlQR: qr,
            urlBar: bar,
            btnText: btnText,
            btnTextColor: btnTextColor,
            btnBGColor: btnBGColor,
            btnHoverBGC: btnHoverBGC,
            bottomText: bottomText,
            enableDemoMode: checkboxEn,
            showGalleryImages: checkGImgs,
            enable_gift_card_edit: giftCardEdit,
            pdf_date_formate: pdfDateFormat,
            gift_popup_cancel_btn: gftPopupCancl,
            gift_popup_save_btn: gftPopupSave,
            gtw_upload_img_btn_text: gtwUploadIgm,
            gtw_choose_img_btn_text: gtwChooseImg,
            // giftCardPrices: giftCardPr,
            l_img: label1,
            l_price: label2,
            l_delivery: label3,
            l_info: label4,
            l_info_text: label5,
            l_you: label6,
            l_u_text: label7,
            l_u_email: label8,
            l_u_pass: label9,
            l_u_pdf: label10,
            l_user_email: label11,
            l_user_email_sub: label12,
            l_u_sms: label13,
            gtw_expiry_date: label14
        },
        success: function(response) {

            jQuery('#smsGift').html(` `);
            // console.log(typeof response);
            // let obj = JSON.parse(response);
            // console.log(response);

            if (response.status == 'ok') {
                jQuery('#smsGift').html(`<p class="success">${response.message}</p>`);
            } else {
                jQuery('#smsGift').html(`<p class="error">Setting information save fail. Some problem</p>`);
                // alert("This action only for admin.");
                // console.log(response.message);
                
            }
        }
    });
}



// ajax_object.ajax_url,
// Generate Token submit function onclick="gtwGenerateTokenSubmit()"
function gtwGenerateTokenSubmit() {

    let emailId  = jQuery("input[name=gtw_c_eamil]").val();
    let password = jQuery("input[name=gtw_c_password]").val();

    // WP Ajax Call
    jQuery('#smsToken').html(`<p class="info"> <b>Wait..</b> processing...</p>`);
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajax_object.ajax_url, // php echo admin_url('admin-ajax.php');
        data: {
            action: 'gtw_generate_token',
            email: emailId,
            pass: password
        },
        success: function(response) { 

            if ( ! response || response.error ) return;
            
            jQuery('#smsToken').html(` `);

            if(response.status == 'ok') { 

                jQuery('#smsToken').html(`<p class="success"> ${response.message}</p>`);

            } else { 

                jQuery('#smsToken').html(`<p class='error'>${response.message}</p>`);

            }
    
        }
    });
 }
 


