function giftCardCustomPriceEnable(data){
    let parent = jQuery(data).parent().parent().parent().parent().parent().parent();
    jQuery(parent).find("input[name=gift_card_default_price]:checked").prop("checked",false);
    jQuery(data).removeAttr("readonly");
    jQuery(parent).find(".gift-card-amount-error").html("<p>Vinsamlegast sláið inn upphæðina sem þið viljið gefa</p>");
}

function giftCardCustomPriceChange(data){
    let parent = jQuery(data).parent().parent().parent().parent().parent().parent();
    let price=jQuery(data).val();
    let min=jQuery(data).attr("data-min");
    if(parseInt(price)<parseInt(min)){
        jQuery(data).addClass("error");
        jQuery(parent).find(".gift-card-amount-error").html("<p>Custom amount should be greater than minimum amount</p>");
        jQuery(parent).find(".single_add_to_cart_button").prop("disabled",true);
        giftCardAmountShow(parent,min);
    }else{
        jQuery(parent).find(".gift-card-amount-error").html("");
        jQuery(parent).find(".single_add_to_cart_button").prop("disabled",false);
        giftCardAmountShow(parent,price);
    }

    
}

function giftCardDefaultPrice(data){
    let parent = jQuery(data).parent().parent().parent().parent().parent().parent();
    let price=jQuery(data).val();
    giftCardAmountShow(parent,price);
    jQuery(parent).find("input[name=custom_price]").val("");
    jQuery(parent).find("input[name=custom_price]").attr("readonly",true);
}

function giftCardAmountShow(parent,amount){
    jQuery(parent).find("input[name=gift_card_amount]").val(amount);
    let value = accounting.formatMoney(amount, {
        symbol: 'kr.',
        decimal: ',',
        thousand: '.',
        precision: '0',
        format: '%v %s'
      });
      jQuery(parent).find(".gift_card_value").html(value);  
}

function giftCardMessagePreview(data){
    //var message = jQuery(data).val();
    var message = data.value;
    //message = jQuery.parseHTML( message.replace(/(<([^>]+)>)/gi, "").replace(/\n/g, '<br/>') );
    data.closest(".entry-summary").previousElementSibling.querySelector(".gift_card_message_text").innerHTML=message.replace(/(<([^>]+)>)/gi, "").replace(/\n/g, '<br/>');
}

function giftCardCustomAmountFocus(data){
    let parent = jQuery(data).parent().parent().parent().parent().parent().parent();
    let price=parseInt(jQuery(data).val());
    let min=parseInt(jQuery(data).attr("data-min"));
    if(price>0){
        if(price<min){
            jQuery(parent).find("input[name=gift_card_default_price].gift_card_default_amount").prop("checked",true);
            giftCardAmountShow(parent,min);
            jQuery(data).val("");
            jQuery(data).attr("readonly",true); 
            jQuery(parent).find(".gift-card-amount-error").html("");
            jQuery(parent).find(".single_add_to_cart_button").prop("disabled",false);
        }
    }else{
        jQuery(parent).find("input[name=gift_card_default_price].gift_card_default_amount").prop("checked",true);
        jQuery(data).val("");
        jQuery(data).attr("readonly",true);
        jQuery(parent).find(".gift-card-amount-error").html("");
        jQuery(parent).find(".single_add_to_cart_button").prop("disabled",false);
    }
}

function selectThisGiftCardImage(data){
    
    let parent = jQuery(data).parent().parent().parent().parent().parent().parent().parent().parent();
    jQuery(parent).find("img.active").removeClass("active");
    let img = jQuery(data).attr("src");
    let cls=jQuery(data).attr("data-class");
    jQuery(data).addClass("active");
    //jQuery("input[name=gift_card_image]").val(img);
    //jQuery("#gift_card_image").attr("src",img);
    
    
    data.closest(".img_thumbnail").nextElementSibling.value=img;
    data.closest(".entry-summary").previousElementSibling.querySelector(".gift_card_image").src=img;
}

