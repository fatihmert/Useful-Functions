#-*- coding: utf-8 -*-

# Author:       Fatih Mert DOĞANCAN
# Github:       https://github.com/fatihmert
# Name:         Vimeo Get All the Links
# Explain:      From Channels For JDownloader 2 (BETA)

import re, requests, sys
from bs4 import BeautifulSoup

#URL HATASI ALIRSANIZ, ASAGIDAKİ DEĞERİ DEĞİŞTİRİN
#IF YOU WILL SEE TO THE URL ERROR, HERE IS THE CHANGE VALUE
sys.setrecursionlimit(10000) #depth value

#ORNEK
LINK = "http://vimeo.com/channels/blendertuts/"

def vimeoGetLinksFromCh(LINK):
	global LINKLER = []
	try:
		if re.search("vimeo",LINK):
			rq = requests.get(LINK)
			DOM = BeautifulSoup(rq.text)
			DOM.prettify()
			paginationNext = DOM.find("li","pagination_next")
			SF_COUNT = int(paginationNext.find_previous_siblings("li")[0].text) #SAYFA SAYISI
			for sayfaNo in range(1,SF_COUNT+1): #SF_COUNT
				SF_LINK = "%spage:%s"%(LINK,sayfaNo)
				sf_rq = requests.get(SF_LINK)
				SF_DOM = BeautifulSoup(sf_rq.text)
				SF_DOM.prettify()
				SF_VIDEOLAR = SF_DOM.find("ol",id="clips")
				for SF_VIDEO_LI in SF_VIDEOLAR:
					 if  not re.search(".*page.*",SF_VIDEO_LI.find_next('a')['href']):
						LINKLER.append("http://vimeo.com%s"%SF_VIDEO_LI.find_next('a')['href'])
				print "%s.Sayfa eklendi!"%sayfaNo
			LINKLER = set(LINKLER) #SAME THE DELETE LINE
			LINKLER = list(LINKLER)
			with open("linkler.txt","w") as f:
				f.write('\n'.join(LINKLER))
		else:
			raise  "LINK VIMEO DEGİL."
	except requests.exceptions.ConnectionError, e:
		raise "Bağlanti hatasi: ",e
	except requests.exceptions.HTTPError, e:
		raise "HTTP hatasi: ",e
	except:
		raise sys.exc_info()[:2]
	return LINKLER
