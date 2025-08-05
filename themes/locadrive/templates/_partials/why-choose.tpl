<div class="why-choose">
    <div class="container">
        <div class="why-choose__wrapper">
            <div class="why-choose__img">
                <img src="{$urls.img_url}si7r.png" alt="image" loading="lazy">
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
                    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren.</p>
                    <a href="#" class="btn btn-light">{l s='Découvrir le concept' d='Shop.Theme.Global'}</a>
                    <ul>
                        <li>
                            <div class="c-icon">
                                <img src="{$urls.img_url}shield.svg" alt="icon" loading="lazy">
                            </div>
                            <div class="c-text">
                                <h3>{l s='PAYMENT 100% SECURED' d='Shop.Theme.Global'}</h3>
                                <p>{l s='lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmo' d='Shop.Theme.Global'}</p>
                            </div>
                        </li>
                        <li>
                            <div class="c-icon">
                                <img src="{$urls.img_url}truck.svg" alt="icon" loading="lazy">
                            </div>
                            <div class="c-text">
                                <h3>{l s='DELIVERY IN 2-3 DAYS' d='Shop.Theme.Global'}</h3>
                                <p>{l s='lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy.' d='Shop.Theme.Global'}</p>
                            </div>
                        </li>
                        <li>
                            <div class="c-icon">
                                <img src="{$urls.img_url}phone-call.svg" alt="icon" loading="lazy">
                            </div>
                            <div class="c-text">
                                <h3>{l s='CUSTOMER SERVICE' d='Shop.Theme.Global'}</h3>
                                <p>{l s='lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam.' d='Shop.Theme.Global'}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>