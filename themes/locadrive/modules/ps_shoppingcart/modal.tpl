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

<div id="blockcart-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {l s='Panier' d='Shop.Theme.Checkout'}
                <button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' d='Shop.Theme.Global'}">
                    <svg focusable="false" viewBox="0 0 26 27" width="10px" height="10px">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13 14.348l11.445 11.685L26 24.445 14.555 12.761 25.5 1.588 23.944 0 13 11.173 2.056 0 .501 1.588 11.445 12.76 0 24.444l1.555 1.588L13 14.348z" fill="currentColor"></path>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-cart">
                    <div class="modal-cart__item">
                        <div class="modal-cart__img">
                            {if $product.default_image}
                                <img
                                        src="{$product.default_image.medium.url}"
                                        data-full-size-image-url="{$product.default_image.large.url}"
                                        title="{$product.default_image.legend}"
                                        alt="{$product.default_image.legend}"
                                        loading="lazy"
                                        class="product-image"
                                >
                            {else}
                                <img
                                        src="{$urls.no_picture_image.bySize.medium_default.url}"
                                        loading="lazy"
                                        class="product-image"
                                />
                            {/if}
                        </div>
                        <div class="modal-cart__body">
                            <div class="product-name">{$product.name}</div>
                            {hook h='displayProductPriceBlock' product=$product type="unit_price"}
                            {foreach from=$product.attributes item="property_value" key="property"}
                                <div class="{$property|lower}">
                                    <span>{l s='%label%:' sprintf=['%label%' => $property] d='Shop.Theme.Global'}</span>
                                    <span>{$property_value}</span>
                                </div>
                            {/foreach}
                            <div class="product-quantity">
                                <span>{l s='Quantity:' d='Shop.Theme.Checkout'}</span>
                                <span>{$product.cart_quantity}</span>
                            </div>
                            <div class="product-price">{$product.price}</div>
                        </div>
                    </div>
                </div>
                <div class="modal-cart__content">
                    {*                    {if $cart.products_count > 1}*}
                    {*                        <p class="cart-products-count">*}
                    {*                            {l s='There are %products_count% items in your cart.' sprintf=['%products_count%' => $cart.products_count] d='Shop.Theme.Checkout'}*}
                    {*                        </p>*}
                    {*                    {else}*}
                    {*                        <p class="cart-products-count">*}
                    {*                            {l s='There is %products_count% item in your cart.' sprintf=['%products_count%' =>$cart.products_count] d='Shop.Theme.Checkout'}*}
                    {*                        </p>*}
                    {*                    {/if}*}
                    <p>
                        <span>{l s='Subtotal:' d='Shop.Theme.Checkout'}</span>
                        <span class="subtotal value">{$cart.subtotals.products.value}</span>
                    </p>
                    {if $cart.subtotals.shipping.value}
                        <p>
                            <span>{l s='Shipping:' d='Shop.Theme.Checkout'}</span>
                            <span class="shipping value">{$cart.subtotals.shipping.value} {hook h='displayCheckoutSubtotalDetails' subtotal=$cart.subtotals.shipping}</span>
                        </p>
                    {/if}

                    {if !$configuration.display_prices_tax_incl && $configuration.taxes_enabled}
                        <p>
                            <span>{$cart.totals.total.label}{if $configuration.display_taxes_label}&nbsp;{$cart.labels.tax_short}{/if}</span>
                            <span class="value">{$cart.totals.total.value}</span>
                        </p>
                        <p class="product-total">
                            <span>{$cart.totals.total_including_tax.label}</span>
                            <span class="value">{$cart.totals.total_including_tax.value}</span>
                        </p>
                    {else}
                        <p class="product-total">
                            <span>{$cart.totals.total.label}&nbsp;{if $configuration.taxes_enabled && $configuration.display_taxes_label}{$cart.labels.tax_short}{/if}</span>
                            <span class="value">{$cart.totals.total.value}</span>
                        </p>
                    {/if}

                    {if $cart.subtotals.tax}
                        <p class="product-tax">
                            <span>{l s='%label%:' sprintf=['%label%' => $cart.subtotals.tax.label] d='Shop.Theme.Global'}</span>
                            <span class="value">{$cart.subtotals.tax.value}</span>
                        </p>
                    {/if}
                    {hook h='displayCartModalContent' product=$product}
                    <div class="cart-content-btn">
                        <a href="{$cart_url}" class="btn btn-white"></i>{l s='Proceed to checkout' d='Shop.Theme.Actions'}</a>
                        <div data-dismiss="modal">
                            <span>{l s='Continue shopping' d='Shop.Theme.Actions'}</span>
                        </div>
                    </div>
                </div>
            </div>
            {hook h='displayCartModalFooter' product=$product}
        </div>
    </div>
</div>
