<?php
abstract class ClsModule extends ClsSettings{
    
    public abstract function getModuleName();
    
    public function isEnabled(){
        return false;
    }
    
    public function init(){
        return false;
    }
    
}

interface interfaceModule {
    
    public function install();
    
    public function getTitle();
    
    public function getSortOrder();
    
    public function getDescription();
    
}
?>