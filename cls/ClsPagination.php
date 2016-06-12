<?php
class ClsPagination {
    
    
    public function createPagination( $links, $pagination, $style="" ) {
        
    	$totalPage = $pagination->getPageCount();
    	$start     = $pagination->getCurrentPage() * $pagination->getPageSize()+1;
    	$page = $pagination->getCurrentPage() + 1;
    	
    	$href = "";
    	if( strpos( $links['href'], "?" ) !== false ){
    	    $href = $links['href'] . "&";
    	}else{
    	    $href = $links['href'] . "?";
    	}
    	
    	$p = $pagination->pageVar;
    	
    	if( $style == "simpleness" ) {
    		$style = "simplenesspagearea";
    		$pageHtml = "";
    		if( $totalPage > 1 ) {
        		$pageHtml = '<div class="'. $style .'">';
                if( $page > 1 ) {
                	$pageHtml.= '<a title="' . $links['title'] . ' page '. ($page-1) .'" href="' . $href . $p . "=" . ($page-1) . '"> < Prev</a>';
                }else{
                    $pageHtml.= '<span class="disabled"> < Prev</span>';
                }
                
                if (intval($page) < intval($totalPage)) {
                    $pageHtml .= '<a title="' . $links['title'] . ' page '. ($page+1) .'" href="' . $href . $p . "=" . ($page+1) . '">Next  > </a>';
                }else{
                    $pageHtml .= '<span class="next disabled">Next  > </span>';
                }
                $pageHtml .= '</div>';
            }
            return $pageHtml;
    		
    	}else{
    	    $style = "pagearea pagination-small pull-right";
    	}
    	
    	$pageHtml = "";
    	if( $totalPage > 1 ) {
    	    list($s,$e) = $pagination->getPageArea();
    	    $pageHtml = '<div class="'. $style .'">';
            $pageHtml.= '<ul>';
            if( $page > 1 ) {
            	$pageHtml.= '<li><a title="' . $links['title'] . ' page '. ($page-1) .'" href="' . $href . $p . "=" . ($page-1) . '"> < Prev</a></li>';
            }
            
            if( $page > 2 ) {
            	$pageHtml.= '<li><a title="' . $links['title'] . ' page 1" href="' . $href . $p . "=1" . '">1</a></li>';
            	$pageHtml.= '<li><span spec=""> ... </span></li>';
            }
            if( $s==0 ) $s=1;
            for( $i=$s; $i <= $e; $i++ ){
                if( $i == $page ) {
                    $pageHtml .= '<li><span spec="">'.$i.'</span></li>';
                }else {
                    $pageHtml .= '<li><a title="' . $links['title'] . ' page '. ($i) .'" href="' . $href . $p . "=" . $i . '">' . $i . '</a></li> ';
                }
            }
            
            if( $totalPage > 10 ) {
            	if( ($totalPage - $page) > 5 ) {
            		$pageHtml.= '<li><span spec=""> ... </span></li>';
            		$pageHtml.= '<li><a title="' . $links['title'] . ' page '. ($totalPage) .'" href="'. $href . $p . "=" . $totalPage .'">'.$totalPage.'</a></li>';
            	}
            }
            if (intval($page) < intval($totalPage)) {
                $pageHtml .= '<li><a title="' . $links['title'] . ' page '. ($page+1) .'" href="' . $href . $p . "=" . ($page+1) . '">Next  > </a></li>';
            }
            $pageHtml .= '</ul>';
            $pageHtml .= '</div>';
    	}
    	return $pageHtml;
    }
    
    public function getProductsInteractPage( $products, $type, $params, $pagination, $total, $count ) {
        $links = ClsFactory::instance( "ClsSeo" )->getProductsInteractLink( $products, $type, $params );
        return $this->createPagination( $links, $pagination );
    }
    
    public function getListPage( $categories, $attributes, $params, $pagination, $style="" ) {
    	$links = ClsFactory::instance( "ClsSeo" )->getCategoriesLink( $categories, true, $attributes, $params );
    	return $this->createPagination( $links, $pagination, $style );
    }
    
    public function getSearchPage( $keywords, $params, $pagination, $style="" ){
        $links = ClsFactory::instance( "ClsSeo" )->getSearchSeoLink( $keywords, $params );
        return $this->createPagination( $links, $pagination, $style );
    }
    
}
?>