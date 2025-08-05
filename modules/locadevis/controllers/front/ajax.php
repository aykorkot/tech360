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

 class LocaDevisAjaxModuleFrontController extends ModuleFrontController
{

    public function initContent()
    {
        ob_end_clean();
        header('Content-Type: application/json');

        $devis = new LocaDevisClass();
        $devis->id_product = (int)Tools::getValue("productId");
        $devis->email = Tools::getValue("email");
        $devis->firstname = Tools::getValue("firstname");
        $devis->lastname = Tools::getValue("lastname");
        $devis->phone = Tools::getValue("phone");
        $devis->product_choice = Tools::getValue("product_choice");
        $devis->departure_agency = Tools::getValue("departure_agency");
        $devis->return_agency = Tools::getValue("return_agency");
        
        $departure_date = str_replace('T', ' ', Tools::getValue('departure_date'));
        $return_date = str_replace('T', ' ', Tools::getValue('return_date'));

        $devis->departure_date = date('d-m-Y H:i', strtotime($departure_date));
        $devis->return_date = date('d-m-Y H:i', strtotime($return_date));

        if ($devis->save()) {
            $msg = 'OK';
            $this->sendEmailDevis($devis->email, $devis->firstname, $devis->lastname, $devis->phone, $devis->product_choice, $devis->departure_agency, $devis->return_agency, $devis->departure_date, $devis->return_date);
        } else {
            $msg = 'Error';
        }
        die(json_encode([
            'msg' => $msg
        ]));
    }

    public function sendEmailDevis($email, $firstname, $lastname, $phone, $product_choice, $departure_agency, $return_agency, $departure_date, $return_date) {

        $shopEmail = Configuration::get('PS_SHOP_EMAIL', null, null, (int)Context::getContext()->shop->id);
        if (!$shopEmail) {
            throw new Exception("L'email du magasin n'est pas configuré.");
        }
 
        $subject = "Demande de devis pour " . $product_choice;
        
        $templateVars = array( 
            '{email}' => $email,
            '{firstname}'  => $firstname, 
            '{lastname}'  => $lastname, 
            '{phone}' => $phone,
            '{product_choice}' => $product_choice,
            '{departure_agency}' => $departure_agency,
            '{return_agency}' => $return_agency,
            '{departure_date}' => $departure_date,
            '{return_date}' => $departure_date
        ); 

        if (!Mail::Send(
            (int)Context::getContext()->language->id,
            'callback_devis',
            $subject,
            $templateVars,
            "ak.korkot@gmail.com", //to
            "Locadrive", //toName
            $email,
            $firstname.' '.$lastname,
            null,
            null,
            dirname(__FILE__).'/mails/'
        )) {
            throw new Exception('Erreur lors de l\'envoi de l\'email.');
        }
    }
}







 
