<?php 
class SQLQuery {
  
  protected $_dbHandle;
  protected $_result;

  /* Connect to the DB */
  function connect($address, $account, $pwd, $name) {
    $this->_dbHandle = @mysql_connect($address, $account, $pwd);
    if ($this->_dbHandle != 0) {
      if (mysql_select_db($name, $this->_dbHandle)) {
        return 1;
      } else {
        return 0;
      }
    } else {
      return 0;
    }
  }

  function disconnect() {
    if (@mysql_close($this->_dbHandle) != 0) {
      return 1;
    } else {
      return 0;
    }
  }

  function selectAll() {
    $query = "SELECT * from '" . $this->_table . "'";
    return $this->query($query);
  }

  function select($id) {
     $query = 'select * from `'.$this->_table.'` where `id` = \''.mysql_real_escape_string($id).'\'';
     return $this->query($query, 1);
  }

  /* Custom SQL Query */
  /* 

    Suppose our SQL Query is something like:

    SELECT table1.field1 , table1.field2, table2.field3, table2.field4 FROM table1,table2 WHERE ….

    Now what our script does is first find out all the output fields and their corresponding tables and place them in arrays – $field 
    and $table at the same index value. For our above example, $table and $field will look like

    $field = array(field1,field2,field3,field4);
    $table = array(table1,table1,table2,table2);

    The script then fetches all the rows, and converts the table to a Model name (i.e. removes the plural and capitalizes 
    the first letter) and places it in our multi-dimensional array and returns the result. 
    The result is of the form $var['modelName']['fieldName']. 
  */

  function query($query, $singleResult = 0) {

    $this->_result = mysql_query($query, $this->_dbHandle);

    # Case insensitive matching for string "select"
    if (preg_match("/select/i", $query)) {
      $result = array();
      $table = array();
      $field = array();
      $tempResults = array();
      $numOfFields = mysql_num_fields($this->_result);
      for ($i=0; $i < $numOfFields; $i++) {
        array_push($table, mysql_field_table($this->_result, $i));
        array_push($field, mysql_field_name($this->_result, $i));
      }

      while ($row = mysql_fetch_row($this->_result)) {
        for ($i = 0;$i < $numOfFields; ++$i) {
          $table[$i] = trim(ucfirst($table[$i]),"s");
          $tempResults[$table[$i]][$field[$i]] = $row[$i];
        }
        if ($singleResult == 1) {
          mysql_free_result($this->_result);
          return $tempResults;
        }
        array_push($result,$tempResults);
      }

      mysql_free_result($this->_result);
      return($result);
    }
  }

  # Get number of rows
  function getNumRows() {
    return mysql_num_rows($this->_result);
  }
  
  function freeResult() {
    mysql_free_result($this->_result);
  }

  function getError() {
    return mysql_error($this->_dbHandle);
  }
}







