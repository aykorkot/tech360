{if isset($items) && $items|@count > 0}
    {foreach $items as $key => $item} 
        {if $item && $item.products|@count > 0}
        <section class="featured-products">
            <div class="c-title">
                {if $item.meta_title != '' }
                    <h2><span>{$item.meta_title}</span></h2>
                {/if}
                <div class="c-title-description">
                    {$item.description nofilter}
                    <a href="#" class="toggle-more">+ Voir plus</a>
                </div>
                {if $item.lien != '' && $item.label != ''}
                    <a class="back__button" href="{$item.lien}">
                        <span>{l s='Voir la sélection' d='Shop.Theme.Actions'}</span>
                        <div>
                            <span>
                                <svg focusable="false" class="c-icon icon--arrow-button" viewBox="0 0 42 10" width="15px" height="15px">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M40.0829 5.5H0V4.5H40.0829L36.9364 1.35359L37.6436 0.646484L41.9971 5.00004L37.6436 9.35359L36.9364 8.64649L40.0829 5.5Z" fill="currentColor"></path>
                                </svg>
                            </span>
                        </div>
                    </a>
                {/if}
            </div>
            {include file="catalog/_partials/productlist.tpl" products=$item.products cssClass="row"}
            {if $item.lien != '' && $item.label != ''}
            <div class="featured-products__action">
                <a class="btn btn-white" href="{$item.lien}">{$item.label}</a>
            </div>
            {/if}
        </section>
        {/if}
    {/foreach}
 {/if}