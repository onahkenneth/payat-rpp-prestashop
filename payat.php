<?php 

if(!defined('_PS_VERSION_'))
  exit;
  
class PayAt extends PaymentModule
{
    private $_html = '';
    private $_postErrors = array();
    
    
    public function __construct()
    {
		$this->name = 'payat';
		$this->tab = 'payment_gateways';
		$this->version = '1.0.0';

		$this->currencies = true;
		$this->currencies_mode = 'radio';
		
		if(!sizeof(Currency::checkPaymentCurrencies($this->id)))
			$this->warning = $this->l('No currency set for this module');
		
		parent::__construct();
		
		$this->displayName = $this->l('Pay@');
		$this->description = $this->l('Accept Payment by Pay@');
		$this->confirmUninstall = $this->l('Are you sure you to delete this module?');
		
    }
    
    public function install()
    {
      if(!parent::install()
      	|| !$this->installCurrency())
      	|| !$this->registerHook('header')
      	|| !$this->registerHook('payment')
      	return false;
      return true;
    }
    
    public function uninstall()
    {
      if(!parent::uninstall())
      	return false;
      return true;
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
    
    public function getContent()
    {
      
    }
}
?>
