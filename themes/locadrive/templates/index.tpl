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
{extends file='page.tpl'}

{block name='page_content_container'}
    <section id="content" class="page-home">
        {block name='page_content_top'}{/block}

        {block name='page_content'}
            {block name='hook_home'} 
                
                {$HOOK_HOME nofilter}
                <div class="block-reassurance">
                    <ul>
                        <li>
                            <div class="block-reassurance-item">
                                <div class="reassurance-icon">
                                    <img src="{$urls.img_url}reservation.svg" alt="Réservation" loading="lazy">
                                </div>
                                <div class="block-reassurance-intro">
                                    <div class="reassurance-title">Réservation rapide</div>
                                    <div class="reassurance-description">06 00 00 00 00</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="block-reassurance-item">
                                <div class="reassurance-icon">
                                    <img src="{$urls.img_url}money-return.svg" alt="Retour d'argent" loading="lazy">
                                </div>
                                <div class="block-reassurance-intro">
                                    <div class="reassurance-title">Retour d'argent</div>
                                    <div class="reassurance-description">Satisfait ou remboursé</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="block-reassurance-item">
                                <div class="reassurance-icon">
                                    <img src="{$urls.img_url}discount.svg" alt="Remises" loading="lazy">
                                </div>
                                <div class="block-reassurance-intro">
                                    <div class="reassurance-title">Remises exclusifs</div>
                                    <div class="reassurance-description">Avantages fidélité</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="block-reassurance-item">
                                <div class="reassurance-icon">
                                    <img src="{$urls.img_url}gift.svg" alt="Cadeaux" loading="lazy">
                                </div>
                                <div class="block-reassurance-intro">
                                    <div class="reassurance-title">Cadeaux spéciaux</div>
                                    <div class="reassurance-description">Surprise à l’achat</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div> 
            {/block}
        {/block}
    </section>
{/block}
