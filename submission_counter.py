# Counts the number of submissions with 100 score in a codechef problem
#requires python 2.x and the library BeautifulSoup


import sys
import urllib
from bs4 import BeautifulSoup
print ("Enter the problem link (add http:// if not present):")
x = raw_input()
pos = x.find('problems')
url = ''
#print pos
if pos == -1:
	print "The problem link is different than what was expected ( expected format: .../problems/...).\nGo to the 'All submissions' link and enter the link of the opened page : "
	url = raw_input()
else :
	url=x[:pos]+'status'+x[(pos+8):]

const_url_suffix = '?sort_by=All&sorting_order=asc&language=All&status=15&handle=&Submit=GO'   #gets the page in which there are only AC solutions
url += const_url_suffix
page = urllib.urlopen(url)
page = page.read()
soup = BeautifulSoup(page)
count = 0
pageno = 0
found = []
#page = urllib.urlopen(url)
#page = page.read()
#soup = BeautifulSoup(page)
#soup.prettify()
proceed = True
while proceed:
	#print url
	page = urllib.urlopen(url)
	page = page.read()
	soup = BeautifulSoup(page)
	pageno +=1
	#print pageno

	submission_rows = soup.find_all('tr',class_='kol')
	#print submission_rows.get_text()
	for row in submission_rows:
		cnt = 1
		username = ''
		score = 0
		for string in row.strings: 							#the text in one row
			if cnt == 3 : username = string     			#gives the 3rd value in the row i.e the username
			if cnt == 4 : 									#gives the 4th value in the row i.e score
				score = int(string)
				if ( found.count(username) == 0 and score == 100):
	 				found.append(username)
	 				count += 1
	 			break
	 		cnt += 1
	# 	username = ''
	# 	score = 0
	# 	for fields in row.find_all('td'):
	# 	
	# 		if fields['width'] == '144' :
	# 			username = fields.find('a')['title']
	# 			#print username
	# 		if fields['width'] == '51':
	# 			score = int(fields.find(text = True))
	# 		if ( found.count(username) == 0 and score == 100):
	# 			found.append(username)
	# 			count += 1
	proceed = False
	for nextbutton in soup.find_all('a',class_='active') :
		if nextbutton.find('img')['src'].find('next') != -1 :
			url = 'http://www.codechef.com' + nextbutton['href']
			proceed = True
			#print nextbutton['href']
		
print "Number of distinct users with 100 points : ",count
