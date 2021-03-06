{*
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement.
*
*
* @author Radu Vasile Catalin
* @copyright 2020-2020 Any Media Development
* @license AFL
*}

<form id="sendsms_order_form" class="defaultForm form-horizontal" method="post" novalidate="">
    <div id="formSendSmsPanel" class="panel">
        <div class="panel-heading">
            {l s='Send SMS' mod='pssendsms'}
        </div>
        {if isset($sendsms_msg)}
            <div class="alert {if ! $sendsms_error}alert-success{else}alert-danger{/if}">
                {$sendsms_msg|escape:'htmlall':'UTF-8'}
            </div>
        {/if}
        <div class="form-wrapper">
            <div class="form-group">
                <label class="control-label col-lg-3 required">
                    {l s='Phone number' mod='pssendsms'}
                </label>
                <div class="col-lg-9">
                    <input type="text" name="sendsms_phone" id="sendsms_phone" value="" class="" size="40" required="required">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Short url? (Please use only urls that start with https:// or http://)' mod='pssendsms'}
                </label>
                <div class="col-lg-9">
                    <input type="checkbox" name="sendsms_url" id="sendsms_url" value="on" class="" size="10">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Add an unsubscribe link? (You must specify {gdpr} key message. {gdpr} key will be replaced automaticaly with confirmation unique confirmation link.)' mod='pssendsms'}
                </label>
                <div class="col-lg-9">
                    <input type="checkbox" name="sendsms_gdpr" id="sendsms_gdpr" value="on" class="" size="10">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3 required">
                    {l s='Message' mod='pssendsms'}
                </label>
                <div class="col-lg-9">
                    <textarea name="sendsms_message" id="sendsms_message" rows="7" class="textarea-autosize" style="overflow: hidden; word-wrap: break-word; resize: none; height: 133px;" maxlength="160"></textarea>
                    <p>{l s='160 remaining characters' mod='pssendsms'}</p>
                    <script type="text/javascript">
                        var ps_sendsms_content = document.getElementById('sendsms_message');
                        ps_sendsms_content.onkeyup = function() {
                            var text_length = this.value.length;
                            var text_remaining = 160 - text_length;
                            this.nextElementSibling.innerHTML = text_remaining + '{l s=' remaining characters' mod='pssendsms'}';
                        }
                    </script>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <button type="submit" value="1" id="sendsms_test_form_submit_btn" name="submitsendsms_order" class="button">
                <i class="process-icon-save"></i> {l s='Send' mod='pssendsms'}
            </button>
        </div>
    </div>
</form>
