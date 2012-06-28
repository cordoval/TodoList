<?php 

namespace Fazer;

//The actual implementation is just this file. Others are usage and tests.

class TodoList extends \ArrayObject 
{
    const CREATE = 'CREATE TABLE IF NOT EXISTS tasks (name VARCHAR(32) PRIMARY KEY, status INT)';
    const SELECT = 'SELECT * FROM tasks';
    const INSERT = 'INSERT INTO tasks VALUES (?,?)';
    const UPDATE = 'UPDATE tasks SET status = ? WHERE name = ?';
    const DELETE = 'DELETE FROM tasks WHERE name = ?';

    protected $db;

    public function __construct(\PDO $db) 
    {
        $this->db = $db;
        $db->exec(static::CREATE);
        $data = $db->query(static::SELECT, \PDO::FETCH_KEY_PAIR)->fetchAll();
        parent::__construct($data, static::ARRAY_AS_PROPS);
    }
    public function offsetSet($task, $status) 
    {
        if (!isset($this[$task]))
            $this->db->prepare(static::INSERT)->execute(array($task, $status));
        else
            $this->db->prepare(static::UPDATE)->execute(array($status, $task));
        parent::offsetSet($task, $status);
    }
    public function offsetUnset($task) 
    {
        $this->db->prepare(static::DELETE)->execute($task);
        if (parent::offsetExists($task)) {
            parent::offsetUnset($task);
        }
    }
}