function openImgCat(data, evt, cityName) {
    jQuery(data).parent().find('.active').removeClass("active");
    jQuery(data).addClass("active");

    jQuery(data).parent().parent().parent().find('div.tabcontent').hide();
    jQuery(data).parent().parent().parent().find('div.'+cityName+'').show();
}


  function deliveryDate(data) {
    let parent = jQuery(data).parent().parent().parent().parent().parent().parent().parent().parent().parent();
    var deliveryDate = jQuery(data).val();
    jQuery(parent).find('._Date').text(deliveryDate.split("-").reverse().join("/"));
  }

function toggleDateDiv(checkbox) {
    if(checkbox.checked == true){
        checkbox.closest(".toggleDateDivClass").nextElementSibling.style.display='block';
    }else{
        checkbox.closest(".toggleDateDivClass").nextElementSibling.style.display='none';
   }
}





// console.log('localStorageVariable = = '+gtwAjax.localStVal);

var gparent='';
let localStVar = gtwAjax.localStVal;

let oldImages=localStorage.getItem(localStVar);
if(oldImages){
    var images =JSON.parse(oldImages);
}else{
    var images =[];
}


let croppi;

// Crop image function
jQuery(document).on('click', '.save-modal', function(ev) {

    jQuery('.modal-crop-img-wrap').block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });
    // jQuery('.modal-crop-img-wrap').unblock();

    croppi.croppie('result', {

        type: 'base64',
        format: 'jpeg',
        // size: 'original'
        size: {
            width: 1000,
            height: 800
        }

    })
    .then(function (resp) {
        
        // WP Ajax Call with submit function
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: gtwAjax.ajaxurl,
            data: {
                action: 'gtw_img_upload_wp_media',
                image: resp
                
            },
            success: function(response) { 
                
                if ( ! response || response.error ) return; 
                
                $('.modal-crop-img-wrap').unblock();
                
                let sNo = $(gparent).attr("id");

                let cardImg = {
                    url: response.data,
                    s_no: sNo,
                };
                if(images.length>0){
                    let oldImage=images.find(u => u.s_no === sNo);
                    if(oldImage){
                        oldImage.url=response.data;
                    }else{
                        images.push(cardImg);
                    }
                    /*let push = true;
                    images.map((item,index)=>{
                        if(item.s_no===sNo){
                            images[index].url=response.data;
                            push = false;
                        }
                    });

                    if(push)images.push(cardImg);*/

                }else{
                    images.push(cardImg);
                }
                // console.log(images);
                const imagesJSON = JSON.stringify(images);
                localStorage.setItem(localStVar, imagesJSON);

                // Get local storage img
                const imgLocalStorage = localStorage.getItem(localStVar);
                const imageAll = JSON.parse(imgLocalStorage);

                const singleImg = imageAll.find(u => u.s_no === sNo);
                if(singleImg)$(gparent).find("img.confirm-img").attr('src', singleImg.url);
                //console.log(singleImg.url);
                
                $('#imageModalContainer').hide('fast', croppi_destroy);
                $(gparent).find("img.confirm-img").click();
            }
        });

    });
});

    

// start Crop image
$(document).on('click', '.upload_my_img', function () {
    // console.log('localStorageVariable = = '+gtwAjax.localStVal);

    let localStVar = gtwAjax.localStVal;

    let oldImages=localStorage.getItem(localStVar);
    if(oldImages){
        var images =JSON.parse(oldImages);
    }else{
        var images =[];
    }


    let uploadImgBtn = $(this).parent().parent().parent().parent().parent().parent();
    //document.getElementById('selectedFile').click();
    let serialNo = $(uploadImgBtn).attr("id");
    // console.log(serialNo);

    // Get local storage img
    const imgAll = images;
    //console.log(imgAll);
    if(imgAll.length>0){
        const sImg = imgAll.find(u => u.s_no === serialNo);

        // console.log(sImg);
        
        if(sImg.s_no === serialNo){
            $(uploadImgBtn).find("img.confirm-img").attr('src', sImg.url);
            // console.log(sImg.url);
        } else {
            $(uploadImgBtn).find("img.confirm-img").attr('src', '');
        }

    }else{
        $(uploadImgBtn).find("img.confirm-img").attr('src', '');
    }
    
});

$(document).on('click', '.upload-aphoto', function () {
        gparent=$(this).parent().parent().parent().parent().parent().parent().parent().parent();
        //document.getElementById('selectedFile').click();
        $(gparent).find("input.selectedFile").click();

        
    }

);

