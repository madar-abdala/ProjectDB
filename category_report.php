<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Category Report</title>

  <?php
  include 'lip/head.php';
  include 'lip/conn.php';

  // Get ID
  $id = isset($_GET['idd'])
    ? mysqli_real_escape_string($conn, $_GET['idd'])
    : '';
  ?>
</head>

<style>
  body {
    background: whitesmoke;
  }

  .container {
    background: white;
    min-height: 900px;
    padding: 30px;
  }
</style>

<body>

  <div class="container">

    <div class="row">

      <table class="table table-bordered">

        <thead>
          <tr>
            <th>Category ID</th>
            <th>Category Name</th>
          </tr>
        </thead>

        <tbody>

          <?php
          $read = mysqli_query(
            $conn,
            "SELECT * FROM menu_categories WHERE category_id='$id'"
          );

          while ($row = mysqli_fetch_assoc($read)) {
            ?>

            <tr>
              <td><?php echo $row['category_id']; ?></td>
              <td><?php echo $row['category_name']; ?></td>
            </tr>

          <?php } ?>

        </tbody>

      </table>

    </div>

  </div>

</body>

</html>