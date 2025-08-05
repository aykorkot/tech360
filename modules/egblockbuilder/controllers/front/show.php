<?php
/**
 * 2024 (c) Egio Digital
 *
 * MODULE EgBlockBuilder
 *
 * @author    Egio Digital
 * @copyright Copyright (c) , Egio Digital
 * @license   Commercial
 * @version    1.0.0
 */


 require_once _PS_MODULE_DIR_ . 'egblockbuilder/classes/EgBlockBuilderClass.php';
 require_once _PS_MODULE_DIR_ . 'egblockbuilder/classes/EgBlockBuilderItemsClass.php';

class egblockbuildershowModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $id = Tools::getValue('id');
        $name = Tools::getValue('name');
       
        $builder = new EgBlockBuilderClass($id,Context::getContext()->language->id,Context::getContext()->shop->id);
        if ($builder->link_rewrite != $name ) {
            $this->redirect_after = '404';
            $this->redirect();
        }
       
        $items = EgBlockBuilderItemsClass::getItemsByEgBlockBuilderId($id);
      
        $base_url = Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__;
 
        if (empty($builder->meta_title)) {
            $title = $category->name ;
        } else {
            $title = $builder->meta_title ;
        } 
        
       
 
        // Prepare an array to hold the data for the template
        $dataForTemplate = [];

        foreach ($items as $item) {

            $id_egblockbuilder_items = $item['id_egblockbuilder_items'];
            $type = $item['type']; 
            $relatedItems = [];
            
            // Check the type and fetch related items accordingly
            switch ($type) {
                case 1:
  
                    $favorites_raw =[];
                  
                    if (!empty($item['chosen_products'])) {
                        $products = explode(',',$item['chosen_products']) ;
                        
                        if (count($products) > 0) {
                            $counter = 1 ;
                            foreach ($products as $product) {
                                if ($counter  <= $item['nb_produit'] && $product != '') { 
                                    $pro = new Product((int)$product);
                                    if( $pro->active == 1 ) {
                                        $favorites_raw[] = ['id_product'=> $product];
                                        $counter++;
                                    }
                                }
                            }  
                            
                            if (count($favorites_raw)) {
                                $favorites_products = EgBlockBuilderItemsClass::getProducts( $favorites_raw );
                                $item['products'] = $favorites_products ; 
                              
                                
                            } 
                            
                        }
                    }    
              
                case 2:
                    $relatedItems = [];
                    break;
                case 3:
                    $relatedItems = [];
                    break;
                case 4:
                    $relatedItems = EgBlockBuilderItemsClass::getElementDetails($id_egblockbuilder_items,$type);
                    break;
                case 5:
                    $relatedItems = EgBlockBuilderItemsClass::getElementDetails($id_egblockbuilder_items,$type);
                    break;    
                default:
                    $relatedItems = []; // No related items if type doesn't match
                    break;
            }

            
            // Assign type and related items to the template data
            $dataForTemplate[] = [
                'type' => $type,
                'relatedItems' => $relatedItems,
                // Include other necessary item details if needed
                'item' => $item,
                
            ];
 
        }
       // dump( $builder); die;
        // Assign the data to the template
        $this->context->smarty->assign( array(
                'itemsData' => $dataForTemplate,
                'type' => $type,
                'meta_title' => $builder->meta_title,
                'meta_desctiption' => $builder->description, 
                'base_url' => $base_url,
                'cat' => $builder->id_category
        )
    );
        parent::initContent();
        
        $this->setTemplate('module:egblockbuilder/views/templates/front/show.tpl');
    }
    public function getTemplateVarPage()
    {
        $id = Tools::getValue('id'); 
        $builder = new EgBlockBuilderClass($id, Context::getContext()->language->id, Context::getContext()->shop->id);
        $category = new Category($builder->id_category, Context::getContext()->language->id, Context::getContext()->shop->id);
    
        // Set title based on builder or category
        $title = empty($builder->meta_title) ? $category->name : $builder->meta_title;
    
        // Set and escape description based on builder or category
        $description = empty($builder->description) ? $category->description : $builder->description;
        $description = preg_replace('/^<p>(.*?)<\/p>/', '$1', $description);

    
        // Retrieve the page variable and set meta title and description
        $page = parent::getTemplateVarPage();
        $page['meta']['title'] = $title;
        $page['meta']['description'] = $description;
    
        return $page;
    }
    
}
