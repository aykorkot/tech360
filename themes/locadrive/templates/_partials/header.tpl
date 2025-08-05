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

{*{block name='header_banner'}*}
{*    {hook h='displayBanner'}*}
{*{/block}*}

<div class="header-top">
    <div class="container">
        <div class="header-top-row">
            <div class="slider-container">
                <ul class="slider-list">
                    <li>🚚 Livraison gratuite à partir de 600 MAD</li>
                    <li>💵 Paiement à la livraison disponible</li>
                    <li>📞 Support client 7j/7</li>
                    <li>🔥 Promotions exclusives chaque semaine</li>
                </ul>
            </div>
            {hook h='displayTop'}
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="header__logo">
            {if $shop.logo_details}
                {if $page.page_name == 'index'}
                    <h1>{renderLogo}</h1>
                {else}
                    {renderLogo}
                {/if}
            {/if}
        </div>
        <div class="header_search">
            {hook h='displayNav1'}
        </div>
        <div class="header__menu">
            <div class="header__tools"> 
                <div class="header__tools-contact">
                    <img width="35" height="35" src="{$urls.img_url}icon_contact.svg" alt="Appelez-nous" loading="lazy">
                    <a href="tel:0660449850" title="Appelez-nous" rel="nofollow">
                       <span>Appelez-nous :</span> 
                       06 60 44 98 50
                    </a>
                </div>
                {block name='header_nav'}
                    {hook h='displayNav2'}
                {/block}
            </div>
            <div class="header__toggle">
                <div class="header__toggle--button">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="header__nav">
    <div class="container">
            {block name='header_top'}
                {hook h='displayNavFullWidth'}
            {/block}
    </div>
</div>