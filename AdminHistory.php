<?php
class AdminHistory extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'ps_sendsms_history';
        $this->identifier = 'id';
        $this->bootstrap = true;
        $this->list_simple_header = false;
        $this->display = 'list';
        $this->meta_title = 'Istoric SMS';
        $this->colorOnBackground = false;
        $this->actions = array();
        $this->no_link = true;
        $this->context = Context::getContext();

        $this->list_no_link = true;

        $this->_defaultOrderBy = 'id';
        $this->_defaultOrderWay = 'DESC';

        $this->fields_list = array(
            'id' => array(
                'title' => 'Id',
                'width' => 30,
                'type' => 'text'
            ),
            'phone' => array(
                'title' => 'Telefon',
                'width' => 140,
                'type' => 'text'
            ),
            'status' => array(
                'title' => 'Status',
                'width' => 30,
                'type' => 'text'
            ),
            'message' => array(
                'title' => 'Mesaj',
                'width' => 50,
                'type' => 'text'
            ),
            'details' => array(
                'title' => 'Detalii',
                'width' => 140,
                'type' => 'text'
            ),
            'content' => array(
                'title' => 'Continut',
                'width' => 140,
                'type' => 'text'
            ),
            'type' => array(
                'title' => 'Tip',
                'width' => 50,
                'type' => 'text'
            ),
            'sent_on' => array(
                'title' => 'Data',
                'width' => 140,
                'type' => 'text'
            ),
        );

        parent::__construct();
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_title = 'Istoric';
        parent::initPageHeaderToolbar();
        unset($this->toolbar_btn['new']);
    }
}
