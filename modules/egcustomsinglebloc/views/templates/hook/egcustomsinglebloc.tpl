{*
 * 2020  (c)  Egio digital
 *
 * MODULE EgBanner
 *
 * @author    Egio digital
 * @copyright Copyright (c) , Egio digital
 * @license   Commercial
 * @version    1.0.0
 *}
{if $status == 1}
{assign var='isMobile' value=Context::getContext()->isMobile()}
    <div class="hp-news">
   
        {if isset($video) && $video|count_characters > 0}
            {if $type_video == "video_type_vimeo"}
                <iframe src="https://player.vimeo.com/video/{$video}" width="650" height="550" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
            {/if}
            {if $type_video == "video_type_youtube"}
                <iframe width="650" height="550" src="https://www.youtube.com/embed/{$video|escape:'html':'UTF-8'}?rel=0&autoplay=1&loop=1"></iframe>
            {/if}
            {if $type_video == "video_type_other"}
                <video width="250" height="650" playsinline autoplay loop muted>
                    <source src="{$video|escape:'html':'UTF-8'}" type="video/mp4">
                    {l s='Your browser does not support the video tag.' d='Modules.Egbanner.Egbanner'}
                </video>
            {/if}
            {if $type_video == "video_type_image"}
                <picture>
                    {if !empty($img_mobile)}
                        <source media="(max-width: 992px)" srcset="{$urls.base_url}{$img_mobile|escape:'html':'UTF-8'}">
                    {/if}
                    <img 
                        class="desktop-img" 
                        width="1920" 
                        height="800" 
                        src="{$urls.base_url}{$img|escape:'html':'UTF-8'}" 
                        alt="{if $title}{$title|escape:'htmlall':'UTF-8'}{/if}" 
                        loading="lazy"
                    >
                </picture>
            {/if}
        {else}
            <picture>
                {if !empty($img_mobile)}
                    <source media="(max-width: 992px)" srcset="{$urls.base_url}{$img_mobile|escape:'html':'UTF-8'}">
                {/if}
                <img 
                    class="desktop-img" 
                    width="1920" 
                    height="800" 
                    src="{$urls.base_url}{$img|escape:'html':'UTF-8'}" 
                    alt="{if $title}{$title|escape:'htmlall':'UTF-8'}{/if}" 
                    loading="lazy"
                >
            </picture>
        {/if}
  
        <div class="hp-news__wrapper">
            <div class="container">
                {if isset($title) && !empty($title)}
                    <h2 >{$title}</h2>
                {/if} 
                {if isset($desc) && !empty($desc)} 
                        <p>{$desc nofilter}</p>
                {/if}
                {if !empty($Lien_btn) && !empty($text_btn)}
                    <a class="btn btn-white" href="{$Lien_btn}">
                        {$text_btn}
                    </a>
                {/if}
            </div>
        </div>
    </div>
{/if}
