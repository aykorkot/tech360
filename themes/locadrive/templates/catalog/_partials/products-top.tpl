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
<div id="js-product-list-top" class="products-selection">

    <div class="total-products">
        <h1>{$category.name}&nbsp;({l s='%product_count%' d='Shop.Theme.Catalog' sprintf=['%product_count%' => $listing.pagination.total_items]})</h1>
    </div>

    <div class="sort-by-row">
        {if !empty($listing.rendered_facets)}
            <div class="filter-button">
                <button id="search_filter_toggler" class="btn btn-light js-search-toggler">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round">
                            <path d="M5 12V4m14 16v-3M5 20v-4m14-3V4m-7 3V4m0 16v-9"/>
                            <circle cx="5" cy="14" r="2"/>
                            <circle cx="12" cy="9" r="2"/>
                            <circle cx="19" cy="15" r="2"/>
                        </g>
                    </svg>
                    {l s='Filter' d='Shop.Theme.Actions'}
                </button>
            </div>
        {/if}
        {block name='sort_by'}
            {include file='catalog/_partials/sort-orders.tpl' sort_orders=$listing.sort_orders}
        {/block}
    </div>

</div>
