<?
header('content-type:application/json;charset=utf8');
           $body = @file_get_contents('php://input');
/**
 * 获取自定义的header数据
 */
function get_all_headers(){

    // 忽略获取的header数据
    $ignore = array('host','content-length','content-type','accept','accept-language','accept-encoding','user-agent','connection');

    $headers = array();

    foreach($_SERVER as $key=>$value){
        if(substr($key, 0, 5)==='HTTP_'){
            $key = substr($key, 5);
            $key = str_replace('_', ' ', $key);
            $key = str_replace(' ', '-', $key);
            $key = strtolower($key);

            if(!in_array($key, $ignore)){
                $headers[$key] = $value;
            }
        }
    }

    return $headers;

}


function jsonpost($url ,$data_string){
            $header = get_all_headers();
            $timestamp = $header['timestamp'];
            $gaea = $header['gaea'];
            $authorizationa = $header['authorizationa'];
            $uranus = $header['uranus'];
			$ip = $header['x-forwarded-for'];
            $ch = curl_init();    
            curl_setopt($ch, CURLOPT_POST, 1);    
            curl_setopt($ch, CURLOPT_URL, $url);    
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);    
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(    
                'Accept: */*',
                'Timestamp:'. $timestamp,
                'Gaea:'. $gaea,
                'Authorization:'. $authorizationa,
                'Uranus:'. $uranus,
                'Accept-Language: zh-Hans-CN;q=1, zh-Hant-CN;q=0.9',
                'Accept-Encoding: gzip, deflate',
                'Content-Type: application/json',      
                'User-Agent: CodoonSport(8.4.0 1490;iOS 11.2.1;iPhone)',
                'Connection: keep-alive',
				'X-FORWARDED-FOR: '. $ip
				)
	);

	        ob_start();    
            curl_exec($ch);    
            $return_content = ob_get_contents();    
            ob_end_clean();    
        
            $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);    
            return json_decode($return_content) ; 

}
    $url  = "http://api.codoon.com/api/mobile_steps_upload_detail";    
    $data = $body;  
    echo json_encode(jsonpost($url ,$data));
?>