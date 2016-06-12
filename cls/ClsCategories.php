<?php
class ClsCategories {
    
    private $_categories = null;
    
    public function getCategories() {
    	if( $this->_categories != null ) {
        	return $this->_categories;
        }
        
        $language = ClsFactory::instance( "ClsLanguage" );
        $languageId = $language->getLanguage();
        
    	$cgs = Hqw::getApplication()->getModels( "categories" );
    	$table = $cgs->getTable();
    	
    	$cgs = $cgs->join( Hqw::getApplication()->getModels( "categories_description" ), array('on'=>'categories_id') );
    	$cgs = $cgs->where( array( 'language_id'=>$languageId ), "AND", "categories_description" );
    	$cgs = $cgs->where( array( 'categories_status'=>1 ) );
    	$cgs = $cgs->order( "category_sort ASC" );
    	return $this->_categories = $cgs->fetchAll();
    }
    
}
?>