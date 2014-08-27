#-*- coding: utf-8 -*-

from bs4 import BeautifulSoup
import mechanize,re

def getSef(s):
	if re.search("\?",s):
		return s
	else:
		di = re.split("\/",s)
		return str(di[-1])

def reIptal(i):
	_ = ""
	for j in i:
		if j == ".":
			_ = _ + "\."
		elif j == "$":
			_ = _ + "\$"
		elif j == "^":
			_ = _ + "\^"
		elif j == "*":
			_ = _ + "\*"
		elif j == "+":
			_ = _ + "\+"
		elif j == "?":
			_ = _ + "\?"
		elif j == "]":
			_ = _ + "\]"
		elif j == "[":
			_ = _ + "\["
		elif j == "{":
			_ = _ + "\{"
		elif j == "}":
			_ = _ + "\}"
		elif j == "\\":
			_ = _ + "\\\\"
		elif j == "|":
			_ = _ + "\|"
		elif j == "(":
			_ = _ + "\("
		elif j == ")":
			_ = _ + "\)"
		else:
			_ = _ + j
	return _

				
def gercekIsim(dic,lst):
	try:
		if dic[lst[0]]:
			return lst[0]
	except KeyError:
		try:
			if dic[lst[1]]:
				return lst[1]
		except KeyError:
			try:
				if dic[lst[2]]:
					return lst[2]
			except IndexError:
				return lst[-1]
		
site = { #http://filesharefreak.com/2009/08/26/100-of-the-best-free-file-hosting-upload-sites
    "hotfile":{
        "DURUM":0,
		"EXT":".com"
        #"HATA_RAPORU", düzenli ifadeler
    },
    "rapidshare":{
        "DURUM":1,
		"EXT":".com",
        #"RE":0, #düzenli ifade
        "HATA":"ERROR: File not found."
    },
    "letitbit":{
		#http://u25107251.letitbit.net/download/38423.31a8b39c937ed820f4fff43fcb52/Pinto.rar.html
        "DURUM":1,
		"EXT":".net",
        #"RE":"letitbit",
        "HATA":"vcaptcha_conteiner" #direk captcha çıkarsa, link "koşulsuz" ölüdür.
    },
    "mediafire":{
        #http://mediafire.com/?y8l6d88d2c8h81t
        "DURUM":1,
		"EXT":".com",
        "HATA":"error_topbanner"
    },
	"uploading":{
		#http://uploading.com/files/851e57a7/test.txt
		"DURUM":1,
		"EXT":".com",
		"HATA":"error_content"
	},
	"rapidgator":{
		#http://rapidgator.net/file/2e2f%C2%AD95cf84e6a98d894%C2%AD550dd226253de/1%C2%AD40328-G-79220%C2%AD1.part01.rar.ht%C2%ADml
		"DURUM":1,
		"EXT":".net",
		"HATA":"Error"
	},
	"uploadable":{
		#http://www.uploadable.ch/file/GD%C2%ADBRmma8jmPD/1402%C2%AD28-G-777842.p%C2%ADart2.rar
		"DURUM":1,
		"EXT":".ch",
		"HATA":"errorMsg"
	},
	"turbobit":{
		#http://turbobit.net/vlg07sthdk3n.html
		"DURUM":1,
		"EXT":".net",
		"HATA":"not found|be deleted|code-404|text-404|Файл не найден"
	},
	"2shared":{
		"DURUM":1,
		"EXT":".com",
		"HATA":"leMsg|important\.gif|not valid"
	},
	"4fastfile":{
		"DURUM":0,
		"EXT":".com"
	},
	"4shared":{
		#http://www.4shared.com/photo/WhbTILRrba/kiril_abc.html
		"DURUM":1,
		"EXT":".com",
		"HATA":"unavailable|class=\"warn\"|linkerror"
	},
	"bayfiles":{
		#http://bayfiles.net/file/1cI4B/1lLiq1/kiril_abc.png
		"DURUM":1,
		"EXT":".net",
		"HATA":"not-found|incorrect"
	},
	"bitshare":{
		#http://bitshare.com/files/mbaj6c8p/kiril-abc.rar.html
		"DURUM":1,
		"EXT":".com",
		"HATA":"quotbox2"
	},
	"box":{
		#https://app.box.com/s/ec74nrzypfpr3adcs6jd
		"DURUM":1,
		"EXT":".com",
		"HATA":"error_message|removed"
	},
	"crocko":{#GELİŞTİRİLİYOR
		#http://www.crocko.com/198A2D90154A47D5A20CD1D8E7AEB405/kiril_abc.rar
		#http://www.crocko.com/del/82969e8d3692632ed207f4c45b0b0866.html
		"DURUM":0,
		"EXT":".com",
		"HATA":""
	},
	"dfiles":{#GELİŞTİRİLİYOR
		#http://dfiles.eu/files/2ahb23fl5 -
		#http://dfiles.eu/files/i6k4fujpe +
		#<form method=post><input type="hidden" name="gateway_result" value="1"/></form>
		"DURUM":0,
		"EXT":".eu",
		"RQ":("POST",{'gateway_result':1}),
		"HATA":"downloadblock|downloadblock_msg"
	},
	"depositfiles":{#GELİŞTİRİLİYOR
		"DURUM":0,
		"EXT":".eu",
		"RQ":("POST",{'gateway_result':1}),
		"HATA":"downloadblock|downloadblock_msg"
	},
	"dropbox":{
		#https://www.dropbox.com/s/qrs9dufdiv23ptq/asd.rar
		#https://www.dropbox.com/s/qrs9dufdiv23ptq/asd.rar
		"DURUM":1,
		"EXT":".com",
		"HATA":"class=\"err\""
	},
	"mega":{
		#https://mega.co.nz/#!jUNAABaS!EU0GtzwceqS_RIcR-Ox2-C4nKcUy1VDc020hh9Du3Qw
		"DURUM":1,
		"EXT":".co.nz",
		"HATA":"error|removed"
	},
	"google":{
		#https://drive.google.com/file/d/0B5zS7ug29tCKdFlWR19iNVBibnc/edit?usp=sharing
		"DURUM":1,
		"EXT":".com",
		"HATA":"not exist|error|remove"
	},
	"uploaded":{
		#GELİŞTİRİLİYOR
		#http://uploaded.net/file/nnn5ttme
		#http://uploaded.net/file/ofi8dfhv/alplsax3.part1.rar
		"DURUM":0,
		"EXT":".net",
		"HATA":""
	},
	"ul":{#GELİŞTİRİLİYOR
		#http://uploaded.net/file/nnn5ttme
		"DURUM":0,
		"EXT":".net",
		"HATA":""
	},
	"zippyshare":{
		#http://www53.zippyshare.com/v/18013299/file.html
		#http://www54.zippyshare.com/v/40568605/file.html
		"DURUM":1,
		"EXT":".com",
		"HATA":"File has expired and does not exist anymore on this server|not exist"
	},
	"sendspace":{ #ISTINA
		#http://www.sendspace.com/file/e8evrf
		#http://www.sendspace.com/file/bjebq7
		"DURUM":1,
		"EXT":".com",
		"HATA":"" #ISTISNA OLDUĞU İÇİN "HATA" boş
	}
}

