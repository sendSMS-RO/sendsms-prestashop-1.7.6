{*
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
 *}

{l s='Message: ' mod='pssendsms'}{$status_name}<br /><br />{l s='Available variables:' mod='pssendsms'}
{literal}
    <button type="button" class="ps_sendsms_button">{billing_first_name}</button>
    <button type="button" class="ps_sendsms_button">{billing_last_name}</button>
    <button type="button" class="ps_sendsms_button">{shipping_first_name}</button>
    <button type="button" class="ps_sendsms_button">{shipping_last_name}</button>
    <button type="button" class="ps_sendsms_button">{tracking_number}</button>
    <button type="button" class="ps_sendsms_button">{order_number}</button>
    <button type="button" class="ps_sendsms_button">{order_date}</button>
    <button type="button" class="ps_sendsms_button">{order_total}</button>
{/literal}
<br />
{l s='Leave the field blank if you do not want to send SMS for this status.' mod='pssendsms'}