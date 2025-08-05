<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
class AdminLocaDevisController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'locadevis';
        $this->className = 'LocaDevisClass';
        $this->identifier = 'id_locadevis';
        $this->list_no_link = true;
        $this->lang = false;

        parent::__construct();

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );
        $this->fields_list = array(
            'id_locadevis' => array('title' => $this->l('ID devis'), 'align' => 'center', 'width' => 25),
            'id_product' => array('title' => $this->l('ID Produit')),
            'firstname' => array('title' => $this->l('Nom')),
            'lastname' => array('title' => $this->l('Prénom')),
            'product_choice' => array('title' => $this->l('Véhicule')),
            'departure_agency' => array('title' => $this->l('Agent de départ')),
            'return_agency' => array('title' => $this->l('Agent de retour')),
            'departure_date' => array('title' => $this->l('Date de départ')),
            'return_date' => array('title' => $this->l('Date de retour')),
            'email' => array('title' => $this->l('Email')),
            'phone' => array('title' => $this->l('Téléphone')),
        );

        $this->actions = array('delete');
    }
}
