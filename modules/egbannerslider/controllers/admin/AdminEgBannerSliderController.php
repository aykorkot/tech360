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
 * @property EgBannerSliderClass $object
 */

class AdminEgBannerSliderController extends ModuleAdminController
{
    protected $position_identifier = 'id_eg_bannerslider';
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'eg_bannerslider';
        $this->className = 'EgBannerSliderClass';
        $this->identifier = 'id_eg_bannerslider';
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
            'id_eg_bannerslider' => array(
                'title' => $this->l('Id')
            ),
            'image' => array(
                'title' => $this->l('Image'),
                'type' => 'text',
                'callback' => 'showBanner',
                'callback_object' => 'EgBannerSliderClass',
                'class' => 'fixed-width-xxl',
                'search' => false,
            ),
            'title' => array(
                'title' => $this->l('Titre'),
                'filter_key' => 'b!title',
            ),
            'active' => array(
                'title' => $this->l('Affichage'),
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
                $destinationFile = _PS_MODULE_DIR_ . $this->module->name.'/views/img/'.$coverImageName; 
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
            $image = $this->stUploadImage('image');  
            $image_hover = $this->stUploadImage('image_hover'); 
            if (isset($image['image']) && !empty($image['image'] )) {
                $_POST['image']= $image['image']; 
            } else{
                $_POST['image'] = $obj->image ;
            } 
            if (isset($image_hover['image']) && !empty($image_hover['image'] )) {
                $_POST['image_hover'] = $image_hover['image'];
            } else {
                $_POST['image_hover'] = $obj->image_hover ;
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
    public function getAvailableVideoType()
    {
        return [
            ['id_video_type' => 'video_type_image', 'type_name' => $this->l('Image')],
            ['id_video_type' => 'video_type_youtube', 'type_name' => $this->l('Youtube')],
            ['id_video_type' => 'video_type_vimeo', 'type_name' => $this->l('Vimeo')],
            ['id_video_type' => 'video_type_other', 'type_name' => $this->l('Source')],
        ];
    }
    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }  
        if (isset($obj->image_hover[1])) {
            $src = $this->module->img_path  . $obj->image_hover[1];
            $image_hover = '<br/><img class="bloc_img" width="200" alt="" src="' .$src.'" /><br/>';
        } else {
            $image_hover = '';
        }
        if (isset($obj->image[1])) {
            $src = $this->module->img_path  . $obj->image[1];
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
                    'label' => $this->l('Titre:'),
                    'name' => 'title',
                    'lang' => true,
                    'desc' => $this->l('Please enter a title for the banner.'),
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Image Desktop:'),
                    'name' => 'image', 
                    'image' => $image ? $image: false,
                    //'delete_url' => self::$currentIndex.'&'.$this->identifier .'='.$obj->id.'&token='.$this->token.'&champ=image&deleteImage=1',
                    'desc' => $this->l('Téléchargez une image pour votre bannière supérieure. Les dimensions recommandées sont de 556 x 555px si vous utilisez le thème par défaut.')
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Image Hover:'),
                    'name' => 'image_hover', 
                    'image' => $image_hover ? $image_hover: false,
                    'desc' => $this->l('Upload an image for hover state. Recommended dimensions are 384 x 366px if you use the default theme.')
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
                    'label' => $this->l('Code video:'),
                    'name' => 'video_vimeo',
                    'lang' => false,
                    'desc' => $this->l('Veuillez entrer votre identifiant vidéo Vimeo.')

                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Lien'),
                    'name' => 'link',
                    'lang' => true,
                    'required' => true,
                    'desc' => $this->l('Please enter a link for the banner.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Affichage'),
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Activé')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Désactivé')
                        )
                    )
                ),
            ),
             'submit' => array(
                'title' => $this->l('Sauvegarder'),
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
