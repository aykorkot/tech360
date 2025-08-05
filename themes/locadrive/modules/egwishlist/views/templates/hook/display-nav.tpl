{*
 * 2019 (c) Egio digital
 *
 * MODULE EgWishList
 *
 * @author    Egio digital
 * @copyright Copyright (c) , Egio digital
 * @license   Commercial
 * @version    1.0.0
 */
*}

<div class="d-inline-block wishlist_btn">
    <a class="header__tools--link" href="{url entity='module' name='egwishlist' controller='view'}">
        <svg xmlns="http://www.w3.org/2000/svg" width="27.519" height="23.503" viewBox="0 0 27.519 23.503">
            <path id="Path_17773" data-name="Path 17773" d="M30.131,81.5a8.2,8.2,0,0,0-6.371,3.19A8.2,8.2,0,0,0,17.4,81.506,7.378,7.378,0,0,0,10,89.188c0,5.133,4.552,8.716,7.876,11.333,1.258.994,2.182,1.7,2.934,2.269a23.041,23.041,0,0,1,2.426,2,.746.746,0,0,0,1.05,0,34.362,34.362,0,0,1,3.687-3l1.662-1.261c2.752-2.1,7.882-6,7.882-11.392A7.352,7.352,0,0,0,30.131,81.5ZM17.4,82.991a6.99,6.99,0,0,1,5.745,3.361.791.791,0,0,0,1.236,0,7,7,0,0,1,5.756-3.364,5.9,5.9,0,0,1,5.9,6.15c0,4.3-3.768,7.525-7.3,10.21l-1.662,1.258c-1.727,1.309-2.567,1.946-3.316,2.628-.548-.489-1.157-.952-2.047-1.629-.719-.548-1.646-1.252-2.92-2.26-3.083-2.423-7.3-5.745-7.3-10.157A5.925,5.925,0,0,1,17.4,82.991Z" transform="translate(-10 -81.5)"/>
        </svg>
        <span class="counter egwishlist-nb"></span>
    </a>
</div>

<div class="notification">
    <div class="notification__item">
        <div class="notification__wrapper">
            <div class="notification__icon">
                <span class="icon-check-mark"></span>
            </div>

            <div class="notification__content">
                <p class="add">{l s='Article ajouté à mes favoris' mod='egwishlist'}</p>
                <p class="remove">{l s='Article supprimé de mes favoris' mod='egwishlist'}</p>
            </div>

            <div class="notification__close">
                <button>
                    <svg focusable="false" aria-hidden="true" viewBox="0 0 24 24" title="Close">
                        <path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>