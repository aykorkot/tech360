<div class="guarantees">
    <div class="container">
        <div class="c-title">
            <div class="container">
                <h2>{l s='🔒 Les garanties 🔒' d='Shop.Theme.Global'}</h2>
            </div>
        </div>
        <div class="row guarantees__items">
            {foreach from=$guarantees item=guarantee}
 
            <div class="guarantees__item">
                {if $guarantee.title}
                <h3 class="guarantees__item--title">
                    {$guarantee.title}
                </h3>
                {/if}
                {if $guarantee.subtitle}
                <h4 class="guarantees__item--subtitle">
                    {$guarantee.subtitle}
                </h4>
                {/if}
                {if $guarantee.description}
                <p class="guarantees__item--description">
                    {$guarantee.description}
                </p>
                {/if}
                <!-- {if $guarantee.logos|@count > 0}
                    <div class="guarantees__item--logos">
                        {foreach from=$guarantee.logos item=logo}
                        {if $logo.src}
                            <img width="94" height="46" src="{$logo.src}" alt="{$logo.alt}" loading="lazy">
                        {/if}
                        {/foreach}
                    </div>
                {/if} -->
            </div>
          
            {/foreach}
        </div>
    </div>
</div>