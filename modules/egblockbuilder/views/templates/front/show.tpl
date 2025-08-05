{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *}
 {extends file='page.tpl'}
{block name='page_content'}
 
{if $itemsData} 
{assign var='isMobile' value=Context::getContext()->isMobile()}
{foreach from=$itemsData item=itemData}
    {if $itemData.item.active}
        {if $itemData.type == 1}
            <div class="container">
                {$itemData.item.title_1 nofilter}
            </div> 
            {if isset($itemData.item.products) && $itemData.item.products|@count > 0} 
                <section class="product-accessories favorites-gender">
                    <div class="container">
                        <div class="tabs">
                            <div class="nav-tabs-overflow">
                                {$itemData.item.title_2 nofilter}
                            </div>
                            <div class="tab-content" id="tab-content">
                                {include file="catalog/_partials/productlist.tpl" productClass="col-xs-12 col-sm-6 col-xl-3"
                                products=$itemData.item.products}
                            </div>
                        </div>
                    </div>
                </section>
            {/if}
        {elseif $itemData.type == 2}
            <div class="cms__banner"> 
                {if $itemData.item.type_video_banniere == "video_type_image"}
                    <picture>
                        {assign var="avif" value="{$base_url}modules/egblockbuilder/views/img/{$itemData.item.banner}"}
                        {assign var="webp" value="{$base_url}modules/egblockbuilder/views/img/{$itemData.item.banner}"}    
                            {if !empty($avif)}<source srcset="{$base_url}modules/egblockbuilder/views/img/{$itemData.item.banner}" type="image/avif">{/if}
                            {if !empty($webp)}<source srcset="{$base_url}modules/egblockbuilder/views/img/{$itemData.item.banner}" type="image/webp">{/if}
                            {if !$isMobile }
                                <img width="1920" height="942" src="{$base_url}modules/egblockbuilder/views/img/{$itemData.item.banner}" alt="" loading="lazy">
                            {else}
                                <img width="1920" height="942" src="{$base_url}modules/egblockbuilder/views/img/{$itemData.item.banner_mobile}" alt="" loading="lazy">
                            {/if}
                    </picture>
                    {elseif $itemData.item.type_video_banniere == "video_type_youtube"}
                    <iframe width="960" height="800" src="https://www.youtube.com/embed/{$itemData.item.video_banniere}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    {elseif $itemData.item.type_video_banniere == "video_type_vimeo"}
                    <iframe src="https://player.vimeo.com/video/{$itemData.item.video_banniere}" width="960" height="800" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    {elseif $itemData.item.type_video_banniere == "video_type_other"}
                    <video width="960" height="800" controls>
                        <source src="{$base_url}modules/egblockbuilder/views/img/{$itemData.item.video_banniere}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video> 
                {/if}
                <div class="container">
                    <div class="cms__banner--content">
                        {$itemData.item.title_2 nofilter}
                    </div>
                    {if $itemData.item.link_button != '' && $itemData.item.text_button != ''}   
                        <a href="{$itemData.item.link_button}" class="btn btn-light">{$itemData.item.text_button nofilter}</a>
                    {/if}
                </div>
            </div> 
        {elseif $itemData.type == 3}  
            <div class="cms__wysiwyg {if $itemData.item.background_color == 1}cms__wysiwyg--bg{/if}">
                <div class="cms__wysiwyg--title">
                    <div class="container">
                       {$itemData.item.title_3 nofilter}
                    </div>
                </div>
                <div class="cms__wysiwyg--content">
                    <div class="container">
                        {$itemData.item.text_3 nofilter}
                    </div>
                </div>
                {if $itemData.item.link_button_3 != '' && $itemData.item.text_button_3 != ''}   
                    <div class="cms__wysiwyg--action">
                        <div class="container"> 
                            <a href="{$itemData.item.link_button_3}" class="btn btn-light">{$itemData.item.text_button_3 nofilter}</a>
                        </div>
                    </div>
                {/if}
            </div> 
        {elseif $itemData.type == 4} 
            {if $itemData.relatedItems|@count > 0}
            <div class="cms__shape">
                <div class="container">
                    <div class="cms__shape--wrapper">
                        <div class="cms__shape--img">
                           {$itemData.item.title_4 nofilter} 
                            {foreach from=$itemData.relatedItems item=relatedItem} 
                            {if $relatedItem.video == "video_type_image"}
                                <picture>
                                {assign var="avif" value="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.double_image}"}
                                {assign var="webp" value="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.double_image}"}    
                                    {if !empty($avif)}<source srcset="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.double_image}" type="image/avif">{/if}
                                    {if !empty($webp)}<source srcset="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.double_image}" type="image/webp">{/if}
                                    {if !$isMobile }
                                        <img class="img-{$relatedItem@iteration}" src="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.double_image}" alt="" loading="lazy">
                                    {else}
                                        <img class="img-{$relatedItem@iteration}" src="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.banner_mobile}" alt="" loading="lazy">
                                    {/if}
                                </picture>
                            {elseif $relatedItem.video == "video_type_youtube"}
                            <iframe width="960" height="800" src="https://www.youtube.com/embed/{$relatedItem.video_double}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            {elseif $relatedItem.video == "video_type_vimeo"}
                            <iframe src="https://player.vimeo.com/video/{$relatedItem.video_double}" width="960" height="800" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            {elseif $relatedItem.video == "video_type_other"}
                            <video width="960" height="800" controls>
                                <source src="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.video_double}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video> 
                            {/if}
                            {/foreach}
                        </div>
                        <div class="cms__shape--content">
                            {$itemData.item.text_4 nofilter}
                        </div>
                    </div>
                </div>
            </div>
            {else}
            <div class="cms__shape">
                <div class="container">
                        {$itemData.item.title_4 nofilter}
                        <div class="cms__shape--content">
                            {$itemData.item.text_4 nofilter}
                        </div> 
                </div>
            </div>
            {/if}
        {elseif $itemData.type == 5}
            {if $itemData.relatedItems|@count > 0}
         
                <div class="cms__twoCols--img">
                    

                            {foreach from=$itemData.relatedItems item=relatedItem} 
                            <div class="cms__twoCols--img--col">
                            <a href="#">  
                            {if $relatedItem.video == "video_type_image"}
                                <picture>
                                {assign var="avif" value="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.double_image}"}
                                {assign var="webp" value="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.double_image}"}    
                                    {if !empty($avif)}<source srcset="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.double_image}" type="image/avif">{/if}
                                    {if !empty($webp)}<source srcset="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.double_image}" type="image/webp">{/if}
                                    {if !$isMobile }
                                        <img src="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.double_image}" alt="" loading="lazy">
                                    {else}
                                        <img src="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.banner_mobile}" alt="" loading="lazy">
                                    {/if}
                                </picture>
                            {elseif $relatedItem.video == "video_type_youtube"}
                            <iframe width="960" height="800" src="https://www.youtube.com/embed/{$relatedItem.video_double}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            {elseif $relatedItem.video == "video_type_vimeo"}
                            <iframe src="https://player.vimeo.com/video/{$relatedItem.video_double}" width="960" height="800" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            {elseif $relatedItem.video == "video_type_other"}
                            <video width="960" height="800" controls>
                                <source src="{$base_url}modules/egblockbuilder/views/img/{$relatedItem.video_double}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video> 
                            {/if}
                            </a>
                            {$relatedItem.text_double nofilter}
                            </div>
                            {/foreach}
                        
                     
                </div>
         - 
            {/if} 
        {/if}
    {/if}
{/foreach}
{/if}
{/block}