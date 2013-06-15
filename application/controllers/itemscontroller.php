<?php

class ItemsController extends Controller {

  # Setting the variables that will be given to the template
  function view($id = null, $name = null) {
    $this->set('title', $name . 'My Todo List App');
    $this->set('todo', $this->Item->select($id));
  }

  function viewAll() {
    $this->set('title', 'All Items - My Todo List App');
    $this->set('todo', $this->Item->selectAll());
  }

  function add() {
    $todo = $_POST['todo'];
    $this->set('title', 'Success Adding - My Todo List App');
    $this->set('todo',$this->Item->query('insert into items (item_name) values (\''.mysql_real_escape_string($todo).'\')')); 
  }

  function delete($id = null) {
    $this->set('title', 'Success Deleting - My Todo List App');
    $this->set('todo', $this->Item->delete($id));
    #$this->set('todo',$this->Item->query('delete from items where id = \''.mysql_real_escape_string($id).'\''));   
  }
}