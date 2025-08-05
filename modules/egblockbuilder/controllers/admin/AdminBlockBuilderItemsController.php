<?php

require_once _PS_MODULE_DIR_ . 'egblockbuilder/classes/EgBlockBuilderItemsClass.php';
require_once _PS_MODULE_DIR_ . 'egimportassociations/classes/EgAssociationProductsClass.php'; 
class AdminBlockBuilderItemsController extends ModuleAdminController
{
    
    protected $position_identifier = 'id_egblockbuilder_items';
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'egblockbuilder_items';
        $this->className = 'EgBlockBuilderItemsClass';
        $this->identifier = 'id_egblockbuilder_items';
        $this->_defaultOrderBy = 'position';
        $this->_defaultOrderWay = 'ASC';
        $this->toolbar_btn = null;
        $this->list_no_link = true;
        $this->lang = true;
        $this->bootstrap = true; 
        $this->addRowAction('edit');
        $this->addRowAction('delete');
       

        Shop::addTableAssociation($this->table, ['type' => 'shop']);
        parent::__construct();
        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            ]
        ];
        $this->fields_list = [
            'id_egblockbuilder_items' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ],
            'title_1' => [
                'title' => $this->l('Titre'), 
                'callback' => 'getDescriptionClean',
                'filter_key' => 'a!title'
            ], 
            'title_2' => [
                'title' => $this->l('Titre'), 
                'callback' => 'getDescriptionClean',
                'filter_key' => 'a!title_2'
            ], 
            'title_3' => [
                'title' => $this->l('Titre'), 
                'callback' => 'getDescriptionClean',
                'filter_key' => 'a!title_3'
            ], 
            'title_4' => [
                'title' => $this->l('Titre'), 
                'callback' => 'getDescriptionClean',
                'filter_key' => 'a!title_4'
            ], 
            'title_5' => [
                'title' => $this->l('Titre'), 
                'callback' => 'getDescriptionClean',
                'filter_key' => 'a!title_5'
            ],  
            'type' => [
                'title' => $this->l('Type'), 
                'callback' => 'getDescriptionClean', 
            ], 
            'active' => [
                'title' => $this->l('Affichage'),
                'align' => 'center',
                'active' => 'status',
                'class' => 'fixed-width-sm',
                'type' => 'bool',
                'orderby' => false
            ],
            'position' => [
                'title' => $this->l('Position'),
                'filter_key' => 'a!position',
                'position' => 'position',
                'align' => 'center',
                'class' => 'fixed-width-md',
            ],
        ];
       
    }
    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        // Start the session if it is not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Clear the existing session value for id_egblockbuilder
        unset($_SESSION['id_egblockbuilder']);

        // Retrieve the new id_egblockbuilder value from the URL
        $id_egblockbuilder = Tools::getValue('id_egblockbuilder');

        // Store the new value in the session
        if ($id_egblockbuilder) {
            $_SESSION['id_egblockbuilder'] = (int)$id_egblockbuilder;
        }
        // Add the WHERE condition if id_egblockbuilder is present
        if ($id_egblockbuilder) {
            $this->_where .= ' AND a.id_egblockbuilder = ' . (int)$id_egblockbuilder;
        }
        
        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
    }
    /**
     * Update Positions Logo
     */
    public function ajaxProcessUpdatePositions()
    {
        $positions = Tools::getValue('egblockbuilder_items'); // Use the correct table name

        $id_egblockbuilder = $_SESSION['id_egblockbuilder'];

        if (is_array($positions) && !empty($positions)) {
            foreach ($positions as $newPosition => $value) {
                $pos = explode('_', $value);

                if (isset($pos[2])) {
                    $id_egblockbuilder_items = (int)$pos[2];
                    if ($item = new EgBlockBuilderItemsClass($id_egblockbuilder_items)) {
                        $item->position = $newPosition;
                        if (!$item->update()) {
                            echo '{"hasError" : true, "Can not update ' . (int)$id_egblockbuilder_items . ' to position ' . (int)$newPosition . ' "}';
                        }
                    } else {
                        echo '{"hasError" : true, "This tab (' . (int)$id_egblockbuilder_items . ') can\'t be loaded"}';
                    }
                }
            }
            // Ensure no duplicate positions
            $this->fixDuplicatePositions($id_egblockbuilder);
            echo '{"success" : true}';
        } else {
            echo '{"hasError" : true, "Invalid positions data"}';
        }
    }

    private function fixDuplicatePositions($id_egblockbuilder)
    {
        $query = new DbQuery();
        $query->select('eg.`id_egblockbuilder_items`, eg.`position`');
        $query->from('egblockbuilder_items', 'eg');
        $query->where('eg.`id_egblockbuilder` =  '.(int) $id_egblockbuilder);
        $query->orderBy('eg.`position` ASC');
        $tabs = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        $position = 0;
        foreach ($tabs as $tab) {
            $position++;
            Db::getInstance()->execute('
                UPDATE `'._DB_PREFIX_.'egblockbuilder_items`
                SET `position` = '.(int)$position.'
                WHERE `id_egblockbuilder_items` = '.(int)$tab['id_egblockbuilder_items']
            );
        }
    }
    /**
     * @param $description
     * @return string Content without html
     */
    public static function getDescriptionClean($description)
    { 
        return Tools::getDescriptionClean($description);
    }
    
    
    // Helper function to check if the current category has the selected category as a child
    private function hasSelectedChild($currentCategoryId, $selected, $children)
    {
        foreach ($children as $child) {
            if ($child['id_category'] === $selected) {
                return true; // The selected category is a direct child
            }
            
            // Check recursively if the selected category is a descendant
            if (isset($child['children']) && !empty($child['children'])) {
                if ($this->hasSelectedChild($currentCategoryId, $selected, $child['children'])) {
                    return true; // Found a matching descendant
                }
            }
        }
    
        return false; // No match found
    }
    
    public function renderForm()
    {

        $html_product = '<div id="selected_products" class="row">
                            <div class="col-md-12">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12">
                                            
                                            <div class="row">
                                                <div class="col-md-12 button_info" >
                                                    <button type="button" class="btn btn-outline-primary sensitive add" id="add_product_arrive" data-id="" data-title="">
                                                        <i class="material-icons">add_circle</i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row card">
                                                <div class="col-lg-12 col-md-12 card-block">
                                                    <table class="table item" id="eg-list-item">
                                                                <thead>
                                                                    <tr class="nodrag nodrop">
                                                                        <th class=""></th>
                                                                        <th class="">
                                                                            <span class="title_box active">ID</span>
                                                                        </th>
                                                                        <th class="">
                                                                            <span class="title_box active">Produit</span>
                                                                        </th>   
                                                                        <th class="">
                                                                            <span class="title_box"></span>
                                                                        </th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="selected_products_row_position ui-sortable" style="">
                        
                                                                </tbody>
                                                            </table><div class="card">
                                                        
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
            $html_double = '<div id="added_double" class="row">
                                <div class="col-md-12">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12 button_info">
                                                        <button type="button" class="btn btn-outline-primary sensitive add_4 add_double" data-type="4" data-title="">
                                                            <i class="material-icons">add_circle</i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary sensitive add_5 add_double" data-type="5" data-title="">
                                                            <i class="material-icons">add_circle</i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row card">
                                                    <div class="col-lg-12 col-md-12 card-block">
                                                        <table class="table item" id="eg-list-item">
                                                            <thead>
                                                                <tr class="nodrag nodrop"> 
                                                                    <th class="center"><span class="title_box active">ID</span></th>
                                                                    <th class="center"><span class="title_box active">Image desktop</span></th>
                                                                    <th class="center"><span class="title_box active">Image mobile</span></th>
                                                                    <th class="center"><span class="title_box active">Type source</span></th>
                                                                    <th class="center"><span class="title_box active">Code video</span></th>
                                                                    <th class="double_designation"><span class="title_box active">Designation</span></th>
                                                                    <th class="center"><span class="title_box active">Status</span></th>
                                                                    <th class="center"><span class="title_box">Actions</span></th>
                                                                    <th class="center"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="added_double_row_position_4 ui-sortable"></tbody>
                                                            <tbody class="added_double_row_position_5 ui-sortable"></tbody>
                                                        </table>
                                                        <div class="card"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
        if (!($obj = $this->loadObject(true))) {
            return;
        }
        
        $idLang = (int) Context::getContext()->language->id;
        $products = Product::getProducts($idLang, 1, 5000, 'id_product', 'asc', false, 1);
        foreach ($products as &$product) {
            // Get the category name for the product's default category
            $category = new Category($product['id_category_default'], $idLang);
            $categoryName = $category->name;
            if ($categoryName ) {$categoryName = ' => '.$categoryName;}
            // Format the product's name with reference, product name, and category name
            $product['name'] = $product['reference'] . '--' . $product['name'] . $categoryName;
        }
        $id_lang = (int) Context::getContext()->language->id;
        if ($obj->nb_produit) {
            $nb_produit = $obj->nb_produit ;
        } else {
            $nb_produit = 1;
        }
        if ($obj->chosen_products) {
            $chosen_products = $obj->chosen_products ;
        } else {
            $chosen_products = 0;
        }
        
        $id_egblockbuilder = (int) Tools::getValue('id_egblockbuilder');
       
        $banner = ''; 
        if (!empty($obj->banner)) {
            $src = $this->module->img_path  . $obj->banner;
            $banner = '<br/><img class="bloc_img" width="200" alt="" src="' .$src.'" /><br/>';
        } else {
            $banner = '';
        }
        $banner_mobile = '';
        if (!empty($obj->banner_mobile)) {
            $src = $this->module->img_path  . $obj->banner_mobile;
            $banner_mobile = '<br/><img class="bloc_img" width="200" alt="" src="' .$src.'" /><br/>';
        } else {
            $banner_mobile = '';
        } 
 
        $last_position  = EgBlockBuilderItemsClass::getLastPosition($id_egblockbuilder) + 1;
       
 
        $categories = Category::getNestedCategories($this->context->language->id);
        
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Block Builder Item'),
            ),
            'input' => array( 
                array(
                    'type' => 'hidden',
                    'label' => $this->trans('Label:', array(), 'Modules.EgBlockBuilder.Admin'),
                    'name' => 'id_egblockbuilder',
                    'value' => $id_egblockbuilder,
                    'required' => true,
                ), 
                array(
                    'type' => 'hidden', 
                    'name' => 'position', 
                ), 
                array(
                    'type' => 'select',
                    'label' => $this->l('Type'),
                    'name' => 'type',
                    'options' => array(
                        'query' => array(
                            array('id_option' => '1', 'name' => 'Produits'),
                            array('id_option' => '2', 'name' => 'Titre + image + lien'),
                            array('id_option' => '3', 'name' => 'Titre + text + bouton'),
                            array('id_option' => '4', 'name' => 'Titre + text + multiple images'), 
                            array('id_option' => '5', 'name' => 'Miultiples images avec designation'),
                        ),
                        'id' => 'id_option',
                        'name' => 'name'
                    ),
                    'required' => true,
                ),
                //'id_option' => '1', 'name' => 'Produits',
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Titre du bloc'),
                    'name' => 'title_1',
                    'id' => 'title_1',
                    'required' => false,
                    'autoload_rte' => true,
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                ),
                array(
                    'type' => 'html',
                    'label' => $this->l('Ajouter'),
                    'name' => 'line_products',
                    'required' => false, 
                    'html_content' => '
                        <div class="line_products" style="margin: 20px 0;">
                            <hr style="border: 0; border-top: 4px solid #78C4D8; margin: 20px 0;">
                        </div>
                        <!-- You can add more HTML content here if needed -->
                    '
                ),
                array(
                    'type' => 'select',
                    'class' => 'chosen',
                    'label' => $this->l('Séléctionner un produit'),
                    'name' => 'productIds',
                    'id' => 'productIds',
                    'multiple' => false,
                    'tab' => 'blocProduct', 
                    'options' => array(
                        'query' => $products,
                        'id' => 'id_product',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'html',
                    'label' => $this->l('Enregistrer'),
                    'name' => 'products',
                    'required' => false, 
                    'html_content' => $html_product
                ),
                array(
                    'type' => 'hidden',
                    'label' => $this->trans('Label:', array(), 'Modules.EgBlockBuilder.Admin'),
                    'name' => 'chosen_products',
                    'value' => $chosen_products
                ),
                array(
                    'type' => 'html',
                    'label' => $this->l('Nombre de produits'),
                    'name' => 'nb_produit',
                    'required' => false, 
                    'html_content' => '<input type="number" value="'.$nb_produit.'" name="nb_produit" id="nb_produit">'
                ), 
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Titre du bloc'),
                    'name' => 'title_2',
                    'id' => 'title_2',
                    'required' => false,
                    'autoload_rte' => true,
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Bannière:'),
                    'name' => 'banner', 
                    'image' => !empty($obj->banner) ? '<br/><img class="bloc_img" width="200" alt="" src="' . $this->module->img_path . $obj->banner . '" /><br/>' : false,
                    'desc' => $this->l('Upload an image for your top banner. The recommended dimensions are 556 x 555px if you are using the default theme.')
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Bannière mobile:'),
                    'name' => 'banner_mobile', 
                    'image' => !empty($obj->banner_mobile) ? '<br/><img class="bloc_img" width="200" alt="" src="' . $this->module->img_path . $obj->banner_mobile . '" /><br/>' : false,
                    'desc' => $this->l('Upload an image for your top banner. The recommended dimensions are 556 x 555px if you are using the default theme.')
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Type video'),
                    'name' => 'type_video_banniere',
                    'multiple' => false,  
                    'options' => [
                        'query' => $this->getAvailableVideoType(),
                        'id' => 'id_video_type',
                        'name' => 'type_name',
                    ],
                    'required' => false,
                ), 
                array(
                    'type' => 'text',
                    'label' => $this->l('Code video'),
                    'name' => 'vide_banniere',
                    'id' => 'video_banniere',
                    'required' => false,
                    'lang' => false, 
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Lien bouton'),
                    'name' => 'link_button',
                    'id' => 'link_button', // Unique ID for dynamic handling
                    'required' => false,
                    'lang' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Titre bouton'),
                    'name' => 'text_button',
                    'id' => 'text_button',  
                    'required' => false,
                    'lang' => false,
                ),   
                // 'id_option' => '3', 'name' => 'Titre + text + bouton',
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Titre du bloc'),
                    'name' => 'title_3',
                    'id' => 'title_3',
                    'required' => false,
                    'autoload_rte' => true,
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Text du bloc'),
                    'name' => 'text_3',
                    'id' => 'text_3',
                    'required' => false,
                    'autoload_rte' => true,
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Lien bouton'),
                    'name' => 'link_button_3',
                    'id' => 'link_button_3', // Unique ID for dynamic handling
                    'required' => false,
                    'lang' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Titre bouton'),
                    'name' => 'text_button_3',
                    'id' => 'text_button_3',  
                    'required' => false,
                    'lang' => false,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->trans('Background'), array(), 'Modules.Egadvancedmenu.Admin',
                    'name' => 'background_color',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Gris', array(), 'Modules.Egadvancedmenu.Admin')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Transparent', array(), 'Modules.Egadvancedmenu.Admin')
                        )
                    )
                ),
                //'id_option' => '4', 'name' => 'Titre + text + double image'
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Titre du bloc'),
                    'name' => 'title_4',
                    'id' => 'title_4',
                    'required' => false,
                    'autoload_rte' => true,
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                ), 
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Text du bloc'),
                    'name' => 'text_4',
                    'id' => 'text_4',
                    'required' => false,
                    'autoload_rte' => true,
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                ),
                //'id_option' => '5', 'name' => 'Doubles images'
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Titre du bloc'),
                    'name' => 'title_5',
                    'id' => 'title_5',
                    'required' => false,
                    'autoload_rte' => true,
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                ),
                array(
                    'type' => 'html',
                    'label' => $this->l('Ajouter vos elements'),
                    'name' => 'line_double',
                    'required' => false, 
                    'html_content' => '
                        <div class="line_double" style="margin: 20px 0;">
                            <hr style="border: 0; border-top: 4px solid #78C4D8; margin: 20px 0;">
                        </div>
                        <!-- You can add more HTML content here if needed -->
                    '
                ),  
                array(
                    'type' => 'file',
                    'label' => $this->l('Image desktop'),
                    'name' => 'double_image',   
                    'image' => !empty($obj->double_image) ? '<br/><img class="bloc_img" width="200" alt="" src="' . $this->module->img_path . $obj->double_image . '" /><br/>' : false,
                    'desc' => $this->l('Upload an image for your top banner. The recommended dimensions are 556 x 555px if you are using the default theme.')
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Image mobile:'),
                    'name' => 'double_image_mobile',   
                    'image' => !empty($obj->double_image_mobile) ? '<br/><img class="bloc_img" width="200" alt="" src="' . $this->module->img_path . $obj->double_image_mobile . '" /><br/>' : false,
                    'desc' => $this->l('Upload an image for your top banner. The recommended dimensions are 556 x 555px if you are using the default theme.')
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Type video'),
                    'name' => 'type_video_double',
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
                    'label' => $this->l('Code video'),
                    'name' => 'video_double',
                    'id' => 'video_double',
                    'required' => false,
                    'lang' => false, 
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Designation'),
                    'name' => 'text_double',
                    'id' => 'text_double',
                    'required' => false,
                    'autoload_rte' => true,
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                ),
               
                array(
                    'type' => 'html',
                    'label' => $this->l('Enregistrer'),
                    'name' => 'html_double',
                    'required' => false, 
                    'html_content' => $html_double
                ),
                
                array(
                    'type' => 'switch',
                    'label' => $this->trans('Affichage'), array(), 'Modules.Egadvancedmenu.Admin',
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Activé', array(), 'Modules.Egadvancedmenu.Admin')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Désactivé', array(), 'Modules.Egadvancedmenu.Admin')
                        )
                    )
                ),


            ),
            'submit' => array(
                'title' => $this->l('Sauvegarder'),
            ),
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
     * AdminController::init() override
     * @see AdminController::init()
     */
    public function init()
    {
        parent::init();
        $id_egblockbuilder = (int) Tools::getValue('id_egblockbuilder');
        $this->toolbar_btn['new'] = array(
            'href' => self::$currentIndex.'&id_egblockbuilder='.$id_egblockbuilder.'&add'.$this->table.'&token='.$this->token,
            'desc' => $this->l('Add New')
        );
        if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
            $this->_where = ' AND b.`id_shop` = ' . (int) Context::getContext()->shop->id;
        }
        if ($this->action && $this->action == 'save') { 
            $last_id = EgBlockBuilderItemsClass::getLastAdded() + 1; 
        } else { 
        }
       
        
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
    /**
     * @see AdminController::initProcess()
     */
    public function processNew()
    { 
        parent::initProcess();
         
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
           
            $banner = $this->stUploadImage('banner'); 
             
            if (isset($banner['image']) && !empty($banner['image'] )) {
                $_POST['banner']= $banner['image']; 
            } else{
                $_POST['banner'] = $obj->banner ;
            }  

            $banner_mobile = $this->stUploadImage('banner_mobile'); 
             
            if (isset($banner_mobile['image']) && !empty($banner_mobile['image'] )) {
                $_POST['banner_mobile']= $banner_mobile['image']; 
            } else{
                $_POST['banner_mobile'] = $obj->banner_mobile ;
            }
  

        } 
        $result = parent::postProcess();
        if (array_key_exists('deleteegblockbuilder_items', $_GET)) { 
            $id_egblockbuilder = (int)Tools::getValue('id_egblockbuilder');
            $link = $this->context->link->getAdminLink('AdminBlockBuilderItems', true, [], ['id_egblockbuilder' => $id_egblockbuilder]);
            Tools::redirectAdmin($link);
        }
        $id_egblockbuilder = (int)Tools::getValue('id_egblockbuilder');
        
        if ($this->action && $this->action == 'save') { 
            $id_egblockbuilder = (int)Tools::getValue('id_egblockbuilder');
            $link = $this->context->link->getAdminLink('AdminBlockBuilderItems', true, [], ['id_egblockbuilder' => $id_egblockbuilder]);
            Tools::redirectAdmin($link);

        }
        return $result;
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
        $types = array('gif', 'jpg', 'jpeg', 'jpe', 'png', 'svg', 'webp', 'avif');
        if (isset($_FILES[$item]) && isset($_FILES[$item]['tmp_name']) && !empty($_FILES[$item]['tmp_name'])) {
            $name = str_replace(strrchr($_FILES[$item]['name'], '.'), '', $_FILES[$item]['name']);
            
            $imageSize = @getimagesize($_FILES[$item]['tmp_name']);
            
            if (!empty($imageSize) && ImageManager::isCorrectImageFileExt($_FILES[$item]['name'], $types)) {
                
                $imageName = explode('.', $_FILES[$item]['name']);
                $imageExt = $imageName[count($imageName) - 1]; // Get the extension correctly
                $tempName = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                $coverImageName = $name . '-' . rand(0, 1000) . '.' . $imageExt;
                $destinationFile = _PS_MODULE_DIR_ . $this->module->name . '/views/img/' . $coverImageName; 
    
                if (!is_dir(_PS_TMP_IMG_DIR_)) {
                    mkdir(_PS_TMP_IMG_DIR_, 0755, true);
                }
    
                if ($upload_error = ImageManager::validateUpload($_FILES[$item])) {
                    $result['error'][] = $upload_error;
                } elseif (!$tempName || !move_uploaded_file($_FILES[$item]['tmp_name'], $tempName)) {
                    $result['error'][] = $this->l('An error occurred during move image.');
                } else {
                    if ($imageExt != "webp") {
                        if (!ImageManager::resize($tempName, $destinationFile, null, null, $imageExt)) {
                            $result['error'][] = $this->l('An error occurred during the image upload.');
                        }
                    } else {
                        $cp = copy($tempName, $destinationFile);
                        if (!$cp) { 
                            $result['error'][] = $this->l('File copy failed.');
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
        }
        return $result;
    }
     
        /**
     * Save Item EG Marque
     */
    public function ajaxProcessSaveEgDetailsArrive()
    {  
        $id_egblockbuilder_items = (int)Tools::getValue('id_egblockbuilder_items');
        $chosen_products = Tools::getValue('chosen_products');
        
        if ($chosen_products && $id_egblockbuilder_items) {
            // Update existing item
            $updated = EgBlockBuilderItemsClass::updateItem_arrive(
                $id_egblockbuilder_items, 
                $chosen_products,
            );
            if ($updated) {
                die(json_encode(true));
            } else {
                die(json_encode(false));
            }
        }
    }

    public function ajaxProcessSaveMultipleImages()
    {
        $id_egblockbuilder_items = (int)Tools::getValue('id_egblockbuilder_items');
        $type = Tools::getValue('type');
        $double_image = $this->stUploadImage('double_image');
        $double_image_mobile = $this->stUploadImage('double_image_mobile');
        $type_video_double = Tools::getValue('type_video_double');
        $video_double = Tools::getValue('video_double');
        $text_double = Tools::getValue('text_double');
        if ($id_egblockbuilder_items == 0) {
            $id_egblockbuilder_items = (int)Db::getInstance()->getValue('SELECT MAX(id_egblockbuilder_items) FROM ' . _DB_PREFIX_ . 'egblockbuilder_items') + 1;
        }
        $data = [
            'id_egblockbuilder_items' => $id_egblockbuilder_items,
            'type' => $type,
            'double_image' => $double_image['image'],
            'double_image_mobile' => $double_image_mobile['image'],
            'text_double' => $text_double,
            'video' => $type_video_double,
            'video_double' => $video_double,
            'active' => 1
        ]; 
        $inserted = Db::getInstance()->insert('multiple_images', $data);

        if ($inserted) {
            $data = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'multiple_images WHERE id_egblockbuilder_items = ' . (int)$id_egblockbuilder_items);
            die(json_encode(['data' => $data, 'success' => true]));
        } else {
            die(json_encode(['success' => false]));
        }
    }

    public function ajaxProcessGetAllMultipleImages()
    {    
        $id_egblockbuilder_items = (int)Tools::getValue('id_egblockbuilder_items');
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'multiple_images WHERE id_egblockbuilder_items = ' . (int)$id_egblockbuilder_items;
        $data = Db::getInstance()->executeS($sql);
        die(json_encode(['data' => $data, 'success' => true]));
    }

    public function ajaxProcessDeleteMultipleImages()
    {
        $id = (int)Tools::getValue('id');
        $deleted = Db::getInstance()->delete('multiple_images', 'id = ' . (int)$id);
        if ($deleted) {
            die(json_encode(['success' => true]));
        } else {
            die(json_encode(['success' => false]));
        }
    }

    public function ajaxProcessGetMultipleImage()
    {
        $id = (int)Tools::getValue('id');
        $data = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'multiple_images WHERE id = ' . (int)$id);
        if ($data) {
            die(json_encode(['data' => $data, 'success' => true]));
        } else {
            die(json_encode(['success' => false]));
        }
    }

    public function ajaxProcessUpdateMultipleImages()
    {
        $id = (int)Tools::getValue('id');
        $id_egblockbuilder_items = (int)Tools::getValue('id_egblockbuilder_items');
        $type = Tools::getValue('type');
        $double_image = $this->stUploadImage('double_image');
        $double_image_mobile = $this->stUploadImage('double_image_mobile');
        $type_video_double = Tools::getValue('type_video_double');
        $video_double = Tools::getValue('video_double');
        $text_double = Tools::getValue('text_double');

        $data = [
            'id_egblockbuilder_items' => $id_egblockbuilder_items,
            'type' => $type,
            'double_image' => !empty($double_image['image']) ? $double_image['image'] : Db::getInstance()->getValue('SELECT double_image FROM ' . _DB_PREFIX_ . 'multiple_images WHERE id = ' . (int)$id),
            'double_image_mobile' => !empty($double_image_mobile['image']) ? $double_image_mobile['image'] : Db::getInstance()->getValue('SELECT double_image_mobile FROM ' . _DB_PREFIX_ . 'multiple_images WHERE id = ' . (int)$id),
            'text_double' => $text_double,
            'video' => $type_video_double,
            'video_double' => $video_double,
            'active' => 1
        ];

        $updated = Db::getInstance()->update('multiple_images', $data, 'id = ' . (int)$id);

        if ($updated) {
            die(json_encode(['success' => true]));
        } else {
            die(json_encode(['success' => false]));
        }
    }

    public function ajaxProcessToggleImageStatus()
    {
        $id = (int)Tools::getValue('id');
        $status = (int)Tools::getValue('status');

        $updated = Db::getInstance()->update('multiple_images', ['active' => $status], 'id = ' . (int)$id);

        if ($updated) {
            die(json_encode(['success' => true]));
        } else {
            die(json_encode(['success' => false]));
        }
    }
}
