<?php
/**
 * 2020  (c)  Egio digital
 *
 * MODULE EgBanner
 *
 * @author    Egio digital
 * @copyright Copyright (c) , Egio digital
 * @license   Commercial
 * @version    1.0.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
 

class EgCustomSingleBloc extends Module {

    protected $_html = '';
    protected $templateFile;
    protected $domain;

    public function __construct()
    {
        $this->name = 'egcustomsinglebloc';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'Egio digital';
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        parent::__construct();

        $this->domain = 'Modules.EgCustomSingleBloc.EgCustomSingleBloc';
        $this->displayName = $this->trans('les news', array(), $this->domain);
        $this->description = $this->trans('Les news', array(), $this->domain);

        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', array(), $this->domain);
        $this->img_path = $this->_path.'views/img/';
        $this->templateFile = 'module:egcustomsinglebloc/views/templates/hook/egcustomsinglebloc.tpl';
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
 
        // Menage Module
        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Les news', array(), $this->domain);
        }
        $tab->class_name = 'AdminEgCustomSingleBloc';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminEgDigital');
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
        

        return parent::install()
            && $this->createTabs() 
            && $this->registerHook('displayHome');
    }

    /**
     * @see Module::uninstall()
     */
    public function uninstall()
    {
        
        $this->removeTabs('AdminEgCustomSingleBloc'); 
        return parent::uninstall();
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }
 
    public function clearCache()
    {
        $this->_clearCache($this->templateFile);
    }

    public function hookDisplayHome()
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId($this->name))) {
            $title =  Configuration::get('EG_CUSTOM_BLOC_TITLE');
            $desc = Configuration::get('EG_CUSTOM_BLOC_DESC'); 
            $text_btn = Configuration::get('TEXT_BUTTON');
            $Lien_btn = Configuration::get('Lien_BUTTON');
            $img = '/modules/egcustomsinglebloc/views/img/'. Configuration::get('EG_CUSTOM_BLOC_IMG');
            $img_mobile = '/modules/egcustomsinglebloc/views/img/'. Configuration::get('EG_CUSTOM_BLOC_IMG_MOBILE');
            $video = Configuration::get('EG_CUSTOM_BLOC_VIMEO');
            $type_video = Configuration::get('type_video');
      
            $status = Configuration::get('EG_CUSTOM_BLOC_STATUS');
           
            $this->context->smarty->assign(array(
                'title' => $title,
                'desc' => $desc,
                'text_btn' => $text_btn,
                'Lien_btn' => $Lien_btn,
                'img' =>$img,
                'img_mobile' => $img_mobile,
                'type_video' => $type_video,
                'video' => $video,
                'status' => $status,
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
            $this->postImage('EG_CUSTOM_BLOC_IMG');
            $this->postImage('EG_CUSTOM_BLOC_IMG_MOBILE');
            Configuration::updateValue('EG_CUSTOM_BLOC_VIMEO', Tools::getValue('EG_CUSTOM_BLOC_VIMEO'));
            Configuration::updateValue('type_video', Tools::getValue('type_video'));
            Configuration::updateValue('EG_CUSTOM_BLOC_TITLE', Tools::getValue('EG_CUSTOM_BLOC_TITLE'));
            Configuration::updateValue('EG_CUSTOM_BLOC_DESC', Tools::getValue('EG_CUSTOM_BLOC_DESC'));
            Configuration::updateValue('TEXT_BUTTON', Tools::getValue('TEXT_BUTTON'));
            Configuration::updateValue('Lien_BUTTON', Tools::getValue('Lien_BUTTON'));
            Configuration::updateValue('EG_CUSTOM_BLOC_STATUS', Tools::getValue('EG_CUSTOM_BLOC_STATUS'));  
             
        
        } 
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
            'EG_CUSTOM_BLOC_IMG' => Configuration::get('EG_CUSTOM_BLOC_IMG'),
            'EG_CUSTOM_BLOC_VIMEO' => Configuration::get('EG_CUSTOM_BLOC_VIMEO'),
            'type_video' => Configuration::get('type_video'),
            'EG_CUSTOM_BLOC_TITLE' => Configuration::get('EG_CUSTOM_BLOC_TITLE'),
            'EG_CUSTOM_BLOC_DESC' => Configuration::get('EG_CUSTOM_BLOC_DESC'),
            'TEXT_BUTTON' => Configuration::get('TEXT_BUTTON'),
            'Lien_BUTTON' => Configuration::get('Lien_BUTTON'),
            'EG_CUSTOM_BLOC_STATUS' => Configuration::get('EG_CUSTOM_BLOC_STATUS'),
            'EG_CUSTOM_BLOC_IMG_MOBILE' => Configuration::get('EG_CUSTOM_BLOC_IMG_MOBILE'),
        );
    }
    public function getAvailableVideoType()
    {
        return [
            ['id_video_type' => 'video_type_image', 'type_name' => $this->l('Image')],
            ['id_video_type' => 'video_type_youtube', 'type_name' => $this->l('Youtube')],
            ['id_video_type' => 'video_type_vimeo', 'type_name' => $this->l('Vimeo')],
            ['id_video_type' => 'video_type_other', 'type_name' => $this->l('Source')], 
        ];
    }
    /**
     * @return array
     */
    protected function getConfigForm()
    {
        $src = $this->img_path . Configuration::get('EG_CUSTOM_BLOC_IMG'); 
        $image = '<br/><img class="bloc_img" width="200" alt="" src="' .$src.'" /><br/>';
        $src_mobile = $this->img_path . Configuration::get('EG_CUSTOM_BLOC_IMG_MOBILE');
        $image_mobile = '<br/><img class="bloc_img" width="200" alt="" src="' .$src_mobile.'" /><br/>';
        return array(
            'form' => array(
                'tinymce' => true,
                'legend' => array(
                    'title' => $this->trans('Configuration du bloc', array(), $this->domain),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Titre', array(), $this->domain),
                        'name' => 'EG_CUSTOM_BLOC_TITLE',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans('Description', array(), $this->domain),
                        'name' => 'EG_CUSTOM_BLOC_DESC',
                        'rows' => 5,
                        'cols' => 40,
                        'autoload_rte' => true,
                    ), 
                    array(
                        'type' => 'file',
                        'label' => $this->l('Image'),
                        'name' => 'EG_CUSTOM_BLOC_IMG',
                        'desc' => $this->l('Télécharger une image.'),
                        'image' => $image ? $image: false,
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Image Mobile'),
                        'name' => 'EG_CUSTOM_BLOC_IMG_MOBILE',
                        'desc' => $this->l('Télécharger une image pour mobile.'),
                        'image' => $image_mobile ? $image_mobile : false,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Type video'),
                        'name' => 'type_video',
                        'multiple' => false,  
                        'options' => [
                            'query' => $this->getAvailableVideoType(),
                            'id' => 'id_video_type',
                            'name' => 'type_name',
                        ],
                        'required' => true,
                    ), 
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Vimeo Video ', array(), $this->domain),
                        'name' => 'EG_CUSTOM_BLOC_VIMEO',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Lien button', array(), $this->domain),
                        'name' => 'Lien_BUTTON',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Titre button', array(), $this->domain),
                        'name' => 'TEXT_BUTTON',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans('Affichage', array(), $this->domain),
                        'name' => 'EG_CUSTOM_BLOC_STATUS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->trans('Activé', array(), $this->domain)
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->trans('Désactivé', array(), $this->domain)
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
  
    public function postImage($key)
    {   
        $output = '';
        $types = array('gif', 'jpg', 'jpeg', 'jpe', 'png', 'svg', 'webp');
        $upload_path = $this->local_path . 'views/img/';
        $load_img = Tools::getValue($key);
      
        if (isset($_FILES[$key]) && is_uploaded_file($_FILES[$key]['tmp_name'])) {
            if ($error = ImageManager::validateUpload(
                $_FILES[$key],
                (Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024 * 1024)
            )) {
                $output .= $this->displayError($error);
            } else {
                $pathinfo = pathinfo($_FILES[$key]['name']);
                if (in_array($pathinfo['extension'], $types)) {
                    do {
                        $uniqid = sha1(microtime());
                    } while (file_exists($upload_path . $uniqid . '.' . $pathinfo['extension']));

                    if (!copy(
                        $_FILES[$key]['tmp_name'],
                        $upload_path . $uniqid . '.' . $pathinfo['extension']
                    )) {
                        $output .= $this->displayError($this->trans('File copy failed', array(), 'Modules.Egpopup'));
                    } else {
                        @unlink($_FILES['file']['tmp_name']);

                        if ($load_img && file_exists($upload_path . $load_img)) {
                            unlink($upload_path . $load_img);
                        }

                        $load_img = $uniqid . '.' . $pathinfo['extension'];
                        Configuration::updateValue($key, $load_img);
                    }
                }
            }
        }

        return $output;
    }
}
