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

<div class="products-sort-order dropdown">
    <button
            class="btn btn-white select-title"
            rel="nofollow"
            data-toggle="dropdown"
            aria-label="{l s='Sort by selection' d='Shop.Theme.Global'}"
            aria-haspopup="true"
            aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="currentColor" d="m9.658 19.78l.158.475zm5-1.666l.158.474zm5.05-10.821l.353.353zm-4.415 4.414l-.354-.353zM5 4.5h14v-1H5zm-.5 2.086V5h-1v1.586zm4.56 4.768L4.647 6.939l-.707.707l4.415 4.415zm-.56 1.06v6.892h1v-6.892zm0 6.892a1 1 0 0 0 1.316.949l-.316-.949zm1.316.949l5-1.667l-.316-.948l-5 1.666zm5-1.667a1 1 0 0 0 .684-.948h-1zm.684-.948v-5.226h-1v5.226zm3.854-10.7l-4.415 4.414l.707.707l4.415-4.415zM19.5 5v1.586h1V5zm.56 2.646a1.5 1.5 0 0 0 .44-1.06h-1a.5.5 0 0 1-.146.353zm-4.56 4.768a.5.5 0 0 1 .146-.353l-.707-.707a1.5 1.5 0 0 0-.439 1.06zm-7.146-.353a.5.5 0 0 1 .146.353h1a1.5 1.5 0 0 0-.44-1.06zM3.5 6.586c0 .398.158.78.44 1.06l.706-.707a.5.5 0 0 1-.146-.353zM19 4.5a.5.5 0 0 1 .5.5h1A1.5 1.5 0 0 0 19 3.5zm-14-1A1.5 1.5 0 0 0 3.5 5h1a.5.5 0 0 1 .5-.5z"/>
        </svg>
        {if $listing.sort_selected}
            {$listing.sort_selected}
        {else}
            {l s='Sort by' d='Shop.Theme.Global'}
        {/if}
    </button>
    <div class="dropdown-menu">
        {foreach from=$listing.sort_orders item=sort_order}
            <a rel="nofollow" href="{$sort_order.url}" class="select-list {['current' => $sort_order.current, 'js-search-link' => true]|classnames}">
                {$sort_order.label}
            </a>
        {/foreach}
    </div>
</div>
