<?php

class CustomContentModel extends ObjectModel
{
    public $id_custom_content;
    public $title;
    public $description;
    public $button_link;
    public $image;

    public static $definition = [
        'table' => 'custom_content',
        'primary' => 'id_custom_content',
        'fields' => [
            'title' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName'],
            'description' => ['type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'],
            'button_link' => ['type' => self::TYPE_STRING, 'validate' => 'isUrl'],
            'image' => ['type' => self::TYPE_STRING, 'validate' => 'isUrlOrEmpty'],
        ]
    ];
}
