{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{block name='product_miniature_item'}
    <div class="js-product product{if !empty($productClasses)} {$productClasses}{/if}{if isset($swiper)} {$swiper}{/if}{if isset($readOnly)} wishlist-no-btm{/if}{if isset($counter) && $counter == 9} product__big{/if}">
        <article class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
            <div>
                {if isset($readOnly)} 
                    <a href="#" class="js-egwishlist-remove"
                    data-id-product="{$product->id_product|intval}"
                    data-url="{url entity='module' name='egwishlist' controller='actions'}">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </a>
                {else} 
                    <div class="wishlist-icon">
                        {hook h='displayProductListFunctionalButtons' product=$product}
                    </div>
                {/if}
                <div class="thumbnail-top">
                    {block name='product_thumbnail'}
                        {if $product.cover}
                            <a href="{$product.url}" class="thumbnail product-thumbnail">
                                <picture>
                                    {if !empty($product.cover.bySize.home_default.sources.avif)}<source srcset="{$product.cover.bySize.home_default.sources.avif}" type="image/avif">{/if}
                                    {if !empty($product.cover.bySize.home_default.sources.webp)}<source srcset="{$product.cover.bySize.home_default.sources.webp}" type="image/webp">{/if}
                                    <img
                                            src="{$product.cover.bySize.home_default.url}"
                                            alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
                                            loading="lazy"
                                            data-full-size-image-url="{$product.cover.large.url}"
                                            width="{$product.cover.bySize.home_default.width}"
                                            height="{$product.cover.bySize.home_default.height}"
                                    />
                                    {if isset($product.images[1])}
                                        <img
                                                class="img-hover"
                                                itemprop="image"
                                                loading="lazy"
                                                src="{$product.images[1].bySize.home_default.url}"
                                                alt="{if !empty($product.images[1].legend)}{$product.images[1].legend}{else}{$product.name}{/if}"
                                        />
                                    {/if}
                                </picture>
                            </a>
                        {else}
                            <a href="{$product.url}" class="thumbnail product-thumbnail">
                                <picture>
                                    {if !empty($urls.no_picture_image.bySize.home_default.sources.avif)}<source srcset="{$urls.no_picture_image.bySize.home_default.sources.avif}" type="image/avif">{/if}
                                    {if !empty($urls.no_picture_image.bySize.home_default.sources.webp)}<source srcset="{$urls.no_picture_image.bySize.home_default.sources.webp}" type="image/webp">{/if}
                                    <img
                                            src="{$urls.no_picture_image.bySize.home_default.url}"
                                            loading="lazy"
                                            width="{$urls.no_picture_image.bySize.home_default.width}"
                                            height="{$urls.no_picture_image.bySize.home_default.height}"
                                    />
                                </picture>
                            </a>
                        {/if} 
                    {/block}

                    {* {assign var=attributes_groups  value=ProductAttribute::getSizeAttributes($product.id_product)} *}
                    
                    {* {if $attributes_groups}
                        <div class="product__variants"> 
                                {foreach from=$attributes_groups item=attribute} 
                                    <a href="{$attribute.url}" class="product__variant--item">{$attribute.name}</a>
                                {/foreach} 
                        </div>
                             
                    {/if}  *}

                    {* {block name='product_variants'}
                        {if $product.main_variants}
                            {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
                        {/if}
                    {/block} *}

                </div>

                <div class="product-description">

                    {block name='product_name'}
                        {if $page.page_name == 'index'}
                            <h3 class="h3 product-title"><a href="{$product.url}" content="{$product.url}">{$product.name|truncate:30:'...'}</a></h3>
                        {else}
                            <h2 class="h3 product-title"><a href="{$product.url}" content="{$product.url}">{$product.name|truncate:30:'...'}</a></h2>
                        {/if}
                    {/block}

                    {block name='product_price_and_shipping'}
                        {if $product.show_price}
                            <div class="product-price-and-shipping">
                                <span class="price" aria-label="{l s='Price' d='Shop.Theme.Catalog'}">
                                    {capture name='custom_price'}{hook h='displayProductPriceBlock' product=$product type='custom_price' hook_origin='products_list'}{/capture}
                                    {if '' !== $smarty.capture.custom_price}
                                        {$smarty.capture.custom_price nofilter}
                                    {else}
                                        {$product.price}
                                    {/if}
                                </span>
                                {if $product.has_discount}
                                    {hook h='displayProductPriceBlock' product=$product type="old_price"}

                                    <span class="regular-price" aria-label="{l s='Regular price' d='Shop.Theme.Catalog'}">{$product.regular_price}</span>
{*                                    {if $product.discount_type === 'percentage'}*}
{*                                        <span class="discount-percentage discount-product">{$product.discount_percentage}</span>*}
{*                                    {elseif $product.discount_type === 'amount'}*}
{*                                        <span class="discount-amount discount-product">{$product.discount_amount_to_display}</span>*}
{*                                    {/if}*}
                                {/if}

                                {hook h='displayProductPriceBlock' product=$product type="before_price"}



                                {hook h='displayProductPriceBlock' product=$product type='unit_price'}

                                {hook h='displayProductPriceBlock' product=$product type='weight'}
                            </div>
                        {/if}
                    {/block}

{*                    {block name='product_reviews'}*}
{*                        {hook h='displayProductListReviews' product=$product}*}
{*                    {/block}*}
                </div>

                {include file='catalog/_partials/product-flags.tpl'}
            </div>
        </article>
    </div>
{/block}
