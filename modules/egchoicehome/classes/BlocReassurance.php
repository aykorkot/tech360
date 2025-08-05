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
 * Class BlocReassurance.
 */
class BlocReassurance extends ObjectModel
{
    /** @var int EdBannerID */
    public $id_eg_bloc_reassurance;

 	/** @var string title Manufacture */
	public $title; 
 
    /** @var  string Long description Manufacture*/
    public $description; 

    /** @var string icon  */
    public $icon;

    /** @var  int sport position */
    public $position;

    /** @var bool Status for display Banner*/
    public $active = true;
    
	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'eg_bloc_reassurance',
		'primary' => 'id_eg_bloc_reassurance',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => array( 
            'title'        => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
            'description'  => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'icon'        => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
            'position'    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'active'      => array('type' => self::TYPE_BOOL), 
        ),
	);

    /**
     * Adds current sport as a new Object to the database
     *
     * @param bool $autoDate    Automatically set `date_upd` and `date_add` columns
     * @param bool $nullValues Whether we want to use NULL values instead of empty quotes values
     *
     * @return bool Indicates whether the Banner has been successfully added
     * @throws
     * @throws
     */
    public function add($autoDate = true, $nullValues = false)
    {
        $this->position = (int) $this->getMaxPosition() + 1;
        return parent::add($autoDate, $nullValues);
    }
    public static function getCriterions($id_lang, $shop = 1, $active = 1)
    {
        $sql = '
            SELECT pcc.`id_eg_bloc_reassurance`, pccl.`title`,pccl.`description`,pccl.`icon`, pcc.active
            FROM `' . _DB_PREFIX_ . 'eg_bloc_reassurance` pcc
            JOIN `' . _DB_PREFIX_ . 'eg_bloc_reassurance_shop` pccs ON (pccs.id_eg_bloc_reassurance = pcc.id_eg_bloc_reassurance)
            JOIN `' . _DB_PREFIX_ . 'eg_bloc_reassurance_lang` pccl ON (pcc.id_eg_bloc_reassurance = pccl.id_eg_bloc_reassurance)
            WHERE pccl.`id_lang` = ' . $id_lang 
            .' AND pcc.active = '.$active  
            . ' AND pccs.id_shop = '.$shop.' 
            ORDER BY pccl.`title` ASC';
        
        $criterions = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);


        return $criterions;
    }
    /**
     * @return int MAX Position Banner
     */
    public static function getMaxPosition()
    {
        $query = new DbQuery();
        $query->select('MAX(position)');
        $query->from('eg_bloc_reassurance', 'eg');

        $response = Db::getInstance()->getRow($query);

        if ($response['MAX(position)'] == null){
            return -1;
        }
        return $response['MAX(position)'];
    }
        /**
     * @return int MAX Position Banner
     */
    public static function getMaxId()
    { 
        $query  = "SELECT MAX(id_eg_bloc_reassurance) as id_eg_bloc_reassurance from `" . _DB_PREFIX_ . "eg_bloc_reassurance`";
        $id_eg_bloc_reassurance = Db::getInstance()->getValue($query);
        if ($id_eg_bloc_reassurance == null){
            return 1;
        }
        return $id_eg_bloc_reassurance;
    }

    /**
     * @param $way int
     * @param $position int Position Banner
     * @return bool
     * @throws
     */
    public function updatePosition($way, $position)
    {
        $query = new DbQuery();
        $query->select('eg.`id_eg_bloc_reassurance`, eg.`position`');
        $query->from('eg_bloc_reassurance', 'eg');
        $query->orderBy('eg.`position` ASC');
        $tabs = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        if (!$tabs ) {
            return false;
        }

        foreach ($tabs as $tab) {
            if ((int) $tab['id_eg_bloc_reassurance'] == (int) $this->id) {
                $moved_tab = $tab;
            }
        }

        if (!isset($moved_tab) || !isset($position)) {
            return false;
        }

        // < and > statements rather than BETWEEN operator
        // since BETWEEN is treated differently according to databases
        return (Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'eg_bloc_reassurance`
            SET `position`= `position` '.($way ? '- 1' : '+ 1').'
            WHERE `position`
            '.($way
                    ? '> '.(int)$moved_tab['position'].' AND `position` <= '.(int)$position
                    : '< '.(int)$moved_tab['position'].' AND `position` >= '.(int)$position
                ))
            && Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'eg_bloc_reassurance`
            SET `position` = '.(int)$position.'
            WHERE `id_eg_bloc_reassurance` = '.(int)$moved_tab['id_eg_bloc_reassurance']));
    }
    /**
     * @param $value string image Banner
     * @return string src
     */
    public static function showBanner($value)
    {
        $src = __PS_BASE_URI__. 'modules/egchoicehome/views/img/'.$value;
        return $value ? '<img src="'.$src.'" width="80" height="40px" class="img img-thumbnail"/>' : '-';
    }
   
}
