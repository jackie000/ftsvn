<?php
interface setting{
    
    public function getSettingsKeyword();
    
    public function setSettingsDefaultValues();
    
    public function getSettingsValues( $key, $default= false, $value=false );
}


?>