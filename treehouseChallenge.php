<?php // treehouseChallenge

  error_reporting(E_ALL); 
  ini_set('display_errors', 1); 

  $hn = 'localhost';
  $un = 'john';
  $pw = 'password';
  $db = 'newsletter'; 

  $dbHelper = new DatabaseHelper($hn, $un, $pw, $db); 
  $dbHelper->getAllSubscribers(); 
  if (isset($_POST['name']) && isset($_POST['email'])) {
    $dbHelper->insertSubscribers($_POST['name'], $_POST['email']); 
  }
  
  class DatabaseHelper {
      public $conn;
      
      public function __construct($hn, $un, $pw, $db) {
          $this->conn = new mysqli($hn, $un, $pw, $db);
          if ($this->conn->connect_error) die("Fatal Error"); 
      }

      public function insertSubscribers($name, $email) {
          if ($stmt = $this->conn->prepare('INSERT INTO subscribers VALUES(?, ?, ?)')) {
              $id = null;
              $stmt->bind_param('iss', $id, $name, $email); 
              $stmt->execute(); 
          }
          else {
              $error = $this->conn->errno . ' ' . $conn->error;
              echo $error; 
          }
      }

      public function getAllSubscribers() {
          $query = 'SELECT * FROM subscribers'; 
          $result = $this->conn->query($query); 
          $rows = $result->num_rows; 
          for ($j = 0; $j < $rows; ++$j) {
              $row = $result->fetch_array(MYSQLI_ASSOC); 
              echo 'Name ' . htmlspecialchars($row['name']) . '<br>';
          }
          $result->close(); 
      }
  }

  class Form {
      public $method, $action, $jsValidation, $jsValidationScript; 

      public function __construct($method, $action) {
          $this->method = $method; 
          $this->action = $action; 
          $this->jsValidation = true; 
      }

      public function setJsFormValidation($validationEnabled) {
          if ($validationEnabled == true) {
              $this->jsValidationScript = <<<_END
              <script>
              function validate() {
                  if (document.forms['newsletterForm'].name.value === "") {
                      alert("Please provide a name!");
                      return false; 
                  } 
                  if (document.forms['newsletterForm'].email.value === "") {
                      alert("Please provide an email!")
                      return false; 
                  }
                  let email = document.forms['newsletterForm'].email.value; 
                  let atpos = email.indexOf('@');
                  let dotpos = email.lastIndexOf('.'); 
                  if (atpos < 1 || (dotpos - atpos < 2) ) {
                      alert("Please enter a valid email."); 
                      return false; 
                  }
                  return true; 
              }
            </script>
_END;
          } else {
             $this->validationScript = "<script> function validate() { return null; } </script>" ;
          }
      }

      public function render_form() {
          if ($this->jsValidation == true) {
              $validationScript = <<<_END
              <script>
              function validate() {
                  if (document.forms['newsletterForm'].name.value === "") {
                      alert("Please provide a name!");
                      return false; 
                  } 
                  if (document.forms['newsletterForm'].email.value === "") {
                      alert("Please provide an email!")
                      return false; 
                  }
                  let email = document.forms['newsletterForm'].email.value; 
                  let atpos = email.indexOf('@');
                  let dotpos = email.lastIndexOf('.'); 
                  if (atpos < 1 || (dotpos - atpos < 2) ) {
                      alert("Please enter a valid email."); 
                      return false; 
                  }
                  return true; 
              }
            </script>
_END;
          } else {
              $validationScript = "<script> function validate() { return null; } </script>" ; 
          }
          return <<<_END
            $this->validationScript
            <form action=$this->action method=$this->method onsubmit="return(validate())" name="newsletterForm">
              Please fill out this form to subscribe to the newsletter:
              Enter Name: <input type="text" name="name">
              Enter Email: <input type="text" name="email">
              <input type="submit" value="Subscribe">
            </form>
_END;
      }
  }

  $new_form = new Form("POST", "treehouseChallenge.php"); 
  echo $new_form->render_form();
  $dbHelper->conn->close(); 
?>