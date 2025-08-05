<?php
class CarBookingBookingModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $this->setTemplate('module:carbooking/views/templates/front/booking.tpl');
    }
}
