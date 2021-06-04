/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 *
 *  @author    Radu Vasile Catalin
 *  @copyright 2020-2020 Any Media Development
 *  @license   AFL 
 */
$(document).ready(function() {
    //check price
    $('#check-price').on('click', function() {
        all = $('#sendsms_all_').is(':checked');
        if (all) {
            if (typeof jQuery('select[id=sendsms_phone_numbers] > option') === "undefined" || jQuery('select[id=sendsms_phone_numbers] > option').val() === null) {
                phones = 0;
            } else {
                phones = jQuery('select[id=sendsms_phone_numbers] > option').length;
            }
        } else {
            if (typeof jQuery('#sendsms_phone_numbers').val() === "undefined" || jQuery('#sendsms_phone_numbers').val() === null) {
                phones = 0
            } else {
                phones = jQuery('#sendsms_phone_numbers').val().length;
            }
        }
        var content = document.getElementById('sendsms_message');
        var lenght = content.value.length;
        var messages = lenght / 160 + 1;
        if (lenght > 0) {
            if (lenght % 160 === 0) {
                messages--
            }
            messages = Math.floor(messages);
            price = sendsms_price_per_phone;
            if (price > 0) {
                //TO DO translation + show output to user
                finalPrice = parseFloat(messages * price * phones).toPrecision(4) + " euro.";
                alert(sendsms_text_estimate_price + finalPrice);
            } else {
                alert(sendsms_text_send_first)
            }
        } else {
            alert(sendsms_text_no_message)
        }
    })
    $('#send-campaign').on('click', function() {
        jQuery('#send-campaign').contents().filter(function() {
            return this.nodeType == Node.TEXT_NODE;
        })[0].nodeValue = sendsms_text_sending;
        jQuery('#send-campaign').attr('disabled', 'disabled');
        all = $('#sendsms_all_').is(':checked');
        content = $('#sendsms_message').val();
        phones = "";
        if (!all) {
            if (jQuery('#sendsms_phone_numbers').val() !== null) {
                phones = jQuery('#sendsms_phone_numbers').val().join("|");
            }
        }
        $.ajax({
            type: 'POST',
            data: {
                method: "sendCampaign",
                all: all,
                content: content,
                phones: phones,
                sendsms_security: sendsms_security,
            },
            dataType: 'json',
            success: function(data) {
                if (data.hasError) {
                    alert(data.error);
                } else {
                    alert(data.response);
                }
                jQuery('#send-campaign').contents().filter(function() {
                    return this.nodeType == Node.TEXT_NODE;
                })[0].nodeValue = sendsms_text_send;
                jQuery('#send-campaign').removeAttr('disabled');
            }
        });
    })
})