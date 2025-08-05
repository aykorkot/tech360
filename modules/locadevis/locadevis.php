<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(dirname(__FILE__) . '/classes/LocaDevisClass.php');

class LocaDevis extends Module
{
    protected $domain;
    public function __construct()
    {
        $this->name = 'locadevis';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Locadrive';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->domain = 'Modules.Locadevis.Locadevis';
        $this->displayName = $this->trans('Devis', array(), $this->domain);
        $this->description = $this->trans('Devis Locadrive', array(), $this->domain);
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', array(), $this->domain);

    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        if (parent::install()
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayProductButtons')
            && $this->createTabs()
        ) {
            return true;
        }
        return false;
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

        if (!parent::uninstall()) {
            $this->removeTabs('AdminLocaDevisGeneral');
            $this->removeTabs('AdminLocaDevis');

            return false;
        }

        return true;
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function hookHeader()
    {
         
        Media::addJsDef(
            [
                'ajax_url' => $this->context->link->getModuleLink($this->name, 'ajax')
            ]
        );

        $this->context->controller->addJS($this->_path . 'views/js/front.js');
    }

    public function hookDisplayProductButtons($params)
    {
        
        $locaDevisClass = new LocaDevisClass();
        $productId = (int)$params['product']['id_product'];
        
        $smartyData = array(
            'productId' => $productId,
        );
        $this->context->smarty->assign($smartyData);
        return $this->display(__FILE__, 'views/templates/hook/button.tpl');

        return '';
    }

    // private function sendBackInStockEmail($productId)
    // {
    //     $subscribers = LocaDevisClass::getSubscribers($productId);

    //     foreach ($subscribers as $subscriber) {
    //         $to = $subscriber['email'];
    //         $subject = $this->l('Product Back in Stock');
    //         $message = $this->l('The product is back in stock. Place your order now!');

    //         Mail::Send(
    //             (int)$this->context->language->id,
    //             'mail_devis',
    //             $subject,
    //             array('{message}' => $message),
    //             $to,
    //             null,
    //             null,
    //             null,
    //             null,
    //             null,
    //             _PS_MODULE_DIR_ . $this->name . '/mails/'
    //         );
    //     }
    // }

    public function createTabs()
    {
        $idParent = (int) Tab::getIdFromClassName('AdminLocaDrive');
        if (empty($idParent)) {
            $parent_tab = new Tab();
            $parent_tab->class_name = 'AdminLocaDrive';
            $parent_tab->module = $this->name;
            $parent_tab->icon = 'library_books';
            foreach (Language::getLanguages(true) as $lang) {
                $parent_tab->name[$lang['id_lang']] = $this->trans(
                    'Custom modules',
                    [],
                    $this->domain
                );
            }
            $parent_tab->id_parent = 0;
            $parent_tab->add();
        }

        $tab = new Tab();
        $tab->class_name = 'AdminLocaDevisGeneral';
        $tab->module = $this->name;
        $tab->icon = 'library_books';
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Locadrive devis managment', array(), $this->domain);
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminLocaDrive');
        $tab->add();

        $tab = new Tab();
        $tab->class_name = 'AdminLocaDevis';
        $tab->module = $this->name;
        $tab->icon = 'library_books';
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans(
                'Locadrive devis managment',
                [],
                $this->domain
            );
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminLocaDevisGeneral');
        $tab->add();

        return true;
    }

    public function removeTabs($class_name)
    {
        if ($tab_id = (int) Tab::getIdFromClassName($class_name)) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }
        return true;
    }
}
