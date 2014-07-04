<?php 

if(!defined('_PS_VERSION_'))
  exit;
  
class PayAt extends PaymentModule
{
    private $_html = '';
    private $_postErrors = array();
    
    
    public function __construct()
    {
      
    }
    
    public function install()
    {
      if(!parent::install()
      	|| !$this->registerHook('header')
      	|| !$this->registerHook('payment')
      	|| !$this->installCurrency())
      	return false;
      return true;
    }
    
    public function uninstall()
    {
      
    }
    
    public function installCurrency()
    {
      
    }
    
    public function getContent()
    {
      
    }
}
?>
