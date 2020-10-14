$(document).ready(function() {
    var ps_sendsms_content = document.getElementsByClassName('ps_sendsms_content');
    for (var i = 0; i < ps_sendsms_content.length; i++) {
        var ps_sendsms_element = ps_sendsms_content[i];
        ps_sendsms_element.onkeyup = function () {
            var text_length = this.value.length;
            var text_remaining = 160 - text_length;
            this.nextElementSibling.innerHTML = text_remaining + ' caractere ramase';
        };
        // activate on page load
        var text_length = ps_sendsms_element.value.length;
        var text_remaining = 160 - text_length;
        ps_sendsms_element.nextElementSibling.innerHTML = text_remaining + ' caractere ramase';
    }
});
