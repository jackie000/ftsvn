<?php
class ClsSeoConfiguration extends ClsConfig implements interfaceModule  {
    
    public function isEnabled(){
        return true;
    }
    
    //install module run
    public function init(){
        $this->install();
        return true;
    }
    
    public function getTitle(){
        return CONFIGURATION_SEO;
    }
    
    public function getDescription(){
        return "";
    }
    
    public function getSortOrder(){
        $value = $this->getSeting();
        if( !empty( $value ) ) {
        	return (int)$value['sort_order'];
        }
        return 1;
    }
    
    public function install(){
        $data = array( 'config_settings_title'=>$this->getTitle(), 'config_settings_description'=>$this->getDescription(), 'sort_order'=>10, 'status'=>1 );
        if( Hqw::getApplication()->getModels( "config_settings" )->insert( $data ) ) {
    	    $configId = Hqw::getApplication()->getModels( "config_settings" )->lastInsertId();
    	    
    	    $t = Hqw::getApplication()->getComponent("Date")->cDate();
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'seo prefix, include category url, detail url', 'config_settings_key_name'=>'SEO_PREFIX', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>1, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'seo postfix, include category url, detail url', 'config_settings_key_name'=>'SEO_POSTFIX', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>2, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'location contry', 'config_settings_key_name'=>'SEO_LOCATION', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>3, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'auto products title, format data.', 'config_settings_key_name'=>'SEO_PRODUCTS_TITLE_FORMAT', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>4, 'set_function'=>'', 'use_function'=>"", "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'auto products keywords, format data.', 'config_settings_key_name'=>'SEO_PRODUCTS_KEYWORDS_FORMAT', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>5, 'set_function'=>'', 'use_function'=>"", "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'auto products description, format data.', 'config_settings_key_name'=>'SEO_PRODUCTS_DESCRIPTION_FORMAT', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>6, 'set_function'=>'', 'use_function'=>"", "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
        }
    }     
    
}

?>