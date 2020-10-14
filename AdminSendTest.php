<?php
class AdminSendTest extends ModuleAdminController
{
    protected $index;

    public function __construct()
    {
        $this->table = 'sendsms_test';
        $this->bootstrap = true;
        $this->meta_title = 'Trimitere SMS test';
        $this->display = 'add';

        $this->context = Context::getContext();

        parent::__construct();

        $this->index = count($this->_conf)+1;
        $this->_conf[$this->index]='Mesajul a fost trimis';
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => 'Trimitere test'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => 'Numar de telefon',
                    'name' => 'sendsms_phone',
                    'size' => 40,
                    'required' => true
                ),
                array(
                    'type' => 'textarea',
                    'rows' => 7,
                    'label' => 'Mesaj',
                    'name' => 'sendsms_message',
                    'required' => true,
                    'class' => 'ps_sendsms_content',
                    'desc' => '160 caractere ramase'
                )
            ),
            'submit' => array(
                'title' => 'Trimite',
                'class' => 'button'
            )
        );

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $this->context->controller->addJS(
            Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->module->name.'/count.js'
        );

        return parent::renderForm();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAdd'.$this->table)) {
            $phone = strval(Tools::getValue('sendsms_phone'));
            $message = strval(Tools::getValue('sendsms_message'));
            $phone = $this->module->validatePhone($phone);
            if (!empty($phone) && !empty($message)) {
                $this->module->sendSms($phone, $message, 'test');
                Tools::redirectAdmin(self::$currentIndex.'&conf='.$this->index.'&token='.$this->token);
            } else {
                $this->errors[] = Tools::displayError('Numarul de telefon nu este valid');
            }
        }
    }
}
