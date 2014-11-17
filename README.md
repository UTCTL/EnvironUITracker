# Environ CPI (Control Panel Interface) 

**Purpose**: In its relation to Environ, its purpose is to visualize the collected data per gameplay session. In its general design, this is a multipurpose web-app. 

* [Branches](#branches)
* [Flow](#flow) 
    * [Site Flow](#site-flow) 
* [Front-end Structure](#front-end-structure) 
    * [Static files](#static-files)
    * [Game](#game) 
    * [Visualization](#visualization) 
* [Back-end Structure](#back-end-structure)
    * [Directory Tree](#directory-tree) 
    * [Settings](#settings) 
    * [Models](#models) 
    * [Views](#views) 
    * [Controllers](#controllers) 
        * [Main controller](#main-controller) 
        * [API controller](#api-controller) 
* [Database Structure](#database-structure) 
    * [Logical Model](#logical-model) 
    * [Relational Model](#relational-model) 
    * [DDL](#ddl)


This is an MVC web app to play, promote, and manage Environ. Admins and educators can log in to create class codes to have users play and track their activity for grading and gameplay/ui evaluation. 

This web app applies a modification of the [Descartes Framework](https://github.com/samueleishion/Descartes "Descartes PHP Framework") at least for now. 

## Branches

## Flow
##### Site Flow 
 ![Site flow image](siteflow.png)

## Front End Structure 
#### Static Files 
#### Game
#### Visualization 

## Back End Structure

#### Directory Tree
+ __controllers__
  + __libs__
        +  *functions.php*: has global functions: clean, encode, now, encodequotes, etc. 
        +  *html.php*: has html wrappers like header, footer, navigation bar, etc. 
    + *controller.php*: handles url patterns given by .htaccess on root. 
    + *operator.php*: handles data transfer/connection requests. works as a private api. 
    + *settings.php*: handles database connection, path information and session initiation. 
+ __models__: holds the models for the application. 
+ __static__: holds static files. scripts, styles, images, etc. 
+ __views__: holds views for the application. 

#### Settings

There are 7 variables that should be set to get the site running on any server. 
* `$sitename`: The name of the site or brand. 
* `$siteurl`: The domain for the website. 
* `$dbhost`: The database host. 
* `$dbuser`: The username for the database. 
* `$dbpass`: The password for the database. 
* `$dbname`: The database name. 
* `$_SESSION['DESpath']`: The path in your server to reach this site. This variable currently requires `$sitename` to be properly set for two reasons: 
    * This variable assumes `$sitename` to be the name of the websites' directory. 
    * It's important to set this site on its own server, especially if you plan to have multiple Descartes-based projects on the server. 
    * This variable can also be reset to any other path you choose (without having to use `$sitename`). 

To better illustrate `$_SESSION['DESpath']`:
```php
$sitename = 'MYSite'; 
$_SESSION['DESpath'] = '/'.$sitename.'/'; 
/* assumes website is located at serverroot/MYSite */
```

#### Models
In a typical MVC style web site your models not only represent the pages' information but more importantly, they are an object-oriented representation of the database's tables. On our particular case, we have 4 models: 
* `User`: allows the front end to easily handle user actions (log in, log out, edit, delete, etc). 
* `UserType`: smaller class in the `User` model file that allows us to distinguish what type of user is a given user (educator/admin) 
* `Session`: allows the front end and the api to easily handle sessions (load data, save data, store temporary data, etc). 
* `Classcode`: smaller class in the `Session` model file that allows us to store sessions based on corresponding classes/courses. 


#### Views
The views are the html web pages that the user gets to see. Their main function is to load the data by calling the models and render it with html. There are 5 views so far: 
* `About`: consists of the About page. It loads no models. 
* `Home`: consists of the Home page. It does discriminate between users to show different content: 
    * If user is not logged in, it shows the splash page. This case loads no models. 
    * If user is logged in and it's an educator, it redirects to the `Tracker` view (_report_ page). 
    * If user is logged in and it's an admin, it shows the admin panel. This case loads the `User` model. 
* `Login`: consists of the login form page. It loads no models. 
    * If the user is logged in, it redirects to the _home_ page. 
* `Play`: consists of the page with the game. It loads no models. 
* `Tracker`: consists of the Tracker data visualization. It loads no models. 
    * This parts doesn't load any models because the data is dynamically loaded via ajax requests. For more information check out the [Front End's Visualization](#visualization) and the [Operator Controller](#operator-controller) sections. 

#### Controllers 
##### Main controller 
##### Operator controller
##### API Controller 

For more information on how the back-end works, take a look at the [Descartes Framework documentation](https://github.com/samueleishion/Descartes "Descartes PHP Framework")

## Database Structure

#### Logical Model 
There are a total of 4 models so far. The interaction is as follows: 

![Logical Model](_bkp/logical_model.png) 

#### Relational Model 
The following model shows more concrete relationships with foreign keys: 

![Relational Model](_bkp/relational_model.png) 

#### DDL
```
CREATE
  TABLE classcodes
  (
    id int NOT NULL,
    classcode text NOT NULL,
    users_id int NOT NULL,
    cname text NOT NULL, 
    PRIMARY KEY (`id`) 
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE
  TABLE sessions
  (
    id int NOT NULL,
    sessionid text NOT NULL,
    accessdatetime datetime NOT NULL,
    name text NOT NULL,
    spent_funds float NOT NULL,
    spent_polcap float NOT NULL,
    last_level int NOT NULL,
    playtime float NOT NULL,
    status    int NOT NULL,
    completed int NOT NULL,
    clicks text NOT NULL,
    drag    int NOT NULL,
    arrows  int NOT NULL,
    wasdkeys    int NOT NULL,
    minimap int NOT NULL,
    numkeys  int NOT NULL,
    panel_time_open float NOT NULL,
    panel_time_close float NOT NULL,
    panel_clicks_open  int NOT NULL,
    panel_clicks_close int NOT NULL,
    region_data text NOT NULL,
    classcodes_id int NOT NULL, 
    PRIMARY KEY (`id`) 
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE
  TABLE types
  (
    id int NOT NULL,
    tname text, 
    PRIMARY KEY (`id`) 
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE
  TABLE users
  (
    id int NOT NULL,
    uname text NOT NULL,
    pword text NOT NULL,
    types_id int, 
    PRIMARY KEY (`id`) 
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

ALTER TABLE classcodes ADD CONSTRAINT classcodes_users_FK FOREIGN KEY (
users_id ) REFERENCES users ( id ) ;

ALTER TABLE sessions ADD CONSTRAINT sessions_classcodes_FK FOREIGN KEY (
classcodes_id ) REFERENCES classcodes ( id ) ;

ALTER TABLE users ADD CONSTRAINT users_types_FK FOREIGN KEY ( types_id )
REFERENCES types ( id ) ;
```
