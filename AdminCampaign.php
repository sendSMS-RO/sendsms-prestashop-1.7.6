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

class AdminCampaign extends ModuleAdminController
{
    protected $index;
    public $cookieManager;
    public function __construct()
    {
        parent::__construct();
        $this->cookieManager = $this->context->cookie;
        $this->bootstrap = true;

        $this->context = Context::getContext();
        $this->meta_title = $this->module->l('SMS Campaign');
        $this->table = 'sendsms_campaign';
        $this->display = 'add';

        $error = strval(Tools::getValue('error'));
        if (!empty($error)) {
            $this->errors[] = Tools::displayError($this->module->l('The phone number is not valid'));
        }

        $sent = (string)(Tools::getValue('sent'));
        if (!empty($sent)) {
            $this->confirmations = array($this->module->l('The message was sent'));
        }

        $this->index = count($this->_conf) + 1;

        $this->_conf[$this->index] = $this->module->l('Customers have been filtered');
    }

    public function renderForm()
    {
        $products = array();
        $productsDb = $this->getListOfProducts();
        $products = array_merge($products, $productsDb);

        $states = array();
        $statesDb = $this->getListOfBillingStates();
        $states = array_merge($states, $statesDb);

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->module->l('Filtering customers')
            ),
            'input' => array(
                array(
                    'type' => 'date',
                    'label' => $this->module->l('Order start period'),
                    'name' => 'sendsms_period_start',
                    'required' => false,
                    'autocomplete' => 'off'
                ),
                array(
                    'type' => 'date',
                    'label' => $this->module->l('Order end period'),
                    'name' => 'sendsms_period_end',
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->module->l('Minimum amount per order'),
                    'name' => 'sendsms_amount',
                    'size' => 40,
                    'required' => false
                ),
                array(
                    'type' => 'select',
                    'label' => $this->module->l('Purchased product (leave blank for all products)'),
                    'name' => 'sendsms_products[]',
                    'multiple' => true,
                    'required' => false,
                    'options' => array(
                        'query' => $products,
                        'id' => 'id_product',
                        'name' => 'name'
                    ),
                    'class' => 'sendsms_productmanager'
                ),
                array(
                    'type' => 'select',
                    'label' => $this->module->l('Billing county (leave blank for all counties)'),
                    'name' => 'sendsms_billing_states[]',
                    'multiple' => true,
                    'required' => false,
                    'options' => array(
                        'query' => $states,
                        'id' => 'id_state',
                        'name' => 'name'
                    ),
                    'class' => 'sendsms_statemanager'
                )
            ),
            'submit' => array(
                'title' => $this->module->l('Filter'),
                'class' => 'btn btn-default'
            )
        );

        # jqueryui
        $this->context->controller->addJQueryPlugin('select2');

        $periodStart = (string)(Configuration::get('PS_SENDSMS_START_PERIOD'));
        $periodEnd = (string)(Configuration::get('PS_SENDSMS_END_PERIOD'));
        $amount = (string)(Configuration::get('PS_SENDSMS_ORDER_AMOUNT'));
        $products = array();
        $billingStates = array();

        if (Configuration::get('PS_SENDSMS_PRODUCTS')) {
            //in modulul de 1.6 folosim "," inloc de ',' deoarece cookiurile nu permit salvarea caracterului ','
            $products = Configuration::get('PS_SENDSMS_PRODUCTS') ? explode(',', Configuration::get('PS_SENDSMS_PRODUCTS')) : array();
        }
        if (Configuration::get('PS_SENDSMS_STATES')) {
            $billingStates = Configuration::get('PS_SENDSMS_STATES') ? explode(',', Configuration::get('PS_SENDSMS_STATES')) : array();
        }
        $numbers = $this->filterPhones($periodStart, $periodEnd, $amount, $products, $billingStates);

        //dummy phones
        // for ($i = 0; $i < 3000; $i++) {
        //     $number = "4021" . $this->randomNumberSequence();
        //     $numbers[] = array('phone' => $number, 'label' => $number);
        // }
        # set form values
        $this->fields_value['sendsms_period_start'] = $periodStart;
        $this->fields_value['sendsms_period_end'] = $periodEnd;
        $this->fields_value['sendsms_amount'] = $amount;
        $this->fields_value['sendsms_products[]'] = $products;
        $this->fields_value['sendsms_billing_states[]'] = $billingStates;

        $this->cookieManager->__set('sendsms_period_start', $periodStart);
        $this->cookieManager->__set('sendsms_period_end', $periodEnd);
        $this->cookieManager->__set('sendsms_amount', $amount);
        $this->cookieManager->__set('sendsms_products', (Configuration::get('PS_SENDSMS_PRODUCTS') ? Configuration::get('PS_SENDSMS_PRODUCTS') :""));
        $this->cookieManager->__set('sendsms_billing_states', (Configuration::get('PS_SENDSMS_STATES') ? Configuration::get('PS_SENDSMS_STATES') : ""));

        $form1 = parent::renderForm();

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->module->l('Customer filtering results')
            ),
            'input' => array(
                array(
                    'type' => 'textarea',
                    'rows' => 7,
                    'label' => $this->module->l('Message'),
                    'name' => 'sendsms_message',
                    'id' => 'sendsms_message',
                    'required' => true,
                    'class' => 'ps_sendsms_content',
                    'desc' => $this->module->l(' characters remained.')
                ),
                array(
                    'type' => 'checkbox',
                    'label' => $this->l('Select all phones?'),
                    'name' => 'sendsms_all',
                    'required' => false,
                    'values' => array(
                        'query' => array(
                            array(
                                'all' => null,
                            )
                        ),
                        'id' => 'all',
                        'name' => 'all'
                    ),
                    'desc' => $this->l('You will not need to select any phone number from the field below'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->module->l('Phones'),
                    'name' => 'sendsms_phone_numbers[]',
                    'required' => false,
                    'multiple' => true,
                    'options' => array(
                        'query' => $numbers,
                        'id' => 'phone',
                        'name' => 'label'
                    ),
                    'desc' => count($numbers) . $this->module->l(' phone number(s)'),
                    'id' => 'sendsms_phone_numbers'
                )
            ),
            'buttons' => array(
                'check' => array(
                    'type' => 'button',
                    'title' => $this->l('Estimate cost'),
                    'name' => 'check-price',
                    'icon' => 'process-icon-preview',
                    'class' => 'btn btn-default pull-right',
                    'id' => 'check-price'
                ),
                'submit' => array(
                    'type' => 'button',
                    'icon' => 'process-icon-save-and-stay',
                    'title' => $this->module->l('Send'),
                    'class' => 'btn btn-default',
                    'name' => 'send',
                    'id' => 'send-campaign'
                )
            )
        );

        $message = (string)(Configuration::get('PS_SENDSMS_CAMPAIGN_MESSAGE'));
        $phones = json_decode(Configuration::get('PS_SENDSMS_CAMPAIGN_PHONES'), true);
        $short = Configuration::get('PS_SENDSMS_CAMPAIGN_SHORT');
        $gdpr = Configuration::get('PS_SENDSMS_CAMPAIGN_GDPR');

        # set form values
        $this->fields_value['sendsms_message'] = $message;
        $this->fields_value['sendsms_phone_numbers[]'] = $phones;
        $this->fields_value['sendsms_url_'] = $short;
        $this->fields_value['sendsms_gdpr_'] = $gdpr;

        $form2 = parent::renderForm();

        return $form1 . $form2;
    }

    public function setMedia($isNewTheme = false)
    {
        Media::addJsDefL('sendsms_var_name', $this->module->l(' remaining characters'));
        Media::addJsDefL('sendsms_price_per_phone', Configuration::get('PS_SENDSMS_PRICE_PER_PHONE', null, null, null, 0));
        Media::addJsDefL('sendsms_text_no_message', $this->module->l('Please enter a message first.'));
        Media::addJsDefL('sendsms_text_send_first', $this->module->l('Please send an SMS first.'));
        Media::addJsDefL('sendsms_text_estimate_price', $this->module->l('The estimate price is '));
        Media::addJsDefL('sendsms_text_sending', $this->module->l('Sending...'));
        Media::addJsDefL('sendsms_text_send', $this->module->l('Send'));

        parent::setMedia();

        # js
        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/count.js'
        );

        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/select2.js'
        );

        $this->context->controller->addCSS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/css/adminCampaign.css'
        );

        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/campaign.js'
        );
    }

    public function postProcess()
    {
        if (Tools::getValue('method') === 'sendCampaign') {
            error_log("procesare ajax");
            $sendsms_period_start = $this->cookieManager->__get('sendsms_period_start');
            $sendsms_period_end = $this->cookieManager->__get('sendsms_period_end');
            $sendsms_amount = $this->cookieManager->__get('sendsms_amount');
            $sendsms_products = "";
            $sendsms_billing_states = "";
            if (!empty($this->cookieManager->__get('sendsms_products'))) {
                $sendsms_products = explode(',', $this->cookieManager->__get('sendsms_products'));
            }
            if (!empty($this->cookieManager->__get('sendsms_billing_states'))) {
                $sendsms_billing_states = explode(',', $this->cookieManager->__get('sendsms_billing_states'));
            }
            $all = Tools::getValue('all');
            $content = Tools::getValue('content');
            $phones = array();
            if (empty($content)) {
                die(json_encode(array('hasError' => true, 'error' => $this->module->l('Your message box is empty'))));
            }
            if ($all === "true") {
                $this->filterPhones($sendsms_period_start, $sendsms_period_end, $sendsms_amount, $sendsms_products, $sendsms_billing_states, $phones);
            } else {
                if (Tools::getValue('phones') != "") {
                    $phones = explode(',', Tools::getValue('phones', false));
                }
            }
            if (count($phones) === 0) {
                die(json_encode(array('hasError' => true, 'error' => $this->module->l('Please select at least one phone number'))));
            }
            $fileUrl = _PS_MODULE_DIR_ . $this->module->name . "/batches/batch.csv";
            $file = fopen($fileUrl, "w");
            if ($file) {
                $from = Configuration::get('PS_SENDSMS_LABEL');
                if (empty($from)) {
                    die(json_encode(array('hasError' => true, 'error' => $this->module->l('Please add a label in the configuration page'))));
                }
                $headers = array(
                    "message",
                    "to",
                    "from"
                );
                fputcsv($file, $headers);
                foreach ($phones as $phone) {
                    fputcsv($file, array(
                        $content,
                        $this->module->validatePhone($phone),
                        $from
                    ), ',', '"', "\0");
                }
                $result = $this->module->createBatch($fileUrl);
                fclose($file);
                if (!unlink($fileUrl)) {
                    die(json_encode(array('hasError' => true, 'error' => $this->module->l("Unable to delete the batch file! Please check file/folder permisions ($fileUrl)"))));
                }
                if ($result === 0) {
                    die(json_encode(array('response' => $this->module->l('Success'))));
                } elseif ($result === -1) {
                    die(json_encode(array('hasError' => true, 'error' => $this->module->l('Please check your username/password/label.'))));
                } elseif ($result === -2) {
                    die(json_encode(array('hasError' => true, 'error' => $this->module->l('Batch sending error. Check your php error_log file'))));
                }
            } else {
                die(json_encode(array('hasError' => true, 'error' => "Unable to open/create batch file! Please check file/folder permisions ($fileUrl)")));
            }
        }
        if (Tools::isSubmit('submitAdd' . $this->table)) {
            $periodStart = (string)(Tools::getValue('sendsms_period_start'));
            $periodEnd = (string)(Tools::getValue('sendsms_period_end'));
            $amount = (string)(Tools::getValue('sendsms_amount'));
            Configuration::updateValue('PS_SENDSMS_START_PERIOD', $periodStart, true);
            Configuration::updateValue('PS_SENDSMS_END_PERIOD', $periodEnd, true);
            Configuration::updateValue('PS_SENDSMS_ORDER_AMOUNT', $amount, true);
            if (Tools::getValue('sendsms_products')) {
                Configuration::updateValue('PS_SENDSMS_PRODUCTS', implode(',', Tools::getValue('sendsms_products')), true);
            } else {
                Configuration::updateValue('PS_SENDSMS_PRODUCTS', null);
            }
            if (Tools::getValue('sendsms_billing_states')) {
                Configuration::updateValue('PS_SENDSMS_STATES', implode(',', Tools::getValue('sendsms_billing_states')), true);
            } else {
                Configuration::updateValue('PS_SENDSMS_STATES', null);
            }
            Tools::redirectAdmin(self::$currentIndex . '&conf=' . $this->index . '&token=' . $this->token);
        }
    }

    private function filterPhones($periodStart, $periodEnd, $amount, $products, $billingStates, &$phones = array())
    {
        $sql = new DbQuery();
        $sql->select('a.phone, a.phone_mobile');
        $sql->from('address', 'a');
        $sql->innerJoin('orders', 'o', 'a.id_address = o.id_address_delivery');
        if (!empty($periodStart)) {
            $sql->where('o.date_add >= \'' . $periodStart . ' 00:00:00\'');
        }
        if (!empty($periodEnd)) {
            $sql->where('o.date_add <= \'' . $periodEnd . ' 23:59:59\'');
        }
        if (!empty($amount)) {
            $sql->where('o.total_paid_tax_incl >= ' . (float)$amount);
        }
        if (!empty($products)) {
            $sql->innerJoin('order_detail', 'od', 'od.id_order = o.id_order');
            $queryWhere = 'od.product_id in (';
            for ($i = 0; $i < count($products); $i++) {
                $queryWhere .= (int)$products[$i];
                if ($i < count($products) - 1) {
                    $queryWhere .= ",";
                }
            }
            $queryWhere .= ")";
            $sql->where($queryWhere);
        }
        if (!empty($billingStates)) {
            $queryWhere = 'a.id_state in (';
            for ($i = 0; $i < count($billingStates); $i++) {
                $queryWhere .= (int)$billingStates[$i];
                if ($i < count($billingStates) - 1) {
                    $queryWhere .= ",";
                }
            }
            $queryWhere .= ")";
            $sql->where($queryWhere);
        }
        $sql->where('CONCAT(a.phone, a.phone_mobile) <> \'\'');
        $values = Db::getInstance()->executeS($sql);
        $phones = array();
        $unique = array();
        if (!empty($values)) {
            foreach ($values as $value) {
                $phone = $this->module->selectPhone($value['phone'], $value['phone_mobile']);
                if (!empty($phone)) {
                    if (!in_array($phone, $phones)) {
                        $phones[] = $phone;
                        $unique[] = array('phone' => $phone, 'label' => $phone);
                    }
                }
            }
        }
        return $unique;
    }

    private function getListOfProducts()
    {
        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $sql = new DbQuery();
        $sql->select('id_product, name');
        $sql->from('product_lang');
        $sql->where('id_lang = ' . $default_lang . ' AND name <> \'\'');
        $sql->orderBy('name ASC');
        return Db::getInstance()->executeS($sql);
    }

    private function getListOfBillingStates()
    {
        $sql = new DbQuery();
        $sql->select('id_state, name');
        $sql->from('state');
        $sql->where('active = 1');
        $sql->orderBy('name ASC');
        return Db::getInstance()->executeS($sql);
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_title = $this->module->l('SMS Campaign');
        parent::initPageHeaderToolbar();
        unset($this->toolbar_btn['new']);
    }

    private function randomNumberSequence($requiredLength = 7, $highestDigit = 8)
    {
        $sequence = '';
        for ($i = 0; $i < $requiredLength; ++$i) {
            $sequence .= mt_rand(0, $highestDigit);
        }
        return $sequence;
    }
}
