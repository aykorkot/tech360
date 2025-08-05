<?php
/**
 * 2020  (c)  Egio digital
 *
 * MODULE EgBanner Slider
 *
 * @author    Egio digital
 * @copyright Copyright (c) , Egio digital
 * @license   Commercial
 * @version    1.0.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(dirname(__FILE__).'/classes/EgBannerSliderClass.php');

class EgBannerSlider extends Module {

    protected $_html = '';
    protected $templateFile;
    protected $domain;
    public $img_path;
    protected $secure_key;
    public function __construct()
    {
        $this->name = 'egbannerslider';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'Egio digital';
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        parent::__construct();

        $this->domain = 'Modules.Egbanner.EgbannerSlider';
        $this->displayName = $this->trans('Eg Lanceur catégorie', array(), $this->domain);
        $this->description = $this->trans('Display bannières Lanceur catégorie in home page', array(), $this->domain);

        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', array(), $this->domain);
        $this->img_path = $this->_path.'views/img/';
        $this->templateFile = 'module:egbannerslider/views/templates/hook/egbannerslider.tpl';
    }

    /**
     * @see  CREATE TAB module in Dashboard
     */
    public function createTabs()
    {
        $idParent = (int) Tab::getIdFromClassName('AdminEgDigital');
        if (empty($idParent)) {
            $parent_tab = new Tab();
            $parent_tab->name = array();
            foreach (Language::getLanguages(true) as $lang) {
                $parent_tab->name[$lang['id_lang']] = $this->trans('Modules TECH360', array(), $this->domain);
            }
            $parent_tab->class_name = 'AdminEgDigital';
            $parent_tab->id_parent = 0;
            $parent_tab->module = $this->name;
            $parent_tab->icon = 'library_books';
            $parent_tab->add();
        }

        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Lanceur catégorie', array(), $this->domain);
        }
        $tab->class_name = 'AdminEgBannerSliderGeneral';
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminEgDigital');
        $tab->module = $this->name;
        $tab->icon = 'library_books';
        $tab->add();

        // Menage Module
        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Config', array(), $this->domain);
        }
        $tab->class_name = 'AdminEgConfBannerSlider';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminEgBannerSliderGeneral');
        $tab->module = $this->name;
        $tab->add();

        // Menage Banner
        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Gestion des Lanceurs', array(), $this->domain);
        }
        $tab->class_name = 'AdminEgBannerSlider';
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminEgBannerSliderGeneral');
        $tab->module = $this->name;
        $tab->add();

        return true;
    }

    /**
     * Remove Tabs module in Dashboard
     * @param $class_name string name Tab
     * @return bool
     * @throws
     * @throws
     */
    public function removeTabs($class_name)
    {
        if ($tab_id = (int)Tab::getIdFromClassName($class_name)) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }
        return true;
    }

    /**
     * @see Module::install()
     */
    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install()
            && $this->createTabs()
            && $this->registerHook('header')
            && $this->registerHook('displayBackOfficeHeader')
            && $this->registerHook('displayHome');
    }

    /**
     * @see Module::uninstall()
     */
    public function uninstall()
    {
        //include(dirname(__FILE__).'/sql/uninstall.php');
        $this->removeTabs('AdminEgConfBannerSlider');
        $this->removeTabs('AdminEgBannerSliderGeneral');
        $this->removeTabs('AdminEgBannerSlider');
        return parent::uninstall();
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/egbannerslider.css');
    }

    public function renderList()
    {
        $idTab = (int) Tab::getIdFromClassName('AdminModules');
        $idEmployee = (int) $this->context->employee->id;
        $token = Tools::getAdminToken('AdminModules'.$idTab.$idEmployee);
        $this->context->smarty->assign(
            array(
                'linkConfigBanner' => $this->context->link->getAdminLink('AdminEgConfBannerSlider'),
                'linkManageBanner' => $this->context->link->getAdminLink('AdminEgBannerSlider'),
            )
        );
        $template = _PS_MODULE_DIR_ . $this->name .'/views/templates/admin/_configure/helpers/list/list_header.tpl';
        return $this->context->smarty->fetch($template);
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    public function clearCache()
    {
        $this->_clearCache($this->templateFile);
    }

    public function hookDisplayHome()
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId($this->name))) {
            $count = (int)Configuration::get('EG_COUNT_BANNER_SLIDER');
            $title_banner = Configuration::get('EG_TITLE_BANNER_SLIDER');
            $text_banner = Configuration::get('EG_TEXT_BANNER_SLIDER',true);
            $limit = isset($count) ? $count : null;
            $status = Configuration::get('EG_BANNER_SLIDER_STATUS');
            $banners = EgBannerSliderClass::getBannerFromHook($limit);
            foreach ($banners as &$banner) {
                $banner['category_name'] = EgBannerSliderClass::getNameCategoryById($banner['id_category']);
            }
            $this->context->smarty->assign(array(
                'banners' => $banners,
                'status' => $status,
                'uri' => $this->img_path,
                'title_banner' => $title_banner,
                'text_banner' => $text_banner
            ));
        }
        return $this->fetch($this->templateFile, $this->getCacheId($this->name));
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        if (Tools::isSubmit('submitModule')) {
            Configuration::updateValue('EG_COUNT_BANNER_SLIDER', Tools::getValue('EG_COUNT_BANNER_SLIDER'));
            Configuration::updateValue('EG_BANNER_SLIDER_STATUS', Tools::getValue('EG_BANNER_SLIDER_STATUS'));
            Configuration::updateValue('EG_TITLE_BANNER_SLIDER', Tools::getValue('EG_TITLE_BANNER_SLIDER'));
            Configuration::updateValue('EG_TEXT_BANNER_SLIDER', Tools::getValue('EG_TEXT_BANNER_SLIDER'),true);
        }

        $this->_html .= $this->renderList();
        $this->_html .= $this->renderForm();
        return $this->_html;
    }

    protected function renderForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * @return array
     */
    public function getConfigFieldsValues()
    {
        return array(
            'EG_COUNT_BANNER_SLIDER' => Configuration::get('EG_COUNT_BANNER_SLIDER'),
            'EG_TITLE_BANNER_SLIDER' => Configuration::get('EG_TITLE_BANNER_SLIDER'),
            'EG_BANNER_SLIDER_STATUS' => Configuration::get('EG_BANNER_SLIDER_STATUS'),
            'EG_TEXT_BANNER_SLIDER' => Configuration::get('EG_TEXT_BANNER_SLIDER',true),
        );
    }

    /**
     * @return array
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'tinymce' => true,
                'legend' => array(
                    'title' => $this->trans('Configure banner', array(), $this->domain),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Number of banner to be displayed', array(), $this->domain),
                        'name' => 'EG_COUNT_BANNER_SLIDER',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Block banners title', array(), $this->domain),
                        'name' => 'EG_TITLE_BANNER_SLIDER',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans('Banner text', array(), $this->domain),
                        'name' => 'EG_TEXT_BANNER_SLIDER',
                        'rows' => 5,
                        'cols' => 40,
                        'autoload_rte' => true,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans('Displayed', array(), $this->domain),
                        'name' => 'EG_BANNER_SLIDER_STATUS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->trans('Enabled', array(), $this->domain)
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->trans('Disabled', array(), $this->domain)
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save', array(), $this->domain),
                ),
            ),
        );
    }
}
