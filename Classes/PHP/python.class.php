<?php

/*
	Author:	 	Fatih Mert Doğancan
	Mail : 		fatihmertdogancan@hotmail.com
	
	RELEASE:	25.01.2014 - 22:40
*/

/*
		!!!!!!!!!!!!!!
		KULLANIM-USING
		!!!!!!!!!!!!!!
		
	#lambda(<exec>,<expression>)
	@param exec: 			string
	@param expression:		string
	
	This function is still developing...
	Bu fonksiyon halen geliştiriliyor.
	
	Python using (Python kullanımı);
		g = lambda x: x**2
		g(8)
		
	Result (Sonuç); 16
	PHP using (PHP kullanımı);
		$e = lambda("g(8)","g = lambda x: x*2");
		echo $e;
	Result (Sonuç); 16
	
	#str(<string>,<3s>)
	@param string:			string
	@param 3s(start:step:stop):	string
	
	Python is use syntax with string and array, like array[2:1]. Syntax [start:stop:step]
	Python'daki söz dizimi [start:stop:step], python'daki tüm depolama hizmetlerini dizi[2:1] şeklinde sıralandırabiliyorsunuz
	
	PHP using, I think after new able add to this function. I added php iconv() function. You are use two way, first way there isn't iconv(), second way so last way iconv() using.
	PHP de kullanırken sonraları problem olabileceğini düşündüğüm için bu fonksiyone yeni bir özellik daha ekkledim. Bu özellikte PHP deki iconv() fonksiyonu. İlk kullanım, iconv()'siz ikincisi ise iconv()'li kullanım.
	
	First way (İlk kullanım);
	str(<$string>,"start:stop:step")
	
	With using "iconv()" (iconv()'li kullanım);
	str(<$string>,"start:stop:step;<string_char_code>:<string_to_char_code>")
	
	For example: (Bir örnek)
	$e = str("1:9;UTF-8:ISO-9","fatihmert");
	echo $e;
	
	Result(sonuc): atihmert
*/


class Python{
	
	private function isInt($i){return preg_match('@^[-]?[0-9]+$@',$i) === 1;}
	private function isVar($i){return preg_match("/(^\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/",$i) === 1;} //isVar("\$fatihmert1995") => true
	//private function varRp($i){return preg_replace("/(^\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/", "\\$1",$i);}
	//private function topVar($i){preg_match_all('/(^\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/',$i,$yakala);return $yakala;}
	private function ilkSon($pat,$i,$s){if($this->str($pat,"0:1") == $i && $this->str($pat,"-1") == $s){return true;}}
	
	public function lambda($kullan,$pattern){
		/*
		
		sadece tek sayı okur.
		
		Python
		g = lambda x: x**2
		g(8) -> 16
		
		PHP
		$e = lambda("g(8)","g = lambda x: x*2");
		echo $e -> 16
		*/
		
		$kullanPattern  = "/(^[a-zA-Z][0-9]*)\(([0-9]*)\)/";
		$kullanPatternYakala = preg_match($kullanPattern,$kullan,$kullanSonuc);
		$kullan_['deger'] = $kullanSonuc[2];
		$kullan_['degisken'] = $kullanSonuc[1];
		
		$patternPattern = "/(^[a-zA-Z][0-9]*) = lambda ([a-zA-Z][0-9]*): (.*)/";
		$patternPatternYakala = preg_match($patternPattern, $pattern, $patternSonuc);
		$pattern_['kullan_degisken'] = $patternSonuc[1];
		$pattern_['degisken'] = $patternSonuc[2];
		$pattern_['algoritma'] = $patternSonuc[3];
		
		$hata = "YOK";
		
		for($i = 0; $i <= strlen($pattern_['algoritma'])-1; $i++){
			if($pattern_['algoritma'][$i] == $pattern_['degisken']){
				$hata = "YOK";
			}else{
				$hata = "VAR";
			}
		}
		
		if($kullan_['degisken'] != $pattern_['kullan_degisken']){
			$hata = "VAR";
		}else{
			$hata = "YOK";
		}
		
		if($hata == "VAR"){
			return "Hatalı eşleşme";
		}else{
			$sonHesap = "";
			for($i = 0; $i <= strlen($pattern_['algoritma'])-1; $i++){
				if($pattern_['degisken'] == $pattern_['algoritma'][$i]){
					$sonHesap = $sonHesap.$kullan_['deger'];
				}else{
					$sonHesap = $sonHesap.$pattern_['algoritma'][$i];
				}
			}
			return eval("return ".$sonHesap.";");
		}
	}
	
	//not:
	//1::-2 çalışmıyor düzenlenecek
	
