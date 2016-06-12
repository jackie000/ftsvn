<?php
class ClsPayease extends ClsPayment implements interfaceModule, interfacePayment {
    
    public function isEnabled(){/*{{{*/
        
        if( strtolower( $this->getValue( 'MODULE_PAYMENT_PAYEASE_STATUS' ) ) == "true" ){
            return true;
        }elseif( strtolower( $this->getValue( 'MODULE_PAYMENT_PAYEASE_STATUS' ) ) == "false" ){
            return false;
        }
        
        return "";
    }/*}}}*/
    
    public function install(){
        $data = array( 'config_settings_title'=>$this->getTitle(), 'config_settings_description'=>$this->getDescription(), 'sort_order'=>2, 'status'=>1 );
        if( Hqw::getApplication()->getModels( "config_settings" )->insert( $data ) ) {
    	    $configId = Hqw::getApplication()->getModels( "config_settings" )->lastInsertId();
    	    
    	    $t = Hqw::getApplication()->getComponent("Date")->cDate();
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Enable Payease Module', 'config_settings_key_name'=>'MODULE_PAYMENT_PAYEASE_STATUS', 'config_settings_key_values'=>'True', "config_settings_key_description"=>'', 'sort_order'=>1, 'set_function'=>'eHtml::htmlRadioOption( "MODULE_PAYMENT_PAYPEASE_STATUS", array( "True", "False" ) )', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Payease ID', 'config_settings_key_name'=>'MODULE_PAYMENT_PAYEASE_ID', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>2, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Payease MD5 Key', 'config_settings_key_name'=>'MODULE_PAYMENT_PAYEASE_MD_KEY', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>3, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Payease transaction URL', 'config_settings_key_name'=>'MODULE_PAYMENT_PAYEASE_WEB_ADDRESS', 'config_settings_key_values'=>'', "config_settings_key_description"=>'<p>Default:<br/>https://pay.yizhifubj.com/prs/e_user_payment.checkit Or https://www.5upay.com/prs/e_user_payment.checkit</p>', 'sort_order'=>5, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Sort Order', 'config_settings_key_name'=>'MODULE_PAYMENT_PAYEASE_SORT_ORDER', 'config_settings_key_values'=>'0', "config_settings_key_description"=>'<p>Sort order of display.</p>', 'sort_order'=>6, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Description', 'config_settings_key_name'=>'MODULE_PAYMENT_PAYEASE_DESCRIPTION', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>7, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
        }
    }
    
    public function getTitle(){
        return MODULE_PAYMENT_PAYEASE;
    }
    
    public function getSortOrder(){
        if( $this->getValue( 'MODULE_PAYMENT_PAYEASE_SORT_ORDER' ) ){
            return $this->getValue( 'MODULE_PAYMENT_PAYEASE_SORT_ORDER' );
        }
        return 1;
    }
    
    public function getDescription(){
        if( $this->getValue( 'MODULE_PAYMENT_PAYEASE_DESCRIPTION' ) ){
            return $this->getValue( 'MODULE_PAYMENT_PAYEASE_DESCRIPTION' );
        }
        return "";
    }
    
