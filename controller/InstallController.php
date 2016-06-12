<?php
class InstallController extends Controller{
    
    public function ActionIndex() {
        $methods = $data = array();
        
        $shippings = $this->installExpress();
        $methods = array_merge( $methods, $shippings );
        
        $payments = $this->installPayment();
        $methods = array_merge( $methods, $payments );
        
        if( count( $methods ) > 0 ) {
            foreach( $methods as $k => $v ) {
                if( $v->isEnabled() === "" ) {
                	$v->init();
                }
                
            }
        }
        
    	$this->render( "index", $data );
    }
    
    public function installExpress(){
        $ce = ClsFactory::instance("ClsExpress");
        return $ce->getShippingMethod();
    }
    
    public function installPayment(){
        $cp = ClsFactory::instance("ClsPayment");
        return $cp->getPaymentMethod();
    }
    
}
?>