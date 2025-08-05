<?php

require_once _PS_MODULE_DIR_.'egblockbuilder/classes/EgBlockBuilderClass.php';

class AdminBlockBuilderController extends ModuleAdminController
{
    protected $position_identifier = 'id_egblockbuilder';
    
    public function __construct()
    {
        $this->table = 'egblockbuilder';
        $this->className = 'EgBlockBuilderClass';
        $this->identifier = 'id_egblockbuilder';
        $this->_defaultOrderBy = 'position';
        $this->_defaultOrderWay = 'ASC';
        $this->toolbar_btn = null;
        $this->list_no_link = true;
        $this->lang = true;
        $this->bootstrap = true;
        $this->addRowAction('view'); // Adding the show action here
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->addRowAction('duplicate'); // Add duplicate action
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
            'id_egblockbuilder' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'callback' => 'getUrl',
                'class' => 'fixed-width-xs'
            ],
            'meta_title' => [
                'title' => $this->l('Titre'),
                'filter_key' => 'b!meta_title',
                'callback' => 'getDescriptionClean',
               
            ],
            'link_rewrite' => [
                'title' => $this->l('link_rewrite'),  
                'class' => 'visualiser_link_rewrite',
            ],
            /*
            'id_category' => [
                'title' => $this->l('Catégorie'),
                'type' => 'select',
                'list' => Category::getCategories((int)Configuration::get('PS_LANG_DEFAULT'), false),
                'filter_key' => 'a!id_category',
                'callback' => 'getCategoryName',
                'filter_type' => 'int',
            ],*/
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
            'id_egblockbuilder' => [
                'title' => $this->l('Visualiser'),
                'callback' => 'getUrl',
                'filter_key' => 'a!id_egblockbuilder',
            ],
        ];
    }

    public function init()
    {
        parent::init(); 
        if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
            $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
        }
    }

    public function postProcess()
    {
        $_POST['type'] = 'show';
        
        if (array_key_exists('duplicateegblockbuilder', $_GET)) {
            return $this->processDuplicate();
        }

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        if ($this->action && $this->action == 'save') {
            // Upload FILES EG Banner
        }

        if ($this->action && $this->action == 'show') {
            return $this->processShow();
        }

        return parent::postProcess();
    }
    public function processView()
    {
        $id_egblockbuilder = (int)Tools::getValue('id_egblockbuilder');
        $link = $this->context->link->getAdminLink('AdminBlockBuilderItems', true, [], ['id_egblockbuilder' => $id_egblockbuilder]);
        Tools::redirectAdmin($link);
    }
 
    public function processDuplicate()
    {
    
        
        $id_egblockbuilder = (int)Tools::getValue('id_egblockbuilder');
        
        // Load the original block
        $originalBlock = new EgBlockBuilderClass($id_egblockbuilder);
        
        if (!Validate::isLoadedObject($originalBlock)) {
            $this->errors[] = $this->l('Cannot load block.');
            return;
        }
    
        // Duplicate the main block
        $duplicatedBlock = $originalBlock->duplicate();
       
        // After duplicating the main block, duplicate the related items
        if ($duplicatedBlock->id) {
            // Get all items related to the original block
            $this->duplicateItems($originalBlock->id, $duplicatedBlock->id);
        }
        
        // Redirect to the edit page of the duplicated block
        $link = $this->context->link->getAdminLink('AdminBlockBuilder', true, [], ['id_egblockbuilder' => $duplicatedBlock->id]);
        Tools::redirectAdmin($link);
    }
    
    // Helper method to duplicate related items
    private function duplicateItems($original_block_id, $new_block_id)
    {
        // Get the current max position for the new block
        $sql = 'SELECT MAX(position) as max_position FROM `'._DB_PREFIX_.'egblockbuilder_items` WHERE `id_egblockbuilder` = '.(int)$new_block_id;
        $maxPosition = (int)Db::getInstance()->getValue($sql);

        // Get all items for the original block
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'egblockbuilder_items` WHERE `id_egblockbuilder` = '.(int)$original_block_id;
        $items = Db::getInstance()->executeS($sql);
        
        foreach ($items as $item) {
            // Remove the ID field to allow for a new insertion
            $old_id_egblockbuilder_items = $item['id_egblockbuilder_items'];
            unset($item['id_egblockbuilder_items']);

            // Set new parent ID
            $item['id_egblockbuilder'] = $new_block_id;
            
            // Increment position for the duplicated item
            $maxPosition++;
            $item['position'] = $maxPosition;
            
            // Insert duplicated item
            Db::getInstance()->insert('egblockbuilder_items', $item);
            
            // Get the newly inserted ID for this item
            $new_item_id = Db::getInstance()->Insert_ID();

            // Duplicate language and shop entries for this new item
            $this->duplicateLangAndShop($old_id_egblockbuilder_items, $new_item_id);

            // Duplicate related items (marques, sac, moments) for this new item
            $this->duplicateRelatedItems('multiple_images', 'id', $old_id_egblockbuilder_items, $new_item_id); 
        }
    }
    // Helper method to duplicate language and shop entries
    private function duplicateLangAndShop($original_item_id, $new_item_id)
    {
        // Duplicate items in the egblockbuilder_items_lang table
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'egblockbuilder_items_lang` WHERE `id_egblockbuilder_items` = '.(int)$original_item_id;
        $langs = Db::getInstance()->executeS($sql);
    
        foreach ($langs as $lang) {
            // Remove ID field to allow for a new insertion
            unset($lang['id_egblockbuilder_items']);
            $lang['id_egblockbuilder_items'] = $new_item_id; // Set new item ID
            
            // Insert duplicated language entry
            Db::getInstance()->insert('egblockbuilder_items_lang', $lang);
        }
    
        // Duplicate items in the egblockbuilder_items_shop table
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'egblockbuilder_items_shop` WHERE `id_egblockbuilder_items` = '.(int)$original_item_id;
        $shops = Db::getInstance()->executeS($sql);
    
        foreach ($shops as $shop) {
            // Remove ID field to allow for a new insertion
            unset($shop['id_egblockbuilder_items']);
            $shop['id_egblockbuilder_items'] = $new_item_id; // Set new item ID
    
            // Insert duplicated shop entry
            Db::getInstance()->insert('egblockbuilder_items_shop', $shop);
        }
    }
    
    // Helper method to duplicate related items (marques, sac, moments)
    private function duplicateRelatedItems($table, $id_field, $original_item_id, $new_item_id)
    {
        // Get all items for the specific related table
        $sql = 'SELECT * FROM `'._DB_PREFIX_.$table.'` WHERE `id_egblockbuilder_items` = '.(int)$original_item_id;
        $items = Db::getInstance()->executeS($sql);
    
        foreach ($items as $item) {
            // Remove ID field to allow for a new insertion
            unset($item[$id_field]);
            
            // Set new parent ID
            $item['id_egblockbuilder_items'] = $new_item_id; 
           
            // Insert the duplicated item for this related table
            Db::getInstance()->insert($table, $item);
        }
    }
    
   

    public static function getCategoryName($id_category)
    {
        $category = new Category($id_category, (int)Configuration::get('PS_LANG_DEFAULT'));
        return $category->name;
    }

    public static function getUrl($id,$element)
    {    
        $id_egblockbuilder = $element['id_egblockbuilder'];
        $link_rewrite = $element['link_rewrite'];
        $base_url = Context::getContext()->shop->getBaseURL(true, true);
        $base_url .= 'show/'.$id_egblockbuilder.'-'.$link_rewrite; 
        return $base_url;
    }

    

    public function renderForm()
    {
        
        $categories = Category::getCategories((int)Configuration::get('PS_LANG_DEFAULT'), true, false);
        $selectedCategory = EgBlockBuilderClass::getCategorySelectedById((int)Tools::getValue('id_egblockbuilder'));
         
        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Block Builder'),
            ],
            'input' => [
                [
                    'type' => 'hidden',
                    'label' => $this->l('Type'),
                    'name' => 'type',
                    'value' => 'show',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Titre page '),
                    'id' => 'name', // for copyMeta2friendlyURL compatibility
                    'name' => 'meta_title',
                    'class' => 'copyMeta2friendlyURL', 
                    'lang' => true,
                    'required' => true, 
                    'hint' => $this->trans('Invalid characters:', array(), 'Admin.Notifications.Info') . ' &lt;&gt;;=#{}'
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Réécriture du lien'),
                    'name' => 'link_rewrite', 
                    'lang' => true,
                    'required' => true, 
                    'hint' => $this->trans('Invalid characters:', array(), 'Admin.Notifications.Info') . ' &lt;&gt;;=#{}'
                ],
                [
                    'type' => 'textarea',
                    'label' => $this->l('Description'),
                    'name' => 'description', 
                    'autoload_rte' => true,
                    'lang' => true,
                    'required' => true,
                    'rows' => 5,
                    'cols' => 40, 
                ],
                [
                    'type' => 'categories',
                    'label' => $this->l('Catégorie'),
                    'name' => 'id_category',
                    'tree' => [
                        'id' => 'categories-tree',
                        'use_search' => true,
                        'use_checkbox' => false,
                        'selected_categories' => [$selectedCategory],
                    ],
                    'required' => false
                ],
                array(
                    'type' => 'switch',
                    'label' => $this->trans('Affichage'), array(), 'Modules.EgBlockBuilder.Admin',
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Activé', array(), 'Modules.EgBlockBuilder.Admin')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Désactivé', array(), 'Modules.EgBlockBuilder.Admin')
                        )
                    )
                ),
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ]
        ];

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        return parent::renderForm();
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
     * @param $value string URL Category
     * @return string URL
     */
    public static function showUrlCms($id_egblockbuilder)
    {  
       // return $value ? '<a href="' . $urlCms . '" target="_blank">' . $urlCms . '</a>' : '-';
    }
    /**
     * Update Positions Logo
     */
    public function ajaxProcessUpdatePositions()
    {
        
        $way = (int)(Tools::getValue('way'));
        $id = (int)(Tools::getValue('id'));
        $positions = Tools::getValue($this->table);

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2]) && (int)$pos[2] === $id) {
                if ($item = new EgBlockBuilderClass((int)$pos[2])) {
                    if (isset($position) && $item->updatePosition($way, $position)) {
                        echo 'ok position ' . (int)$position . ' for tab ' . (int)$pos[1] . '\r\n';
                    } else {
                        echo '{"hasError" : true, "Can not update ' . (int)$id . ' to position ' . (int)$position . ' "}';
                    }
                } else {
                    echo '{"hasError" : true, "This tab (' . (int)$id . ') can t be loaded"}';
                }
                break;
            }
        }
    }
}
