{*
 * 2020  (c)  Egio digital
 *
 * MODULE EgBanner
 *
 * @version    1.0.0
 *}
{if  $status == 1}
{assign var='isMobile' value=Context::getContext()->isMobile()}
    <div class="why-choose">
        <div class="container">
            <div class="why-choose__wrapper">
                <div class="why-choose__img">
                    <img src="{if $isMobile}{$img_mobile|escape:'html':'UTF-8'}{else}{$img|escape:'html':'UTF-8'}{/if}" alt="{if $title}{$title|escape:'htmlall':'UTF-8'}{/if}" loading="lazy">
                </div>
                <div class="why-choose__details">
                    <div class="why-choose__details--inner">
                        <h2>
                            {l
                            s='Pourquoi choisir [1]%shopName%[/1]'
                            sprintf=[
                            '%shopName%' => {$shop.name},
                            '[1]' => "<span>",
                            '[/1]' => "</span>"
                            ]
                            d='Shop.Theme.Global'
                            }
                        </h2>
                        {if isset($desc) && !empty($desc)}
                            <p>{$desc nofilter}</p>
                        {/if}
                        <div class="single-bloc-link">
                            {if isset($btn_url) && !empty($btn_url)}
                                <a class="btn btn-light" href="{$btn_url}">
                                    {if isset($btn_txt) && !empty($btn_txt)}
                                        {$btn_txt}
                                    {else}
                                        {l s='Découvrir le concept' d='Shop.Theme.Global'}
                                    {/if}
                                </a>
                            {/if}
                        </div>
                        {if $reassurances}
                            <ul>
                                {foreach $reassurances as $item}
                                    {if $item.active}
                                        <li>
                                            <div class="c-icon">
                                                <img src="{$src}{$item.icon}" alt="icon" loading="lazy">
                                            </div>
                                            <div class="c-text">
                                                <h3>{$item.title}</h3>
                                                <p>{$item.description nofilter}</p>
                                            </div>
                                        </li>
                                    {/if}
                                {/foreach}
                            </ul>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}
