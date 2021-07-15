<?php
class sbumoduledisplayModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $this->setTemplate('module:sbumodule/views/templates/front/display.tpl');
    }
}