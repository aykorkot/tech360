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

{block name='content'}

    <section id="main">
        <div class="cart__header">
            {block name='continue_shopping'}
                <a class="back__button" href="{$urls.pages.index}">
                    <span>{l s='Retour aux achats' d='Shop.Theme.Actions'}</span>
                    <div>
                        <span>
                            <svg focusable="false" class="c-icon icon--arrow-button" viewBox="0 0 42 10" width="15px" height="15px">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M40.0829 5.5H0V4.5H40.0829L36.9364 1.35359L37.6436 0.646484L41.9971 5.00004L37.6436 9.35359L36.9364 8.64649L40.0829 5.5Z" fill="currentColor"></path>
                            </svg>
                        </span>
                    </div>
                </a>
            {/block}
            {if $cart.products}
                <h1 class="h1">{l s='Shopping Cart' d='Shop.Theme.Checkout'}</h1>
            {else}
                <h1 class="h1">{l s='Votre panier est vide 🥲' d='Shop.Theme.Checkout'}</h1>
            {/if}
        </div>

        {if $cart.products}
        <div class="cart-grid">

            <!-- Left Block: cart product informations & shipping -->
            <div class="cart-grid-body">

                <!-- cart products detailed -->
                <div class="card cart-container">
                    {block name='cart_overview'}
                        {include file='checkout/_partials/cart-detailed.tpl' cart=$cart}
                    {/block}
                </div>

                <!-- shipping informations -->
                {block name='hook_shopping_cart_footer'}
                    {hook h='displayShoppingCartFooter'}
                {/block}
            </div>

            <!-- Right Block: cart subtotal & cart total -->
            <div class="cart-grid-right">

                {block name='cart_summary'}
                    <div class="card cart-summary">

                        {block name='hook_shopping_cart'}
                            {hook h='displayShoppingCart'}
                        {/block}

                        {block name='cart_totals'}
                            {include file='checkout/_partials/cart-detailed-totals.tpl' cart=$cart}
                        {/block}

                    </div>
                {/block}

{*                {block name='hook_reassurance'}*}
{*                    {hook h='displayReassurance'}*}
{*                {/block}*}

            </div>

        </div>
        {/if}

        {hook h='displayCrossSellingShoppingCart'}

    </section>
{/block}
