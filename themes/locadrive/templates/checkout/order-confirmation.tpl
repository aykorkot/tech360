{extends file='page.tpl'}

{block name='page_content_container' prepend}
    <section id="content-hook_order_confirmation">

      <div class="confirmation-header">
        {block name='order_confirmation_header'}
          <h1>
            {l s='Your order is confirmed' d='Shop.Theme.Checkout'}
          </h1>
        {/block}
        <p>
          {l s='Un e-mail vous a été envoyé à l\'adresse <strong>%email%</strong>.' d='Shop.Theme.Checkout' html=true sprintf=['%email%' => $order_customer.email]}
          <br>
          {if $order.details.invoice_url}
            {* [1][/1] is for a HTML tag. *}
            {l
              s='You can also [1]download your invoice[/1]'
              d='Shop.Theme.Checkout'
              sprintf=[
                '[1]' => "<a href='{$order.details.invoice_url}'>",
                '[/1]' => "</a>"
              ]
            }
          {/if}
        </p>
      </div>
      {* {block name='hook_order_confirmation'}
        {$HOOK_ORDER_CONFIRMATION nofilter}
      {/block} *}
    </section>
{/block}

{block name='page_content_container'}
  <section id="content" class="page-content page-order-confirmation card">
    <div class="card-block">
      <div class="row">

        {block name='order_confirmation_table'}
          {include
            file='checkout/_partials/order-confirmation-table.tpl'
            products=$order.products
            subtotals=$order.subtotals
            totals=$order.totals
            labels=$order.labels
            add_product_link=false
          }
        {/block}

        {block name='order_details'}
          <div id="order-details">
            <h2 class="h3 card-title">{l s='Order details' d='Shop.Theme.Checkout'}</h2>
            <ul>
              <li id="order-reference-value">{l s='Référence de la commande : <strong>%reference%</strong>' d='Shop.Theme.Checkout' html=true sprintf=['%reference%' => $order.details.reference]}</li>
              <li>{l s='Payment method: %method%' d='Shop.Theme.Checkout' sprintf=['%method%' => $order.details.payment]}</li>
              {if !$order.details.is_virtual}
                <li>
                  {l s='Shipping method: %method%' d='Shop.Theme.Checkout' sprintf=['%method%' => $order.carrier.name]} ({$order.carrier.delay})
                </li>
              {/if}
              {if $order.details.recyclable}
                <li>  
                  <em>{l s='You have given permission to receive your order in recycled packaging.' d="Shop.Theme.Customeraccount"}</em>
                </li>
              {/if}
            </ul>
          </div>
        {/block}

      </div>
    </div>
  </section>

  {block name='hook_payment_return'}
    {if ! empty($HOOK_PAYMENT_RETURN)}
    <section id="content-hook_payment_return" class="card definition-list">
      <div class="order-content-bottom">
        {$HOOK_PAYMENT_RETURN nofilter}
      </div>
    </section>
    {/if}
  {/block}

  <!-- {if !$registered_customer_exists}
    {block name='account_transformation_form'}
      <div class="card">
        <div class="card-block">
          {include file='customer/_partials/account-transformation-form.tpl'}
        </div>
      </div>
    {/block}
  {/if} -->

  {block name='hook_order_confirmation_1'}
    {hook h='displayOrderConfirmation1'}
  {/block}

  {block name='hook_order_confirmation_2'}
    <section id="content-hook-order-confirmation-footer">
      {hook h='displayOrderConfirmation2'}
    </section>
  {/block}
{/block}
