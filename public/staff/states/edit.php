<?php
require_once('../../../private/initialize.php');

if(!isset($_GET['id'])) {
  redirect_to('index.php');
}
$states_result = find_state_by_id($_GET['id']);
// No loop, only one result
$state = db_fetch_assoc($states_result);

// Set default values for all variables the page needs.
$errors = array();

if(is_post_request()) {
  if(isset($_POS['name'])) { $state['name'] = $_POST['name']; }
  if(isset($_POS['code'])) { $state['code'] = $_POST['code']; }
  if(isset($_POS['country_id'])) { $state['country_id'] = $_POST['country_id']; }

  $result = update_state($state);
  $errors = on_db_success($result);

}
?>
<?php $page_title = 'Staff: Edit State ' . $state['name']; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="index.php">Back to States List</a><br />

  <h1>Edit State: <?php echo $state['name']; ?></h1>

  <form action="new.php" method="post">
    State Name:<br />
    <input type="text" name="name" value="<?php echo $state['name']; ?>" /><br />
    State Code:<br />
    <input type="text" name="code" value="<?php echo $state['code']; ?>" /><br />
    Country ID:<br />
    <input type="text" name="country_id" value="<?php echo $state['country_id']; ?>" /><br />
    <br />
    <input type="submit" name="submit" value="Update" />
  </form>
</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