function gtw_file_change(data) {
    if (data.files[0]==undefined) return;
    $('#imageModalContainer').show('fast', modal_shown);
    let reader=new FileReader();

    reader.addEventListener("load", function () {
            window.src=reader.result;
            $(gparent).find("input.selectedFile").val('');



        }, false);

    if (data.files[0]) {
        reader.readAsDataURL(data.files[0]);
    }
}

// let croppi;

function modal_shown() {
    let width=document.getElementById('crop-image-container').offsetWidth - 20;
    jQuery('#crop-image-container').height((width - 80) + 'px');

    croppi=jQuery('#crop-image-container').croppie( {
            viewport: {
                width: 300, // width
                height: 240
            },
        }
    );
    jQuery('.modal-body1').height(document.getElementById('crop-image-container').offsetHeight + 50 + 'px');

    croppi.croppie('bind', {
            url: window.src,
        }

    ).then(function () {
            croppi.croppie('setZoom', 0);
        }

    );
}

jQuery('#imageModalContainer').on('hidden.bs.modal', function() {});

function croppi_destroy() {
    croppi.croppie('destroy');
}


// jQuery(".closeCrop").click(function() {
//     console.log('clicked Now...');
    
//     jQuery('#imageModalContainer').hide('fast', croppi_destroy);
// });

jQuery(document).on('click', '.closeCrop', function() {
    
    jQuery('#imageModalContainer').hide('fast', croppi_destroy);

});








function cart_popup_open_action(index){
   
    jQuery("#"+index+"").addClass('model-open');
}

jQuery(document).on("click", ".close-btn, .bg-overlay, .cancel-btn", function () {
    jQuery(".popup-model-main").removeClass('model-open');
});



