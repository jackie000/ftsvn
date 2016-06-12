<?php
class ClsCoupon {
    
    public $_info = null;
    
    public function getCoupon( $couponCode ){
        if( $this->_info == null ) {
            $coupon = Hqw::getApplication()->getModels( "coupon" )->fetch( array( 'coupon_code'=> $couponCode ) );
            
        	if( $coupon ) {
        		$ctime = strtotime( ClsFactory::instance( "ClsCommon" )->getDatetime() );
        	    $stime = strtotime( $coupon['start_date'] );
        	    $etime = strtotime( $coupon['end_date'] );
        	    if( $ctime >= $stime && $ctime < strtotime( "+1 day", $etime ) && (int)$coupon['max_number_times'] >= 0 ) {
        	        $this->_info = $coupon;
        	    }
        	}
        	
        }
        return  $this->_info;
    }
    
    
}

?>