    public function processSubmit(){
        if( $this->getValue( 'MODULE_PAYMENT_PAYEASE_ID' ) ){
            $data = array();
            
            $currency = ClsFactory::instance( "ClsCurrency" );
            $signin = ClsFactory::instance("ClsSignin");
            $currentUser = $signin->getUser();
            $userBase = $currentUser->getBase();
            
            $checkout = ClsCheckout::getCheckout();
            
            $shippingId = $checkout->getShippingAddress();
            $shippingAddress = $currentUser->getAddressById( $shippingId );
            
            $clsCommon = ClsFactory::instance( "ClsCommon" );
            $iso2 = $clsCommon->getCountriesCode2( $shippingAddress['country_id'] );
            $shipCountry = $this->getShipCountry( $iso2 );
            
            
            $buss = $this->getValue( 'MODULE_PAYMENT_PAYEASE_ID' );
            $url = $this->getValue( 'MODULE_PAYMENT_PAYEASE_WEB_ADDRESS' );
            $md5key = $this->getValue( 'MODULE_PAYMENT_PAYEASE_MD_KEY' );
            
            $ymd = date("Ymd");
            $oid = $ymd . '-' . $buss . '-' . substr( microtime(), 2, 6 );
            
            $moneyType = $this->getMoneyType();
            $amount = $currency->getCurrencyValues( $checkout->getCheckoutTotal() );
            $rcvname = $buss;
            $md5info = $moneyType . $ymd . $amount . $rcvname . $oid . $buss . $url;
            $md5info = bin2hex( mhash( MHASH_MD5, $md5info, $md5key ) );
            
            $data = array(
                'v_mid'=>$buss,
                'v_oid'=>$oid,
                'v_rcvname'=>$rcvname,
                'v_rcvaddr'=>$shippingAddress['state'] . ' ' . $shippingAddress['city'] . '' . $shippingAddress['street_address'],
                'v_rcvtel'=>$shippingAddress['phone_number'],
                'v_rcvpost'=>$shippingAddress['postcode'],
                'v_amount'=>$amount,
                'v_ymd'=>$ymd,
                'v_orderstatus'=>1,
                'v_moneytype'=>$moneyType,
                'v_url'=>Hqw::getApplication()->createUrl('checkout/success', array( 'ref'=>strtolower( MODULE_PAYMENT_PAYEASE ) ) ),
                'v_md5info'=>$md5info,
                'v_shipstreet'=>$shippingAddress['street_address'],
                'v_shipcity'=>$shippingAddress['city'],
                'v_shipstate'=>$this->getShipState( $shipCountry, $shippingAddress['state']),
                'v_shippost'=>$shippingAddress['postcode'],
                'v_shipcountry'=>$shipCountry,
                'v_shipphone'=>$shippingAddress['phone_number'],
                'v_shipemail'=>$userBase['user_email_address']
            );
            
            if( !empty( $data ) ) {
                //$html = '<body onLoad="document.paypease_form.submit();">';
                //$html .= '<form method="post" name="paypease_form" action="'. $url .'">';
                
                foreach( $data as $k=>$v ) {
                    error_log( "$k=>$v \r\n", 3,  "payease.log");
                    $html .= '<input type="hidden" name="'.$k.'" value="'.$v.'">';
                }
                
                //$html .= '</form></body>';
                $payease = array(
                    'oid'=>$oid,
                    'session_key'=>$signin->getSessionId(),
                    'money_type'=>$moneyType,
                    'amount'=>$amount,
                    'url'=>$url,
                    'user_id'=>$currentUser->getUserId(),
                    'odate'=>Hqw::getApplication()->getComponent("Date")->cDate()
                );
                error_log( "insert payease data: ". json_encode( $payease ) ." \r\n", 3,  "payease.log");
                if( Hqw::getApplication()->getModels( "payease" )->insert( $payease ) ){
                    return $html;
                }else{
                    return false;
                }
            }
        }
        /*
        <form name="checkout_confirmation" action="http://weddinglee.com/cc_payease.php" method="post" id="checkout_confirmation" onsubmit="submitonce();">
        <input name="v_mid" value="5550" type="hidden">
        <input name="v_oid" value="20150226-5550-1076-061125" type="hidden">
        <input name="v_rcvname" value="5550" type="hidden">
        <input name="v_rcvaddr" value="beijingbeijingroom 303, building 9, huaqingjiayuan" type="hidden">
        <input name="v_rcvtel" value="13691046335" type="hidden">
        <input name="v_rcvpost" value="100083" type="hidden">
        <input name="v_amount" value="135.59" type="hidden">
        <input name="v_ymd" value="20150226" type="hidden">
        <input name="v_orderstatus" value="1" type="hidden">
        <input name="v_ordername" value="lizhiqiang" type="hidden">
        <input name="v_moneytype" value="1" type="hidden">
        <input name="v_url" value="http://weddinglee.com/return_payease.php" type="hidden">
        <input name="v_md5info" value="8189e84c380560d14a0d4761de79137c" type="hidden">
        <input name="v_shipstreet" value="room 303, building 9, huaqingjiayuan" type="hidden">
        <input name="v_shipcity" value="beijing" type="hidden">
        <input name="v_shipstate" value="beijing" type="hidden">
        <input name="v_shippost" value="100083" type="hidden">
        <input name="v_shipcountry" value="156" type="hidden">
        <input name="v_shipphone" value="13691046335" type="hidden">
        <input name="v_shipemail" value="victor@hqtarget.com" type="hidden">
        <input name="Remark" type="hidden">
        */
    }
    
