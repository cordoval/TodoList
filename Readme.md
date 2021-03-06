SfCon2012 Community Project by @alganet
---------------------------------------

[![Build Status](https://secure.travis-ci.org/cordoval/TodoList.png?branch=master)](http://travis-ci.org/cordoval/TodoList)

This project was somehow hinted by Augusto's presentation on TDD at SfCon2012 in Bello Horizonte, Brasil.

This approach here on the repo is Alexandre's approach [here](https://gist.github.com/2976029) to a similar problem. Not exactly TDD but good testing practices
and mocking.

The current idea is to make this gist into a proper repo and contribute as a community as an exercise to put in practice
what we have all learned at the conference, foster collaboration, resolve doubts, and perhaps take it to the next level.

base code by @alganet, @augustohp had a TDD approach, and the other talks are all inspirting this repo.

Usage
=====

```
<?php

$todo = new TodoList(new PDO('mysql:host=localhost;dbName=tasks', 'user', 'pass'));

$todo['Implement the todo list'] = true; //done it!
$todo['Create some tests'] = false;      //not done yet
unset($todo['Make a gist']);             //remove this one
```