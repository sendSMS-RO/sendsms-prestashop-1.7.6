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
    protected $indexError;

    public function __construct()
    {
        parent::__construct();

        $this->bootstrap = true;       

        $this->context = Context::getContext();
        $this->meta_title = $this->module->l('SMS Campaign');
        $this->table = 'sendsms_campaign';
        $this->display = 'add';

        $this->indexError = count($this->_error) + 1;

        $this->_error[$this->indexError] = $this->module->l('You must choose at least one phone number and enter a message');

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
                'class' => 'button'
            )
        );

        

        # jqueryui
        $this->context->controller->addJQueryPlugin('select2');

        $periodStart = (string)(Tools::getValue('periodStart'));
        $periodEnd = (string)(Tools::getValue('periodEnd'));
        $amount = (string)(Tools::getValue('amount'));
        $products = array();
        $billingStates = array();

        if (Configuration::hasKey('SENDSMS_PRODUCTS')) {
            $products = Configuration::get('SENDSMS_PRODUCTS') ? explode('|', Configuration::get('SENDSMS_PRODUCTS')) : array();
        }
        if (Configuration::hasKey('SENDSMS_STATES')) {
            $billingStates = Configuration::get('SENDSMS_STATES') ? explode('|', Configuration::get('SENDSMS_STATES')) : array();
        }
        $numbers = $this->filterPhones($periodStart, $periodEnd, $amount, $products, $billingStates);

        # set form values
        $this->fields_value['sendsms_period_start'] = $periodStart;
        $this->fields_value['sendsms_period_end'] = $periodEnd;
        $this->fields_value['sendsms_amount'] = $amount;
        $this->fields_value['sendsms_products[]'] = $products;
        $this->fields_value['sendsms_billing_states[]'] = $billingStates;

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
                    'required' => true,
                    'class' => 'ps_sendsms_content',
                    'desc' => $this->module->l(' characters remained.')
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
                    'desc' => count($numbers) . $this->module->l(' phone number(s)')
                )
            ),
            'submit' => array(
                'title' => $this->module->l('Send'),
                'class' => 'button',
                'name' => 'send'
            )
        );

        $form2 = parent::renderForm();

        return $form1 . $form2;
    }

    public function setMedia()
    {
        Media::addJsDefL('sendsms_var_name', $this->module->l(' remaining characters'));
        parent::setMedia();

        # js
        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/count.js'
        );

        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/select2.js'
        );
    }

    public function postProcess()
    {
        //dump($this);
        
        if (Tools::isSubmit('send')) {
            $message = (string)(Tools::getValue('sendsms_message'));
            $phones = Tools::getValue('sendsms_phone_numbers');
            $back = $_SERVER['HTTP_REFERER'];

            if (empty($message) || empty($phones)) {
                if (!empty($back)) {
                    Tools::redirectAdmin($back . '&error=2');
                } else {
                    Tools::redirectAdmin(self::$currentIndex . '&error=2&token=' . $this->token);
                }
            } else {
                # send sms
                foreach ($phones as $phone) {
                    $phone = Validate::isPhoneNumber($phone) ? $phone : "";
                    if (!empty($phone)) {
                        $this->module->sendSms($message, 'campaign', $phone);
                    }
                }
                Tools::redirectAdmin(self::$currentIndex . '&sent=1&token=' . $this->token);
            }
        } elseif (Tools::isSubmit('submitAdd' . $this->table)) {
            $periodStart = (string)(Tools::getValue('sendsms_period_start'));
            $periodEnd = (string)(Tools::getValue('sendsms_period_end'));
            $amount = (string)(Tools::getValue('sendsms_amount'));
            $url = array();
            if (!empty($periodStart)) {
                $url[] = 'periodStart=' . urlencode($periodStart);
            }
            if (!empty($periodEnd)) {
                $url[] = 'periodEnd=' . urlencode($periodEnd);
            }
            if (!empty($amount)) {
                $url[] = 'amount=' . urlencode($amount);
            }
            if (Tools::getValue('sendsms_products')) {
                Configuration::updateValue('SENDSMS_PRODUCTS', implode("|", Tools::getValue('sendsms_products')), true);
            } else {
                Configuration::updateValue('SENDSMS_PRODUCTS', null);
            }
            if (Tools::getValue('sendsms_billing_states')) {
                Configuration::updateValue('SENDSMS_STATES', implode("|", Tools::getValue('sendsms_billing_states')), true);
            } else {
                Configuration::updateValue('SENDSMS_STATES', null);
            }

            Tools::redirectAdmin(self::$currentIndex . '&conf=' . $this->index . '&token=' . $this->token . '&' . implode('&', $url));
        }
    }

    private function filterPhones($periodStart, $periodEnd, $amount, $products, $billingStates)
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
}
