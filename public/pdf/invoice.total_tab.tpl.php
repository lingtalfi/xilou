<table id="total-tab" width="100%">

	<tr>
		<td class="grey" width="70%">
			{l s='Total Products' pdf='true'}
		</td>
		<td class="white" width="30%">
			{displayPrice currency=$order->id_currency price=$footer.products_before_discounts_tax_excl}
		</td>
	</tr>

	{if $footer.product_discounts_tax_excl > 0}

		<tr>
			<td class="grey" width="70%">
				{l s='Total Discounts' pdf='true'}
			</td>
			<td class="white" width="30%">
				- {displayPrice currency=$order->id_currency price=$footer.product_discounts_tax_excl}
			</td>
		</tr>

	{/if}
	{if !$order->isVirtual()}
	<tr>
		<td class="grey" width="70%">
			{l s='Shipping Cost' pdf='true'}
		</td>
		<td class="white" width="30%">
			{if $footer.shipping_tax_excl > 0}
				{displayPrice currency=$order->id_currency price=$footer.shipping_tax_excl}
			{else}
				{l s='Free Shipping' pdf='true'}
			{/if}
		</td>
	</tr>
	{/if}

	{if $footer.wrapping_tax_excl > 0}
		<tr>
			<td class="grey">
				{l s='Wrapping Cost' pdf='true'}
			</td>
			<td class="white">{displayPrice currency=$order->id_currency price=$footer.wrapping_tax_excl}</td>
		</tr>
	{/if}

	<tr class="bold">
		<td class="grey">
			{l s='Total (Tax excl.)' pdf='true'}
		</td>
		<td class="white">
			{displayPrice currency=$order->id_currency price=$footer.total_paid_tax_excl}
		</td>
	</tr>
	{if $footer.total_taxes > 0}
	<tr class="bold">
		<td class="grey">
			{l s='Total Tax' pdf='true'}
		</td>
		<td class="white">
			{displayPrice currency=$order->id_currency price=$footer.total_taxes}
		</td>
	</tr>
	{/if}
	<tr class="bold big">
		<td class="grey">
			{l s='Total' pdf='true'}
		</td>
		<td class="white">
			{displayPrice currency=$order->id_currency price=$footer.total_paid_tax_incl}
		</td>
	</tr>
</table>
