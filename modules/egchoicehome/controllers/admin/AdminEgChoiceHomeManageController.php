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

/**
 * @property BlocReassurance $object
 */

class AdminEgChoiceHomeManageController extends ModuleAdminController
{
    protected $position_identifier = 'id_eg_bloc_reassurance';
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'eg_bloc_reassurance';
        $this->className = 'BlocReassurance';
        $this->identifier = 'id_eg_bloc_reassurance';
        $this->_defaultOrderBy = 'position';
        $this->_defaultOrderWay = 'ASC';
        $this->toolbar_btn = null;
        $this->list_no_link = true;
        $this->lang = true;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        Shop::addTableAssociation($this->table, array('type' => 'shop'));

        parent::__construct();

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );

        $this->fields_list = array(
            'id_eg_bloc_reassurance' => array(
                'title' => $this->l('Id')
            ),
            'icon' => array(
                'title' => $this->l('Image'),
                'type' => 'text',
                'callback' => 'showBanner',
                'callback_object' => 'BlocReassurance',
                'class' => 'fixed-width-xxl',
                'search' => false,
            ),
            'title' => array(
                'title' => $this->l('Title'),
                'filter_key' => 'b!title',
            ),
            'active' => array(
                'title' => $this->l('Displayed'),
                'align' => 'center',
                'active' => 'status',
                'class' => 'fixed-width-sm',
                'type' => 'bool',
                'orderby' => false
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'filter_key' => 'a!position',
                'position' => 'position',
                'align' => 'center',
                'class' => 'fixed-width-md',
            ),
        );
    }

    /**
     * @param $description
     * @return string Content without html
     */
    public static function getDescriptionClean($description)
    {
        return Tools::getDescriptionClean($description);
    }

    /**
     * AdminController::init() override
     * @see AdminController::init()
     */
    public function init()
    {
        parent::init();

        if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
            $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
        }
    }

    /**
     * @see AdminController::initPageHeaderToolbar()
     */
    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_banner'] = array(
                'href' => self::$currentIndex.'&addeg_bannerslider&token='.$this->token,
                'desc' => $this->l('Add new banner'),
                'icon' => 'process-icon-new'
            );
        }
        parent::initPageHeaderToolbar();
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
        if (isset($_FILES[$item]) && isset($_FILES[$item]['tmp_name']) && !empty($_FILES[$item]['tmp_name'])) {
            $name = str_replace(strrchr($_FILES[$item]['name'], '.'), '', $_FILES[$item]['name']);
            $imageSize = @getimagesize($_FILES[$item]['tmp_name']);
            if ($this->isCorrectImageFileExt($_FILES[$item]['name'])) {
                $imageName = explode('.', $_FILES[$item]['name']);
                $imageExt = $imageName[1];
                $coverImageName = $name .'-'.rand(0, 1000).'.'.$imageExt;
                $destinationFile = _PS_MODULE_DIR_ . 'egchoicehome/views/img/'.$coverImageName;
                if (!move_uploaded_file($_FILES[$item]['tmp_name'], $destinationFile)) {
                    $result['error'][] = $this->l('An error occurred during move image.');
                }
                if (!count($result['error'])) {
                    $result['image'] = $coverImageName;
                if (!empty($imageSize)) {
                    $result['width'] = $imageSize[0];
                    $result['height'] = $imageSize[1];
                }
                }  
                return $result;
            }
        } else {
            return $result;
        }
    }
    /**
     * Check if image file extension is correct.
     *
     * @param string $filename Real filename
     * @param array|null $authorizedExtensions
     *
     * @return bool True if it's correct
     */
    public static function isCorrectImageFileExt($filename, $authorizedExtensions = null)
    {
        // Filter on file extension
        if ($authorizedExtensions === null) {
            $authorizedExtensions = array('gif', 'jpg', 'jpeg', 'jpe', 'png', 'svg');
        }
        $nameExplode = explode('.', $filename);
        if (count($nameExplode) >= 2) {
            $currentExtension = strtolower($nameExplode[count($nameExplode) - 1]);
            if (!in_array($currentExtension, $authorizedExtensions)) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }
 
    /**
     * AdminController::postProcess() override
     * @see AdminController::postProcess()
     */
    public function postProcess()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }
        // Upload FILES EG Banner
        if ($this->action && $this->action == 'save') {
            $image = $this->stUploadImage('icon'); 
            if (isset($image['image']) && !empty($image['image'] )) {
                $_POST['icon']= $image['image']; 
            } else{
                $_POST['icon'] = $obj->icon ;
            }  
        }
        // Delete Images EG Banner
        if (Tools::isSubmit('forcedeleteImage') || Tools::getValue('deleteImage')) {
            $champ = Tools::getValue('champ');
            $imgValue = Tools::getValue('image');
            EgBannerSliderClass::updateEgBannerImag($champ, $imgValue);
            if (Tools::isSubmit('forcedeleteImage')) {
                Tools::redirectAdmin(self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminEgBannerSlider'));
            }
        }

        return parent::postProcess();
    }

    /**
     * @see AdminController::initProcess()
     */
    public function initProcess()
    {
        $this->context->smarty->assign(array(
            'uri' => $this->module->getPathUri()
        ));
        parent::initProcess();
    }

    public function getHookList()
    {
        $hooks = array();
        foreach ($this->myHook as $key => $hook)
        {
            $hooks[$key]['key'] = $hook;
            $hooks[$key]['name'] = $hook;
        }
        return $hooks;
    }

    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }  
 
        if (isset($obj->icon[1])) {
            $src = $this->module->img_path  . $obj->icon[1];
            $image = '<br/><img class="bloc_img" width="200" alt="" src="' .$src.'" /><br/>';
        } else {
            $image = '';
        }
        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Page'),
                'icon' => 'icon-folder-close'
            ),
            // custom template
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Title:'),
                    'name' => 'title',
                    'lang' => true,
                    'desc' => $this->l('Please enter a title for the banner.'),
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Description'),
                    'name' => 'description',
                    'autoload_rte' => true,
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}',
                    'desc' => $this->l('Please enter a description for the banner.')
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Image Desktop:'),
                    'name' => 'icon', 
                    'image' => $image ? $image: false,
                    //'delete_url' => self::$currentIndex.'&'.$this->identifier .'='.$obj->id.'&token='.$this->token.'&champ=image&deleteImage=1',
                    'desc' => $this->l('Upload an image for your top banner. The recommended dimensions are 556 x 555px if you are using the default theme.')
                ),    
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display'),
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
            ),
             'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );


        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association'),
                'name' => 'checkBoxShopAsso',
            );
        }

        return parent::renderForm();
    }

    /**
     * Update Positions Banner
     */
    public function ajaxProcessUpdatePositions()
    {
        $way = (int)(Tools::getValue('way'));
        $idEgBanner = (int)(Tools::getValue('id'));
        $positions = Tools::getValue($this->table);

        foreach ($positions as $position => $value){
            $pos = explode('_', $value);

            if (isset($pos[2]) && (int)$pos[2] === $idEgBanner){
                if ($banner = new EgBannerSliderClass((int)$pos[2])){
                    if (isset($position) && $banner->updatePosition($way, $position)){
                        echo 'ok position '.(int)$position.' for tab '.(int)$pos[1].'\r\n';
                    } else {
                        echo '{"hasError" : true, "errors" : "Can not update tab '.(int)$idEgBanner.' to position '.(int)$position.' "}';
                    }
                } else {
                    echo '{"hasError" : true, "errors" : "This tab ('.(int)$idEgBanner.') can t be loaded"}';
                }

                break;
            }
        }
    }
}
