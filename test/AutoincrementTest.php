<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelAutoincrementTest
 *
 * @group autoincrement
 * @author suleymanmelikoglu
 */
class ModelAutoincrementTest extends ControllerTestCase {

    private $dbName = "masterdb_test";
    private $collectionName = "counters";
    private $collectionInTest = "test";
    
    private function _deleteAll() {
        $m = new Mongo();
        $db = $this->dbName;
        $collection = $this->collectionName;
        $m->$db->$collection->remove();
        
    }
    
    private function _delete($param) {
        $m = new Mongo();
        $db = $this->dbName;
        $collection = $this->collectionName;
        $m->$db->$collection->remove($param);
        
    }

    public function testIncrementSimple() {
        $this->_deleteAll();
        
        $db = "masterdb_test";
        $collection = $this->collectionInTest;
        $a = new Model_Mongodb_Autoincrement($collection, $db);
        
        $n = $a->getNext();
        $this->assertEquals(1, $n);
        
        $n = $a->getNext();
        $this->assertEquals(2, $n);
        
        $n = $a->getNext();
        $this->assertEquals(3, $n);
        
        $n = $a->getNext();
        $this->assertEquals(4, $n);
    }
    
    public function testIncrementGetNextUniqueNumber() {
        $this->_deleteAll();
        
        $db = "masterdb_test";
        $collection = $this->collectionInTest;
        $a = new Model_Mongodb_Autoincrement($collection, $db);
        
        // add 7 rows
        for ($i = 1; $i <= 7; $i++)
            $a->getNextUnique();
        
        // delete third row 
        $a->removeId(3);
        
        // next unique available should be 3
        $n = $a->getNextUnique();
        $this->assertEquals(3, $n);
        
        // next available should be 8
        $n = $a->getNextUnique();
        $this->assertEquals(8, $n);
        
    }
    
    public function testIncrementGetNextUniqueNumberLongerGap() {
        $this->_deleteAll();
        
        $db = "masterdb_test";
        $collection = $this->collectionInTest;
        $a = new Model_Mongodb_Autoincrement($collection, $db);
        
        // add 7 rows
        for ($i = 1; $i <= 7; $i++)
            $a->getNextUnique();
        
        // delete third and 4th row 
        $a->removeId(3);
        $a->removeId(4);
        
        // next unique available should be 3
        $n = $a->getNextUnique();
        $this->assertEquals(3, $n);
        
        // next unique available should be 4
        $n = $a->getNextUnique();
        $this->assertEquals(4, $n);
        
        // next available should be 8
        $n = $a->getNextUnique();
        $this->assertEquals(8, $n);
        
    }
    
    public function testIncrementGetNextUniqueNumberLongerGapUnsorted() {
        $this->_deleteAll();
        
        $db = "masterdb_test";
        $collection = $this->collectionInTest;
        $a = new Model_Mongodb_Autoincrement($collection, $db);
        
        // add 7 rows
        for ($i = 1; $i <= 40; $i++)
            $a->getNextUnique();
        
        // delete third and 4th row 
        $a->removeId(31);
        $a->removeId(4);
        
        // next unique available should be 3
        $n = $a->getNextUnique();
        $this->assertEquals(4, $n);
        
        // next unique available should be 4
        $n = $a->getNextUnique();
        $this->assertEquals(31, $n);
        
        // next available should be 8
        $n = $a->getNextUnique();
        $this->assertEquals(41, $n);
        
    }
    
}

