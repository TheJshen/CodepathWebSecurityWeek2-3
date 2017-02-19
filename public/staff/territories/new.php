<?php
require_once('../../../private/initialize.php');

// Set default values for all varaibles the page needs.

$errors = array();
$territory = array(
  'name' => '',
  'state_id' => '',
  'position' => ''
);

if(isset($_GET['id'])) {
  $territory['state_id'] = $_GET['id'];
}

if(is_post_request()) {

  if(isset($_POST['name'])) { $territory['name'] = $_POST['name']; }
  if(isset($_POST['position'])) { $territory['position'] = $_POST['position']; }

  $result = insert_territory($territory);
  if($result === true) {
    $new_id = db_insert_id($db);
    redirect_to('show.php?id=' . u($new_id));
  } else {
    $errors = $result;
  }
}
?>
<?php $page_title = 'Staff: New Territory'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="../states/show.php?id=<?php echo h(s($_GET['id']))?>">Back to State Details</a><br />

  <h1>New Territory</h1>

  <?php echo display_errors($errors); ?>

  <form action="new.php?id=<?php echo h(s(u($territory['state_id']))); ?>" method="post">
    Territory Name:<br />
    <input type="text" name="name" value="<?php echo h(s($territory['name'])); ?>" /><br />
    Position:<br />
    <input type="text" name="position" value="<?php echo h(s($territory['position'])); ?>" /><br />
    <br />
    <input type="submit" name="submit" value="Create" />
  </form>
</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
