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


{*{block name='hook_footer_before'}*}
{*    {hook h='displayFooterBefore'}*}
{*{/block}*}


<div class="footer__top">
    <div class="container">
        <div class="row">
            <div class="footer__top--left">
                <div class="header__logo">
                    {if $shop.logo_details}
                        {if $page.page_name == 'index'}
                             {renderLogo}
                        {else}
                            {renderLogo}
                        {/if}
                    {/if}
                </div>
                <div class="footer__description">
                    <p>🚚 Livraison partout au Maroc</p>
                    <p>✅ Garantie jusqu'à 2 ans</p>
                    <p>📞 Service client 7j/7</p>
                    <p>🔒 Paiement sécurisé</p>
                    <p>🔧 SAV rapide & efficace</p>
                </div>
                {block name='hook_footer'}
                    {hook h='displayFooter'}
                {/block}
            </div>
            <div class="footer__top--right">
                {block name='hook_footer_after'}
                    {hook h='displayFooterAfter'}
                {/block}
            </div>
        </div>
    </div>
</div>

<div class="footer__bottom">
    <div class="container">
        <div class="footer__copyright">
            {block name='copyright_link'}
                {l
                    s='%copyright% [1]%shopName%.com[/1] - %year%. Tous droits réservés.'
                    sprintf=[
                        '%shopName%' => {$shop.name},
                        '%year%' => 'Y'|date,
                        '%copyright%' => '©',
                        '[1]' => "<span>",
                        '[/1]' => "</span>"
                    ]
                    d='Shop.Theme.Global'
                }
            {/block}
        </div>
    </div>
</div>

<div class="back__toTop">
    <i class="material-icons">chevron_left</i>
</div>