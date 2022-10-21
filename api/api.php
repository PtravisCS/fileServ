
<?PHP

  header('Content-Type: application/json');

  include 'wlogin.php';
  include './config/database.php';

  $json = file_get_contents('php://input');

  if ($json) {
    $post = json_decode($json);

    if (property_exists($post, 'password')) {
      $password = $post->password; 
    } else {
      $password = "";
    }

    if (property_exists($post, 'username')) {
      $username = $post->username;
    } else {
      $username = "";
    }

    if (property_exists($post, 'action')) {
      $action = $post->action;
    } else {
      $action = null;
    }
  
  } else {

    $password = "";
    $username = "";
    $action = null;

  }

  switch ($action) {

    case "AUTH":
      if (authenticator::authenticate($username, $password) > -1) {

        $response = ['status' => '200', 'response' => array()];
        echo json_encode($response);

      } else {

        $response = ['status' => '401', 'response' => array()];
        echo json_encode($response);

      }
      break;


    case "GET":

      $access_level = authenticator::authenticate($username, $password);

      if ($access_level > -1) {

        $response = get($post, $access_level, $username);
        echo json_encode($response);

      }

      break;

    default:
      $response = ['status' => '400', 'response' => array('details' => 'Request Malformed', 'action' => $action)];
      echo json_encode($response);
      break;

  }

  function get($post, $access_level, $username) {
  

    
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (property_exists($post, 'fields') && property_exists($post, 'status')) {
        
      $fields = $post->fields;
      $status = $post->status;

      $sql = "Select * FROM Tasks where (assigner = ? OR assignee = ?) and open = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($username, $username, $status));

    } else if (property_exists($post, 'fields')) {

      $fields = $post->fields;
      $sql = "Select * FROM Tasks where (assigner = ? OR assignee = ?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($username, $username));

    } else if (property_exists($post, 'status')) {

      $status = $post->status;
      $sql = "Select * FROM Tasks WHERE (assigner = ? OR assignee = ?) and open = ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($username, $username, $status));

    } else {

      $sql = "SELECT * FROM Tasks where (assigner = ? OR assignee = ?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($username, $username));
    }

    $data = $q->fetchAll(PDO::FETCH_ASSOC);
		Database::disconnect();

    $rows = array();

    foreach ($data as $row) {

      $rows['data'][] = $row;

    }


    return $rows;
  }

?>
