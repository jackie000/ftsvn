<?php
class ClsFactory{
    
    private static $CLS_CLASS = array();
    
    
    public static function instance( $name ) {
        
        if( !$name ) {
        	return false;
        }
        
    	if( array_key_exists( $name, self::$CLS_CLASS ) ) {
    		return self::$CLS_CLASS[$name];
    	}else{
    	    $class = new ReflectionClass( $name );
    	    
    	    $args = func_get_args();
    	    $args = array_slice( $args, 1 );
    	    if( $class->getConstructor() === null ) {
    	        $object = call_user_func( array( $class, 'newInstance' ) );
    	    }else{
    	        $object = call_user_func_array( array( $class, 'newInstance' ), $args );
    	    }
    	    return self::$CLS_CLASS[$name] = $object;
    	}
    }
    
}
?>