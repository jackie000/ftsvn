<?php
class ClsStandard extends ClsExpress implements interfaceModule,interfaceShipping {
    
    public function isEnabled(){
        
        if( $this->getValue( 'MODULE_SHIPPING_STANDARD_STATUS' ) == "True" ){
            return true;
        }
        
        return false;
    }
    
    //install module run
    public function init(){
        $this->install();
        return true;
    }
    
    public function install(){
        
        $data = array( 'config_settings_title'=>$this->getTitle(), 'config_settings_description'=>$this->getDescription(), 'sort_order'=>1, 'status'=>1 );
        if( Hqw::getApplication()->getModels( "config_settings" )->insert( $data ) ) {
    	    $configId = Hqw::getApplication()->getModels( "config_settings" )->lastInsertId();
    	    
    	    $t = Hqw::getApplication()->getComponent("Date")->cDate();
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Enable Per Weight Unit Standard Shipping', 'config_settings_key_name'=>'MODULE_SHIPPING_STANDARD_STATUS', 'config_settings_key_values'=>'True', "config_settings_key_description"=>'<p>Do you want to offer per unit rate shipping?</p><br /><p>Product Quantity * Units (products_weight) * Cost per Unit</p>', 'sort_order'=>1, 'set_function'=>'eHtml::htmlRadioOption( "MODULE_SHIPPING_STANDARD_STATUS", array( "True", "False" ) )', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Standard Shipping Cost per Unit', 'config_settings_key_name'=>'MODULE_SHIPPING_STANDARD_COST_PER_UNIT', 'config_settings_key_values'=>'', "config_settings_key_description"=>'<p>NOTE: When using this Shipping Module be sure to check the Tare settings in the Shipping/Packaging and set the Largest Weight high enough to handle the price, such as 5000.00 and the adjust the settings on Small and Large packages which will add to the price as well.</p><br /><p>The shipping cost will be used to determin shipping charges based on: Product Quantity * Units (products_weight) * Cost per Unit - in an order that uses this shipping method.</p>', 'sort_order'=>2, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Handling Fee', 'config_settings_key_name'=>'MODULE_SHIPPING_STANDARD_HANDLING_FEE', 'config_settings_key_values'=>'0', "config_settings_key_description"=>'<p>Handling fee for this shipping method.</p>', 'sort_order'=>3, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Sort Order', 'config_settings_key_name'=>'MODULE_SHIPPING_STANDARD_SORT_ORDER', 'config_settings_key_values'=>'0', "config_settings_key_description"=>'<p>Sort order of display.</p>', 'sort_order'=>4, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Delivery Day Description', 'config_settings_key_name'=>'MODULE_SHIPPING_STANDARD_DELIVERY_DAY_DESCRIPTION', 'config_settings_key_values'=>'', "config_settings_key_description"=>'<p>receive remark.</p>', 'sort_order'=>5, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Description', 'config_settings_key_name'=>'MODULE_SHIPPING_STANDARD_DESCRIPTION', 'config_settings_key_values'=>'', "config_settings_key_description"=>'<p>Select shipping method of display.</p>', 'sort_order'=>6, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
        }
        
    }
    
    public function getTitle(){
        return MODULE_EXPRESS_STANDARD;
    }
    
    public function getDescription(){
        if( $this->getValue( 'MODULE_SHIPPING_STANDARD_DESCRIPTION' ) ){
            return $this->getValue( 'MODULE_SHIPPING_STANDARD_DESCRIPTION' );
        }
        return "";
    }
    
    public function getSortOrder(){
        if( $this->getValue( 'MODULE_SHIPPING_STANDARD_SORT_ORDER' ) ){
            return $this->getValue( 'MODULE_SHIPPING_STANDARD_SORT_ORDER' );
        }
        return 1;
    }
    
    public function getShippingCost( $weight = 0 ){
        if( $weight == "0" ) {
        	return 0;
        }else{
            
            $handlingFee = 0;
            if( $this->getValue( 'MODULE_SHIPPING_STANDARD_HANDLING_FEE' ) ){
                $handlingFee = floatval( $this->getValue( 'MODULE_SHIPPING_STANDARD_HANDLING_FEE' ) );
            }
            if( $this->getValue( 'MODULE_SHIPPING_STANDARD_COST_PER_UNIT' ) ) {
                return $weight * floatval( $this->getValue( 'MODULE_SHIPPING_STANDARD_COST_PER_UNIT' ) ) + $handlingFee;
                //return number_format( round( $weight * floatval( $this->getValue( 'MODULE_SHIPPING_STANDARD_COST_PER_UNIT' ) ) + $handlingFee , 2 ), 2, ".", "" );
            }
        }
        
        return false;
    }
    
    public function getDeliveryDay(){
        
        if( $this->getValue( 'MODULE_SHIPPING_STANDARD_DELIVERY_DAY_DESCRIPTION' ) ){
            return $this->getValue( 'MODULE_SHIPPING_STANDARD_DELIVERY_DAY_DESCRIPTION' );
        }
        return "";
        
    }
}
?>