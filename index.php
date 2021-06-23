<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";
$insert = false;
$update = false;
$del = false;
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die('failed' . mysqli_connect_error());
}

if(isset($_GET['delete'])){
$sno=$_GET['delete'];

$sql="DELETE FROM `notes` WHERE `notes`.`sno` = $sno";
$res=mysqli_query($conn,$sql);

if($res){
   $del=true;
}
else{
    echo 'error '.mysqli_error($conn);
}
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['snoedit'])) {
        $sno = $_POST['snoedit'];
        $title = $_POST["titleedit"];
        $descp = $_POST['descpedit'];
        $sql = "UPDATE `notes` SET `title` = ' $title', `descp` = '$descp' WHERE `notes`.`sno` = $sno";

        $res = mysqli_query($conn, $sql);
        if ($res) {
            $update = true;
        } else {
            echo "failed" . mysqli_error($conn);
        }
    } else {
        $title = $_POST["title"];
        $descp = $_POST['descp'];
        if ($title != null && $descp != null) {
            $sql = "INSERT INTO `notes` (`title`, `descp`) VALUES ('$title', '$descp')";

            $res = mysqli_query($conn, $sql);

            if ($res) {
                $insert = true;
            } else {
                echo "failed" . mysqli_error($conn);
            }
        }
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    <title>i-Notes App</title>
</head>

<body>
    <!-- Button trigger modal -->
    <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Launch demo modal
</button> -->

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/PROJECT/index.php" method="POST">
                        <input type="hidden" name='snoedit' id='snoedit'>
                        <div class="mb-3">
                            <label for="title" class="form-label">Note Title</label>
                            <input type="text" class="form-control" id="titleedit" name="titleedit">

                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Description</label>

                        </div>
                        <div class="form-floating">
                            <textarea class="form-control" id="descedit" name="descpedit" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>

                        </div>
                        <button type="submit" class="btn btn-primary my-3">Update Note</button>
                    </form>
                </div>

            </div>
        </div>
    </div>




    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">i-Notes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <?php
    if ($insert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>success</strong>Record Has been Inserted
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    ?>
    <?php
    if ($update) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>success</strong>updated succesfully
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    ?>
    <?php
    if ($del) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>success</strong>deleted successfully
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    ?>
    <div class="container my-4">
        <h1>Add a Note</h1>

        <form action="/PROJECT/index.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Note Title</label>
                <input type="text" class="form-control" id="title" name="title">

            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Description</label>

            </div>
            <div class="form-floating">
                <textarea class="form-control" id="desc" name="descp" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>

            </div>
            <button type="submit" class="btn btn-primary my-3">Add Note</button>
        </form>
    </div>
    <div class="container my-4">

        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">Sno</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $sql = "SELECT * FROM `notes`";
                $res = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($res)) {


                    echo "<tr>
                            <th scope='row'>" . $i . "</th>
                            <td>" . $row['title'] . "</td>
                            <td>" . $row['descp'] . "</td>
                            <td><button class='btn btn-sm btn-primary edit' id=" . $row['sno'] . ">Edit</button><button class='btn btn-sm btn-primary del' id=d" . $row['sno'] . ">Delete</button>
                           </td>
                        </tr>";
                    $i++;
                }
                ?>


            </tbody>
        </table>

    </div>
    <hr>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js">
    </script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((e) => {
            e.addEventListener("click", (a) => {
                tr = a.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                desc = tr.getElementsByTagName("td")[1].innerText;
                titleedit.value = title;
                descedit.value = desc;
                snoedit.value = a.target.id;

                $('#editModal').modal('toggle');
            });
        });
        del = document.getElementsByClassName('del');
        Array.from(del).forEach((e) => {
            e.addEventListener("click", (a) => {
                sno=a.target.id.substr(1, );
                if (confirm("Are you sure want to delete this note?")) {
                    console.log('res');
                    window.location = `/PROJECT/index.php?delete=${sno}`;
                } else {
                    console.log('bo')
                }
            });
        });
    </script>
</body>

</html>