    public function getShipCountry( $iso2 ){
        switch( $iso2 ){
			case 'AL':$iso2='008';break;
			case 'DZ':$iso2='012';break;
			case 'AF':$iso2='004';break;
			case 'AR':$iso2='032';break;
			case 'AE':$iso2='784';break;
			case 'AW':$iso2='533';break;
			case 'OM':$iso2='512';break;
			case 'AZ':$iso2='031';break;
			case 'EG':$iso2='818';break;
			case 'ET':$iso2='231';break;
			case 'IE':$iso2='372';break;
			case 'EE':$iso2='233';break;
			case 'AD':$iso2='020';break;
			case 'AO':$iso2='024';break;
			case 'AI':$iso2='660';break;
			case 'AG':$iso2='028';break;
			case 'AT':$iso2='040';break;
			case 'AU':$iso2='036';break;
			case 'MO':$iso2='446';break;
			case 'BB':$iso2='052';break;
			case 'PG':$iso2='598';break;
			case 'BS':$iso2='044';break;
			case 'PK':$iso2='586';break;
			case 'PY':$iso2='600';break;
			case 'PS':$iso2='374';break;
			case 'BH':$iso2='048';break;
			case 'PA':$iso2='591';break;
			case 'BR':$iso2='076';break;
			case 'BY':$iso2='112';break;
			case 'BM':$iso2='060';break;
			case 'BG':$iso2='100';break;
			case 'MP':$iso2='580';break;
			case 'PW':$iso2='585';break;
			case 'BJ':$iso2='204';break;
			case 'BE':$iso2='056';break;
			case 'IS':$iso2='352';break;
			case 'PR':$iso2='630';break;
			case 'PL':$iso2='616';break;
			case 'BO':$iso2='068';break;
			case 'BA':$iso2='070';break;
			case 'BW':$iso2='072';break;
			case 'BZ':$iso2='084';break;
			case 'BT':$iso2='064';break;
			case 'BF':$iso2='854';break;
			case 'BI':$iso2='108';break;
			case 'BV':$iso2='074';break;
			case 'KP':$iso2='408';break;
			case 'GQ':$iso2='226';break;
			case 'DK':$iso2='208';break;
			case 'DE':$iso2='276';break;
			case 'TP':$iso2='626';break;
			case 'TG':$iso2='768';break;
			case 'DO':$iso2='214';break;
			case 'DM':$iso2='212';break;
			case 'RU':$iso2='643';break;
			case 'EC':$iso2='218';break;
			case 'ER':$iso2='232';break;
			case 'FR':$iso2='250';break;
			case 'FO':$iso2='234';break;
			case 'PF':$iso2='258';break;
			case 'GF':$iso2='254';break;
			case 'TF':$iso2='260';break;
			case 'VA':$iso2='336';break;
			case 'PH':$iso2='608';break;
			case 'FJ':$iso2='242';break;
			case 'FI':$iso2='246';break;
			case 'CV':$iso2='132';break;
			case 'GM':$iso2='270';break;
			case 'CG':$iso2='178';break;
			case 'CO':$iso2='170';break;
			case 'CR':$iso2='188';break;
			case 'GD':$iso2='308';break;
			case 'GL':$iso2='304';break;
			case 'GE':$iso2='268';break;
			case 'CU':$iso2='192';break;
			case 'GP':$iso2='312';break;
			case 'GU':$iso2='316';break;
			case 'GY':$iso2='328';break;
			case 'KZ':$iso2='398';break;
			case 'HT':$iso2='332';break;
			case 'KR':$iso2='410';break;
			case 'NL':$iso2='528';break;
			case 'AN':$iso2='530';break;
			case 'HM':$iso2='334';break;
			case 'HN':$iso2='340';break;
			case 'KI':$iso2='296';break;
			case 'DJ':$iso2='262';break;
			case 'KG':$iso2='417';break;
			case 'GN':$iso2='324';break;
			case 'GW':$iso2='624';break;
			case 'CA':$iso2='124';break;
			case 'GH':$iso2='288';break;
			case 'GA':$iso2='266';break;
			case 'KH':$iso2='116';break;
			case 'CZ':$iso2='203';break;
			case 'ZW':$iso2='716';break;
			case 'CM':$iso2='120';break;
			case 'QA':$iso2='634';break;
			case 'KY':$iso2='136';break;
			case 'CC':$iso2='166';break;
			case 'KM':$iso2='174';break;
			case 'CI':$iso2='384';break;	
			case 'KW':$iso2='414';break;
			case 'HR':$iso2='191';break;
			case 'KE':$iso2='404';break;
			case 'CK':$iso2='184';break;
			case 'LV':$iso2='428';break;
			case 'LS':$iso2='426';break;
			case 'LA':$iso2='418';break;
			case 'LB':$iso2='422';break;
			case 'LR':$iso2='430';break;
			case 'LY':$iso2='434';break;
			case 'LT':$iso2='440';break;
			case 'LI':$iso2='438';break;
			case 'RE':$iso2='638';break;
			case 'LU':$iso2='442';break;
			case 'RW':$iso2='646';break;
			case 'RO':$iso2='642';break;
			case 'MG':$iso2='450';break;
			case 'MT':$iso2='470';break;
			case 'MV':$iso2='462';break;
			case 'FK':$iso2='238';break;
			case 'MW':$iso2='454';break;
			case 'MY':$iso2='458';break;
			case 'ML':$iso2='466';break;
			case 'MK':$iso2='807';break;
			case 'MH':$iso2='584';break;
			case 'MQ':$iso2='474';break;
			case 'YT':$iso2='175';break;
			case 'MU':$iso2='480';break;
			case 'MR':$iso2='478';break;
			case 'US':$iso2='840';break;
			case 'AS':$iso2='016';break;
			case 'UM':$iso2='581';break;
			case 'VI':$iso2='850';break;
			case 'MN':$iso2='496';break;
			case 'MS':$iso2='500';break;
			case 'BD':$iso2='050';break;
			case 'PE':$iso2='604';break;
			case 'FM':$iso2='583';break;
			case 'MM':$iso2='104';break;
			case 'MD':$iso2='498';break;
			case 'MA':$iso2='504';break;
			case 'MC':$iso2='492';break;
			case 'MZ':$iso2='508';break;
			case 'MX':$iso2='484';break;
			case 'NA':$iso2='516';break;
			case 'ZA':$iso2='710';break;
			case 'AQ':$iso2='010';break;
			case 'GS':$iso2='239';break;
			case 'YU':$iso2='891';break;
			case 'NR':$iso2='520';break;
			case 'NP':$iso2='524';break;
			case 'NI':$iso2='558';break;
			case 'NE':$iso2='562';break;
			case 'NG':$iso2='566';break;
			case 'NU':$iso2='570';break;
			case 'NO':$iso2='578';break;
			case 'NF':$iso2='574';break;
			case 'PN':$iso2='612';break;
			case 'PT':$iso2='620';break;
			case 'JP':$iso2='392';break;
			case 'SE':$iso2='752';break;
			case 'CH':$iso2='756';break;
			case 'SV':$iso2='222';break;
			case 'SL':$iso2='694';break;
			case 'SN':$iso2='686';break;
			case 'CY':$iso2='196';break;
			case 'SC':$iso2='690';break;
			case 'SA':$iso2='682';break;
			case 'CX':$iso2='162';break;
			case 'ST':$iso2='678';break;
			case 'SH':$iso2='654';break;
			case 'KN':$iso2='659';break;
			case 'LC':$iso2='662';break;
			case 'SM':$iso2='674';break;
			case 'PM':$iso2='666';break;
			case 'VC':$iso2='670';break;
			case 'LK':$iso2='144';break;
			case 'SK':$iso2='703';break;
			case 'SI':$iso2='705';break;
			case 'SJ':$iso2='744';break;
			case 'SZ':$iso2='748';break;
			case 'SD':$iso2='736';break;
			case 'SR':$iso2='740';break;
			case 'SO':$iso2='706';break;
			case 'SB':$iso2='090';break;
			case 'TJ':$iso2='762';break;
			case 'TH':$iso2='764';break;
			case 'TZ':$iso2='834';break;
			case 'TO':$iso2='776';break;
			case 'TC':$iso2='796';break;
			case 'TT':$iso2='780';break;
			case 'TN':$iso2='788';break;
			case 'TV':$iso2='798';break;
			case 'TR':$iso2='792';break;
			case 'TM':$iso2='795';break;
			case 'TK':$iso2='772';break;
			case 'WF':$iso2='876';break;
			case 'VU':$iso2='548';break;
			case 'GT':$iso2='320';break;
			case 'VE':$iso2='862';break;
			case 'BN':$iso2='096';break;
			case 'UG':$iso2='800';break;
			case 'UA':$iso2='804';break;
			case 'UY':$iso2='858';break;
			case 'UZ':$iso2='860';break;
			case 'ES':$iso2='724';break;
			case 'EH':$iso2='732';break;
			case 'WS':$iso2='882';break;
			case 'GR':$iso2='300';break;
			case 'HK':$iso2='344';break;
			case 'SG':$iso2='702';break;
			case 'NC':$iso2='540';break;
			case 'NZ':$iso2='554';break;
			case 'HU':$iso2='348';break;
			case 'SY':$iso2='760';break;
			case 'JM':$iso2='388';break;
			case 'AM':$iso2='051';break;
			case 'YE':$iso2='887';break;
			case 'IQ':$iso2='368';break;
			case 'IR':$iso2='364';break;
			case 'IL':$iso2='376';break;
			case 'IT':$iso2='380';break;
			case 'IN':$iso2='356';break;
			case 'ID':$iso2='360';break;
			case 'GB':$iso2='826';break;
			case 'VG':$iso2='092';break;
			case 'IO':$iso2='086';break;
			case 'JO':$iso2='400';break;
			case 'VN':$iso2='704';break;
			case 'ZM':$iso2='894';break;
			case 'ZR':$iso2='180';break;	
			case 'TD':$iso2='148';break;
			case 'GI':$iso2='292';break;
			case 'CL':$iso2='152';break;
			case 'CF':$iso2='140';break;
			case 'CN':$iso2='156';break;
			case 'TW':$iso2='158';break;
		}
		return $iso2;
    }
    
