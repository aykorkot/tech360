<?php

require_once dirname(__FILE__) . '/../../classes/CustomContent.php';

class AdminCustomContentController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'custom_content';
        $this->className = 'CustomContentModel';
        $this->lang = false;
        $this->bootstrap = true;

        parent::__construct();

        $this->fields_list = [
            'id_custom_content' => ['title' => 'ID'],
            'title' => ['title' => 'Titre'],
            'description' => [
                'title' => $this->l('Description'),
                'callback' => 'formatDescription',
                'width' => '150px'
            ],
            'button_link' => ['title' => 'Lien'],
            'image' => [
                'title' => $this->l('Image'),
                'orderby' => false,
                'search' => false,
            ]
        ];

        $this->actions = ['edit', 'delete'];

        $this->bulk_actions = [
            'delete' => ['text' => $this->l('Supprimer la sélection'), 'confirm' => $this->l('Supprimer les éléments sélectionnés ?')]
        ];
    }

    public function formatDescription($value, $row)
    {
        // Nettoyer les balises HTML
        $text = strip_tags($value);

        // Découper en mots
        $words = preg_split('/\s+/', $text);

        // Si plus de 10 mots, tronquer et ajouter "..."
        if (count($words) > 15) {
            $words = array_slice($words, 0, 15);
            $text = implode(' ', $words) . '...';
        } else {
            $text = implode(' ', $words);
        }

        // Retourner le texte (pas de max-width ni div)
        return '<div style="max-width:300px; white-space: normal; overflow-wrap: break-word;">' . htmlspecialchars($text) . '</div>';

 
    }


    public function displayImage($image, $row)
    {
        if ($image) {
            $url = _PS_BASE_URL_ . __PS_BASE_URI__ . 'img/' . $image;
            return '<img src="' . $url . '" style="max-width:100px; max-height:50px;" alt="Image">';
        }
        return '';
    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => ['title' => 'Ajouter un bloc'],
            'input' => [
                ['type' => 'text', 'label' => 'Titre', 'name' => 'title', 'required' => true],
                ['type' => 'textarea', 'label' => 'Description', 'name' => 'description', 'autoload_rte' => true],
                ['type' => 'text', 'label' => 'Lien du bouton', 'name' => 'button_link'],
                ['type' => 'file', 'label' => 'Image', 'name' => 'image']
            ],
            'submit' => ['title' => 'Enregistrer']
        ];

        return parent::renderForm();
    }

    public function processAdd()
    {
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $image_name = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

            // Créer le dossier s’il n’existe pas
            $uploadDir = _PS_IMG_DIR_ . 'customcontent/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image_name);

            // Stocker uniquement le nom du fichier
            $_POST['image'] = $image_name;
        }

        parent::processAdd();
    }

}
