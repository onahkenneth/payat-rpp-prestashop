<?php 

if(!defined('_PS_VERSION_'))
	exit;

class payat extends PaymentModule
{
	private $_html = '';
	private $_postErrors = array();
	
	public function __construct() {
		$this->name = 'payat';
		$this->tab = 'payments_gateways';
		$this->version = '1.0.0';
		$this->author = 'ZASoundz';
		
		$this->currencies = true;
		$this->currencies_mode = 'radio';
		
		if(!sizeof(Currency::checkPaymentCurrencies($this->id)))
			$this->warning = $this->l('No currency set for this module');
		
		parent::__construct();
		
		$this->displayName = $this->l('Pay@ ZA');
		$this->description = $this->l('Hybrid payment module to integrate Pay@');
		$this->confirmUninstall = $this->l('Are you sure about removing these module?');
	}
	
	public function install() {
		return parent::install() 
			&& $this->installCurrency()
			&& $this->registerHook('header')
			&& $this->registerHook('payment') 
			&& $this->registerHook('paymentReturn');
	}
	
	public function uninstall() {
		return parent::uninstall() 
			&& $this->unregisterHook('header')
			&& $this->unregisterHook('payment') 
			&& $this->unregisterHook('paymentReturn');
	}
	
	public function installCurrency()
	{
		$currency = new Currency();
		$currency_rand_id = $currency->getIdByIsoCode('ZAR');
	
		if(is_null($currency_rand_id))
		{
			$currency->name = "South African Rand";
			$currency->iso_code = "ZAR";
			$currency->sign = "R";
			$currency->format = 1;
			$currency->blank = 1;
			$currency->decimals = 1;
			$currency->deleted = 0;
			$currency->conversion_rate = 0.45; // we set it to an arbitrary value
			$currency->add();
			$currency->refreshCurrencies();
		}
	
		return true;
	}
	
	public function hookHeader($params) {
		
	}
	
	public function getContent()
	{
	
	}
	
	public function displayMessage()
	{
		$this->_html .= '
    			<div class="conf confirm">
    				<img src"../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />
    						'.$this->l('Module settings updated').'
    			</div>';
	}
	
	public function hookPayment($params) {
		if (!$this->active)
			return;
		if (!$this->checkCurrency($params['cart']))
			return;
		
		
		$this->smarty->assign(array(
				'this_path' => $this->_path,
				'this_path_pa' => $this->_path,
				'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'
		));
		return $this->display(__FILE__, 'payment.tpl');
	}
	
	public function hookPaymentReturn($params) {
		if (!$this->active)
			return;
		
		$state = $params['objOrder']->getCurrentState();
		if ($state == Configuration::get('PS_OS_BANKWIRE') || $state == Configuration::get('PS_OS_OUTOFSTOCK'))
		{
			$this->smarty->assign(array(
					'total_to_pay' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
					'bankwireDetails' => Tools::nl2br($this->details),
					'bankwireAddress' => Tools::nl2br($this->address),
					'bankwireOwner' => $this->owner,
					'status' => 'ok',
					'id_order' => $params['objOrder']->id
			));
			if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
				$this->smarty->assign('reference', $params['objOrder']->reference);
		}
		else
			$this->smarty->assign('status', 'failed');
		return $this->display(__FILE__, 'payment_return.tpl');
	}
}
?>