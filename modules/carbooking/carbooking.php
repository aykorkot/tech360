<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class CarBooking extends Module
{
    public function __construct()
    {
        $this->name = 'carbooking';
        $this->author = 'Locadrive';
        $this->version = '1.0.0';
        $this->tab = 'front_office_features';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Car Booking');
        $this->description = $this->l('Module de réservation de voiture');

        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => _PS_VERSION_
        ];
    }

    public function install()
    {
        return parent::install() &&
               $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookDisplayHome($params)
    {
        return $this->display(__FILE__, 'views/templates/front/booking.tpl');
    }
}
