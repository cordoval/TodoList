<?php 

namespace Fazer;

//The actual implementation is just this file. Others are usage and tests.

class TodoList extends \ArrayObject 
{
    protected $db;
    protected $create;
    protected $select;
    protected $insert;
    protected $update;
    protected $delete;
    
    public function __construct(\PDO $db, $listName) 
    {
        $this->initSQLs($listName);
        $this->db = $db;
        $db->exec($this->create);
        $data = $db->query($this->select, \PDO::FETCH_KEY_PAIR)->fetchAll();
        parent::__construct($data, static::ARRAY_AS_PROPS);
    }
    public function offsetSet($task, $status) 
    {
        if (!isset($this[$task]))
            $this->db->prepare($this->insert)->execute(array($task, $status));
        else
            $this->db->prepare($this->update)->execute(array($status, $task));
        parent::offsetSet($task, $status);
    }
    public function offsetUnset($task) 
    {
        $this->db->prepare($this->delete)->execute($task);
        if (parent::offsetExists($task)) {
            parent::offsetUnset($task);
        }
    }

    /**
     * initialize SQLs
     * 
     * @param $listName
     */
    private function initSQLs($listName)
    {
        $this->create = 'CREATE TABLE IF NOT EXISTS '.$listName.' (name VARCHAR(32) PRIMARY KEY, status INT)';
        $this->select = 'SELECT * FROM '.$listName;
        $this->insert = 'INSERT INTO '.$listName.' VALUES (?,?)';
        $this->update = 'UPDATE '.$listName.' SET status = ? WHERE name = ?';
        $this->delete = 'DELETE FROM '.$listName.' WHERE name = ?';
    }

    public function getCreate()
    {
        return $this->create;
    }

    public function getSelect()
    {
        return $this->select;
    }

    public function getInsert()
    {
        return $this->insert;
    }

    public function getUpdate()
    {
        return $this->update;
    }

    public function getDelete()
    {
        return $this->delete;
    }
}