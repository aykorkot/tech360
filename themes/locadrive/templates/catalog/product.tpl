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
{extends file=$layout}

{block name='head' append}
    <meta property="og:type" content="product">
    {if $product.cover}
        <meta property="og:image" content="{$product.cover.large.url}">
    {/if}

    {if $product.show_price}
        <meta property="product:pretax_price:amount" content="{$product.price_tax_exc}">
        <meta property="product:pretax_price:currency" content="{$currency.iso_code}">
        <meta property="product:price:amount" content="{$product.price_amount}">
        <meta property="product:price:currency" content="{$currency.iso_code}">
    {/if}
    {if isset($product.weight) && ($product.weight != 0)}
        <meta property="product:weight:value" content="{$product.weight}">
        <meta property="product:weight:units" content="{$product.weight_unit}">
    {/if}
{/block}

{block name='head_microdata_special'}
    {include file='_partials/microdata/product-jsonld.tpl'}
{/block}

{block name='content'}

    <section id="main">
        <meta content="{$product.url}">

        <div class="row product-container js-product-container product__view">
            <div class="col-md-6 product__view--media">
                {block name='page_content_container'}
                    <section class="page-content" id="content">
                        {block name='page_content'}
                            {block name='product_cover_thumbnails'}
                                {include file='catalog/_partials/product-cover-thumbnails.tpl'}
                            {/block}
                        {/block}
                    </section>
                {/block}
            </div>
            <div class="col-md-6 product__view--infos">

                {block name='breadcrumb'}
                    {include file='_partials/breadcrumb.tpl'}
                {/block}

                <div class="product__view--intro">
                    <div class="product__view--intro--left">
                        {block name='page_header_container'}
                            {block name='page_header'}
                                <h1 class="h1">{block name='page_title'}{$product.name}{/block}</h1>
                            {/block}
                        {/block}
                    </div>
                    <div class="product__view--intro--right">
                        {hook h='displayProductListFunctionalButtons' product=$product}
 
                    </div>
                </div>

                {block name='product_prices'}
                    {include file='catalog/_partials/product-prices.tpl'}
                {/block}

                <div class="product-information">
 

                    {if $product.is_customizable && count($product.customizations.fields)}
                        {block name='product_customization'}
                            {include file="catalog/_partials/product-customization.tpl" customizations=$product.customizations}
                        {/block}
                    {/if}

                    <div class="product-actions js-product-actions">
                        {block name='product_buy'}
                            <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                                <input type="hidden" name="token" value="{$static_token}">
                                <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                                <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id" class="js-product-customization-id">

                                {block name='product_variants'}
                                    {include file='catalog/_partials/product-variants.tpl'}
                                {/block}

                                {block name='product_pack'}
                                    {if $packItems}
                                        <section class="product-pack">
                                            <p class="h3">{l s='This pack contains' d='Shop.Theme.Catalog'}</p>
                                            {foreach from=$packItems item="product_pack"}
                                                {block name='product_miniature'}
                                                    {include file='catalog/_partials/miniatures/pack-product.tpl' product=$product_pack showPackProductsPrice=$product.show_price}
                                                {/block}
                                            {/foreach}
                                        </section>
                                    {/if}
                                {/block}

                                {block name='product_discounts'}
                                    {include file='catalog/_partials/product-discounts.tpl'}
                                {/block}

                                {block name='product_add_to_cart'}
                                    {include file='catalog/_partials/product-add-to-cart.tpl'}
                                {/block}

                                

                                {* Input to refresh product HTML removed, block kept for compatibility with themes *}
                                {block name='product_refresh'}{/block}
                            </form>
                            {block name='product_additional_info'}
                                {include file='catalog/_partials/product-additional-info.tpl'}
                            {/block}
                        {/block}
                    </div>

                    {block name='product_tabs'}

                        <div class="accordions">

                            {if $product.description}
                                <div class="accordion__item">
                                    <div class="accordion__title">{l s='Description' d='Shop.Theme.Catalog'}</div>
                                    <div class="accordion__content">
                                        {block name='product_description'}
                                            {$product.description nofilter}
                                        {/block}
                                    </div>
                                </div>
                            {/if}

                            <div class="accordion__item">
                                <div class="accordion__title">{l s='Équipements' d='Shop.Theme.Catalog'}</div>
                                <div class="accordion__content">
                                    {if isset($product.description_short)}
                                        <div class="product-description">
                                            {$product.description_short nofilter}
                                        </div>
                                    {/if}
                                </div>
                            </div>

                            {if $product.attachments}
                                <div class="accordion__item">
                                    <div class="accordion__title">{l s='Attachments' d='Shop.Theme.Catalog'}</div>
                                    <div class="accordion__content">
                                        {block name='product_attachments'}
                                            {if $product.attachments}
                                                {foreach from=$product.attachments item=attachment}
                                                    <h4>
                                                        <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">{$attachment.name}</a>
                                                    </h4>
                                                    <p>{$attachment.description}</p>
                                                    <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
                                                        {l s='Download' d='Shop.Theme.Actions'} ({$attachment.file_size_formatted})
                                                    </a>
                                                {/foreach}
                                            {/if}
                                        {/block}
                                    </div>
                                </div>
                            {/if}

                            {foreach from=$product.extraContent item=extra key=extraKey}
                                <div class="accordion__item">
                                    <div class="accordion__title">{$extra.title}</div>
                                    <div class="accordion__content">
                                        {$extra.content nofilter}
                                    </div>
                                </div>
                            {/foreach}

                        </div>
                    {/block}

                    <div class="product-details">
                        <div class="product-details__content">
                            {block name='product_details'}
                                {include file='catalog/_partials/product-details.tpl'}
                            {/block}
                        </div>
                    </div>

                    {block name='hook_display_reassurance'}
                        {hook h='displayReassurance'}
                    {/block}
                </div>
            </div>
        </div>
 
     

        {block name='product_accessories'}
            {if $accessories}
                <section class="product-accessories featured-products">
                    <div class="c-title">
                        <div class="container">
                            <h2>{l s='You might also like' d='Shop.Theme.Catalog'}</h2>
                        </div>
                    </div>
                    <div class="products row">
                        {foreach from=$accessories item="product_accessory" key="position"}
                            {block name='product_miniature'}
                                {include file='catalog/_partials/miniatures/product.tpl' product=$product_accessory position=$position}
                            {/block}
                        {/foreach}
                    </div>
                </section>
            {/if}
        {/block}

        {block name='product_footer'}
            {hook h='displayFooterProduct' product=$product category=$category}
        {/block}

        {block name='product_images_modal'}
            {include file='catalog/_partials/product-images-modal.tpl'}
        {/block}

        {block name='page_footer_container'}
            <footer class="page-footer">
                {block name='page_footer'}
                    <!-- Footer content -->
                {/block}
            </footer>
        {/block}
    </section>

{/block}
