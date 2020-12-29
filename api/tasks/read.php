<?

include_once '../config/database.php';
include_once '../objects/task.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


$database = new Database();
$db = $database->getConnection();
  
$task = new Task($db);

query products
$stmt = $task->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0) {

  // products array
  $tasks_arr=array();
  $tasks_arr["records"]=array();

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    // extract row
    // this will make $row['name'] to
    // just $name only
    extract($row);

    $task_item=array(
      "id" => $id,
      "title" => $title,
      "description" => html_entity_decode($description),
      "assignee" => $assignee,
      "assigner" => $assigner,
      "date_assigned" => $date_assigned,
      "due_date" => $due_date
    );

    array_push($tasks_arr["records"], $task_item);
  }

  // set response code - 200 OK
  http_response_code(200);

  // show products data in json format
  echo json_encode($products_arr);
}

// no products found will be here

?>