	public function str($str,$pattern){
		//[start:stop:step]
		//pattern ->			([-]?[0-9]*|\s):([-]?[0-9]*|\s):([-]?[0-9]*|\s)
		
		//iconv'li yeni pattern -> ([-]?[0-9]*|\s?):([-]?[0-9]*|\s?):?([-]?[0-9]*|\s?);(.*|\s?):(.*|\s?)
		//iconv'li kullanım -> "1:9:-3;UTF-8:ISO-9" -> UTF-8'i ISO-9'a çevirir
		preg_match("/([-]?[0-9]*|\s?):([-]?[0-9]*|\s?):?([-]?[0-9]*|\s?);?(.*|\s?):(.*|\s?)/", $pattern, $yakala);
		$start = $yakala[1];
		$stop = $yakala[2];
		$step = $yakala[3];
		
		$karakter = $yakala[4];
		$donusecek = $yakala[5];
		
		$iconv = isset($karakter) && isset($donusecek) ? true : false;
		
		if(empty($start) && empty($stop) && $step == "-1"){//istisna durum
			return $iconv == true ? iconv($karakter,$donusecek,strrev($str)) : strrev($str);
		}else if(empty($start) && empty($stop) && isset($step)){//istisna durum
			$rev = "";
			$yeni = "";
			if($step[0] == "-" && $stop != "-1"){$rev = "VAR";}
			$atla = abs($step);
			for($i = 0; $i <= strlen($str); $i++){
				$offset = $i*$atla;
				if(isset($str[$offset])){
					$yeni = $yeni.$str[$offset];
				}
			}
			if($rev != "VAR"){
				return $iconv == true ? iconv($karakter,$donusecek,substr($yeni,0,strlen($str)-1)) : substr($yeni,0,strlen($str)-1);
				//"hepsi boş, step dolu o da +";
			}else{
				return $iconv == true ? iconv($karakter,$donusecek,strrev(substr($yeni,0,strlen($str)-1))) : strrev(substr($yeni,0,strlen($str)-1));
				//"hepsi boş, step dolu o da -";
			}
		}
		
		if(empty($start) && empty($stop) && empty($step)){
			return $str;
			//"hepsi boş";
		}else if(empty($start)){
			if(isset($stop) && empty($step)){
				$rev = "";
				if($stop[0] == "-"){$rev = "VAR";}
				if($rev != "VAR"){
					return $iconv == true ? iconv($karakter,$donusecek,substr($str,0,$stop)) : substr($str,0,$stop);
					//"start ve step boş, stop dolu"
				}else{
					return $iconv == true ? iconv($karakter,$donusecek,strrev(substr($str,0,$stop))) : strrev(substr($str,0,$stop));
					//"start ve step boş, stop -1";
				}
			}else if(isset($stop) && isset($step)){
				$rev = "";
				if($stop[0] == "-"){$rev = "VAR";}
				$yeni = "";
				if($step == 1){
					if($rev != "VAR"){
						return $iconv == true ? iconv($karakter,$donusecek,$str) : $str;
						//"start boş, stop ve step dolu, step 1";
					}else{
						return $iconv == true ? iconv($karakter,$donusecek,strrev(substr($str,0,abs($stop)))) : strrev(substr($str,0,abs($stop))); //abs -> mutlak değer (-5 = 5)
						//"start boş, stop -, step dolu, step 1";
					}
				}else{
					$atla = abs($step);
					for($i = 0; $i <= strlen($str); $i++){
						$offset = $i*$atla;
						if(isset($str[$offset])){
							$yeni = $yeni.$str[$offset];
						}
					}
					if($rev != "VAR"){
						return $iconv == true ? iconv($karakter,$donusecek,substr($yeni,0,$stop)) : substr($yeni,0,$stop);
						//"start boş, step ve stop dolu";
					}else{
						return $iconv == true ? iconv($karakter,$donusecek,strrev(substr($yeni,0,abs($stop)))) : strrev(substr($yeni,0,abs($stop)));
						//"start boş, step ve stop -";
					}
				}
			}
		//start boş değilse
		}else if(!empty($start)){
			if(isset($stop) && empty($step)){
				$rev = "";
				if($stop[0] == "-"){$rev = "VAR";}
				if($rev != "VAR"){
					return $iconv == true ? iconv($karakter,$donusecek,substr($str,$start,$stop)) : substr($str,$start,$stop);
					//return "step boş, start ve stop dolu";
				}else{
					return $iconv == true ? iconv($karakter,$donusecek,strrev(substr($str,0,abs($stop)))) : strrev(substr($str,0,abs($stop)));
					//"step boş, start ve stop dolu, stop -";
				}
			}else if(isset($stop) && isset($step)){
				
				//hepsi dolu
				$rev = "";
				if($stop[0] == "-"){$rev = "VAR";}
				$yeni = "";
				if($step == 1){
					if($rev != "VAR"){
						return $iconv == true ? iconv($karakter,$donusecek,substr($str,$start,$stop)) : substr($str,$start,$stop);
						//"hepsi dolu, step 1";
					}else{
						return $iconv == true ? iconv($karakter,$donusecek,substr($str,$start,abs($stop))) : substr($str,$start,abs($stop));
						//"hepsi dolu, step 1, stop -";
					}
				}else{
					if($stop[0] == "-"){$rev = "VAR";}
					$atla = abs($step);
					for($i = 0; $i <= strlen($str); $i++){
						$offset = $i*$atla;
						if(isset($str[$offset])){
							$yeni = $yeni.$str[$offset];
						}
					}
					if($rev != "VAR"){
						return $iconv == true ? iconv($karakter,$donusecek,substr($yeni,$start,$stop)) : substr($yeni,$start,$stop);
						//"hepsi dolu";
					}else{
						return $iconv == true ? iconv($karakter,$donusecek,strrev(substr($yeni,$start,abs($stop)))) : strrev(substr($yeni,$start,abs($stop)));
						//"hepsi dolu, stop -";
					}
				}
			}
		}
	}
	
}


?>                                              
