

<?php
require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Approval</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        table{
            max-width: 80%;
    margin: auto;
    margin-top: 5%;
        }
    </style>
</head>

<body>
    <div class="center">
        <h1>Uploaded Capstones</h1>

        <table class='table table-striped'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Date Created</th>
                <th>File Content</th>
                <th>Date File Uploaded</th>
                <th>Action</th>
            </tr>
            </thead>

            <?php
            $query = "SELECT * FROM upload_capstones WHERE status = 'pending' ORDER BY UpCapstoneID ASC";
            $result = mysqli_query($connect, $query);
            while ($row = mysqli_fetch_array($result)) {
            ?>
            <tbody>
                <tr>
                    <td><?php echo $row['UpCapstoneID']; ?></td>
                    <td><?php echo $row['Capstone_Title']; ?></td>
                    <td><?php echo $row['Date_Created']; ?></td>
                    <td><?php echo $row['FileContent']; ?></td>
                    <td><?php echo $row['Date_File_Uploaded']; ?></td>
                    <td>
                        <form action="prof-approval.php" method="post">
                            <input type="hidden" name="capstoneID" value="<?php echo $row['UpCapstoneID']; ?>">

                            <Button type='submit' ame="approve" value="Approve" class='btn btn-success'>Accept</Button>
                    <Button  type='submit' name="reject" value="Reject" class='btn btn-danger' >Reject</Button>
                        </form>
                    </td>
                    

                </tr>
                </tbody>
            <?php } ?>
        </table>
    </div>

    <?php
    if (isset($_POST['approve'])) {
        $UpCapstoneID = $_POST['capstoneID'];

        $select = "UPDATE upload_capstones SET status = 'approved' WHERE UpCapstoneID = '$UpCapstoneID'";
        $result = mysqli_query($connect, $select);

        echo "<script>alert('Capstone Approved!')</script>";
        header("Refresh: 1; url='prof-approval.php'");
    }
    if (isset($_POST['reject'])) {
        $UpCapstoneID = $_POST['capstoneID'];

        //delete file from folder
        $query = "SELECT * FROM upload_capstones WHERE UpCapstoneID = '$UpCapstoneID'";
        $res = mysqli_query($connect, $query);
        while ($row = mysqli_fetch_array($res)) {
            // echo $row['FileContent'];
            unlink('capstones/' . $row['FileContent']);
        }

        $select = "DELETE FROM upload_capstones WHERE UpCapstoneID = '$UpCapstoneID'";
        $result = mysqli_query($connect, $select);
        echo "<script>alert('Capstone Rejected.')</script>";


        header("Refresh: 1; url='prof-approval.php'");
    }
    ?>
</body>

</html>
