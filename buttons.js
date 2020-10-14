$(document).ready(function() {
    var ps_sendsms_buttons = document.getElementsByClassName('ps_sendsms_button');
    for (var i = 0; i < ps_sendsms_buttons.length; i++) {
        var ps_sendsms_button = ps_sendsms_buttons[i];
        ps_sendsms_button.onclick = function () {
            // append text
            var element = this.parentElement.nextElementSibling.getElementsByTagName('textarea')[0];
            element.value += this.innerHTML;
            // trigger sms count
            var text_length = element.value.length;
            var text_remaining = 160 - text_length;
            element.nextElementSibling.innerHTML = text_remaining + ' caractere ramase';
        };
    }
});
