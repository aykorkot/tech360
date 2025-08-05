<?php

class EgBlockBuilderClass extends ObjectModel
{
    public $id;
    public $meta_title;
    public $link_rewrite;
    public $id_category;
    public $position;
    public $active;
    public $description; 

    public static $definition = array(
        'table' => 'egblockbuilder',
        'primary' => 'id_egblockbuilder',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => array( 
            'meta_title' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true),
            'link_rewrite' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true),
            'description' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true),
            'id_category' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'), 
            'position'    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'active'      => array('type' => self::TYPE_BOOL),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null, Context $context = null)
    {
        parent::__construct($id, $id_lang, $id_shop);
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
        $query->select('eg.`id_egblockbuilder`, eg.`position`');
        $query->from('egblockbuilder', 'eg');
        $query->orderBy('eg.`position` ASC');
        $tabs = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        if (!$tabs) {
            return false;
        }

        foreach ($tabs as $tab) {
            if ((int) $tab['id_egblockbuilder'] == (int) $this->id) {
                $moved_tab = $tab;
            }
        }

        if (!isset($moved_tab) || !isset($position)) {
            return false;
        }

        // < and > statements rather than BETWEEN operator
        // since BETWEEN is treated differently according to databases
        return (Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'egblockbuilder`
            SET `position`= `position` '.($way ? '- 1' : '+ 1').'
            WHERE `position`
            '.($way
                    ? '> '.(int)$moved_tab['position'].' AND `position` <= '.(int)$position
                    : '< '.(int)$moved_tab['position'].' AND `position` >= '.(int)$position
                ))
            && Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'egblockbuilder`
            SET `position` = '.(int)$position.'
            WHERE `id_egblockbuilder` = '.(int)$moved_tab['id_egblockbuilder']));
    }
    /**
     * Get the last position + 1 for the egblockbuilder items
     * 
     * @param int $id_category Optional category filter
     * @return int
     */
    public function getNextPosition($id_category = null)
    {
        $sql = 'SELECT MAX(position) AS max_position
                FROM ' . _DB_PREFIX_ . 'egblockbuilder';
        
        // Optionally filter by category if provided
        if ($id_category !== null) {
            $sql .= ' WHERE id_category = ' . (int)$id_category;
        }

        $result = Db::getInstance()->getValue($sql);

        return (int)$result + 1; // Return the next position
    }
    public function copyFrom($source)
    {
        // Get all available languages
        $languages = Language::getLanguages();
        
        // Copy multilingual fields
        foreach ($languages as $lang) {
            $this->meta_title[$lang['id_lang']] = isset($source->meta_title[$lang['id_lang']]) ? $source->meta_title[$lang['id_lang']] : '';
            $this->description[$lang['id_lang']] = isset($source->description[$lang['id_lang']]) ? $source->description[$lang['id_lang']] : '';
            $this->link_rewrite[$lang['id_lang']] = isset($source->link_rewrite[$lang['id_lang']]) ? $source->link_rewrite[$lang['id_lang']] : '';
        }
        
        // Copy non-multilingual fields
        $this->id_category = $source->id_category;
        $this->position = $this->getNextPosition();
        $this->active = $source->active; 
    }
    public function duplicate()
    {
        // Create a new instance
        $duplicatedBlock = new self();
        
        // Copy properties from the current instance (including multilingual fields)
        $duplicatedBlock->copyFrom($this); 

        // Handle multilingual fields (like meta_title)
        $languages = Language::getLanguages();
        foreach ($languages as $lang) {
            // Set the meta_title for each language
            $duplicatedBlock->meta_title[$lang['id_lang']] .= ' (Copy)';
        }

        // Save the duplicated block
        if ($duplicatedBlock->save()) {
            return $duplicatedBlock; // Optionally return the ID of the duplicated block
        }

        return false; // Save failed
    
    }
    
}
