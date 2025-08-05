{*
 * 2020  (c)  Egio digital
 *
 * MODULE EgBanner
 *
 * @author    Egio digital
 * @copyright Copyright (c) , Egio digital
 * @license   Commercial
 * @version    1.0.0
 */
*}
{assign var='isMobile' value=Context::getContext()->isMobile()}
{if isset($banners) && !empty($banners) && $status == 1}
    <div class="popular-categories">
        <div class="c-title">
            {if $title_banner}
                <h2><span>{$title_banner}</span></h2>
            {/if}
            <div class="c-title-description">
                {{$text_banner nofilter} nofilter}
                <a href="#" class="toggle-more">+ Voir plus</a>
            </div>
        </div>
        <div class="popular-categories__wrapper">
            {foreach from=$banners item=banner}
                <div class="category__card">
                    {if isset($banner.link) && !empty($banner.link)}
                        <a href="{$banner.link}">
                    {/if} 
                        {assign var="video" value=$banner.video_vimeo}
                        {assign var="video_count" value=$video|count_characters}
                        {if isset($video) && $video_count > 0}
                            {if $banner.type_video == "video_type_image"}

                                {if isset($banner.image) && !empty($banner.image)}
                                    <img src="{$uri}{$banner.image|escape:'html':'UTF-8'}" title="{if $banner.title}{$banner.title|escape:'htmlall':'UTF-8'}{/if}" width="480" height="620" loading="lazy">
                                {/if}
                                {if $banner.title}{$banner.title|escape:'htmlall':'UTF-8'}{/if}
                                {if $isMobile && isset($banner.image_hover) && !empty($banner.image_hover)}
                                    <img class="hidden-md-up" src="{$uri}{$banner.image_hover|escape:'html':'UTF-8'}" title="{if $banner.title}{$banner.title|escape:'htmlall':'UTF-8'}{/if}" width="480" height="620" loading="lazy">
                               {/if}
                               
                            {else}
                                {if $banner.type_video == "video_type_vimeo"}
                                    <iframe src="https://player.vimeo.com/video/{$video}" width="480" height="620" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                {/if}
                                {if $banner.type_video == "video_type_youtube"}
                                    <iframe height="620" width="480" src="https://www.youtube.com/embed/il_t1WVLNxk?playlist={$video|escape:'html':'UTF-8'}&loop=1"></iframe>
                                {/if}
                                {if $banner.type_video == "video_type_other"}
                                    <video width="480" height="620" playsinline autoplay loop muted>
                                        <source src="{$video|escape:'html':'UTF-8'}" type="video/mp4">
                                        {l s='Your browser does not support the video tag.' d='Modules.Egbanner.Egbanner'}
                                    </video>
                                {/if}
                            {/if}
                        {else}
                            {if isset($banner.image) && !empty($banner.image)}
                                <img src="{$uri}{$banner.image|escape:'html':'UTF-8'}" title="{if $banner.title}{$banner.title|escape:'htmlall':'UTF-8'}{/if}" width="480" height="620" loading="lazy">
                            {/if}
                            <div class="category__card--inner">
                                {if $banner.title}
                                    <h3 class="category__card--title">{$banner.title|escape:'htmlall':'UTF-8'}</h3>
                                {/if}
                                {if isset($banner.image_hover) && !empty($banner.image_hover)}
                                    <img src="{$uri}{$banner.image_hover|escape:'html':'UTF-8'}" title="{if $banner.title}{$banner.title|escape:'htmlall':'UTF-8'}{/if}" width="480" height="620" loading="lazy">
                                {/if}
                            </div>
                        {/if} 
                    {if isset($banner.link) && !empty($banner.link)}
                        </a>
                    {/if}
                </div>
            {/foreach}
        </div>
    </div>
{/if}
