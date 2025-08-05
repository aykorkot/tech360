<section class="custom-content">
    {foreach from=$custom_contents item=item}
        <div class="custom-content-block">
            <a class="custom-content-link" href="{$item.button_link}">
                {if isset($item.image) && $item.image != ''}
                    <img src="{$base_dir}img/customcontent/{$item.image}" alt="{$item.title}">
                {/if}
                <div class="custom-content-intro">
                    <div class="custom-content-description">
                        {if $item.description} 
                            {$item.description nofilter}
                        {/if}
                    </div>
                    <h2>{$item.title}</h2>
                    {if $item.button_link}
                        <div class="back__button">
                            <svg focusable="false" class="c-icon icon--arrow-button" viewBox="0 0 42 10" width="15px" height="15px">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M40.0829 5.5H0V4.5H40.0829L36.9364 1.35359L37.6436 0.646484L41.9971 5.00004L37.6436 9.35359L36.9364 8.64649L40.0829 5.5Z" fill="currentColor"></path>
                            </svg>
                            <span>{l s='Voir plus' d='Shop.Theme.Actions'}</span>
                        </div>
                    {/if}
                </div>
            </a>
        </div>
    {/foreach}
</section>
