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

class AdminHistory extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'ps_sendsms_history';
        $this->identifier = 'id';
        $this->bootstrap = true;
        $this->list_simple_header = false;
        $this->display = 'list';
        $this->meta_title = $this->module->l('SMS History');
        $this->colorOnBackground = false;
        $this->actions = array();
        $this->no_link = true;
        $this->context = Context::getContext();

        $this->list_no_link = true;

        $this->_defaultOrderBy = 'id';
        $this->_defaultOrderWay = 'DESC';

        $this->fields_list = array(
            'id' => array(
                'title' => $this->module->l('Id'),
                'width' => 30,
                'type' => 'text'
            ),
            'phone' => array(
                'title' => $this->module->l('Phone'),
                'width' => 140,
                'type' => 'text'
            ),
            'status' => array(
                'title' => $this->module->l('Status'),
                'width' => 30,
                'type' => 'text'
            ),
            'message' => array(
                'title' => $this->module->l('Message'),
                'width' => 50,
                'type' => 'text'
            ),
            'details' => array(
                'title' => $this->module->l('Details'),
                'width' => 140,
                'type' => 'text'
            ),
            'content' => array(
                'title' => $this->module->l('Content'),
                'width' => 140,
                'type' => 'text'
            ),
            'type' => array(
                'title' => $this->module->l('Type'),
                'width' => 50,
                'type' => 'text'
            ),
            'sent_on' => array(
                'title' => $this->module->l('Date'),
                'width' => 140,
                'type' => 'text'
            ),
        );
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_title = $this->module->l('History');
        parent::initPageHeaderToolbar();
        unset($this->toolbar_btn['new']);
    }
}
