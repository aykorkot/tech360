<?php

class EgProduitVentesClass extends ObjectModel
{
    public $id;
    public $meta_title;
    public $description;  
    public $hook_names;
    public $id_category;
    public $lien;
    public $label; 
    public $limit_nb;
    public $position;
    public $active;


    public static $definition = array(
        'table' => 'egproduitventes',
        'primary' => 'id_egproduitventes',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => array(
            'meta_title' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true),
            'description' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'id_category' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true), 
            'hook_names' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml','required' => true),
            'lien' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'label' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml'), // Added
            'limit_nb'    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'position'    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'active'      => array('type' => self::TYPE_BOOL),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null, Context $context = null)
    {
        parent::__construct($id, $id_lang, $id_shop);
    }
    public static function getAllByHook($hook,$id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = Context::getContext()->shop->id;
        } 
        $idLang = (int) Context::getContext()->language->id;
        $query = new DbQuery();
        $query->select('p.`id_egproduitventes`');
        $query->from('egproduitventes', 'p');
        $query->where('p.`active` =1');
        $query->where('p.`hook_names` = "'.$hook.'"');
    
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
    }
    public static function getCategorySelectedById($id_egproduitventes, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = Context::getContext()->shop->id;
        } 
        $idLang = (int) Context::getContext()->language->id;
        $query = new DbQuery();
        $query->select('p.`id_category`');
        $query->from('egproduitventes', 'p');
        $query->where('p.`id_egproduitventes` = '.(int) $id_egproduitventes);
    
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }
     
    /**
     * @param $IdCategory int Category ID
     * @return string name category
     */
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
    public function updatePosition($way, $position)
    {
        $query = new DbQuery();
        $query->select('ep.`id_egproduitventes`, ep.`position`');
        $query->from('egproduitventes', 'ep');
        $query->orderBy('ep.`position` ASC');
        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
    
        if (!$products) {
            return false;
        }
    
        foreach ($products as $product) {
            if ((int) $product['id_egproduitventes'] == (int) $this->id) {
                $moved_product = $product;
            }
        }
    
        if (!isset($moved_product) || !isset($position)) {
            return false;
        }
    
        // < and > statements rather than BETWEEN operator
        // since BETWEEN is treated differently according to databases
        return (Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'egproduitventes`
            SET `position` = `position` '.($way ? '- 1' : '+ 1').'
            WHERE `position`
            '.($way
                    ? '> '.(int)$moved_product['position'].' AND `position` <= '.(int)$position
                    : '< '.(int)$moved_product['position'].' AND `position` >= '.(int)$position
                ))
            && Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'egproduitventes`
            SET `position` = '.(int)$position.'
            WHERE `id_egproduitventes` = '.(int)$moved_product['id_egproduitventes']));
    } 
}