def destek():
	return ["{0} {1}".format(k+v["EXT"],str(v["DURUM"]).replace("0","-").replace("1","+")) for k,v in site.iteritems()]
	
def linkKontrol(link,httpErr=0,uA='Firefox'):
	if link[:4] == "http":
		tamIsim = re.split("\/",link)[2]
	else:
		tamIsim = re.split("\/",link)[0]
		link = "http://" + link
	
	mc = mechanize.Browser()
	mc.set_handle_robots(False)
	mc.set_handle_refresh(False)
	mc.addheaders = [('User-agent', uA)]
	
	noktasiz = re.split("\.",tamIsim)
	
	tamName = gercekIsim(site,noktasiz)
	rf = re.search(reIptal(site[tamName]),link)
	if rf or rf == None:
		try:
			tamName = gercekIsim(site,noktasiz)
			if site[tamName]["DURUM"]:
				mc.open(link)
				if tamName == "sendspace": #istisna
					rp = mc.response()
					dom = BeautifulSoup(rp.read())
					dom.prettify()
					for a in dom.find_all('a'):
						if a.get('id') == "download_button":
							try:
								mc.open(a.get('href'))
								return "+" #return, olmasaydı dosyayı okuyacaktı (yani belleğe imdirmiş olacaktı)
							except mechanize.HTTPError, e:
								if httpErr: #geliştirici için
									return "-{0}".format(e.code)
								else:
									return "-"
				#DEPOSITFILES, SELF LINKE POST GONDERIMDE BULUNUYOR, HALEN UĞRAŞIYORUM
				#if tup(tamName,("dfiles","depositfiles")):
				#	if site["dfiles"]["RQ"][0]:
				#		resp = mechanize.Request(link,site["dfiles"]["RQ"][1])
				#		o = mechanize.urlopen(resp)
				#		ref = re.findall(site["dfiles"]["HATA"],o.read())
				rp = mc.response()
				ref = re.findall(site[tamName]["HATA"],rp.read())
				if ref: #hata raporu varsa,
					return "-"
				else:
					return "+"
			else:
				return "?"
		except mechanize.HTTPError, e:
			if httpErr: #geliştirici için
				return "-{0}".format(e.code)
			else:
				return "-"
	else: #site değişkenin de olmayan, kullanım dışı siteler
		#Gibi
		#https://www.google.com/istihza/fatihmert
		try:
			mc.open(link)
			return "+"
		except mechanize.HTTPError, e:
			if httpErr: #geliştirici için
				return "-{0}".format(e.code)
			else:
				return "-"
		except:
			#raise u"Boyle bir site mevcut değil #0"
			return "?"
