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

if (!defined('_PS_VERSION_')) {
    exit;
}

class PsSendSMS extends Module
{


    protected $configValues = array(
        'PS_SENDSMS_USERNAME',
        'PS_SENDSMS_PASSWORD',
        'PS_SENDSMS_LABEL',
        'PS_SENDSMS_SIMULATION',
        'PS_SENDSMS_SIMULATION_PHONE',
        'PS_SENDSMS_STATUS'
    );

    public function __construct()
    {

        $this->name = 'pssendsms';
        $this->tab = 'advertising_marketing';
        $this->version = '1.0.5';
        $this->author = 'Any Place Media SRL';
        $this->module_key = '01417c91c848ebbc67f458d260e61f98';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.7.6');
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('SendSMS');
        $this->description = $this->l('Use our SMS shipping solution to deliver the right information at the right time.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('PS_SENDSMS_USERNAME') || !Configuration::get('PS_SENDSMS_PASSWORD')) {
            $this->warning = $this->l('No username and / or password was set');
        }
    }

    private function installDb()
    {
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'ps_sendsms_history`;');

        if (!Db::getInstance()->Execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ps_sendsms_history` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `phone` varchar(255) DEFAULT NULL,
            `status` varchar(255) DEFAULT NULL,
            `message` varchar(255) DEFAULT NULL,
            `details` longtext,
            `content` longtext,
            `type` varchar(255) DEFAULT NULL,
            `sent_on` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8')) {
            return false;
        }
        return true;
    }

    private function uninstallDb()
    {
        if (!Db::getInstance()->Execute('DROP TABLE `' . _DB_PREFIX_ . 'ps_sendsms_history`;')) {
            return false;
        }
        return true;
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!$this->installDb()) {
            return false;
        }

        if (!parent::install()) {
            return false;
        }

        # register hooks
        if (!$this->registerHook('actionOrderStatusPostUpdate')) {
            return false;
        }
        if (!$this->registerHook('displayAdminOrderLeft')) {
            return false;
        }

        return true;
    }

    public function enable($force_all = false)
    {
        $tabNames = array();
        $result = Db::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "lang order by id_lang");
        if (is_array($result)) {
            foreach ($result as $row) {
                $tabNames['main'][$row['id_lang']] = $this->l('SendSMS');
                $tabNames['history'][$row['id_lang']] =  $this->l('History');
                $tabNames['campaign'][$row['id_lang']] =  $this->l('Campaign');
                $tabNames['test'][$row['id_lang']] =  $this->l('Send a test');
            }
        }
        $idTab = Tab::getIdFromClassName("IMPROVE");
        $this->installModuleTab('SendSMSTab', $tabNames['main'], $idTab);
        $idTab = Tab::getIdFromClassName("SendSMSTab");
        $this->installModuleTab('AdminHistory', $tabNames['history'], $idTab);
        $this->installModuleTab('AdminCampaign', $tabNames['campaign'], $idTab);
        $this->installModuleTab('AdminSendTest', $tabNames['test'], $idTab);

        return parent::enable($force_all);
    }

    public function disable($force_all = false)
    {
        $this->uninstallModuleTab('SendSMSTab');
        $this->uninstallModuleTab('AdminHistory');
        $this->uninstallModuleTab('AdminCampaign');
        $this->uninstallModuleTab('AdminSendTest');

        return parent::disable($force_all);
    }

    public function uninstall()
    {
        if (!parent::uninstall() || !$this->uninstallDb()) {
            return false;
        }

        foreach ($this->configValues as $config) {
            if (!Configuration::deleteByName($config)) {
                return false;
            }
        }
        
        return true;
    }

    public function getContent()
    {
        $output = null;
        if (Tools::isSubmit('submit' . $this->name)) {
            # get info
            $username = (string)(Tools::getValue('PS_SENDSMS_USERNAME'));
            $password = (string)(Tools::getValue('PS_SENDSMS_PASSWORD'));
            $label = (string)(Tools::getValue('PS_SENDSMS_LABEL'));
            $isSimulation = (string)(Tools::getValue('PS_SENDSMS_SIMULATION_'));
            $simulationPhone = (string)(Tools::getValue('PS_SENDSMS_SIMULATION_PHONE'));
            $statuses = array();

            $orderStatuses = OrderState::getOrderStates($this->context->language->id);
            foreach ($orderStatuses as $status) {
                $statuses[$status['id_order_state']] = (string)(Tools::getValue('PS_SENDSMS_STATUS_' . $status['id_order_state']));
            }

            # validate and update settings
            if (empty($username) || empty($label) || (empty($password) && !Configuration::get('PS_SENDSMS_PASSWORD'))) {
                $output .= $this->displayError($this->l('You must complete your username, password, and sender label'));
            } else {
                # validate phone number
                if (!empty($simulationPhone) && !Validate::isPhoneNumber($simulationPhone)) {
                    $output .= $this->displayError($this->l('Phone number is invalid'));
                } else {
                    Configuration::updateValue('PS_SENDSMS_SIMULATION_PHONE', $simulationPhone);
                }
                Configuration::updateValue('PS_SENDSMS_USERNAME', $username);
                if (!empty($password)) {
                    Configuration::updateValue('PS_SENDSMS_PASSWORD', $password);
                }
                Configuration::updateValue('PS_SENDSMS_LABEL', $label);
                Configuration::updateValue('PS_SENDSMS_SIMULATION', !empty($isSimulation) ? 1 : 0);
                Configuration::updateValue('PS_SENDSMS_STATUS', serialize($statuses));
                $output .= $this->displayConfirmation($this->l('The settings have been updated'));
            }
        }
        return $output . $this->displayForm();
    }

    public function displayForm()
    {
        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('User name'),
                    'name' => 'PS_SENDSMS_USERNAME',
                    'required' => true,
                    'desc' => $this->l('Don\'t have sendSMS account? Sign up for FREE ') . '<a href="https://hub.sendsms.ro/en/login" target="_blank">' . $this->l('here') . '</a>.' . $this->l('You can find more details about sendSMS ') . '<a href="http://www.sendsms.ro/en" target="_blank">' . $this->l('here') . '</a>.'
                ),
                array(
                    'type' => 'password',
                    'label' => $this->l('Password / API Key'),
                    'name' => 'PS_SENDSMS_PASSWORD',
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Shipper label'),
                    'name' => 'PS_SENDSMS_LABEL',
                    'required' => true,
                    'desc' => $this->l('maximum 11 numeric alpha characters'),
                    'maxlength' => 11
                ),
                array(
                    'type' => 'checkbox',
                    'label' => $this->l('SMS sending simulation'),
                    'name' => 'PS_SENDSMS_SIMULATION',
                    'required' => false,
                    'values' => array(
                        'query' => array(
                            array(
                                'simulation' => null,
                            )
                        ),
                        'id' => 'simulation',
                        'name' => 'simulation'
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Simulation phone number'),
                    'name' => 'PS_SENDSMS_SIMULATION_PHONE',
                    'required' => false
                )
            )
        );

        # add order statuses to options
        $defaults = array(
            10 => $this->l('The order sitename.com with the number {order_name} has been processed and is waiting for the payment in total value of {order_total}. Info: 0722xxxxxx'),
            14 => $this->l('The order sitename.com with the number {order_name} has been processed in the refund system. The total payment amount is {order_total}. Info: 0722xxxxxx'),
            1 => $this->l('The order sitename.com with the number {order_name} has been processed and is awaiting payment in full by {order_total}. Info: 0722xxxxxx'),
            11 => $this->l('The order sitename.com with the number {order_name} has been processed and we are waiting and waiting for PayPal confirmation. Info: 0722xxxxxx'),
            6 => $this->l('The order sitename.com with the number {order_name} has been canceled - the reason being: lack of stock / delivery time longer than 10 days. Info: 07xxxxxxxx'),
            5 => $this->l('The order sitename.com with the number {order_name} worth {order_total} has been delivered to the courier and will be delivered within 24 hours. Info: 07xxxxxxxx'),
            2 => $this->l('Payment for order number {order_name} of {order_total} has been accepted! Info: 07xxxxxxxx'),
            8 => $this->l('We encountered an error processing your payment for the sitename.com order with the number {order_name} worth {order_total} Info: 07xxxxxxxx'),
            7 => $this->l('The value of the sitename.com order with the number {order_number} of {order_total} has been returned! Info: 07xxxxxxxx'),
            4 => $this->l('The order with the number {order_number} worth {order_total} has been delivered to the courier and will be delivered within 24 hours. Info: 07xxxxxxxx')
        );
        $orderStatuses = OrderState::getOrderStates($this->context->language->id);
        foreach ($orderStatuses as $status) {
            $this->fields_form[0]['form']['input'][] = array(
                'type' => 'textarea',
                'rows' => 7,
                'label' => $this->l('Message: ') . '<stg>' . $status['name'] . '</stg>' . '<br /><br />' . $this->l('Available variables:') . '<button type="button" class="ps_sendsms_button">{billing_first_name}</button> 
                    <button type="button" class="ps_sendsms_button">{billing_last_name}</button> 
                    <button type="button" class="ps_sendsms_button">{shipping_first_name}</button> 
                    <button type="button" class="ps_sendsms_button">{shipping_last_name}</button>
                    <button type="button" class="ps_sendsms_button">{tracking_number}</button> 
                    <button type="button" class="ps_sendsms_button">{order_number}</button> 
                    <button type="button" class="ps_sendsms_button">{order_date}</button> 
                    <button type="button" class="ps_sendsms_button">{order_total}</button>' . '<br /><br />' . $this->l('Leave the field blank if you do not want to send SMS for this status.'),
                'name' => 'PS_SENDSMS_STATUS_' . $status['id_order_state'],
                'required' => false,
                'class' => 'ps_sendsms_content',
                'desc' => '<div>' . (isset($defaults[$status['id_order_state']]) ? $this->l('Ex: ') . $defaults[$status['id_order_state']] : '') . '</div>'
            );
        }

        # add submit button
        $this->fields_form[0]['form']['submit'] = array(
            'title' => $this->l('Save'),
            'class' => 'btn btn-default pull-right'
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value['PS_SENDSMS_USERNAME'] = Configuration::get('PS_SENDSMS_USERNAME');
        $helper->fields_value['PS_SENDSMS_PASSWORD'] = Configuration::get('PS_SENDSMS_PASSWORD');
        $helper->fields_value['PS_SENDSMS_LABEL'] = Configuration::get('PS_SENDSMS_LABEL');
        $helper->fields_value['PS_SENDSMS_SIMULATION_'] = Configuration::get('PS_SENDSMS_SIMULATION');
        $helper->fields_value['PS_SENDSMS_SIMULATION_PHONE'] = Configuration::get('PS_SENDSMS_SIMULATION_PHONE');
        $statuses = unserialize(Configuration::get('PS_SENDSMS_STATUS'));
        foreach ($orderStatuses as $status) {
            $helper->fields_value['PS_SENDSMS_STATUS_' . $status['id_order_state']] = isset($statuses[$status['id_order_state']]) ? $statuses[$status['id_order_state']] : '';
        }

        Media::addJsDefL('sendsms_var_name', $this->l(' remaining characters'));

        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->name . '/views/js/count.js'
        );
        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->name . '/views/js/buttons.js'
        );

        return $helper->generateForm($this->fields_form);
    }

    public function hookDisplayAdminOrderLeft($params)
    {
        if (!$this->active) {
            return false;
        }

        if (Tools::isSubmit('submitsendsms_order')) {
            require_once _PS_CLASS_DIR_ . 'order/Order.php';
            require_once _PS_CLASS_DIR_ . 'Customer.php';
            $id_order = (int)$params['id_order'];
            $order = new Order($id_order);
            $customer = new Customer((int)$order->id_customer);

            $phone = (string)(Tools::getValue('sendsms_phone'));
            $message = (string)(Tools::getValue('sendsms_message'));
            $phone = Validate::isPhoneNumber($phone) ? $phone : "";
            if (!empty($phone) && !empty($message)) {
                $this->sendSms($message, 'single order', $phone);
                $msg = $this->l('The message has been sent');
                $msg_error = false;

                # add message
                require_once _PS_CLASS_DIR_ . 'CustomerMessage.php';
                require_once _PS_CLASS_DIR_ . 'CustomerThread.php';
                //check if a thread already exist
                $id_customer_thread = CustomerThread::getIdCustomerThreadByEmailAndIdOrder($customer->email, $order->id);
                if (!$id_customer_thread) {
                    $customer_thread = new CustomerThread();
                    $customer_thread->id_contact = 0;
                    $customer_thread->id_customer = (int)$order->id_customer;
                    $customer_thread->id_shop = (int)$this->context->shop->id;
                    $customer_thread->id_order = (int)$order->id;
                    $customer_thread->id_lang = (int)$this->context->language->id;
                    $customer_thread->email = $customer->email;
                    $customer_thread->status = 'open';
                    $customer_thread->token = Tools::passwdGen(12);
                    $customer_thread->add();
                } else {
                    $customer_thread = new CustomerThread((int)$id_customer_thread);
                }
                $customer_message = new CustomerMessage();
                $customer_message->id_customer_thread = $customer_thread->id;
                $customer_message->id_employee = (int)$this->context->employee->id;
                $customer_message->message = $this->l('The message has been sent to ') . $phone . ': ' . $message;
                $customer_message->private = 1;
                $customer_message->add();
            } else {
                $msg = $this->l('The phone number was invalid');
                $msg_error = true;
            }
            $this->context->smarty->assign(array(
                'sendsms_msg' => $msg,
                'sendsms_error' => $msg_error
            ));
        }

        return $this->display(__FILE__, '/views/templates/admin/admin_order_sendsms.tpl');
    }

    public function hookActionOrderStatusPostUpdate($params)
    {
        if (!$this->active) {
            return false;
        }

        # get params
        $orderId = $params['id_order'];
        $statusId = $params['newOrderStatus']->id;

        # get configuration
        $statuses = unserialize(Configuration::get('PS_SENDSMS_STATUS'));
        if (isset($statuses[$statusId])) {
            # get order details
            $order = new Order($orderId);
            $billingAddress = new Address($order->id_address_invoice);
            $shippingAddress = new Address($order->id_address_delivery);
            $order_carrier = new OrderCarrier((int)$order->getIdOrderCarrier());
            $shipping_number = $order_carrier->tracking_number;
            if (empty($shipping_number)) {
                $shipping_number = $order->shipping_number;
            }

            # get billing phone number
            $phone = Validate::isPhoneNumber($this->selectPhone($billingAddress->phone, $billingAddress->phone_mobile)) ? $this->selectPhone($billingAddress->phone, $billingAddress->phone_mobile) : "";
            
            $currency = new Currency($order->id_currency);

            # transform variables
            $message = $statuses[$statusId];
            $replace = array(
                '{billing_first_name}' => $this->cleanDiacritice($billingAddress->firstname),
                '{billing_last_name}' => $this->cleanDiacritice($billingAddress->lastname),
                '{shipping_first_name}' => $this->cleanDiacritice($shippingAddress->firstname),
                '{shipping_last_name}' => $this->cleanDiacritice($shippingAddress->lastname),
                '{order_number}' => $order->reference,
                '{tracking_number}' => $shipping_number,
                '{order_date}' => date('d-m-Y', strtotime($order->date_add)),
                '{order_total}' => number_format($order->total_paid, $this->context->currency->precision, ',', '') . " " . $currency->sign
            );
            foreach ($replace as $key => $value) {
                $message = str_replace($key, $value, $message);
            }

            # send sms
            $this->sendSms($message, 'order', $phone);
        }
    }

    public function selectPhone($phone, $mobile)
    {
        # if both, prefer mobile
        if (!empty($phone) && !empty($mobile)) {
            return $mobile;
        }

        if (!empty($mobile)) {
            return $mobile;
        }

        return $phone;
    }

    public function sendSms($message, $type = 'order', $phone = '')
    {
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $username = Configuration::get('PS_SENDSMS_USERNAME');
        $password = Configuration::get('PS_SENDSMS_PASSWORD');
        $isSimulation = Configuration::get('PS_SENDSMS_SIMULATION');
        $simulationPhone = Validate::isPhoneNumber(Configuration::get('PS_SENDSMS_SIMULATION_PHONE')) ? $phone : "";
        $from = Configuration::get('PS_SENDSMS_LABEL');
        if (empty($username) || empty($password)) {
            return false;
        }
        if ($isSimulation && empty($simulationPhone)) {
            return false;
        } elseif ($isSimulation && !empty($simulationPhone)) {
            $phone = $simulationPhone;
        }
        if (empty($phone)) {
            return false;
        }
        $message = $this->cleanDiacritice($message);

        if (!empty(trim($message))) {
            $curl = curl_init();

            $useragent = $_SERVER['HTTP_USER_AGENT'];

            curl_setopt($curl, CURLOPT_USERAGENT, "SendSMS.RO API Agent for " . $useragent);
            curl_setopt($curl, CURLOPT_REFERER, _PS_BASE_URL_);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_URL, 'https://api.sendsms.ro/json?action=message_send_gdpr&username=' . $username . '&password=' . $password . '&from=' . $from . '&to=' . $phone . '&text=' . urlencode($message) . '&short=true');
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Connection: keep-alive"));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $status = curl_exec($curl);
            $status = json_decode($status, true);

            # history
            Db::getInstance()->insert('ps_sendsms_history', array(
                'phone' => pSQL($phone),
                'status' => isset($status['status']) ? pSQL($status['status']) : pSQL(''),
                'message' => isset($status['message']) ? pSQL($status['message']) : pSQL(''),
                'details' => isset($status['details']) ? pSQL($status['details']) : pSQL(''),
                'content' => pSQL($message),
                'type' => $type,
                'sent_on' => date('Y-m-d H:i:s')
            ));
        }
    }

    public function cleanDiacritice($string)
    {
        $balarii = array(
            "\xC4\x82",
            "\xC4\x83",
            "\xC3\x82",
            "\xC3\xA2",
            "\xC3\x8E",
            "\xC3\xAE",
            "\xC8\x98",
            "\xC8\x99",
            "\xC8\x9A",
            "\xC8\x9B",
            "\xC5\x9E",
            "\xC5\x9F",
            "\xC5\xA2",
            "\xC5\xA3",
            "\xC3\xA3",
            "\xC2\xAD",
            "\xe2\x80\x93"
        );
        $cleanLetters = array("A", "a", "A", "a", "I", "i", "S", "s", "T", "t", "S", "s", "T", "t", "a", " ", "-");
        return str_replace($balarii, $cleanLetters, $string);
    }

    private function installModuleTab($tabClass, $tabName, $idTabParent)
    {
        $tab = new Tab();
        $tab->name = $tabName;
        $tab->class_name = $tabClass;
        $tab->module = $this->name;
        $tab->id_parent = $idTabParent;

        if (!$tab->save()) {
            return false;
        }
        return Tab::getIdFromClassName($tabClass);
    }

    private function uninstallModuleTab($tabClass)
    {
        $idTab = Tab::getIdFromClassName($tabClass);
        if ($idTab != 0) {
            $tab = new Tab($idTab);
            $tab->delete();
            return true;
        }
        return false;
    }
}
