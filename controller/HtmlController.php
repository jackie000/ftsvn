<?php
class HtmlController extends Controller{
    
    public function getLayouts() {
    	return "layout";
    }
    
    public function ActionAboutUs(){
        $data = array();
        $this->render( "aboutus", $data );
    }
}
?>
    