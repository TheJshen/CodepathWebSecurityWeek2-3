<?php require_once('../../../private/initialize.php'); ?>

<?php
if(!isset($_GET['id'])) {
  redirect_to('index.php');
}
$id = $_GET['id'];
$territory_result = find_territory_by_id($id);
// No loop, only one result
$territory = db_fetch_assoc($territory_result);
$state_id = $territory['state_id'];
?>

<?php $page_title = 'Staff: Territory of ' . $territory['name']; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="../states/show.php?id=<?php echo h(s(u($territory['state_id'])))?>">Back to State Details</a>
  <br />

  <h1>Territory: <?php echo h(s($territory['name'])); ?></h1>

  <?php
    echo "<table id=\"territory\">";
    echo "<tr>";
    echo "<td>Name: </td>";
    echo "<td>" . h(s($territory['name'])) . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>State ID: </td>";
    echo "<td>" . h(s($territory['state_id'])) . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Position: </td>";
    echo "<td>" . h(s($territory['position'])) . "</td>";
    echo "</tr>";
    echo "</table>";

    db_free_result($territory_result);
  ?>
  <br />
  <a href="edit.php?id=<?php echo h(s(u($territory['id'])))?>">Edit</a><br />

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
