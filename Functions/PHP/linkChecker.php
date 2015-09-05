<?php 
	/*
		Author: Fatih Mert Doğancan
		Release: 05.09.2015 - 17:14
		Note: Python to PHP
		Project Time: 30dk-1saat
		Name:Link Checker
		Version: 0.1
		Support uplaoded 16 sites,
			rapidshare.com,letitbit.net,
			mediafire.com,uploading.com,
			rapidgator.net,uploadable.ch,
			turbobit.net,2shared.com,
			4shared.com,bayfiles.net,
			bitshare.com,box.com,dropbox.com,
			mega.co.nz,drive.google.com,
			zippyshare.com
	*/
	
	//https://github.com/rmccue/Requests/
	include('library/Requests.php');
	
	//http://simplehtmldom.sourceforge.net/
	//include('library/simple_html_dom.php');
	
	Requests::register_autoloader();
	
	function reIptal($i){
		$_ = "";
		$chars = @str_split($i);
		if(is_array($i)){
			$chars = $i;
		}
		foreach($chars as $ch){
			if($ch == "."){
				$_ .= "\.";
			}else if($ch == "$"){
				$_ .= "\$";
			}else if($ch == "^"){
				$_ .= "\^";
			}else if($ch == "*"){
				$_ .= "\*";
			}else if($ch == "+"){
				$_ .= "\+";
			}else if($ch == "?"){
				$_ .= "\?";
			}else if($ch == "]"){
				$_ .= "\]";
			}else if($ch == "["){
				$_ .= "\[";
			}else if($ch == "{"){
				$_ .= "\{";
			}else if($ch == "}"){
				$_ .= "\}";
			}else if($ch == "\\"){
				$_ .= "\\\\";
			}else if($ch == "|"){
				$_ .= "\|";
			}else if($ch == "("){
				$_ .= "\(";
			}else if($ch == ")"){
				$_ .= "\)";
			}else{
				$_ .= $ch;
			}
		}
		return $_;
	}
	
	
	$site = array(
		"hotfile" => array(
			"DURUM"=>0,
			"EXT"=>".com"
		),
		"rapidshare" => array(
			"DURUM"=>1,
			"EXT"=>".com",
			"HATA"=>"ERROR=> File not found."
		),
		"letitbit" => array(
			"DURUM"=>1,
			"EXT"=>".net",
			"HATA"=>"vcaptcha_conteiner"
		),
		"mediafire" => array(
			"DURUM"=>1,
			"EXT"=>".com",
			"HATA"=>"error_topbanner"
		),
		"uploading" => array(
			"DURUM"=>1,
			"EXT"=>".com",
			"HATA"=>"error_content"
		),
		"rapidgator" => array(
			"DURUM"=>1,
			"EXT"=>".net",
			"HATA"=>"Error"
		),
		"uploadable" => array(
			"DURUM"=>1,
			"EXT"=>".ch",
			"HATA"=>"errorMsg"
		),
		"turbobit" => array(
			"DURUM"=>1,
			"EXT"=>".net",
			"HATA"=>"not found|be deleted|code-404|text-404|???? ?? ??????"
		),
		"2shared" => array(
			"DURUM"=>1,
			"EXT"=>".com",
			"HATA"=>"leMsg|important\.gif|not valid"
		),
		"4fastfile" => array(
			"DURUM"=>1,
			"EXT"=>".com"
		),
		"4shared" => array(
			"DURUM"=>1,
			"EXT"=>".com",
			"HATA"=>"unavailable|class=\"warn\"|linkerror"
		),
		"bayfiles" => array(
			"DURUM"=>1,
			"EXT"=>".net",
			"HATA"=>"not-found|incorrect"
		),
		"bitshare" => array(
			"DURUM"=>1,
			"EXT"=>".com",
			"HATA"=>"quotbox2"
		),
		"box" => array(
			"DURUM"=>1,
			"EXT"=>".com",
			"HATA"=>"error_message|removed"
		),
		"crocko" => array(
			"DURUM"=>0,
			"EXT"=>".com",
			"HATA"=>""
		),
		"dropbox" => array(
			"DURUM"=>1,
			"EXT"=>".com",
			"HATA"=>"class=\"err\""
		),
		"mega" => array(
			"DURUM"=>1,
			"EXT"=>".co.nz",
			"HATA"=>"error|removed"
		),
		"google" => array(
			"DURUM"=>1,
			"EXT"=>".com",
			"HATA"=>"not exist|error|remove"
		),
		"zippyshare" => array(
			"DURUM"=>1,
			"EXT"=>".com",
			"HATA"=>"File has expired and does not exist anymore on this server|not exist"
		),
		"sendpsace" => array(
			"DURUM"=>1,
			"EXT"=>".com",
			"HATA"=>""
		)
	);
	
	$link = "http://www53.zippyshare.com/v/18013299/file.html";
	$httpErr = 0;
	$uA = "Firefox";
	
	$tamIsim = "";
	
	if(substr($link,0,4) == "http"){
		$tamIsim = preg_split("@\/@",$link)[2];
	}else{
		$tamIsim = preg_split("@\/@",$link)[0];
		$link = "http://".$link;
	}
	
	function gercekIsim($arr1,$arr2){
		try{
			if(@$arr1[$arr2[0]]){
				return $arr2[0];
			}else{
				return $arr2[1];
			}
		} catch (Exception $e){
			try{
				if($arr1[$arr2[1]]){
					return $arr2[1];
				}
			} catch (Exception $e){
				try{
					if($arr1[$arr2[2]]){
						return $arr2[2];
					}
				} catch(Exception $e){
					return $arr2[-1];
				}
			}
		}
	}
	
	$noktasiz = preg_split('@\.@',$tamIsim);
	
	$tamName = gercekIsim($site,$noktasiz);
	$rf_ = preg_match("@".reIptal([$tamName])."@",$link,$rf);
	if($site[$tamName]["DURUM"]){
		$response = Requests::get($link);
		$ref = preg_match("@".$site[$tamName]["HATA"]."@",$response->body);
		if($ref == 1){
			echo $link." -";
		}else{
			echo $link." +";
		}
	}
	
?>
