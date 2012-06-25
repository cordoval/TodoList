<?php

$todo = new TodoList(new PDO('mysql:host=localhost;dbName=tasks', 'user', 'pass'));

$todo['Implement the todo list'] = true; //done it!
$todo['Create some tests'] = false;      //not done yet
unset($todo['Make a gist']);             //remove this one