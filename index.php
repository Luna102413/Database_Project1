<?php
$mn = intval(filter_input(INPUT_GET, 'mn'));
$cn = intval(filter_input(INPUT_GET, "cn"));

require_once('config.php');

//the connection to the database
$conn = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

//if there's no connection...
if (!$conn) 
{
  die('Could not connect: ' . mysqli_connect_error());
}

//store table names in array
$tblArr = array();
$tblArr[] = "student";
$tblArr[] = "course";
$tblArr[] = "section";
$tblArr[] = "grade_report";
$tblArr[] = "prerequisite";

$table_name = $tblArr[$mn];

//retrieve all columns for the current table
$sql = "SHOW COLUMNS FROM $table_name";
$result1 = mysqli_query($conn, $sql);

//while there's a connection, store fields in array
while ($record = mysqli_fetch_array($result1)) 
{
    $fields[] = $record['0'];
}

$optArr = array();
$optArr[] = "Student";
$optArr[] = "Course";
$optArr[] = "Section";
$optArr[] = "Grade Report";
$optArr[] = "Prerequisite";

$column_names = array();

$query = "SELECT * FROM  $table_name ORDER BY $fields[$cn]";
$result2 = mysqli_query($conn, $query);

while ($line = mysqli_fetch_array($result2, MYSQL_ASSOC))
{
    $i = 0;
    foreach ($line as $column_name)
    {
        $column_names[$i][] = $column_name;
        $i++;
    }
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
                    <?php for($i = 0; $i < count($optArr); $i++) {?>
                    <li class="nav-item <?php if ($i == $mn) echo 'active'; ?>">
                        <a class="nav-link" href="?mn=<?php echo "$i"?>"><?php echo ucwords(str_replace("_", " ",$optArr[$i]))?></a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
        <main role="main" class="containter">
            <!-- where data will be displayed in a table -->
            <table class="table">
                <thead>
                    <tr>
                        <!-- displays the column names for each database, when selected -->
                        <?php for ($i = 0; $i < count($fields); $i++) { ?>
                            <th style="width: 8em"><?php print $fields[$i]; ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- displays the data from selected table -->
                    <?php for ($j = 0; $j < count($column_names[0]); $j++) { ?>
                        <tr>
                            <?php for ($k = 0; $k < count($fields); $k++) { ?>
                                <td>
                                    <?php print $column_names[$k][$j]; ?>
                                </td>
                            <?php } ?>
                            <td>
                                <div>
                                    <!-- buttons for when user wants to edit/delete a row in the table -->
                                    <button class="btn btn-secondary" onClick="editButton()"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger" onClick="deleteButton()"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>

                    <!-- Up and down arrows for sorting data -->
                    <!-- <button type="button" onClick="arrowKeys()"><i class="fa fa-arrow-up"></i></button>
                    <button type="button" onClick="arrowKeys()"><i class="fa fa-arrow-down"></i></button> -->
                    <?php for ($i = 0; $i < count($fields); $i++) { ?>
                    <td style="width: 8em"><input type="button" onclick="sortCurrentField(<?php print $mn; ?>,<?php print $i; ?>)" value="Sort"/></td>
                    <?php } ?>
                    
                </tbody>
                <tfooter>
                    <tr>
                        <td>
                            <div>
                                <!-- adding a new row -->
                                <button type="button" value="Yes" OnClick="newRow(this)">New row</button>
                                
                                <?php for ($k = 0; $k < count($fields); $k++) { ?>
                                    <td>
                                        <div id="dvTextBox" style="display:none">
                                            <input type="text" id="txtBox"/>
                                        </div>
                                    </td>
                                <?php } ?>
                            </div>   
                        </td>
                    </tr>
                </tfooter>
            </table>
        </main>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        
        <script type="text/javascript">
            function newRow(btnNewRow)
            {
                var dvTextBox = document.getElementById("dvTextBox");
                dvTextBox.style.display = btnNewRow.value == "Yes" ? "block" : "none";
            }

            function deleteButton()
            {
                
            }

            function editButton()
            {
                
            }

            // function for sorting the column data
            function sortCurrentField(u,v) 
            {
                document.location.href = "index.php?mn=" + u + "&cn=" + v;
            }

        </script>
    </body>
</html>
<!-- closing the connection -->
<?php mysqli_close($conn) ?>
