<?php

if (!defined('_PS_VERSION_')) {
    exit;
}
use PrestaShop\PrestaShop\Adapter\BestSales\BestSalesProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
require_once _PS_MODULE_DIR_.'egproduitventes/classes/EgProduitVentesClass.php';

class EgProduitVentes extends Module
{
    public function __construct()
    {
        $this->name = 'egproduitventes';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Egio Digital';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Meilleures ventes');
        $this->description = $this->l('Un module pour afficher un bloc de meiulleures ventes par catégorie.');
        $this->img_path = $this->_path . 'views/img/';
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');
        return parent::install() && 
            $this->registerHook('displayBackofficeHeader') && 
            $this->registerHook('displayHome') &&
            $this->registerHook('displayCategory') &&
            $this->registerHook('displayFooter') &&
            $this->registerHook('displayFooterCategory') && 
            $this->createTabs();
    } 
    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');
        return parent::uninstall() &&
            $this->removeTabs('AdminProduitVentes');
    }
  
    /**
     * @see CREATE TAB module in Dashboard
     */
    public function createTabs()
    {
        $idParent = (int) Tab::getIdFromClassName('AdminEgDigital');
        if (empty($idParent)) {
            $parent_tab = new Tab();
            $parent_tab->name = array();
            foreach (Language::getLanguages(true) as $lang) {
                $parent_tab->name[$lang['id_lang']] = $this->l('Modules TECH360');
            }
            $parent_tab->class_name = 'AdminEgDigital';
            $parent_tab->id_parent = 0;
            $parent_tab->add();
        }

        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->l('Meilleures ventes');
        }
        $tab->class_name = 'AdminProduitVentes';
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminEgDigital');
        $tab->module = $this->name;
        $tab->icon = 'library_books';
        $tab->add();

        return true;
    }

    /**
     * Remove Tabs module in Dashboard
     * @param $class_name string name Tab
     * @return bool
     */
    public function removeTabs($class_name)
    {
        if ($tab_id = (int) Tab::getIdFromClassName($class_name)) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }
        return true;
    }
 
    public function hookDisplayBackofficeHeader($params)
    {  
        
    }
    public function hookDisplayHome($params)
    {
        $items = $this->processEgProduitVentes('displayHome');
        
        // Assign variables to Smarty
        $this->context->smarty->assign([
            'items' => $items,
        ]);
    
        return $this->display(__FILE__, 'displayHome.tpl');
    }
    
    public function hookDisplayCategory($params)
    {
        $items = $this->processEgProduitVentes('displayCategory');
        
        // Assign variables to Smarty
        $this->context->smarty->assign([
            'items' => $items,
        ]);
    
        return $this->display(__FILE__, 'displayHome.tpl'); // Updated template if necessary
    }
    
    public function hookDisplayFooter($params)
    {
        $items = $this->processEgProduitVentes('displayFooter');
        
        // Assign variables to Smarty
        $this->context->smarty->assign([
            'items' => $items,
        ]);
    
        return $this->display(__FILE__, 'displayHome.tpl'); // Updated template if necessary
    }
    
    public function hookDisplayFooterCategory($params)
    {
        $items = $this->processEgProduitVentes('displayFooterCategory');
        
        // Assign variables to Smarty
        $this->context->smarty->assign([
            'items' => $items,
        ]);
    
        return $this->display(__FILE__, 'displayHome.tpl'); // Updated template if necessary
    }
    protected function processEgProduitVentes($hook_name)
    {
        $id_shop = (int)Context::getContext()->shop->id;
        $id_lang = (int)Context::getContext()->language->id;
        $rows = EgProduitVentesClass::getAllByHook($hook_name);
        
        $items = [];
        
        foreach ($rows as $row) { 
            $id_egproduitventes = $row['id_egproduitventes'];
            $egProduitVentes = new EgProduitVentesClass($id_egproduitventes, $id_lang, $id_shop);
        
            if (Validate::isLoadedObject($egProduitVentes)) {
                $meta_title = $egProduitVentes->meta_title;
                $description = $egProduitVentes->description; 
                $id_cat = $egProduitVentes->id_category;
                $limit = $egProduitVentes->limit_nb;
                $label = $egProduitVentes->label;
                
                // Get lien or fallback to category URL
                $lien = !empty($egProduitVentes->lien) ? $egProduitVentes->lien : $this->context->link->getCategoryLink($id_cat, null, $id_lang);
                
                // Fetch products associated with this push product
                $products_raw = $this->getProductsByCategoryAndManufacturer($id_lang, 0, $limit, 'id_product', 'DESC', $id_cat,null, true);
                
                if ($products_raw === false) {
                    $products = [];
                } else {
                    $products = $this->getProducts($products_raw);
                }
            
                // Prepare items array
                if ($egProduitVentes->active) {
                    $items[] = [
                        'meta_title' => $meta_title, 
                        'description' => $description,
                        'label' => $label,
                        'products' => $products,
                        'lien' => $lien,
                    ];
                }

            }
        }

        return $items;
    }
    public static function getProductsByCategoryAndManufacturer(
        $id_lang,
        $start,
        $limit,
        $order_by,
        $order_way,
        $id_category = false,
        $id_manufacturer = false,
        $only_active = false,
        Context $context = null
    ) {
        if (!$context) {
            $context = Context::getContext();
        }

        $front = true;
        if (!in_array($context->controller->controller_type, ['front', 'modulefront'])) {
            $front = false;
        }

        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            die(Tools::displayError());
        }
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'p';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        } elseif ($order_by == 'position') {
            $order_by_prefix = 'c';
        }

        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by_prefix = $order_by[0];
            $order_by = $order_by[1];
        }
        $sql = 'SELECT p.*, product_shop.*, pl.* , m.`name` AS manufacturer_name, s.`name` AS supplier_name
                FROM `' . _DB_PREFIX_ . 'product` p
                ' . Shop::addSqlAssociation('product', 'p') . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . Shop::addSqlRestrictionOnLang('pl') . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
                LEFT JOIN `' . _DB_PREFIX_ . 'supplier` s ON (s.`id_supplier` = p.`id_supplier`)' .
                ($id_category ? 'LEFT JOIN `' . _DB_PREFIX_ . 'category_product` c ON (c.`id_product` = p.`id_product`)' : '') . '
                WHERE pl.`id_lang` = ' . (int) $id_lang .
                    ($id_category ? ' AND c.`id_category` = ' . (int) $id_category : '') .
                    ($id_manufacturer ? ' AND p.`id_manufacturer` = ' . (int) $id_manufacturer : '') .
                    ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') .
                    ($only_active ? ' AND product_shop.`active` = 1' : '') . '
                ORDER BY ' . (isset($order_by_prefix) ? pSQL($order_by_prefix) . '.' : '') . '`' . pSQL($order_by) . '` ' . pSQL($order_way) .
                ($limit > 0 ? ' LIMIT ' . (int) $start . ',' . (int) $limit : '');
        $rq = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if ($order_by == 'price') {
            Tools::orderbyPrice($rq, $order_way);
        }

        foreach ($rq as &$row) {
            $row = Product::getTaxesInformations($row);
        }
        if (!$rq) {
            return []; // Retournez un tableau vide si aucune donnée n'est trouvée
        } else {
            return $rq;
        }
    } 
   
    protected function getProducts( $products )
    {
   
        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        if (version_compare(_PS_VERSION_, '1.7.5', '>=')) {
            $presenter = new \PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductListingPresenter(
                new ImageRetriever(
                    $this->context->link
                ),
                $this->context->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                $this->context->getTranslator()
            );
        } else {
            $presenter = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
                new ImageRetriever(
                    $this->context->link
                ),
                $this->context->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                $this->context->getTranslator()
            );
        }

        $products_for_template = [];

        foreach ($products as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        return $products_for_template;
    }

}