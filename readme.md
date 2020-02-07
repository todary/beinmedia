# Project Title
 Task Bein Media

## TASK
We have a json file of contacts composed of the following fields:
id: the primary key
names: array of strings
hits: array of integers (tells how many times a name is registered)
Suppose that file contains contacts with Arabic and English names, and we need to translate those names from Arabic to English and vice-versa.
What should you do?
Make a code to read the file and save it’s data to a MySQL DataBase.
Translate from Google Translate API.
Use this API key if you want “AIzaSyDWDkFwa69skrgjQbj2HUkXIah3zZB9bqI”.
Save the translated names to MySQL DataBase.
Increase hits count related to the name after translation by one.
Don’t translate duplicate names but increase it’s hits.
Please take into account the best approach to reduce the time, resources usage and cost of the process
The attached sample is just a snapshot from a very large scale file we will use.

## Installation
Using Composer :

```
composer install
```

If you don't have composer, you can get it from [Composer](https://getcomposer.org/)


To migrate Database after set Database name and access in .env file
```
php artisan migrate

```

make sure for file job if not make this commint
```
php artisan queue:table
php artisan migrate
```


## How to  Run the application
run job Queues
```
php artisan queue:listen
```


this application used by call route 

##### to upload file
```
url :- http://localhost/beinmedia/api/upload
method :- post
paramter :- file_json
type :-file    
```




##### to get names
```
url :- http://localhost/beinmedia/api/names
method :- get  
```




### used packages

https://github.com/landrok/language-detector
https://packagist.org/packages/stichoza/google-translate-php




