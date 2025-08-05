<?php



class AdminProduitPopulaireController extends ModuleAdminController
{
    protected $position_identifier = 'id_egproduitpopulaire';
    
    public function __construct()
    {
        $this->table = 'egproduitpopulaire';
        $this->className = 'EgProduitPopulaireClass';
        $this->identifier = 'id_egproduitpopulaire';
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
            'id_egproduitpopulaire' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ],
            'meta_title' => [
                'title' => $this->l('Titre'),
                'filter_key' => 'b!meta_title',
                'callback' => 'getDescriptionClean',
            ],
            'id_category' => [
                'title' => $this->l('Catégorie'),
                'type' => 'select',
                'list' => Category::getCategories((int)Configuration::get('PS_LANG_DEFAULT'), false),
                'filter_key' => 'a!id_category',
                'callback' => 'getCategoryName',
                'filter_type' => 'int',
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

    public function init()
    {
        parent::init();

        if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
            $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
        }
    }

    public function postProcess()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        if ($this->action && $this->action == 'save') { 
        }

        if ($this->action && $this->action == 'show') {
            return $this->processShow();
        }

        return parent::postProcess();
    }
 
    public static function getCategoryName($id_category)
    {
        $category = new Category($id_category, (int)Configuration::get('PS_LANG_DEFAULT'));
        return $category->name;
    }

 
    public function getAvailableHooks()
    {
        return [
            ['id_hook' => 'displayHome', 'hook_name' => $this->l('Display Home')],
            ['id_hook' => 'displayCategory', 'hook_name' => $this->l('Display Category')],
            ['id_hook' => 'displayFooter', 'hook_name' => $this->l('Display Footer')],
            ['id_hook' => 'displayFooterCategory', 'hook_name' => $this->l('Display Footer Category')],
        ];
    }
    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }
        $categories = Category::getCategories((int)Configuration::get('PS_LANG_DEFAULT'), true, false);
        $selectedCategory = EgProduitPopulaireClass::getCategorySelectedById((int)Tools::getValue('id_egproduitpopulaire'));
        
   
        if ($obj->limit_nb) {
            $limit_nb = $obj->limit_nb ;
        } else {
            $limit_nb = 8;
        }
        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Block Builder'),
            ],
            'input' => [
 
                [
                    'type' => 'text',
                    'label' => $this->l('Titre'),
                    'id' => 'name', // for copyMeta2friendlyURL compatibility
                    'name' => 'meta_title',
                    'class' => 'copyMeta2friendlyURL', 
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
                    'type' => 'select',
                    'label' => $this->l('Select Hooks'),
                    'name' => 'hook_names',
                    'multiple' => false,  
                    'options' => [
                        'query' => $this->getAvailableHooks(),
                        'id' => 'id_hook',
                        'name' => 'hook_name',
                    ],
                    'required' => true,
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
                    'required' => true
                ], 
                [
                    'type' => 'text',
                    'label' => $this->l('Lien'),
                    'id' => 'lien',  
                    'name' => 'lien', 
                    'lang' => false,
                    'required' => false, 
                    'hint' => $this->trans('Invalid characters:', array(), 'Admin.Notifications.Info') . ' &lt;&gt;;=#{}'
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Label lien'),
                    'id' => 'label',  
                    'name' => 'label', 
                    'lang' => true,
                    'required' => false, 
                    'hint' => $this->trans('Invalid characters:', array(), 'Admin.Notifications.Info') . ' &lt;&gt;;=#{}'
                ],
                [
                    'type' => 'html',
                    'label' => $this->l('Nombre de produits'),
                    'name' => 'limit_nb',
                    'required' => false, 
                    'html_content' => '<input type="number" value="'.$limit_nb.'" name="limit_nb" id="limit_nb">'
                ], 
                [
                    'type' => 'switch',
                    'label' => $this->trans('Affichage', array(), 'Modules.EgBlockBuilder.Admin'),
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Activé', array(), 'Modules.EgBlockBuilder.Admin')
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Désactivé', array(), 'Modules.EgBlockBuilder.Admin')
                        ]
                    ]
                ],
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

    public static function getDescriptionClean($description)
    {
        return Tools::getDescriptionClean($description);
    }
 
    public function ajaxProcessUpdatePositions()
    {
        $way = (int)(Tools::getValue('way'));
        $id = (int)(Tools::getValue('id'));
        $positions = Tools::getValue($this->table);

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2]) && (int)$pos[2] === $id) {
                if ($item = new EgProduitPopulaireClass((int)$pos[2])) {
                    if (isset($position) && $item->updatePosition($way, $position)) {
                        echo 'ok position ' . (int)$position . ' for tab ' . (int)$pos[1] . '\r\n';
                    } else {
                        echo '{"hasError" : true, "Can not update ' . (int)$id . ' to position ' . (int)$position . ' "}';
                    }
                } else {
                    echo '{"hasError" : true, "This tab (' . (int)$id . ') can\'t be loaded"}';
                }
                break;
            }
        }
    }
}
