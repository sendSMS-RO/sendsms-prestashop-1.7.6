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
    //phptext = <?php echo json_encode($vars); ?>;
    var ps_sendsms_content = document.getElementsByClassName('ps_sendsms_content');
    for (var i = 0; i < ps_sendsms_content.length; i++) {
        var ps_sendsms_element = ps_sendsms_content[i];
        ps_sendsms_element.onkeyup = function () {
            var text_length = this.value.length;
            var text_remaining = 160 - text_length;
            //console.log(sendsms_var_name);
            this.nextElementSibling.innerHTML = text_remaining + sendsms_var_name;
        };
        // activate on page load
        var text_length = ps_sendsms_element.value.length;
        var text_remaining = 160 - text_length;
        ps_sendsms_element.nextElementSibling.innerHTML = text_remaining + sendsms_var_name;
    }
});
