<?php
class PaypalController extends Controller{
    
    public function getLayouts() {
    	return "layout";
    }
    
    public function ActionIpn(){
        //$_POST['cmd'] == "_notify-validate" 验证 VERIFIED
        $clsPayments = ClsFactory::instance( "ClsPayment" );
        $payment = $clsPayments->getPayment( "paypal" );
        $payment->postIPN();
    }
}
?>
    