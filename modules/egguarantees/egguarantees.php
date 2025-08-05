<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class Egguarantees extends Module
{
    protected $domain;
    
    public function __construct()
    {
        $this->name = 'egguarantees';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Your Name';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        $this->domain = 'Modules.Egguarantees.Egguarantees';
        parent::__construct();

        $this->displayName = $this->l('Guarantees');
        $this->description = $this->l('Displays guarantees on the front office.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');
        return parent::install() && $this->createTabs()  && $this->registerHook('displayFooterBefore');
    }

    public function uninstall()
    {
        $this->removeTabs('AdminEgguarantees'); 
        //include(dirname(__FILE__).'/sql/uninstall.php');
        return parent::uninstall();
    }
    /**
     * @see  CREATE TAB module in Dashboard
     */
    public function createTabs()
    {
        $idParent = (int) Tab::getIdFromClassName('AdminEgDigital');
        if (empty($idParent)) {
            $parent_tab = new Tab();
            $parent_tab->name = array();
            foreach (Language::getLanguages(true) as $lang) {
                $parent_tab->name[$lang['id_lang']] = $this->trans('Modules TECH360', array(), $this->domain);
            }
            $parent_tab->class_name = 'AdminEgDigital';
            $parent_tab->id_parent = 0;
            $parent_tab->module = $this->name;
            $parent_tab->icon = 'library_books';
            $parent_tab->add();
        }
 
        // Menage Module
        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Guaranties', array(), $this->domain);
        }
        $tab->class_name = 'AdminEgguarantees';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminEgDigital');
        $tab->module = $this->name;
        $tab->add();

         

        return true;
    }
        /**
     * Remove Tabs module in Dashboard
     * @param $class_name string name Tab
     * @return bool
     * @throws
     * @throws
     */
    public function removeTabs($class_name)
    {
        if ($tab_id = (int)Tab::getIdFromClassName($class_name)) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }
        return true;
    }
    public function hookDisplayFooterBefore($params)
    { 
        $this->context->smarty->assign('guarantees', $this->getGuarantees());
        return $this->display(__FILE__, 'views/templates/hook/guarantees.tpl');
    }

    private function getGuarantees()
    {
        $sql = 'SELECT g.id_egguarantees, g.title, g.subtitle,g.description, l.src, l.alt
                FROM ' . _DB_PREFIX_ . 'egguarantees g
                LEFT JOIN ' . _DB_PREFIX_ . 'egguarantees_logos l ON g.id_egguarantees = l.id_egguarantees
                WHERE g.active = 1
                ORDER BY g.position ASC';
        $results = Db::getInstance()->executeS($sql);

        $guarantees = []; 
        foreach ($results as $row) {
            if (!isset($guarantees[$row['id_egguarantees']])) {
                $guarantees[$row['id_egguarantees']] = [
                    'title' => $row['title'],
                    'subtitle' => $row['subtitle'],
                    'description' => $row['description'],
                    'logos' => [],
                ];
            }
            
            $guarantees[$row['id_egguarantees']]['logos'][] = [
                'src' => $this->context->link->getBaseLink().$row['src'],
                'alt' => $row['alt'],
            ];
        }

        return array_values($guarantees);
    }
}
