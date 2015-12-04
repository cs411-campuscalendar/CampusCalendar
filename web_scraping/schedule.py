import urllib.request
from urllib.request import FancyURLopener

thisurl = "http://fightingillini.com/schedule.aspx?path=wbball"

class MyOpener(FancyURLopener):
    version = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36'

openurl = MyOpener().open

html = openurl(thisurl).read()

print(html)