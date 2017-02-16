#!/usr/bin/env python

#run: python3.5 assigne_room.py >> output_log.txt

import requests
import time
print (str(time.strftime("%Y-%m-%d %H:%M"))+":") #print today 
s = requests.Session() # open session
#form data
form_data = "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"_username\"\r\n\r\nadmin\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"_password\"\r\n\r\npassword\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--"

#header 
headers = {
	'Connection': 'keep-alive',
	'content-type': "multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
}

#inicitalize cookies
n_cookies={}
n_cookies["PHPSESSID"] = "78jld6fj72162p6lnmrshimv75"

#login (call twice to login with the cookies)
response = s.post("http://localhost:8000/login",  data = form_data, headers = headers,cookies = n_cookies)
response = s.get("http://localhost:8000/login",  headers = headers,cookies = s.cookies)

#API request /Agreement/assignedRooms/
response = s.post( "http://localhost:8000/Agreement/assignedRooms/", headers = headers,cookies = s.cookies)
print(response.text)
print("\n")




