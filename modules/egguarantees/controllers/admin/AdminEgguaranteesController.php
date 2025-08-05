<?php

require_once _PS_MODULE_DIR_.'egguarantees/classes/Guarantee.php';

class AdminEgguaranteesController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'egguarantees';
        $this->className = 'Guarantee';
        $this->_defaultOrderBy = 'position';
        $this->_defaultOrderWay = 'ASC';
        $this->lang = false;
        $this->bootstrap = true;
        $this->addRowAction('edit');
        $this->addRowAction('delete');  

        parent::__construct();

        $this->fields_list = array(
            'id_egguarantees' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'title' => array(
                'title' => $this->l('Title'),
            ),
            'subtitle' => array(
                'title' => $this->l('Subtitle'),
            ),
            'description' => array(
                'title' => $this->l('Description'),
            ),
            'position' => array(
                'title' => $this->l('Position'), 
                'filter_key' => 'a!position',
                'position' => 'position',
                'align' => 'center',
                'class' => 'fixed-width-md',
            ),
            'active' => array(
                'title' => $this->l('Active'), 
                'align' => 'center',
                'active' => 'status',
                'class' => 'fixed-width-sm',
                'type' => 'bool',
                'orderby' => false
            ),
        );
    }

    public function renderForm()
    {
        $id = (int)Tools::getValue('id_egguarantees');
        $logos = [];

        if ($id) {
            $logos = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'egguarantees_logos WHERE id_egguarantees = ' . (int)$id);
        }

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Guarantee'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'name' => 'title',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Subtitle'),
                    'name' => 'subtitle',
                    'required' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Description'),
                    'name' => 'description',
                    'autoload_rte' => true,
                    'rows' => 5,
                    'cols' => 40, 
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Add Logos'),
                    'name' => 'logos',
                    'multiple' => true,
                    'desc' => $this->l('Upload multiple logos.'),
                ),
                array(
                    'type' => 'html',
                    'label' => $this->l('Existing Logos'),
                    'name' => 'existing_logos',
                    'html_content' => $this->renderLogos($logos),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            ),
        );

        return parent::renderForm();
    }

    private function renderLogos($logos)
    {
        $html = '<div class="logos">';
        foreach ($logos as $logo) {
            $html .= '<div class="logo">';
            $html .= '<img src="/' .$logo['src'] . '" alt="' . $logo['alt'] . '" width="94" height="46">';
            $html .= '<a href="' . $this->context->link->getAdminLink('AdminEgguarantees') . '&deleteLogo&id_logo=' . $logo['id_egguarantees_logos'] . '" class="btn btn-danger btn-xs">' . $this->l('Delete') . '</a>';
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('deleteLogo')) {
            $id_logo = (int)Tools::getValue('id_logo');
            $logo = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'egguarantees_logos WHERE id_egguarantees_logos = ' . (int)$id_logo);

            if ($logo) {
                @unlink(_PS_IMG_DIR_ . $logo['src']);
                Db::getInstance()->delete('egguarantees_logos', 'id_egguarantees_logos = ' . (int)$id_logo);
            }
        }
       
        if (Tools::isSubmit('submitAdd' . $this->table)) {
            $id = (int)Tools::getValue('id_egguarantees');
            $title = Tools::getValue('title');
            $subtitle = Tools::getValue('subtitle');
            $description = Tools::getValue('description');
            $active = (bool)Tools::getValue('active');
            $logos = $_FILES['logos'];
            
            // Save guarantee data
            $guarantee = new Guarantee($id);
            $guarantee->title = $title;
            $guarantee->subtitle = $subtitle;
            $guarantee->description = $description;
            $guarantee->active = $active; 
            $guarantee->save();

            // Save logos
            if (!empty($logos['name'][0])) {
                $uploadDir = _PS_MODULE_DIR_ . 'egguarantees/views/img/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                foreach ($logos['name'] as $key => $name) {
                    if ($logos['error'][$key] === UPLOAD_ERR_OK) {
                        $tmp_name = $logos['tmp_name'][$key];
                        $filename = uniqid() . '_' . $name;
                        move_uploaded_file($tmp_name, $uploadDir . $filename);

                        Db::getInstance()->insert('egguarantees_logos', array(
                            'id_egguarantees' => (int)$guarantee->id,
                            'src' => pSQL('modules/egguarantees/views/img/' . $filename),
                            'alt' => pSQL($name),
                        ));
                    }
                }
            }
        }

        parent::postProcess();
    }

    public function ajaxProcessUpdatePositions()
    {
        $way = (int)(Tools::getValue('way'));
        $id = (int)(Tools::getValue('id'));
        $positions = Tools::getValue($this->table);

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2]) && (int)$pos[2] === $id) {
                if ($item = new Guarantee((int)$pos[2])) {
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