import requests
import csv
from datetime import datetime

def make_request(name, description, date, location):
	payload = {"name":name,
		"description":description,
		"date":date,
		"location":location
	}

	url="http://campuscalendar.web.engr.illinois.edu/add_event.php"

	r = requests.post(url, params=payload)
	print(r.url, r.text)



#make_request("CS 411 Project Meeting 2","Start project demo work","2015-10-17","Seibel Atrium")
file_name = "C:/Users/neeas_000/Documents/UIUC/CS 411/baseball.csv"
with open(file_name, 'rb') as csvfile:
	spamreader = csv.reader(csvfile, delimiter=',', quotechar='"')

	header = True

	for row in spamreader:
		#print ', '.join(row)
		if header:
			header=False
			continue
		name = "Baseball"

		date = datetime.strptime(row[2], '%m.%d.%y')

		date = date.strftime("%Y-%m-%d")

		description = "Game against " + row[1]
		location = row[10]
		make_request(name, description, date, location)