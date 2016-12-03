# Generic Search Engine Evaluation System

**`eval-engine`** is a **user-feedback based evaluation system**, 
the basis for which has been discussed in brief and recored in `txt/eval-basis.txt`. While an instantiation of this system was used as an evalutaion module for [LaSer](https://github.com/biswajitsc/LaSer), viz. available in the `laser-eval` branch, the `master` branch contains a generic version of the same.

----

| Type   | Filename              | Description                                                                    |
|:------ |:--------------------- |:------------------------------------------------------------------------------ |
|        | index.php             | index page of the website, contains guidelines & login                         |
|        | init.php              | sets up the cookies for the webpage (er and query info)                        |
|        | get-users.php         | helper for index.php; returns list of usernames that match a given prefix      |
|        | json-util.php         | helper for get-users.php; converts object (primitive/list/dict) to JSON object |
|        | evaluation.php        | php page intended to display model results to users; recored their feedback    |
| PHP    | eval-submit.php       | helper for evaluation.php; converts form into db insert queries                |
|        | sql-helper.php        | helper functions to connect, query db and manipulate data                      |
|        | thanks.php            | php page displayed at the end of evaluation                                    |
|        |                       |                                                                                |
| CSS    | css/index.css         | stylesheet for index.php                                                       |
|        |                       |                                                                                |
| CONFIG | sql-config.ini        | config file containing system specific parameters                              |
|        |                       |                                                                                |
|        | sql/init-database.sql | set of create table cmds to initialize the db                                  |
| SQL    | sql/sample-data.sql   | sample representative dummy data for experimental purpose                      |
|        | sql/reset-db.sql      | set of drop table cmds to clear the db                                         |
|        |                       |                                                                                |
|        | txt/eval-basis.txt    | breif on the "basis" of this user-based evaluation system                      |
| TXT    | txt/todo.txt          | todo list, set of uncompleted features                                         |
|        | txt/changes.txt       | changes to be made in the db of `1aser-eval` to be compatible with new db type |
|        |                       |                                                                                |
| PY     | score.py              | python script that generates scores for all evaluated systems                  |
|        | crawl-math/           | python script(s) for crawl and dump for mathematical equations based search    |
|        |                       |                                                                                |

----

## Usage

1. Install `LAMP` stack as `Apache`, `MySQL` and `PHP` are required. *(For linux users : [LAMP](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu))*
2. Make a copy of the `sql-config-template.ini` as `sql-config.ini`
3. Make a db for the system, initialize using cmds from sql/
4. Fill the information about the db and data in `sql-config.ini`
5. Host folder on the Apache server and viola !
