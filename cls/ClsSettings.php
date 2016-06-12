<?php
abstract class ClsSettings {
    
    public static $MAX_ADDRESS = 5;
    
    
    public function getSeting(){
        static $CONFIG_SETTINGS = array();
        if( isset( $CONFIG_SETTINGS[$this->getTitle()] ) ) {
        	return $CONFIG_SETTINGS[$this->getTitle()];
        }
        
        $stt = Hqw::getApplication()->getModels( "config_settings" );
    	$stt = $stt->where( array( 'config_settings_title'=>$this->getTitle() ) );
    	$result = $stt->fetch();
    	if( $result ) {
    		$CONFIG_SETTINGS[$this->getTitle()] = $result;
    	}else{
    	    $CONFIG_SETTINGS[$this->getTitle()] = array();
    	}
    	return $CONFIG_SETTINGS[$this->getTitle()];
    }
    
    public function remove(){
        $result = $this->getSeting();
        if( !empty( $result ) ) {
        	$configSetting = Hqw::getApplication()->getModels( "config_settings" );
        	$configSetting->delete( array( 'config_settings_id'=>$result['config_settings_id'] ) );
        	
        	$configSettingKey = Hqw::getApplication()->getModels( "config_settings_key" );
        	$configSettingKey->delete( array( 'config_settings_id'=>$result['config_settings_id'] ) );
        }
    }
    
    public function getKeys(){
        static $CONFIG_KEYS = array();
        
        if( isset( $CONFIG_KEYS[$this->getTitle()] ) ) {
        	return $CONFIG_KEYS[$this->getTitle()];
        }
        
        $stt = Hqw::getApplication()->getModels( "config_settings" );
    	$stt = $stt->join( Hqw::getApplication()->getModels( "config_settings_key" ), array('on'=>'config_settings_id') );
    	$stt = $stt->where( array( 'config_settings_title'=>$this->getTitle() ) );
    	$result = $stt->fetchAll();
    	if( $result ) {
    		$CONFIG_KEYS[$this->getTitle()] = $result;
    	}else{
    	    $CONFIG_KEYS[$this->getTitle()] = array();
    	}
    	return $CONFIG_KEYS[$this->getTitle()];
    }
    
    public function getValue( $key ){
        $keys = $this->getKeys();
        if( !empty( $keys ) ) {
        	foreach( $keys as $k => $v ) {
        		if( $v['config_settings_key_name'] == $key ) {
        			return $v['config_settings_key_values'];
        		}
        	}
        }
        
        return false;
    }
}
?>