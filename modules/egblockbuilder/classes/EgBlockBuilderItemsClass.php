<?php

use PrestaShop\PrestaShop\Adapter\BestSales\BestSalesProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
   
use PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductListingPresenter;
 
 

class EgBlockBuilderItemsClass extends ObjectModel
{
    public $id;
    public $id_egblockbuilder;
    public $type; 
    public $title_1;
    public $title_2;
    public $title_3;
    public $title_4;
    public $title_5; 
    public $link_button;
    public $text_button; 
    public $link_button_3;
    public $text_button_3;
    public $banner;
    public $banner_mobile;
    public $type_video_banniere;
    public $video_banniere; 
    public $chosen_products;
    public $nb_produit;
    public $position;
    public $active;
    public $background_color;
    public $text_3;
    public $text_4;

    public static $definition = array(
        'table' => 'egblockbuilder_items',
        'primary' => 'id_egblockbuilder_items',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => array(
            'id_egblockbuilder'    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'type' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'title_1' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => false),
            'title_2' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => false),
            'title_3' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => false),
            'title_4' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => false),
            'title_5' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => false),
            'link_button' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isCleanHtml', 'required' => false),
            'text_button' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isCleanHtml', 'required' => false),
            'link_button_3' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isCleanHtml', 'required' => false),
            'text_button_3' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isCleanHtml', 'required' => false),
            'banner' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => false),
            'banner_mobile' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => false),
            'type_video_banniere' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => false),
            'video_banniere' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => false),
            'chosen_products' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => false),
            'nb_produit'    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'position'    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'active'      => array('type' => self::TYPE_BOOL),
            'background_color' => array('type' => self::TYPE_BOOL),
            'text_3' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => false),
            'text_4' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => false),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null, Context $context = null)
    {
        parent::__construct($id, $id_lang, $id_shop);
    }

    public static function updateItem_arrive($id_egblockbuilder_items, $chosen_products)
    {
        $query = "UPDATE " . _DB_PREFIX_ . "egblockbuilder_items 
                  SET chosen_products = '" . $chosen_products . "'  
                  WHERE id_egblockbuilder_items = " . (int)$id_egblockbuilder_items;
    
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query);
    } 

    public static function getLastAdded()
    {
        $query = new DbQuery();
        $query->select('dp.id_egblockbuilder_items');
        $query->from('egblockbuilder_items', 'dp');       
        $query->orderBy('dp.id_egblockbuilder_items DESC'); 
    
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query); 
    }

    public static function getLastPosition($id_egblockbuilder)
    {
        $query = new DbQuery();
        $query->select('dp.position');
        $query->from('egblockbuilder_items', 'dp');
        $query->where('dp.`id_egblockbuilder` =  '.(int) $id_egblockbuilder);
        $query->orderBy('dp.position DESC'); 
    
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query); 
    }
 

    public static function getCategorySelectedById($id_egblockbuilder, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = Context::getContext()->shop->id;
        } 
        $idLang = (int) Context::getContext()->language->id;
        $query = new DbQuery();
        $query->select('b.`id_category`');
        $query->from('egblockbuilder', 'b');
        $query->leftJoin('egblockbuilder_lang', 'bl', 'bl.`id_egblockbuilder` = b.`id_egblockbuilder`');
        $query->leftJoin('egblockbuilder_shop', 'bs', 'bs.`id_egblockbuilder` = bl.`id_egblockbuilder`');
        $query->where('b.`id_egblockbuilder` =  '.(int) $id_egblockbuilder.' AND bl.`id_lang` =  '.$idLang.' AND bl.id_shop ='.$id_shop);
      
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    public static function getNameCategoryById($IdCategory)
    {
        $idLang = (int) Context::getContext()->language->id;

        $query = new DbQuery();
        $query->select('cl.`id_category`');
        $query->from('egblockbuilder', 'cl');
        $query->leftJoin('eg_category_block', 'cb', 'cb.`id_category` = cl.`id_category`'.Shop::addSqlRestrictionOnLang('cl'));
        $query->where('cb.`id_category` =  '.(int) $IdCategory.' AND cl.`id_lang` =  '.$idLang);

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    public static function getItemsByEgBlockBuilderId($id_egblockbuilder)
    {
        $query = new DbQuery();
        $query->select('ei.*, eil.*, eis.*'); // Select from items, lang, and shop
        $query->from('egblockbuilder_items', 'ei');
        $query->leftJoin('egblockbuilder_items_lang', 'eil', 'ei.id_egblockbuilder_items = eil.id_egblockbuilder_items AND eil.id_lang = ' . (int)Context::getContext()->language->id);
        $query->leftJoin('egblockbuilder_items_shop', 'eis', 'ei.id_egblockbuilder_items = eis.id_egblockbuilder_items AND eis.id_shop = ' . (int)Context::getContext()->shop->id);
        $query->where('ei.id_egblockbuilder = ' . (int)$id_egblockbuilder);
        $query->where('ei.active = 1'); // Only active items
        $query->orderBy('ei.position ASC');
       
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
    }

    public static function getCategoryName($id_category)
    {
        $category = new Category($id_category, (int)Configuration::get('PS_LANG_DEFAULT'));
        return $category->name;
    }

    public static function getProducts($products)
    {
        $assembler = new ProductAssembler(Context::getContext());

        $presenterFactory = new ProductPresenterFactory(Context::getContext());
        $presentationSettings = $presenterFactory->getPresentationSettings();
        if (version_compare(_PS_VERSION_, '1.7.5', '>=')) {
            $presenter = new \PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductListingPresenter(
                new ImageRetriever(
                    Context::getContext()->link
                ),
                Context::getContext()->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                Context::getContext()->getTranslator()
            );
        } else {
            $presenter = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
                new ImageRetriever(
                    Context::getContext()->link
                ),
                Context::getContext()->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                Context::getContext()->getTranslator()
            );
        }

        $products_for_template = [];
       
        foreach ($products as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                Context::getContext()->language
            );
        }

        return $products_for_template;
    }
    public static function getElementDetails($id_egblockbuilder_items, $type)
    {
        $context = Context::getContext();
        $sql = 'SELECT mi.*
                FROM `'._DB_PREFIX_.'egblockbuilder_items` ei
                LEFT JOIN `'._DB_PREFIX_.'egblockbuilder_items_lang` eil ON ei.id_egblockbuilder_items = eil.id_egblockbuilder_items
                LEFT JOIN `'._DB_PREFIX_.'multiple_images` mi ON ei.id_egblockbuilder_items = mi.id_egblockbuilder_items
                WHERE ei.id_egblockbuilder_items = '.(int)$id_egblockbuilder_items.'
                AND mi.type = '.(int)$type.'
                AND eil.id_lang = '.(int)$context->language->id.'
                AND eil.id_shop = '.(int)$context->shop->id;

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }
}
