*************************
*        hello          *
*      everybody        *
*                       *
*************************

Project Background:
 
Before I started this project i did not know what no_sql was. However, 
I got frustrated that for every new web app I was making I had to make
a new database and tables .. and blahblahblah.. using mysql manager that
takes time.. and is quite annoying. I wanted a way to create all that 
on one page and still be able to have my test app access the database, 
a bit normally.

Granted It is possible to run the create database code or create table
code serverside, I thought that just having one table on the server that
served everyapplication would not only save space, and time. This would 
especially benefit applications that are under testing and dont have lots
of data to upload.

Upon doing more research online for similar concepts, I got introduced
to the world of No_sql. Realistically nosql implementations are widely
diversified so nosql doesnt have a strict implementation policy so...

Project basics:
-The data is all stored under one table... including indexing data,
which is admitedly risky, and might slow index search time, but 
because of this it is possible to store the data in e.g one text file
with serialized data. The text file search time can be faster than mysql
database search times if the server speed, hard disk read speed e.t.c are 
considered.


What I want:

Well ever since I saw that MongoDB had such a system I was a bit distraught
since I first thought it was unique. And in its current form I did not know
how to commercialize it. So I decided to go the open source route. Lets make
the app flexible enough to store data on any media, txt, sql, mysql dbs,dat files
json, xml etc. Go HAM.. on the code. but if you wish to use it commercially or 
in another place please refer to my GitHub account. Thankyou.