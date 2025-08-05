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

<div class="blockreassurance_product">
    {foreach from=$blocks item=$block key=$key}
        <div{if $block['type_link'] !== $LINK_TYPE_NONE && !empty($block['link'])} style="cursor:pointer;" onclick="window.open('{$block['link']}')"{/if}>
            <span class="item-product">
                {if $block['icon'] != 'undefined'}
                    {if $block['custom_icon']}
                        <img width="20" height="20" src="{$block['custom_icon']}" loading="lazy">
                    {elseif $block['icon']}
                        <img width="20" height="20" src="{$block['icon']}" loading="lazy">
                    {/if}
                {/if}
            </span>
            {if empty($block['description'])}
              <p class="block-title">{$block['title']}</p>
            {else}
              <span class="block-title">{$block['title']}</span>
              <p>{$block['description'] nofilter}</p>
            {/if}
        </div>
    {/foreach}
</div>