    public function getShipState( $countryCode, $state ){
        if( $countryCode == '840' ) {
        	switch( $state ){
				case 'Alabama':$state='AL';break;
				case 'Alaska':$state='AK';break;
				case 'American Samoa':$state='AS';break;
				case 'Arizona':$state='AZ';break;
				case 'Arkansas':$state='AR';break;
				case 'Armed Forces Africa':$state='AR';break;
				case 'Armed Forces Americas':$state='AR';break;
				case 'Armed Forces Canada':$state='AR';break;
				case 'Armed Forces Europe':$state='AR';break;
				case 'Armed Forces Middle East':$state='AR';break;
				case 'Armed Forces Pacific':$state='AR';break;
				case 'California':$state='CA';break;
				case 'Colorado':$state='CO';break;
				case 'Connecticut':$state='CT';break;
				case 'Delaware':$state='DE';break;
				case 'District of Columbia':$state='DC';break;
				case 'Federated States Of Micronesia':$state='FM';break;
				case 'Florida':$state='FL';break;
				case 'Georgia':$state='GA';break;
				case 'Guam':$state='GU';break;
				case 'Hawaii':$state='HI';break;
				case 'Idaho':$state='ID';break;
				case 'Illinois':$state='IL';break;
				case 'Indiana':$state='IN';break;
				case 'Iowa':$state='IA';break;
				case 'Kansas':$state='KS';break;
				case 'Kentucky':$state='KY';break;
				case 'Louisiana':$state='LA';break;
				case 'Maine':$state='ME';break;
				case 'Marshall Islands':$state='MH';break;
				case 'Maryland':$state='MD';break;
				case 'Massachusetts':$state='MA';break;
				case 'Michigan':$state='MI';break;
				case 'Minnesota':$state='MN';break;
				case 'Mississippi':$state='MS';break;
				case 'Missouri':$state='MO';break;
				case 'Montana':$state='MT';break;
				case 'Nebraska':$state='NE';break;
				case 'Nevada':$state='NV';break;
				case 'New Hampshire':$state='NH';break;
				case 'New Jersey':$state='NJ';break;
				case 'New Mexico':$state='NM';break;
				case 'New York':$state='NY';break;
				case 'North Carolina':$state='NC';break;
				case 'North Dakota':$state='ND';break;
				case 'Northern Mariana Islands':$state='MP';break;
				case 'Ohio':$state='OH';break;
				case 'Oklahoma':$state='OK';break;
				case 'Oregon':$state='OR';break;
				case 'Palau':$state='PW';break;
				case 'Pennsylvania':$state='PA';break;
				case 'Puerto Rico':$state='PR';break;
				case 'Rhode Island':$state='RI';break;
				case 'South Carolina':$state='SC';break;
				case 'South Dakota':$state='SD';break;
				case 'Tennessee':$state='TN';break;
				case 'Texas':$state='TX';break;
				case 'Utah':$state='UT';break;
				case 'Vermont':$state='VT';break;
				case 'Virgin Islands':$state='VI';break;
				case 'Virginia':$state='VA';break;
				case 'Washington':$state='WA';break;
				case 'West Virginia':$state='WV';break;
				case 'Wisconsin':$state='WI';break;
				case 'Wyoming':$state='WY';break;
			}
        }elseif( $countryCode == '124' ){
            switch( $state ){
				case 'Alberta':$state='AB';break;
				case 'British Columbia':$state='BC';break;
				case 'Manitoba':$state='MB';break;
				case 'New Brunswick':$state='NB';break;
				case 'Newfoundland':$state='NL';break;
				case 'Northwest Territories':$state='NT';break;
				case 'Nova Scotia':$state='NS';break;
				case 'Nunavut':$state='NU';break;
				case 'Ontario':$state='ON';break;
				case 'Prince Edward Island':$state='PE';break;
				case 'Quebec':$state='QC';break;
				case 'Saskatchewan':$state='SK';break;
				case 'Yukon Territory':$state='YT';break;
			}
        }
        
        $pattern = '/[\x7f-\xff\d]/';
        $state = preg_replace( $pattern, '', $state );
        return $state;
    }
    
