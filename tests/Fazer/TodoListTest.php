<?php

namespace Fazer;

class TodoListTest extends \PHPUnit_Framework_TestCase {
    
    const CREATE = 'CREATE TABLE IF NOT EXISTS tasks (name VARCHAR(32) PRIMARY KEY, status INT)';
    const SELECT = 'SELECT * FROM tasks';
    const INSERT = 'INSERT INTO tasks VALUES (?,?)';
    const UPDATE = 'UPDATE tasks SET status = ? WHERE name = ?';
    const DELETE = 'DELETE FROM tasks WHERE name = ?';
    
    public function setUp($data = array())
    {       
        $this->db = $this->getMock('PDO', array('exec', 'query', 'prepare'), array('sqlite::memory:'));
        $this->db->expects($this->once())
             ->method('exec')
             ->with($this->equalTo(self::CREATE, \PDO::FETCH_KEY_PAIR));
        $this->stm = $this->getMock('PDOStatement', array('fetchAll', 'execute'));
        $this->stm->expects($this->once())
              ->method('fetchAll')
              ->will($this->returnValue($data));
        $this->db->expects($this->once())
             ->method('query')
             ->with(self::SELECT)
             ->will($this->returnValue($this->stm));
        $this->todo = new TodoList($this->db, 'tasks');
    }
    public function testConstructor() {
        $this->setUp(array('Foo' => true, 'Bar' => false));
        $this->assertCount(2, $this->todo);
        $this->assertEquals($this->todo['Foo'], true);
        $this->assertEquals($this->todo['Bar'], false);
    }
    public function testOffsetSetForNonExistingTask() {
        $this->stm->expects($this->once())
                  ->method('execute')
                  ->with($this->equalTo(array('Baz', true)));
        $this->db->expects($this->once())
                 ->method('prepare')
                 ->with($this->equalTo(self::INSERT))
                 ->will($this->returnValue($this->stm));
        $this->todo['Baz'] = true;
    }
    public function testOffsetSetForExistingTask() {
        $this->setUp(array('Baz' => false));

        $this->stm->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(array(true, 'Baz')));
        $this->db->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo(self::UPDATE))
            ->will($this->returnValue($this->stm));

        $this->todo['Baz'] = true;
    }
    public function testOffsetUnSetForNonExistingTask() {
        $this->stm->expects($this->once())
            ->method('execute')
            ->with($this->equalTo('Baz'));
        $this->db->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo(self::DELETE))
            ->will($this->returnValue($this->stm));
        unset($this->todo['Baz']);
    }
    public function testOffsetUnSetForExistingTask() {
        $this->setUp(array('Baz' => false));

        $this->stm->expects($this->once())
            ->method('execute')
            ->with($this->equalTo('Baz'));
        $this->db->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo(self::DELETE))
            ->will($this->returnValue($this->stm));
        unset($this->todo['Baz']);
    }
}
