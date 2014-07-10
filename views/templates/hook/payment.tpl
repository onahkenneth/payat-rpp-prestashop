{*
* 2014 ZASoundz
*
* NOTICE OF LICENSE
*
*  @author Kenneth Onah <onah.kenneth@gmail.com>
*  @copyright  ZASoundz (www.zasoundz.co.za)
*  Property of ZASoundz
*}
<p class="payment_module">
	{if $confirmPayment == 1}
		<a href="{$link->getModuleLink('payat', 'payment', [], true)}" title="{l s='Pay with Credit Card' mod='payat'}">
			<img src="{$this_path_pa}credit-card.png" alt="{l s='Pay with Pay@' mod='payat'}" />
	{else}
		<img src="{$this_path_pu}payu.gif" alt="{l s='Pay with Credit Card' mod='payat'}" />
		{l s='Payment gateway not available' mod='payat'}
		
	{/if}
</p>