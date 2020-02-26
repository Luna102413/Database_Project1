<?php
session_start();

$table = filter_input(INPUT_GET, 'table');
$orderby = filter_input(INPUT_GET, "orderby");

require_once('config.php');

//the connection to the database
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

//if there's no connection...
if (!$conn) 
{
  die('Could not connect: ' . mysqli_connect_error());
}

//store table names in array
$tables = array();
$tables[] = "course";
$tables[] = "grade_report";
$tables[] = "prerequisite";
$tables[] = "section";
$tables[] = "student";

$table_name = $table;
if(!isset($table_name))
{
    $table_name = $tables[0];
}

$fields = array();

//retrieve all columns for the current table
$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = '$table_name'";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $fields[] = $row[0];
}

$query = "SELECT * FROM $table_name" . (isset($orderby) ? " ORDER BY $orderby" : '');
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result))
{
    $data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <!-- title for the database, including the font and display -->
        <title>Manage Data in University Database</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link  rel="stylesheet" type="text/css" href="css/styles.css">
    </head>
    <body>
        <!-- nav bar settings -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#">University Database</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <!-- displays the table names from the database, as well as ignoring underscores -->
                    <?php foreach ($tables as $table_index => $table_value): ?>
                    <li class="nav-item <?php if ($table_value == $table_name) echo 'active'; ?>">
                        <a class="nav-link" href="<?php echo "?table=$table_value" ?>"><?php echo ucwords(str_replace("_", " ",$tables[$table_index]))?></a>
                    </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </nav>
        <main role="main" class="container">
            <!-- where data will be displayed in a table -->
            <table class="table" style="margin: 1.5rem auto;">
                <thead>
                    <tr>
                        <!-- displays the column names for each database, when selected -->
                        <?php foreach($fields as $field_index => $field_value): ?>
                            <th data-index = "<?php echo $field_index ?>" data-value="<?php echo $field_value ?>"><?php echo $field_value ?></th>
                        <?php endforeach ?>
                        <th scope ="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- displays the data from selected table -->
                    <?php foreach($data as $row_index => $row_value): ?>
                        <tr data-row="<?php echo md5(implode(',', $row_value))?>">
                            <?php foreach($row_value as $column_index => $column_value): ?>
                                <td data-index="<?php echo $$column_index ?>" data-value="<?php echo $column_value ?>">
                                    <?php echo $column_value ?>
                                </td>
                            <?php endforeach ?>
                            <td>
                                <div class="btn-group">
                                    <!-- buttons for when user wants to edit/delete a row in the table -->
                                    <button class="btn btn-secondary" onclick="editButton('<?php echo md5(implode(',', $row_value))?>')"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger" onclick="deleteButton('<?php echo md5(implode(',', $row_value))?>')"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <?php foreach ($fields as $field_index => $field_value): ?>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-dark" type="button" onclick="sort('<?php echo $field_value ?>', 'asc')"><i class="fa fa-arrow-up"></i></button>
                                    <button class="btn btn-dark" type="button" onclick="sort('<?php echo $field_value ?>', 'desc')"><i class="fa fa-arrow-down"></i></button>
                                </div>
                            </td>
                        <?php endforeach ?>
                        <td></td>
                    </tr>
                    <tr id="input-row">
                        <?php foreach($fields as $field_index => $field_value): ?>
                        <td>
                                <input class="form-control" type="text" name="<?php echo $field_value ?>" placeholder="<?php echo $field_value ?>" style="display: none !important;">
                        </td>
                        <?php endforeach ?>
                        <td>
                            <button id="action-button" class="btn btn-secondary" onclick="openCreate()">New Row</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </main>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        <script src="js/main.js"></script>
    </body>
</html>
<!-- closing the connection -->
<?php mysqli_close($conn) ?>
