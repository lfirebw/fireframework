# fireframework [![version](https://img.shields.io/badge/version-1.0.1-red.svg)](https://semver.org)
FireFramework is a simple easy mvc framework for build any app and website

#### History
This web framework is created for personal uses, after two years working and upgrading some features, the framework was release to public and any interested in web developing with php

## Features
* Easy Installation
* Simple and easy MVC Arquitecture
* Simple routing system
* Easy API integration
* Layout system

**Coming soon more features**

# Installation

######  1) First Clone the repository 1) First Clone the repository 

```bash
git clone https://github.com/lfirebw/fireframework.git
```
The project structure is:
>├── app
│   └── config
│   └── controllers
│   └── javascript
│   └── middleware
│   └── models
│   └── styles
│   └── views
│   │   └── layout
├── public
│   └── assets
│   │   └── css
│   │   └── js
│   │   └── fonts
│   │   └── img
├── vendor
│   └── core
│   └── database
│   └── helpers
├── storage

###### 2) Then of clone the project, open config folder and modify file of general configuration:
>├── app
│   └── config
**│   │   └── general.php**

```php
return array(
    'root' => '/',
    'layout' => 'default',
    'route' => '/app',
    'isLogin' => false,
    'onlyRouting' => false
);
```
- **root**: is root path folder eg:  if our project is hosted like localhost/myproject/ then root parameter is "/myproject"
- **layout**: indicate the layout will you use
- **route**: default path of route file, this is path for find the route file on your project
- **isLogin**: if you project need a login system then set to ***true***
- **onlyRouting**: if your project required navigate only with your routing configurated then set to ***true***

###### 3) Configure the database connection:
>├── app
│   └── config
│   │   └── general.php
**│   │   └── db.php**

```php
return array(
	"host" => 'localhost',
	"user" => 'root', 
	"password" => 'root',
	"dbname" => 'test',
	"port" => '3306',
	"charset" => 'utf-8',
	"prefix" => ''
);
```
###### 3) Configure the site information:
>├── app
│   └── config
│   │   └── general.php
│   │   └── db.php
**│   │   └── site.php**

```php
return array(
  "name_app" => "Fire Framework",
  "description" => "FireFramework is an MVC framework web created by emmyseco All Right Reserved",
  "keywords" => array("webapp","framework"),
  "copyright" => "All Right Reserved www.emmyseco.com 2010 - 2018"
);
```
## License
[MIT](https://choosealicense.com/licenses/mit/)