<?php
class printScreen {
    
    public static function redirect( $url ) {
    	Hqw::getApplication()->getRequest()->redirect( $url );
    }
    
    public static function tips( $options ){
        echo "tips<br />";
        var_dump( $options );
        echo "<br />";
    }
    
    public static function back() {
    	echo "back <br />";
    }
}
?>