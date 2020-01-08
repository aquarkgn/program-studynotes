<?php

class Tools_Funcion {
    
    /**
     * 文件大小单位换算
     * @return string
     */
    public static function getFileSize($url, $unit = 'M'){
        $size = filesize($url);
        if(!$size) return false;
        $base= 1024;
        $list = array('B','K','M','G','T','P');
        $key = array_search($unit,$list);
        $i = floor(log($size,$base));
        if($i >= ($key + 1)){
            $i=$key;
        }
        return floor($size/pow($base,$i));
    }
    
    	/**
	 * @param $arr
	 * @return array
	 * @author zhangxianbiao@zuoyebang.com
	 * Notes:数组排序
	 */
	protected function sortName($arr) {
		$ret = [];
		if (empty($arr)) {
			return $ret;
		}
		
		$groupArr = $this->splitArrStrByFirstChar($arr);
		//$groupArr顺序就是按照文字、字母、数字、特殊字符的顺序排序
		foreach ($groupArr as $type => $needSortArr) {
			if (!empty($groupArr[$type])) {
				if ($type == 'chinese') {
					//汉字单独进行转码后排序
					foreach ($needSortArr as $key => $value) {
						$needSortArr[$key] = iconv('UTF-8', 'GBK//IGNORE', $value);
					}
					sort($needSortArr);
					foreach ($needSortArr as $key => $value) {
						$needSortArr[$key] = iconv('GBK', 'UTF-8//IGNORE', $value);
					}
				} else {
					//数字，英文，特殊字符直接排序
					sort($needSortArr);
				}
				$ret = array_merge($ret, $needSortArr);
			}
		}
		return $ret;
	}
	
	/**
	 * @param $arr
	 * @return array
	 * @author zhangxianbiao@zuoyebang.com
	 * Notes:根据首字母分词
	 */
	private function splitArrStrByFirstChar($arr) {
		$ret = [
			'chinese' => [],
			'english' => [],
			'number'  => [],
			'special' => [],
		];
		
		if (!$arr || !is_array($arr)) {
			return $ret;
		}
		foreach ($arr as $key => $value) {
			if (!strval($value)) {
				continue;
			}
			//获取字符串的第一个字符
			$str0 = mb_substr($value, 0, 1, 'utf-8');
			//将UTF-8转换成GBK编码
			$str = iconv('UTF-8', 'GBK', $str0);
			if (ord($str) > 128) {
				//汉字开头，汉字没有以U、V开头的
				$ret['chinese'][] = $value;
			} elseif (ord($str) >= 48 && ord($str) <= 57) {
				//数字开头
				$ret['number'][] = $value;
			} elseif ((ord($str) >= 65 && ord($str) <= 90) || (ord($str) >= 97 && ord($str) <= 122)) {
				//大写-小写英文开头
				$ret['english'][] = $value;
			} else {
				//特殊字符
				$ret['special'][] = $value;
			}
		}
		return $ret;
	}



	/**
	 *
	 *	版权声明：本文为CSDN博主「隔壁小王攻城狮」的原创文章，遵循 CC 4.0 BY-SA 版权协议，转载请附上原文出处链接及本声明。
	 *	原文链接：https://blog.csdn.net/df981011512/article/details/73732232
	 *
	 *
	 * 将字符串(中文同样实用)转为ascii（注意：我默认当前我们的php文件环境是UTF-8，如果是GBK的话mb_convert_encoding操作就不需要)
	 */
	public function strtoascii($str){
		$str=mb_convert_encoding($str,'GB2312');
		$change_after='';
		for($i=0;$i<strlen($str);$i++){
				$temp_str=dechex(ord($str[$i]));
				$change_after.=$temp_str[1].$temp_str[0];
		}
		return strtoupper($change_after);
}

/**
 * 将ascii转为字符串(中文同样实用)（注意：我默认当前我们的php文件环境是UTF-8，如果是GBK的话mb_convert_encoding操作就不需要)
 */
public function asciitostr($sacii){
	$asc_arr= str_split(strtolower($sacii),2);
	$str='';
	for($i=0;$i<count($asc_arr);$i++){
			$str.=chr(hexdec($asc_arr[$i][1].$asc_arr[$i][0]));
	}
	return mb_convert_encoding($str,'UTF-8','GB2312');
}

}