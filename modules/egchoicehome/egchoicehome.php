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

include_once(dirname(__FILE__).'/classes/BlocReassurance.php');

class EgChoiceHome extends Module {

    protected $_html = '';
    protected $templateFile;
    protected $domain;
 
    public function __construct()
    {
        $this->name = 'egchoicehome';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'Egio digital';
        $this->need_instance = 0; 
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->context = Context::getContext();
 
        parent::__construct();

        $this->domain = 'Modules.EgChoiceHome.EgChoiceHome';
        $this->displayName = $this->trans('Block Pourquoi Choisir', array(), $this->domain);
        $this->description = $this->trans('Display Block Pourquoi Choisir', array(), $this->domain);

        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', array(), $this->domain);
        $this->img_path = $this->_path.'views/img/';
        $this->templateFile = 'module:egchoicehome/views/templates/hook/choicehome.tpl';
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
            $tab->name[$lang['id_lang']] = $this->trans('Bloc choisir', array(), $this->domain);
        }
        $tab->class_name = 'AdminEgChoiceHomeBlocGeneral';
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminEgDigital');
        $tab->module = 'egchoicehome';
        $tab->icon = 'library_books';
        $tab->add();

        // Menage Module
        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Configuration', array(), $this->domain);
        }
        $tab->class_name = 'AdminEgChoiceHomeBloc';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminEgChoiceHomeBlocGeneral');
        $tab->module = 'egchoicehome';
        $tab->add();

        // Menage Banner
        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Reassurances', array(), $this->domain);
        }
        $tab->class_name = 'AdminEgChoiceHomeManage';
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminEgChoiceHomeBlocGeneral');
        $tab->module = 'egchoicehome';
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
            && $this->registerHook('displayHome');
    }

    /**
     * @see Module::uninstall()
     */
    public function uninstall()
    {
        //include(dirname(__FILE__).'/sql/uninstall.php');
        $this->removeTabs('AdminEgChoiceHomeBloc'); 
        $this->removeTabs('AdminEgChoiceHomeManage');
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
            $title =  Configuration::get('EG_CHOICE_HOME_TITLE');
            $sub_title =  Configuration::get('EG_CHOICE_HOME_TITLE_SUB');
            $desc = Configuration::get('EG_CHOICE_HOME_DESC'); 
            $btn_txt = Configuration::get('EG_CHOICE_HOME_BTN_TXT');
            $btn_url = Configuration::get('EG_CHOICE_HOME_BTN_URL');
            $img = '/modules/egchoicehome/views/img/'. Configuration::get('EG_CHOICE_HOME_IMG');
            $img_mobile = '/modules/egchoicehome/views/img/'. Configuration::get('EG_CHOICE_HOME_IMG_MOBILE');
            $status = Configuration::get('EG_CHOICE_HOME_STATUS');

            $reassurances = BlocReassurance::getCriterions(Context::getContext()->language->id, Context::getContext()->shop->id, 1);
            $src = __PS_BASE_URI__. 'modules/egchoicehome/views/img/';
            $this->context->smarty->assign(array(
                'title' => $title,
                'sub_title' => $sub_title,
                'desc' => $desc,
                'btn_txt' => $btn_txt,
                'btn_url' => $btn_url,
                'img' =>$img, 
                'img_mobile' => $img_mobile,
                'reassurances' => $reassurances,
                'src' => $src,
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
            if(Tools::getValue('EG_CHOICE_HOME_IMG')!=""){ 
                $image = $this->stUploadImage('EG_CHOICE_HOME_IMG');  
                if (isset($image['image']) && !empty($image['image'] )) {
                    $_POST['image']= $image['image']; 
                    Configuration::updateValue('EG_CHOICE_HOME_IMG', $image['image']);
                }
                
            } 
            if(Tools::getValue('EG_CHOICE_HOME_IMG_MOBILE')!=""){ 
                $image_mobile = $this->stUploadImage('EG_CHOICE_HOME_IMG_MOBILE');  
                if (isset($image_mobile['image']) && !empty($image_mobile['image'] )) {
                    $_POST['image_mobile']= $image_mobile['image']; 
                    Configuration::updateValue('EG_CHOICE_HOME_IMG_MOBILE', $image_mobile['image']);
                }
            }
            Configuration::updateValue('EG_CHOICE_HOME_TITLE', Tools::getValue('EG_CHOICE_HOME_TITLE'));
            Configuration::updateValue('EG_CHOICE_HOME_TITLE_SUB', Tools::getValue('EG_CHOICE_HOME_TITLE_SUB'));
            Configuration::updateValue('EG_CHOICE_HOME_DESC', Tools::getValue('EG_CHOICE_HOME_DESC'));
            Configuration::updateValue('EG_CHOICE_HOME_BTN_TXT', Tools::getValue('EG_CHOICE_HOME_BTN_TXT'));
            Configuration::updateValue('EG_CHOICE_HOME_BTN_URL', Tools::getValue('EG_CHOICE_HOME_BTN_URL'));
            Configuration::updateValue('EG_CHOICE_HOME_STATUS', Tools::getValue('EG_CHOICE_HOME_STATUS'));  
            
        
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
            'EG_CHOICE_HOME_IMG' => Configuration::get('EG_CHOICE_HOME_IMG'), 
            'EG_CHOICE_HOME_IMG_MOBILE' => Configuration::get('EG_CHOICE_HOME_IMG_MOBILE'),
            'EG_CHOICE_HOME_TITLE' => Configuration::get('EG_CHOICE_HOME_TITLE'),
            'EG_CHOICE_HOME_TITLE_SUB' => Configuration::get('EG_CHOICE_HOME_TITLE_SUB'),
            'EG_CHOICE_HOME_DESC' => Configuration::get('EG_CHOICE_HOME_DESC'),
            'EG_CHOICE_HOME_BTN_TXT' => Configuration::get('EG_CHOICE_HOME_BTN_TXT'),
            'EG_CHOICE_HOME_BTN_URL' => Configuration::get('EG_CHOICE_HOME_BTN_URL'),
            'EG_CHOICE_HOME_STATUS' => Configuration::get('EG_CHOICE_HOME_STATUS'),
        );
    }

    /**
     * @return array
     */
    protected function getConfigForm()
    {
        $src = $this->img_path . Configuration::get('EG_CHOICE_HOME_IMG'); 
        $image = '<br/><img class="bloc_img" width="200" alt="" src="' .$src.'" /><br/>';
        $src_mobile = $this->img_path . Configuration::get('EG_CHOICE_HOME_IMG_MOBILE'); 
        $image_mobile = '<br/><img class="bloc_img" width="200" alt="" src="' .$src_mobile.'" /><br/>';
        return array(
            'form' => array(
                'tinymce' => true,
                'legend' => array(
                    'title' => $this->trans('Configure Single Bloc', array(), $this->domain),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Title', array(), $this->domain),
                        'name' => 'EG_CHOICE_HOME_TITLE',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Sub title', array(), $this->domain),
                        'name' => 'EG_CHOICE_HOME_TITLE_SUB',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans('Description', array(), $this->domain),
                        'name' => 'EG_CHOICE_HOME_DESC',
                        'rows' => 5,
                        'cols' => 40,
                        'autoload_rte' => true,
                    ), 
                    array(
                        'type' => 'file',
                        'label' => $this->l('Image'),
                        'name' => 'EG_CHOICE_HOME_IMG',
                        'desc' => $this->l('Upload Image.'),
                        'image' => $image ? $image: false,
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Mobile Image'),
                        'name' => 'EG_CHOICE_HOME_IMG_MOBILE',
                        'desc' => $this->l('Upload Mobile Image.'),
                        'image' => $image_mobile ? $image_mobile : false,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Button Text', array(), $this->domain),
                        'name' => 'EG_CHOICE_HOME_BTN_TXT',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Button Link', array(), $this->domain),
                        'name' => 'EG_CHOICE_HOME_BTN_URL',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->trans('Displayed', array(), $this->domain),
                        'name' => 'EG_CHOICE_HOME_STATUS',
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
     /**
     * @param $item
     * @return array
     */
    protected function stUploadImage($item)
    {
       
        $result = array(
            'error' => array(),
            'image' => '',
        );
        $types = array('gif', 'jpg', 'jpeg', 'jpe', 'png', 'svg','webp','avif');
        if (isset($_FILES[$item]) && isset($_FILES[$item]['tmp_name']) && !empty($_FILES[$item]['tmp_name'])) {
            $name = str_replace(strrchr($_FILES[$item]['name'], '.'), '', $_FILES[$item]['name']);
            
            $imageSize = @getimagesize($_FILES[$item]['tmp_name']);
            
            if (!empty($imageSize) &&
                ImageManager::isCorrectImageFileExt($_FILES[$item]['name'], $types)) {
                    
                $imageName = explode('.', $_FILES[$item]['name']);
                $imageExt = $imageName[1];
                $tempName = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                $coverImageName = $name .'-'.rand(0, 1000).'.'.$imageExt;
                $destinationFile = _PS_MODULE_DIR_ . 'egchoicehome/views/img/'.$coverImageName;
                
                if ($upload_error = ImageManager::validateUpload($_FILES[$item])) {
                    $result['error'][] = $upload_error;
                } elseif (!$tempName || !move_uploaded_file($_FILES[$item]['tmp_name'], $tempName)) {
                    $result['error'][] = $this->l('An error occurred during move image.');
                } else {
                    if ( $imageExt != "webp") {
                       
                        if (!ImageManager::resize($tempName, $destinationFile, null, null, $imageExt)){
                            $result['error'][] = $this->l('An error occurred during the image upload.');
                        }
                    } else {
                        $pathinfo = pathinfo($_FILES[$item]['name']);
                        
                        $cp =  copy(
                            $tempName,
                            $destinationFile
                        );
                        if (!$cp) { 
                            $output .= $this->trans('File copy failed', array(), 'Modules.Egpopup');
                        }  
                        
                    }
                }
                if (isset($tempName)) {
                    @unlink($tempName);
                }

                if (!count($result['error'])) {
                    $result['image'] = $coverImageName;
                    $result['width'] = $imageSize[0];
                    $result['height'] = $imageSize[1];
                }
                return $result;
            }
        } else {
            return $result;
        }
    }

}
