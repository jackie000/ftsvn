<?php
class ClsUserFactory{
    
    private static $CLS_USERS = array();
    
    
    public static function instance( $userId ) {
        
        if( !$userId ) {
        	return false;
        }
        
    	if( array_key_exists( $userId, self::$CLS_USERS ) ) {
    		return self::$CLS_USERS[$userId];
    	}else{
    	    return  self::$CLS_USERS[$userId] = new ClsUser( $userId );
    	}
    }
    
}

?>