{*
* 2021 (c) Egio digital
*
* MODULE EgSizeData
*
* @version 1.0.0
*}
{if isset($content) && !empty($content)}
    
    <div id="popup__sizeGuide" class="popup">
        <div class="popup__inner">
            <div class="popup__header">
                {l s='Guide des tailles : ' d='Shop.Theme.Actions'} {$content.title nofilter}
                <div class="popup__close">
                    <svg focusable="false" aria-hidden="true" viewBox="0 0 24 24" title="Close">
                        <path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
                    </svg>
                </div>
            </div>
            <div class="popup__body">
                <div class="h2">{$product.name}</div>
                <div class="h3">Comment mesurer ?</div>
                <div>{$content.contenu nofilter}</div>
                <div class="l-half-width text-center">
                    {$image|unescape: "html" nofilter}
                </div>
            </div>
        </div>
    </div>

{/if}