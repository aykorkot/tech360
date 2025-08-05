<?php

class Guarantee extends ObjectModel
{
    public $id_egguarantees;
    public $title;
    public $subtitle;
    public $description;
    public $position;
    public $active;

    public static $definition = array(
        'table' => 'egguarantees',
        'primary' => 'id_egguarantees',
        'fields' => array(
            'title' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'subtitle' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'description' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
            'position' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        ),
    );

    public function updatePosition($way, $position)
    {
        if (!$res = Db::getInstance()->executeS('
            SELECT `id_egguarantees`
            FROM `' . _DB_PREFIX_ . 'egguarantees`
            ORDER BY `position` ASC'
        )) {
            return false;
        }

        foreach ($res as $item) {
            if ((int)$item['id_egguarantees'] == (int)$this->id) {
                $moved_item = $item;
            }
        }

        if (!isset($moved_item) || !isset($position)) {
            return false;
        }

        return (Db::getInstance()->execute('
            UPDATE `' . _DB_PREFIX_ . 'egguarantees`
            SET `position`= `position` ' . ($way ? '- 1' : '+ 1') . '
            WHERE `position`
            ' . ($way
                ? '> ' . (int)$moved_item['position'] . ' AND `position` <= ' . (int)$position
                : '< ' . (int)$moved_item['position'] . ' AND `position` >= ' . (int)$position)) &&
            Db::getInstance()->execute('
            UPDATE `' . _DB_PREFIX_ . 'egguarantees`
            SET `position` = ' . (int)$position . '
            WHERE `id_egguarantees` = ' . (int)$moved_item['id_egguarantees']));
    }

    public static function getNextPosition()
    {
        return (Db::getInstance()->getValue('SELECT MAX(`position`) + 1 FROM `' . _DB_PREFIX_ . 'egguarantees`'));
    }
}