<?php
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

include 'csrf.class.php';
class AdminSendTest extends ModuleAdminController
{
    protected $index;
    private $csrf;
    public function __construct()
    {
        parent::__construct();

        $this->csrf = new Csrf();
        $this->table = 'sendsms_test';
        $this->bootstrap = true;
        $this->meta_title = $this->module->l('Send a test SMS');
        $this->display = 'add';

        $this->context = Context::getContext();


        $this->index = count($this->_conf) + 1;
        $this->_conf[$this->index] = $this->module->l('The message was sent');
    }

    public function renderForm()
    {
        $token_id = $this->csrf->getTokenId();
        $token_value = $this->csrf->getToken();
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->module->l('Send a test')
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => $token_id
                ),
                array(
                    'type' => 'text',
                    'label' => $this->module->l('Phone number'),
                    'name' => 'sendsms_phone',
                    'size' => 40,
                    'required' => true
                ),
                array(
                    'type' => 'textarea',
                    'rows' => 7,
                    'label' => $this->module->l('Messsage'),
                    'name' => 'sendsms_message',
                    'required' => true,
                    'class' => 'ps_sendsms_content',
                    'desc' => $this->module->l('160 characters remaining')
                ),
                array(
                    'type' => 'checkbox',
                    'label' => $this->l('Short url?'),
                    'name' => 'sendsms_url',
                    'required' => false,
                    'values' => array(
                        'query' => array(
                            array(
                                'url' => null,
                            )
                        ),
                        'id' => 'url',
                        'name' => 'url'
                    ),
                    'desc' => 'Please use only urls that start with https:// or http://'
                ),
                array(
                    'type' => 'checkbox',
                    'label' => $this->l('Add an unsubscribe link?'),
                    'name' => 'sendsms_gdpr',
                    'required' => false,
                    'values' => array(
                        'query' => array(
                            array(
                                'gdpr' => null,
                            )
                        ),
                        'id' => 'gdpr',
                        'name' => 'gdpr'
                    ),
                    'desc' => 'You must specify {gdpr} key message. {gdpr} key will be replaced automaticaly with confirmation unique confirmation link. If {gdpr} key is not specified confirmation link will be placed at the end of message.'
                )
            ),
            'submit' => array(
                'title' => $this->module->l('Send'),
                'class' => 'btn btn-default'
            )
        );

        $this->fields_value[$token_id] = $token_value;


        Media::addJsDefL('sendsms_var_name', $this->module->l(' remaining characters'));

        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/count.js'
        );

        return parent::renderForm();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAdd' . $this->table)) {
            if ($this->csrf->checkValid()) {
                $phone = (string)(Tools::getValue('sendsms_phone'));
                $message = (string)(Tools::getValue('sendsms_message'));
                $phone = Validate::isPhoneNumber($phone) ? $phone : "";
                $short = Tools::getValue('sendsms_url_') ? true : false;
                $gdpr = Tools::getValue('sendsms_gdpr_') ? true : false;

                if (!empty($phone) && !empty($message)) {
                    $this->module->sendSms($message, 'test', $phone, $short, $gdpr);
                    Tools::redirectAdmin(self::$currentIndex . '&conf=' . $this->index . '&token=' . $this->token);
                } else {
                    if (empty($phone)) {
                        $this->errors[] = Tools::displayError($this->module->l('The phone number is not valid'));
                    } elseif (empty($message)) {
                        $this->errors[] = Tools::displayError($this->module->l('Please enter e message'));
                    }
                }
            } else {
                $this->errors[] = Tools::displayError($this->module->l('Invalid CSRF token!'));
            }
        }
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_title = $this->module->l('Send a test SMS');
        parent::initPageHeaderToolbar();
        unset($this->toolbar_btn['new']);
    }
}
