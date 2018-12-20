<?php // treehouseChallenge

  error_reporting(E_ALL); 
  ini_set('display_errors', 1); 

  $hn = 'localhost';
  $un = 'john';
  $pw = 'password';
  $db = 'newsletter'; 

  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die("Fatal Error");

  if (isset($_POST['name']) && isset($_POST['email']))
  {
    if ($stmt = $conn->prepare('INSERT INTO subscribers VALUES(?, ?, ?)'))
    {
        $id = null; 
        $name = $_POST['name']; 
        $email = $_POST['email']; 

        $stmt->bind_param('iss', $id, $name, $email); 
        $stmt->execute(); 
        printf("%d Row inserted.\n", $stmt->affected_rows); 
    } 
    else 
    {
        $error = $conn->errno . ' ' . $conn->error; 
        echo $error; 
    }
    
  }
  

  class Form 
  {
      public $method, $action, $js_validation; 
      public function __construct($method, $action) 
      {
          $this->method = $method; 
          $this->action = $action; 
          $this->js_validation = false; 
      }

      public function render_form()
      {
          if ($this->js_validation == true)
          {
              echo "Did this hit?"; 
              $validationScript = <<<_END
              <script>
              function makeAlert() {
                 alert("Did this work?"); 
              }

              makeAlert(); 
            </script>
_END;
          } else
          {
              $validationScript = ""; 
          }
          return <<<_END
            $validationScript; 
            <form action=$this->action method=$this->method>
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
  $conn->close();  
?>