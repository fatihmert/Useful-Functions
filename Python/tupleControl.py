#-*- coding: utf-8 -*-

def tupleControl(ifade,demet):
	if type(demet) is type(("",)):
		for i in demet:
			if ifade == i:
				return 1
				
# For example

import sys

if tupleControl(sys.argv[1],("-h","h","-H","-H")):
  print "Help"
  
# Good works.
