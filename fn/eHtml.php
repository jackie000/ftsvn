<?php
class eHtml{
    
    public static $_OPTIONS_INPUT_PREFIX = "options";
    
    public static function createAttributesHtml( $attributeFormat, $string='' ) {
    	if( $attributeFormat ) {
    		if( $attributeFormat['type'] == "select" ) {
    			return self::createAttributesSelected( $attributeFormat, $string );
    		}elseif( $attributeFormat['type'] == "text" ){
    		    return self::createAttributesText( $attributeFormat, $string );
    		}
    	}
    }
    
    
    public static function createAttributesSelected( $values, $s='' ) {
        $currency = ClsFactory::instance( "ClsCurrency" );
        //Please correct the following: On the Option for: Color
        $string =  "<select attribute_name=\"_".$values['products_options_name'] . "\" style=\"width:200px;\" id=\"". self::$_OPTIONS_INPUT_PREFIX . "_" . $values['products_options_id'] . "\" name=\"". self::$_OPTIONS_INPUT_PREFIX . "[" . $values['products_options_id'] . "]\" {$s} _required=\"". $values['products_options_name'] .", You picked an Invalid Selection.\" >";
        $string .= "<option value=''>Select from below…</option>";
        if( $values['values'] ) {
        	foreach( $values['values'] as $k => $v ) {
        	    $prefixPrice = $select = "";
        	    if( $v['products_options_values_price'] > 0 ) {
        	    	$prefixPrice = " ( " . $v['price_prefix'] . $currency->getCurrencySign() . $currency->getCurrencyValues( $v['products_options_values_price'] ) . " )";
        	    }
        	    if( isset( $values['default_attribute_value_id'] ) && $v['products_options_values_id'] == $values['default_attribute_value_id'] ) {
        	    	$select = " selected=\"selected\" ";
        	    }
        		$string .= "<option {$select} value=\"". $v['products_options_values_id'] ."\">".$v['products_options_values_name'] . $prefixPrice . "</option>";
        	}
        }
        $string .= "</select>";
        return $string;
    }
    
    public static function createAttributesText( $values, $s='' ) {
    	$string = "<input type=\"text\" attribute_name=\"_".$values['products_options_name'] . "\" style=\"width:188px;\" id=\"". self::$_OPTIONS_INPUT_PREFIX . "_" . $values['products_options_id'] . "\" name=\"". self::$_OPTIONS_INPUT_PREFIX . "[txt_" . $values['products_options_id']  ."]\"  {$s} _required=\"" . $values['products_options_name'] . ", This is a required field.\"> ";
    	return $string;
    }
    
    public static function htmlRadioOption( $name, $options=array(), $default="" ){
        $html = "";
        if( !empty( $options ) ){
            foreach( $options as $k => $v ) {
                $checked = $v == $default ? "checked='checked'" : "";
            	$html .= "<label><input type=\"radio\" name=\"". $name ."\" value=\"". $v ."\" ". $checked ." > " .$v. " </label>";
            }
        }
        return $html;
    }
    
    public static function htmlSelectOption( $name, $options=array(), $default="" ){
        $html = "<select name=\"". $name ."\">";
        if( !empty( $options ) ){
            foreach( $options as $k => $v ) {
                $checked = $v == $default ? "selected='selected'" : "";
            	$html .= "<option value=\"". $v ."\" ". $checked ." > " .$v. " </option>";
            }
        }
        $html .= "</select>";
        return $html;
    }
    
    public static function htmlText( $name, $default="" ){
        $html = "<input type=\"text\" name=\"" . $name . "\" value=\"" . $default . "\">";
        return $html;
    }
    
    public static function htmlHidden( $name, $value="" ){
        $html = "<input type=\"hidden\" name=\"" . $name . "\" value=\"" . $value . "\">";
        return $html;
    }
    
}

?>