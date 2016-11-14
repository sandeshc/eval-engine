import os
import socket
import urllib
import urllib2
import httplib
import ConfigParser
from lxml import html
import MySQLdb as mysqldb
from urllib import urlencode, quote
from collections import OrderedDict

SOM = 1
LASER = 0
NOTOPRES = 5
HTTP_RETRIES = 5

# disabling proxy for localhost
os.environ['NO_PROXY'] = '127.0.0.1'

# establishing database connection
sqlconf = ConfigParser.ConfigParser()
sqlconf.read('../sql-config.ini')
db = mysqldb.connect(sqlconf.get('database', 'server'), sqlconf.get('database', 'username'), 
        sqlconf.get('database', 'password'), sqlconf.get('database', 'dbname'))
cursor = db.cursor()

def getPage(url):
    global HTTP_RETRIES
    attempt = 0
    while attempt <= HTTP_RETRIES:
        attempt += 1
        try: 
            pagesource = urllib2.urlopen(url).read()
            break
        except urllib2.HTTPError:
            attempt = HTTP_RETRIES + 1
            break
        except urllib2.URLError:
            continue
        except httplib.HTTPException:
            continue
        except socket.error:
            continue
    if attempt > HTTP_RETRIES:
        pagesource = "<html></html>"
    page = html.fromstring( pagesource )
    return page

def arxivTitle(x):
    url = 'http://arxiv.org/abs/hep-th/' + x
    page = getPage(url)
    return page.xpath('//h1[@class="title mathjax"]/text()')[0].strip()

def laserParser(latexQuery):
    global NOTOPRES
    head = 'http://127.0.0.1/laser-search/index.php'
    query = head + '?' + urlencode( OrderedDict( mathSnippet=latexQuery ) )
    page = getPage(query)
    pageTitles = [ arxivTitle(x) for x in page.xpath('//div[@class="search-result"]/center/a/text()')[:NOTOPRES] ]
    pageLinks = page.xpath('//div[@class="search-result"]/center/a/@href')[:NOTOPRES]
    pageContext = [ '$$' + x.strip() + '$$' for x in page.xpath('//div[@class="search-result"]/math/@alttext')[:NOTOPRES] ]
    results = zip(pageTitles, pageLinks, pageContext)
    if len(results) != NOTOPRES:
        print 'results < NOTOPRES', 'laserParser'
        exit(0)
    return results

def searchOnMathParser(latexQuery):
    global NOTOPRES
    head = 'http://www.searchonmath.com/result'
    query = head + '?' + urlencode( OrderedDict( equation=latexQuery ) )
    page = getPage(query)
    pageTitles = [ x.replace('_', ' ') for x in page.xpath('//section[@class="page_content"]/article[@class="result"]/h2/a/text()')[:NOTOPRES] ]
    pageLinks = page.xpath('//section[@class="page_content"]/article[@class="result"]/h2/a/@href')[:NOTOPRES]
    pageContext = [ x.strip() for x in page.xpath('//section[@class="page_content"]/article[@class="result"]/div/div/text()')[:NOTOPRES] ]
    results = zip(pageTitles, pageLinks, pageContext)
    if len(results) != NOTOPRES:
        print 'results < NOTOPRES', 'searchOnMathParser'
        exit(0)
    return results

def transform(value):
    value = value.replace('<=', '\\le ')
    value = value.replace('>=', '\\ge ')
    value = value.replace('<', '\\lt ')
    value = value.replace('>', '\\gt ')
    value = value.replace('\\left\\lt', '\\left<')
    value = value.replace('\\left\\gt', '\\left>')
    value = value.replace('\\right\\lt', '\\right<')
    value = value.replace('\\right\\gt', '\\right>')
    value = value.replace('%\n', '')
    value = value.replace('\\', '\\\\')
    value = value.replace("'", "\\'")
    value = value.encode('utf-8')
    return value

def insertIntoTableGetId(table, value):
    value = transform(value)
    query = "SELECT id FROM " + table + " WHERE value = '" + value + "' LIMIT 1;"
    # print query
    cursor.execute(query)
    qresult = cursor.fetchall()
    if len(qresult) > 0:
        return str(qresult[0][0])
    else:
        query = "INSERT INTO " + table + " (value) VALUES('" + value + "');"
        # print query
        cursor.execute(query)
        query = "SELECT id FROM " + table + " WHERE value = '" + value + "' LIMIT 1;"
        # print query
        cursor.execute(query)
        return str(cursor.fetchall()[0][0])

def insertIntoDB(qid, queryResults, sysTyp):
    rank = 0
    for result in queryResults:
        rank += 1 
        did = insertIntoTableGetId('docs', result[1] + ' ' + result[0]) # TODO: to ignore/consider the document title, for now ignored
        query = "INSERT INTO modelresults (systyp, qid, rank, did, context) VALUES(" + ",".join(["'%s'" % sysTyp, qid, str(rank), did, "'%s'" % transform(result[2])]) + ")"
        # print query
        cursor.execute(query)

def main():
    global SOM
    global LASER
    queryFile = open('queries.txt', 'r')
    for latexQuery in queryFile:
        latexQuery = latexQuery.strip()
        print latexQuery
        qid = insertIntoTableGetId('queries', '$' + latexQuery + '$')
        somQR = searchOnMathParser(latexQuery)
        insertIntoDB(qid, somQR, SOM)
        laserQR = laserParser(latexQuery)
        insertIntoDB(qid, laserQR, LASER)
    db.commit()

if __name__ == '__main__':
    main()
