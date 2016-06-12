<?php
class TestController extends Controller{
    
    
    public function ActionIndex() {
    	$data = array();
    	$this->render( "test", $data );
    }
}
?>