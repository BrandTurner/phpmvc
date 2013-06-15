<?php
class Template {

  protected $variables = array();
  protected $_controller;
  protected $_action;

  function __construct($controller, $action) {
    $this->_controller = $controller;
    $this->_action = $action;
  }

  # Set Variables
  function set($name, $value) {
    $this->variables[$name] = $value;
  }

  # Render the template
  function render() {
    # This function takes an array and makes the keys into variables and the values into the variable's value
    extract($this->variables);

    # If there is not a specified header & footer in the view/controllerName directory, grabs the global header
    # and footer.
    if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php')) {
      include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php');
    } else {
      include (ROOT . DS . 'application' . DS . 'views' . DS . 'header.php');
    }

    include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php');       
     
    if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php')) {
      include (ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php');
    } else {
       include (ROOT . DS . 'application' . DS . 'views' . DS . 'footer.php');
    }
  }
}