    public function getMoneyType(){
        $currency = ClsFactory::instance( "ClsCurrency" );
        $type = array(
            "CNY"=>0,"USD"=>1,"EUR"=>2,"GBP"=>3,"JPY"=>4,"KER"=>5,"AUD"=>6,"RUB"=>7,"CHF"=>8,"HKD"=>9,"SGD"=>10,"MOP"=>11
        );
        if( array_key_exists( $currency->getCurrency(), $type ) ) {
        	return $type[$currency->getCurrency()];
        }
        return 1;
    }
    
    public function afterSubmit(){
        $md5key = $this->getValue( 'MODULE_PAYMENT_PAYEASE_MD_KEY' );
        if( isset( $_POST['v_amount'] ) && isset( $_POST['v_moneytype'] ) && isset( $_POST['v_md5money'] ) ) {
            error_log( "post payease data: ". json_encode( $_POST ) ." \r\n", 3,  "payease.log");
        	$md5Money = bin2hex( mhash( MHASH_MD5, $_POST['v_amount']. $_POST['v_moneytype'], $md5key ) );
        	error_log( "post payease v_md5money param: ". $_POST['v_md5money'] ."  ### mhash md5money : ". $md5Money ." \r\n", 3,  "payease.log");
        	if( $_POST['v_md5money'] == $md5Money ) {
        		$payease = Hqw::getApplication()->getModels( "payease" )->where( array( 'oid'=> $_POST['v_oid'] ) )->fetch();
        		if( $payease && isset( $payease['session_key'] ) ) {
        			$checkout = ClsCheckout::getCheckout( $payease['session_key'] );
        			$currency = $checkout->getCurrency();
        			if( $_POST['v_amount'] == $currency->getCurrencyValues( $checkout->getCheckoutTotal() ) ) {
        				if( ( $userId = (int)$checkout->getUserId() ) == null && ( $shippingId = (int)$checkout->getShippingAddress() ) == null && ( $shippingMethod = $checkout->getShippingMethod() ) == null && ( $billingId = (int)$checkout->getBillingAddress() ) == null && ( $paymentMethod = $checkout->getPaymentMethod() ) == null ) {
        				    
        				    $checkout->setPaymentTranId( $_POST['v_oid'] );
        				    error_log( "post payease checkout set tran id \r\n", 3,  "payease.log");
        				    
        				    list( $orderId, $orderNumber ) = $checkout->createOrder();
        				    
        				    $sc = $checkout->getShoppingCart();
                            $items = $sc->getCheckoutItems();
                            if( count( $items ) > 0 ){
                                //shopping cart clean
                                foreach( $items as $k => $v ) {
                                	$v->updateOrder( $orderId );
                                }
                            }
                            
                            
                            $payease = array(
                                'orders_id'=>$orderId,
                                'pstatus'=>$_POST["v_pstatus"],
                                'pstring'=>$_POST["v_pstring"]
                            );
                            if( Hqw::getApplication()->getModels( "payease" )->where( array( 'oid'=> $_POST['v_oid'] ) )->update( $payease ) ){
                                
                            }else{
                                error_log( "post payease update payease table data losed: \r\n", 3,  "payease.log");
                            }
                    		
                    		ClsCheckout::cleanCheckout();
                    		return array( true, ClsOrdersFactory::instance( $orderId ));
        				    
        				}
        			}else{
        			    error_log( "post payease data error: amount check no equal \r\n", 3,  "payease.log");
        			}
        			
        		}else{
        		    error_log( "post payease data error: payease table no record, ". $_POST['v_oid'] ." \r\n", 3,  "payease.log");
        		}
        	}else{
        	    error_log( "post payease data error: money check no equal \r\n", 3,  "payease.log");
        	}
        }
        
        return array( false, MODULE_PAYMENT_PAYEASE_PAYMENT_ERROR );
    }
    
}

?>
