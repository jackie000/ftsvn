<?php
class ClsCommon{
    
    public function getDatetime() {
    	return Hqw::getApplication()->getComponent("Date")->cDate();
    }
    
    /**
     *
     * @param array $data
     * @param array or int $fid
     * @param array $result
     * @return array
     */
    public function getCategoriesChildren( $data, $fid, $result=array() ){
        if( !empty( $data ) ) {
        	$new = array();
        	foreach( $data as $k => $v ) {
        		if( is_array( $fid ) && in_array( $v['category_parent_id'], $fid ) ) {
        			$new[] = $v['categories_id'];
        			$result[] = $v;
        		}elseif( $v['category_parent_id'] == $fid ){
        		    $new[] = $v['categories_id'];
        		    $result[] = $v;
        		}
        	}
        	
        	if( empty( $new ) ) {
        		return $result;
        	}else{
        	    return $this->getCategoriesChildren( $data, $new, $result );
        	}
        }
        return $result;
    }
    
    /**
     *
     * @param array $data
     * @param int $currentId
     * @param array $result
     * @return array
     */
    public function getCategoriesFather( $data, $currentId, $result=array() ){
        if( !empty( $data ) ) {
        	foreach( $data as $k => $v ) {
        		if( $v['categories_id'] == $currentId ){
        		    $result[] = $v;
        		    if( $v['category_parent_id'] != 0 ) {
            			$currentId = $v['category_parent_id'];
            			return $this->getCategoriesFather( $data, $currentId, $result );
            		}
        		}
        	}
        	return $result;
        }
    }
    
    public function getCountries( $countriesId ){
        if( $countriesId == 0 || $countriesId == null ) {
        	return false;
        }
        $countries = Hqw::getApplication()->getModels( "countries" );
        $result =  $countries->fetch( array( 'countries_id'=>(int)$countriesId ) );
        if( isset( $result['countries_name'] ) ) {
        	return $result['countries_name'];
        }
        return false;
    }
    
    public function getCountriesCode2( $countriesId ){
        $countries = Hqw::getApplication()->getModels( "countries" );
        $result =  $countries->fetch( array( 'countries_id'=>(int)$countriesId ) );
        if( isset( $result['countries_iso_code_2'] ) ) {
        	return $result['countries_iso_code_2'];
        }
        return false;
    }
    
    public function getZones( $zoneId ){
        if( $zoneId == 0 || $zoneId == null ) {
        	return false;
        }
        $zones = Hqw::getApplication()->getModels( "zones" );
        $result = $zones->fetch( array( 'zone_id'=>(int)$zoneId ) );
        if( isset( $result['zone_name'] ) ) {
        	return $result['zone_name'];
        }
        return false;
    }
    
    public function getWeightUnit(){
        return "kg";
    }
    
    public function getOrderStatus( $status ){
        $os = Hqw::getApplication()->getModels( "orders_status" );
        $result = $os->fetch( array( 'orders_status_id'=>(int)$status ) );
        if( isset( $result['orders_status_name'] ) ) {
        	return $result['orders_status_name'];
        }
        return "New";
    }
}