jQuery(document).on("change","label.sms_email",function(){
    let parent = jQuery(this).parent().parent();
    // let value = jQuery(parent).find("input[name=send_recipient]:checked").val();
    let value = jQuery(parent).find("input[type=radio]:checked").val();
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




function validEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}

// ajax for Cart page popup with Form submit function onclick='cart_popup_update_btn()'

function cart_popup_update_btn(cartKey) {
    // let popup = jQuery(".pop-up-content-wrap");
    let emailId = jQuery("input[name=gift_card_email_"+cartKey+"]").val();
    let userName = jQuery("input[name=gift_card_name_"+cartKey+"]").val();
    let userPhone = jQuery("input[name=gift_card_phone_"+cartKey+"]").val();
    let senderName = jQuery("input[name=gift_card_sender_name_"+cartKey+"]").val();
    let userSMS = jQuery("textarea[name=gift_card_custome_message_"+cartKey+"]").val();

    // let recipientVal = jQuery(".pop-up-content-wrap .radio-buttob-form").find("input[name=send_recipient_"+cartKey+"]:checked").val();
    // let recipientVal = jQuery("input[name=send_recipient_"+cartKey+"]:checked").val();

    let cartDate = jQuery("input[name=gift_card_date_"+cartKey+"]").val();
    let cartHour = jQuery("select[name=gift_card_time_hour_"+cartKey+"] option:selected").val();
    let cartMinit = jQuery("select[name=gift_card_time_minit_"+cartKey+"] option:selected").val();
    // console.log(cartDate);


    send_mail_to_recipient  =  jQuery("input[name=send_recipient_"+cartKey+"]:checked").val();
    // send_mail_to_recipient  =  jQuery('[name="send_recipient_"+cartKey+"]:checked').val();

    // console.log(dddd);


    // Vallidation
    if(userName.trim()=='' || userName==null){
        jQuery("input[name=gift_card_name_"+cartKey+"]").css('border-color','red');
        jQuery("input[name=gift_card_name_"+cartKey+"]").focus();	
        jQuery("#"+cartKey+" .pop-up-content-wrap").find('.gift_card_name_span').html('Nafn viðtakanda er ekki rétt.');
        // jQuery("#"+cartKey+" .pop-up-content-wrap").find('.gift_card_sender_name_span').html('Nafn viðtakanda er ekki rétt.');
        
        return false;
    }
    else{
        jQuery("input[name=gift_card_name_"+cartKey+"]").css('border','0px');
        jQuery("#"+cartKey+" .pop-up-content-wrap").find('.gift_card_name_span').html(' ');
    } 
    // name vallidation end

    if(senderName.trim()=='' || senderName==null){
        jQuery("input[name=gift_card_sender_name_"+cartKey+"]").css('border-color','red');
        jQuery("input[name=gift_card_sender_name_"+cartKey+"]").focus();	
        jQuery("#"+cartKey+" .pop-up-content-wrap").find('.gift_card_sender_name_span').html('Nafn sendanda er ekki rétt.');

        return false;
    }
    else{
        jQuery("input[name=gift_card_sender_name_"+cartKey+"]").css('border','0px');
        jQuery("#"+cartKey+" #"+cartKey+" .pop-up-content-wrap").find('.gift_card_sender_name_span').html('');
    } 
    // sender name vallidation end


    if(send_mail_to_recipient==1 || send_mail_to_recipient==3){

        if(emailId.trim()=='' || emailId==null || !validEmail(emailId)){ // validateEmail
            jQuery("input[name=gift_card_email_"+cartKey+"]").css('border-color','red');
            jQuery("input[name=gift_card_email_"+cartKey+"]").focus();	
            jQuery("#"+cartKey+" .gift_card_email_span").html('Netfang viðtakanda er ekki rétt.');
            jQuery("#"+cartKey+" .email_required").removeClass("hidden");
        
            return false;
        }
        else{
            jQuery("input[name=gift_card_email_"+cartKey+"]").css('border','0px');
            jQuery("#"+cartKey+" .gift_card_email_span").html('');
            
        }

    }else{
        jQuery("#"+cartKey+" .pop-up-content-wrap").find('.gift_card_email_span').html('');
        jQuery("#"+cartKey+" .email_required").addClass("hidden");
    }


    if(send_mail_to_recipient==2 || send_mail_to_recipient==3){

        if(userPhone.trim()=='' || userPhone==null || !(jQuery.isNumeric(userPhone)) || userPhone.length!=7){
            jQuery("input[name=gift_card_phone_"+cartKey+"]").css('border-color','red');
            jQuery("input[name=gift_card_phone_"+cartKey+"]").focus();	
            jQuery("#"+cartKey+" .pop-up-content-wrap").find('.gift_card_phone_span').html('Símanúmer viðtakanda er ekki rétt');
            
            jQuery("#"+cartKey+" .pop-up-content-wrap").find(".phone_required").removeClass("hidden");
            // jQuery( "#productHeader-"+myID ).trigger( "click" );
        
            return false;
        }
        else{
            jQuery("input[name=gift_card_phone_"+cartKey+"]").css('border','0px');
            jQuery("#"+cartKey+" .pop-up-content-wrap").find('.gift_card_phone_span').html('');
        }

    }else{
        jQuery("#"+cartKey+" .pop-up-content-wrap").find('.gift_card_phone_span').html('');
        jQuery("#"+cartKey+" .pop-up-content-wrap").find(".phone_required").addClass("hidden");
    }

    // email & phone vallidation end






    // WP Ajax Call with submit function
    jQuery("#"+cartKey).find('#sms').html(`<h3><span class='loding'>Í vinnslu... </span></h3>`);
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url:  gtwAjax.ajaxurl,
        data: {
            action: 'gtw_in_cart_page_popup_update_data',
            email: emailId,
            cartItemKey: cartKey,
            name: userName,
            sName: senderName,
            phone: userPhone,
            message: userSMS,
            date: cartDate,
            hour: cartHour,
            minit: cartMinit,
            requireCheck: send_mail_to_recipient
        },
        success: function(response) { 
            if ( ! response || response.error ) return;
            jQuery("#"+cartKey).find('#sms').html(` `);
            if(response.status == 'ok') { 
                jQuery("#"+cartKey).find('#sms').html(`${response.messages}`);
                jQuery(".popup-model-main").removeClass('model-open');
                // jQuery('from.woocommerce-cart-form')[0].submit();
                jQuery('button[name=update_cart]').click();
            } else { 
                jQuery("#"+cartKey).find('#sms').html(`<p class='error'>Some problam</p>`);
            }

        }
    });


}
