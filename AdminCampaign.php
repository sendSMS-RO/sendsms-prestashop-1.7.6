<?php
class AdminCampaign extends ModuleAdminController
{
    protected $index;

    public function __construct()
    {
        $this->table = 'sendsms_campaign';
        $this->bootstrap = true;
        $this->meta_title = 'Campanie SMS';
        $this->display = 'add';

        $this->context = Context::getContext();

        $error = (string)(Tools::getValue('error'));
        if (!empty($error)) {
            $this->errors = array('Trebuie sa alegeti cel putin un numar de telefon si sa introduceti un mesaj');
        }

        $sent = (string)(Tools::getValue('sent'));
        if (!empty($sent)) {
            $this->confirmations = array('Mesajul a fost trimis');
        }

        parent::__construct();

        $this->index = count($this->_conf) + 1;

        $this->_conf[$this->index] = 'Clientii au fost filtrati';
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
                'title' => 'Filtrare clienti'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => 'Perioada inceput plasare comanda',
                    'name' => 'sendsms_period_start',
                    'size' => 40,
                    'required' => false,
                    'class' => 'sendsms_datepicker'
                ),
                array(
                    'type' => 'text',
                    'label' => 'Perioada sfarsit plasare comanda',
                    'name' => 'sendsms_period_end',
                    'size' => 40,
                    'required' => false,
                    'class' => 'sendsms_datepicker'
                ),
                array(
                    'type' => 'text',
                    'label' => 'Suma minima pe comanda',
                    'name' => 'sendsms_amount',
                    'size' => 40,
                    'required' => false
                ),
                array(
                    'type' => 'select',
                    'label' => 'Produs cumparat (lasati gol pentru toate produsele)',
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
                    'label' => 'Judet facturare (lasati gol pentru toate judetele)',
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
                'title' => 'Filtreaza',
                'class' => 'button'
            )
        );

        # css
        $this->context->controller->addCSS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/css/datepicker/css/default.css'
        );
        $this->context->controller->addCSS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/css/datepicker/css/default.date.css'
        );

        # js
        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/count.js'
        );
        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/datepicker/picker.js'
        );
        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/datepicker/picker.date.js'
        );
        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/datepicker-2.js'
        );
        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/views/js/select2.js'
        );

        # jqueryui
        $this->context->controller->addJQueryPlugin('select2');

        $periodStart = (string)(Tools::getValue('periodStart'));
        $periodEnd = (string)(Tools::getValue('periodEnd'));
        $amount = (string)(Tools::getValue('amount'));
        $products = array();
        $billingStates = array();

        if (Configuration::hasKey('SENDSMS_PRODUCTS'))
        {
            $products = Configuration::get('SENDSMS_PRODUCTS') ? explode('|', Configuration::get('SENDSMS_PRODUCTS')) : array();
        }
        if (Configuration::hasKey('SENDSMS_STATES'))
        {
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
                'title' => 'Rezultate filtrare clienti'
            ),
            'input' => array(
                array(
                    'type' => 'textarea',
                    'rows' => 7,
                    'label' => 'Mesaj',
                    'name' => 'sendsms_message',
                    'required' => true,
                    'class' => 'ps_sendsms_content',
                    'desc' => '160 caractere ramase'
                ),
                array(
                    'type' => 'select',
                    'label' => 'Telefoane',
                    'name' => 'sendsms_phone_numbers[]',
                    'required' => false,
                    'multiple' => true,
                    'options' => array(
                        'query' => $numbers,
                        'id' => 'phone',
                        'name' => 'label'
                    ),
                    'desc' => count($numbers).' numere de telefon'
                )
            ),
            'submit' => array(
                'title' => 'Trimite',
                'class' => 'button',
                'name' => 'send'
            )
        );

        $form2 = parent::renderForm();

        return $form1.$form2;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('send')) {
            $message = (string)(Tools::getValue('sendsms_message'));
            $phones = Tools::getValue('sendsms_phone_numbers');
            $back = $_SERVER['HTTP_REFERER'];
            if (empty($message) || empty($phones)) {
                if (!empty($back)) {
                    Tools::redirectAdmin($back.'&error=1');
                } else {
                    Tools::redirectAdmin(self::$currentIndex . '&error=1&token=' . $this->token);
                }
            } else {
                # send sms
                foreach ($phones as $phone) {
                    $phone = $this->module->validatePhone($phone);
                    if (!empty($phone)) {
                        $this->module->sendSms($message, 'campaign', $phone);
                    }
                }
                Tools::redirectAdmin(self::$currentIndex.'&sent=1&token='.$this->token);
            }
        } elseif (Tools::isSubmit('submitAdd' . $this->table)) {
            $periodStart = (string)(Tools::getValue('sendsms_period_start'));
            $periodEnd = (string)(Tools::getValue('sendsms_period_end'));
            $amount = (string)(Tools::getValue('sendsms_amount'));
            $url = array();
            if (!empty($periodStart)) {
                $url[] = 'periodStart='.urlencode($periodStart);
            }
            if (!empty($periodEnd)) {
                $url[] = 'periodEnd='.urlencode($periodEnd);
            }
            if (!empty($amount)) {
                $url[] = 'amount='.urlencode($amount);
            }
            if (Tools::getValue('sendsms_products'))
            {
                Configuration::updateValue('SENDSMS_PRODUCTS', implode("|", Tools::getValue('sendsms_products')), true);
            } else
            {
                Configuration::updateValue('SENDSMS_PRODUCTS', null);
            }
            if (Tools::getValue('sendsms_billing_states'))
            {
                Configuration::updateValue('SENDSMS_STATES', implode("|", Tools::getValue('sendsms_billing_states')), true);
            } else
            {
                Configuration::updateValue('SENDSMS_STATES', null);
            }

            Tools::redirectAdmin(self::$currentIndex . '&conf=' . $this->index . '&token=' . $this->token.'&'.implode('&', $url));
        }
    }

    private function filterPhones($periodStart, $periodEnd, $amount, $products, $billingStates)
    {
        $sql = new DbQuery();
        $sql->select('a.phone, a.phone_mobile');
        $sql->from('address', 'a');
        $sql->innerJoin('orders', 'o', 'a.id_address = o.id_address_delivery');
        if (!empty($periodStart)) {
            $sql->where('o.date_add >= \''.$periodStart.' 00:00:00\'');
        }
        if (!empty($periodEnd)) {
            $sql->where('o.date_add <= \''.$periodEnd.' 23:59:59\'');
        }
        if (!empty($amount)) {
            $sql->where('o.total_paid_tax_incl >= '.(double)$amount);
        }
        if (!empty($products)) {
            $sql->innerJoin('order_detail', 'od', 'od.id_order = o.id_order');
            $queryWhere = 'od.product_id in (';
            for ($i = 0; $i < count($products); $i++)
            {
                $queryWhere .= (int)$products[$i];
                if ($i < count($products) - 1)
                {
                    $queryWhere .= ",";
                }
            }
            $queryWhere .= ")";
            $sql->where($queryWhere);
        }
        if (!empty($billingStates)) {
            $queryWhere = 'a.id_state in (';
            for ($i = 0; $i < count($billingStates); $i++)
            {
                $queryWhere .= (int)$billingStates[$i];
                if ($i < count($billingStates) - 1)
                {
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
        $sql->where('id_lang = '.$default_lang.' AND name <> \'\'');
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
}
