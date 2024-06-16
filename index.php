<?php
$insert = false;
$delete = false;
$update = false;

// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Ensure this is correct for your MySQL setup
$database = "inote"; // Specific database used 

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    // Output the specific connection error for debugging
    die("There was some problem: " . mysqli_connect_error());
}

// Handling form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['snoEdit'])) {
        // Update the record
        $sno = $_POST['snoEdit'];
        $title = $_POST['titleEdit'];
        $description = $_POST['descriptionEdit'];

        // SQL query to be executed for update
        $sql = "UPDATE `inote` SET `title` = '$title', `description` = '$description' WHERE `sno` = $sno";
        $result = mysqli_query($conn, $sql);

        // Check if the record was updated successfully
        if ($result) {
            $update = true;
        } else {
            echo "Record has not been updated: " . mysqli_error($conn);
        }
    } else {
        // Insert new record
        $title = $_POST['title'];
        $description = $_POST['description'];

        // SQL query to be executed for insertion
        $sql = "INSERT INTO `inote` (`title`, `description`, `tstamp`) VALUES ('$title', '$description', current_timestamp())";
        $result = mysqli_query($conn, $sql);

        // Check if the record was inserted successfully
        if ($result) {
            $insert = true;
        } else {
            echo "Record has not been inserted: " . mysqli_error($conn);
        }
    }
}

// Delete functionality
if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];

    // SQL query to delete a record
    $sql = "DELETE FROM `inote` WHERE `sno` = $sno";
    $result = mysqli_query($conn, $sql);

    // Check if the record was deleted successfully
    if ($result) {
        $delete = true;
    } else {
        echo "Record deletion failed: " . mysqli_error($conn);
    }
}

// Close the connection
mysqli_close($conn);
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <title>CRUD Project</title>
    
</head>

<body>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/CRUD-project/index.php" method="POST">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="form-group">
                            <label for="title">Note Title</label>
                            <input type="text" class="form-control" name="titleEdit" id="titleEdit"
                                aria-describedby="titleHelp">
                        </div>
                        <div class="form-group">
                            <label for="description">Note Description</label>
                            <textarea class="form-control" name="descriptionEdit" id="descriptionEdit"
                                rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this note?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="deleteLink">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/CRUD-project/index.php"><img src="/CRUD-project/PHP-logo.svg" height="30px" alt=""></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#">About <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#">Contact us <span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <!-- Success/Error Alerts -->
    <div id="alertContainer" class="container my-4">
        <?php
        // Display success or error alerts with auto close using JavaScript
        if ($insert) {
            echo "<div id='insertAlert' class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Inserted Successfully!</strong> Your note has been successfully added.
                </div>";
        }
        if ($delete) {
            echo "<div id='deleteAlert' class='alert alert-danger alert-dismissible fade show' role='alert'>
                    <strong>Deleted Successfully!</strong> Your note has been successfully deleted.
                </div>";
        }
        if ($update) {
            echo "<div id='updateAlert' class='alert alert-warning alert-dismissible fade show' role='alert'>
                    <strong>Updated Successfully!</strong> Your note has been successfully updated.
                </div>";
        }
        ?>
    </div>

    <div class="container my-4">
        <h2> Add a note to iNote App </h2>

        <form action="/CRUD-project/index.php" method="POST">
            <div class="form-group">
                <label for="title">Note Title</label>
                <input type="text" class="form-control" name="title" id="title" aria-describedby="titleHelp"
                    placeholder="Enter title...">
                <small id="titleHelp" class="form-text text-muted">Enter your Note Title...</small>
            </div>
            <div class="form-group">
                <label for="description">Note Description</label>
                <textarea class="form-control" placeholder="Enter Description..." name="description" id="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>

    <div class="container my-4">
        <table class="table" id="myTable">
            <thead>
            <tr>
            <th scope="col" style="width: 10%">S.No</th>
            <th scope="col" style="width: 20%">Title</th>
            <th scope="col" style="width: 40%">Description</th>
            <th scope="col" style="width: 15%">Actions</th> <!-- Adjusted width for Actions column -->
        </tr>
            </thead>
            <tbody>

            <?php
            // Re-open the connection to fetch and display data
            $conn = mysqli_connect($servername, $username, $password, $database);

            $sql = "SELECT * FROM `inote`";
            $result = mysqli_query($conn, $sql);
            $no = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $no = $no + 1;
                echo "<tr>
                    <th scope='row'>" . $no . "</th>
                    <td>" . $row['title'] . "</td>
                    <td>" . $row['description'] . "</td>
                    <td>
                        <button class='btn btn-sm btn-primary edit' data-toggle='modal' data-target='#editModal' data-sno='" . $row['sno'] . "'>Edit</button>
                        <button class='btn btn-sm btn-danger delete' data-toggle='modal' data-target='#deleteModal' data-sno='" . $row['sno'] . "'>Delete</button>
                    </td>
                </tr>";
            }

            // Close the connection
            mysqli_close($conn);
            ?>
            </tbody>
        </table>
    </div>
    <hr>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDzwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTables
            $('#myTable').DataTable();

            // Edit button click handler
            $('.edit').click(function () {
                var sno = $(this).data('sno');
                var title = $(this).closest('tr').find('td:eq(0)').text().trim();
                var description = $(this).closest('tr').find('td:eq(1)').text().trim();

                $('#snoEdit').val(sno);
                $('#titleEdit').val(title);
                $('#descriptionEdit').val(description);
            });

            // Delete button click handler
            $('.delete').click(function () {
                var sno = $(this).data('sno');
                $('#deleteLink').attr('href', '/CRUD-project/index.php?delete=' + sno);
            });

            // Auto close alerts after 3 seconds
            setTimeout(function () {
                $('#insertAlert, #deleteAlert, #updateAlert').alert('close');
            }, 3000);
        });
    </script>
</body>

</html>
