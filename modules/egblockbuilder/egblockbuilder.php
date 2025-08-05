<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_.'egblockbuilder/classes/EgBlockBuilderClass.php';


class EgBlockBuilder extends Module
{
    public function __construct()
    {
        $this->name = 'egblockbuilder';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Egio Digital';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Générateur de blocs show');
        $this->description = $this->l('Un module pour créer des blocs personnalisés pour votre site.');
        $this->img_path = $this->_path . 'views/img/';
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');
        return parent::install() && 
            $this->registerHook('displayBackofficeHeader') &&
            $this->registerHook('displayDashboardToolbarTopMenu') &&
            $this->registerHook('moduleRoutes') &&
            $this->installTab();
    } 
    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');
        return parent::uninstall() &&
            $this->uninstallTab();
    }
    
    public function hookModuleRoutes($params)
    {
        return [
            'module-egblockbuilder-show' => [
                'controller' => 'show',
                'rule' => 'show/{id}-{name}',
                'keywords' => [
                    'id' => ['regexp' => '[0-9]+', 'param' => 'id'],
                    'name' => ['regexp' => '[_a-zA-Z0-9\pL\pS-]+', 'param' => 'name'],
                ],
                'params' => [
                    'fc' => 'module',
                    'module' => 'egblockbuilder',
                ]
            ],
            'module-egblockbuilder-marque' => [
                'controller' => 'marque',
                'rule' => 'marque/{id}-{name}',
                'keywords' => [
                    'id' => ['regexp' => '[0-9]+', 'param' => 'id'],
                    'name' => ['regexp' => '[_a-zA-Z0-9\pL\pS-]+', 'param' => 'name'],
                ],
                'params' => [
                    'fc' => 'module',
                    'module' => 'egblockbuilder',
                ]
            ],
        ];
    }
    public function hookDisplayDashboardToolbarTopMenu($params)
    {
        // Get the current controller name
        $controller_name = Tools::getValue('controller'); 
        $id_egblockbuilder =  Tools::getValue('id_egblockbuilder');  
        $originalBlock = new EgBlockBuilderClass($id_egblockbuilder);
        // Check if the current controller is the one where you want to display the button
        if ($controller_name === 'AdminBlockBuilderItems' ) {
            if (isset($_GET["updateegblockbuilder_items"]) || isset($_GET["addegblockbuilder_items"])) {
                return '';
            } 
            $controllerName = 'AdminBlockBuilder';
            $this->context->smarty->assign([
                'custom_link' => $this->context->link->getAdminLink($controllerName)
            ]);
    
            return $this->display(__FILE__, 'views/templates/admin/dashboard_toolbar_top_menu.tpl');
        }
    
        // Return empty string if the current controller is not 'AdminBlockBuilder'
        return '';
    }
    
    private function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminBlockBuilder';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->l('Générateur de blocs');
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('IMPROVE');
        $tab->module = $this->name;
        return $tab->add(); 
    }

    private function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminBlockBuilder');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }

        $id_tab = (int)Tab::getIdFromClassName('AdminBlockBuilderBrand');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        return true;
    }

 
    public function hookDisplayBackofficeHeader($params)
    {  

        $this->context->controller->addJqueryPlugin('alerts');
        $this->context->controller->addJqueryPlugin('fancybox');
        $this->context->controller->addJqueryPlugin('alerts');
        $this->context->controller->addJqueryUI('ui.sortable');
        if (Tools::getValue('controller') == 'AdminBlockBuilder' || Tools::getValue('controller') == 'AdminBlockBuilderItems' || Tools::getValue('controller') == 'AdminBlockBuilderBrand') {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
        
        $id_lang = (int) $this->context->language->id;
       
        $base_url = $this->context->shop->getBaseURL(true, true);
        $baseName = basename(_PS_ADMIN_DIR_);
        $tokenC  = Tools::getAdminTokenLite(Tools::getValue('controller'));
        $EgBlockBuilderItemsController = $base_url.$baseName.'/index.php?controller='.Tools::getValue('controller').'&token='.$tokenC;
        $src_rac = $this->img_path ;
        Media::addJsDef([
            'id_lang' => $id_lang, 
            'src_rac' => $src_rac, 
            'EgBlockBuilderItemsController' => $EgBlockBuilderItemsController,
            'base_url' => $base_url,
        ]
    );
    }
    
    public function getContent()
    {
        $output = null;
 

        return $output ;
    }

 

}
