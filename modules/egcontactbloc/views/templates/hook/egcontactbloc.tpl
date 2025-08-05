{*
 * 2020  (c)  Egio digital
 *
 * MODULE EgBanner
 *
 * @author    Egio digital
 * @copyright Copyright (c) , Egio digital
 * @license   Commercial
 * @version    1.0.0
 */}
*}

{if  $status == 1}
{assign var='isMobile' value=Context::getContext()->isMobile()}
<div class="contact--banner">
    <div class="container">
        <div class="contact--banner__wrapper">
            {if isset($img) && !empty($img) && !$isMobile}
            <img width="1792" height="645" src="{$img}" alt="{$title}" loading="lazy">
            {/if}
            {if isset($EG_CONTACT_BLOC_IMG_MOBILE) && !empty($EG_CONTACT_BLOC_IMG_MOBILE) && $isMobile}
            <img width="768" height="432" src="{$EG_CONTACT_BLOC_IMG_MOBILE}" alt="{$title}" loading="lazy" class="mobile-only">
            {/if}
            <div class="contact--banner__inner">
                 
                {if isset($title) && !empty($title)}
                    <h2>{$title}</h2>
                {/if} 
                {if isset($sub_title) && !empty($sub_title)}
                    <h3>{$sub_title}</h3>
                {/if} 
                {if isset($desc) && !empty($desc)}
                    <p>
                        {$desc|strip_tags:'UTF-8'|truncate:360:'...' nofilter}
                    </p>
                {/if} 
                {if isset($btn_url) && !empty($btn_txt)} 
                    <a class="btn" href="{$btn_url}">
                        {$btn_txt}
                    </a>
                {/if}
            </div>
        </div>
    </div>
</div>
{/if}
