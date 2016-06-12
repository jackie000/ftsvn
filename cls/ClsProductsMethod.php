<?php
class ClsProductsMethod {
    
    
    public function getProductsImagesDir() {
    	return "cache";
    }
    
    public function getDefaultImages(){
        return "images/no_photo.png";
    }
    
    public function getThumbnailImagesQuality() {
    	return 100;
    }
    
    public function getReviewsLevels( $rating ) {
    	$rating = (int)$rating;
    	$level = "high";
    	if( $rating >=4 && $rating<=5 ) {
    		$level = "high";
    	}elseif( $rating>=2 && $rating<=3 ) {
    		$level = "middle";
    	}elseif( $rating<=1 ) {
    		$level = "base";
    	}
    	return $level;
    }
    
    public function getReviewsRatingFormat( $rating ){
        $rating = floatval( $rating );
        $r = "5";
        if( $rating == 5 ) {
        	$r = "5";
        }elseif( $rating <= 5 &&  $rating > 4.5 ){
            $r = "5";
        }elseif( $rating > 4 &&  $rating <= 4.5 ){
            $r = "4.5";
        }elseif( $rating <= 4 &&  $rating > 3.5 ){
            $r = "4";
        }elseif( $rating > 3 &&  $rating <= 3.5 ){
            $r = "3.5";
        }elseif( $rating <= 3 &&  $rating > 2.5 ){
            $r = "3";
        }elseif( $rating > 2 &&  $rating <= 2.5 ){
            $r = "2.5";
        }elseif( $rating <= 2 &&  $rating > 1.5 ){
            $r = "2";
        }
        
        return $r;
    }
    
    
    /**
     * products images thumbnail by width and height
     *
     * @param string or array $imagesPath abs
     * @param int $w width
     * @param int $h height
     * @return array or string or bool
     */
    public function thumbnailImage( $imagesPath, $w=0, $h=0 ){
        
        if( is_array( $imagesPath ) && !empty( $imagesPath ) ) {
            $result = array();
        	foreach( $imagesPath as $k => $v ) {
        	    if( $v != "" && file_exists( realpath( $v ) ) ) {
        	    	$result[] = $this->thumb( $v, (int)$w, (int)$h, $this->getThumbnailImagesQuality() );
        	    }
        	}
        	return $result;
        }elseif( is_string( $imagesPath ) && $imagesPath != "" && file_exists( realpath( $imagesPath ) ) ){
            return $this->thumb( $imagesPath, (int)$w, (int)$h, $this->getThumbnailImagesQuality() );
        }else{
            return $this->thumb( $this->getDefaultImages(), (int)$w, (int)$h, $this->getThumbnailImagesQuality() );
            
        }
        
        return false;
    }
    
    /**
     * image thumb
     *
     * @param string $originPath
     * @param int $w
     * @param int $h
     * @param int $quality
     * @return string
     */
    public function thumb( $originPath, $w=0, $h=0, $quality=100 ) {
        
        $basePath = Hqw::getApplication()->getBasePath();
        
        $realPath = '';
        if( $w == 0 && $h == 0 ) {
        	$realPath = $basePath . DIRECTORY_SEPARATOR . $this->getProductsImagesDir() . DIRECTORY_SEPARATOR . $this->imagesPath( $originPath );
        }else{
            $realPath = $basePath . DIRECTORY_SEPARATOR . $this->getProductsImagesDir() . DIRECTORY_SEPARATOR . $w . "x" . $h . DIRECTORY_SEPARATOR . $this->imagesPath( $originPath );
        }
        
        $url = str_replace( $basePath, '', $realPath );
        if ( !file_exists( $realPath ) ) {
            Hqw::getApplication()->getComponent("File")->CreateFolder( dirname( $realPath ) );
            Hqw::getApplication()->getComponent("Image")->thumb( $originPath, $realPath, 'jpg', $w, $h );
        }
        return $url;
    }
    
    /**
     * products system path
     *
     * @param string $imagesPath
     * @return string
     */
    public function imagesPath( $imagesPath ) {
        if( !$imagesPath ) {
        	return false;
        }
        $sideword = crc32( $imagesPath );
        $sideword = sprintf("%u",$sideword);
        $sideword = sprintf("%010d",$sideword);
        $sideword = $sideword & 0xCCCC9999;
        
        $fileInfo  = pathinfo($imagesPath);
        $ext  = $fileInfo['extension'];
        
        return substr( $sideword, 0, 3 ) . DIRECTORY_SEPARATOR . substr( $sideword, -3 ). DIRECTORY_SEPARATOR . $sideword . "." . $ext;
    }
    
}
?>