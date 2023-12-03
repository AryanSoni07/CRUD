<?php
$insert=false;
$update=false;
$delete=false;


$servername="localhost";
$username="root";
$password="";
$db="notes";

$conn=mysqli_connect($servername,$username,$password,$db);
if(!$conn)
{
    die("Connection was not successful : ".mysqli_connect_error());
}

if(isset($_GET['delete'])){
  $sno=$_GET['delete'];
  $delete = true;
  $sql="DELETE FROM `notes` WHERE `notes`.`Sno` = $sno";
    $result=mysqli_query($conn,$sql);
}

if($_SERVER['REQUEST_METHOD']=='POST')
{
  if (isset($_POST['snoEdit']))
  {
    $Sno=$_POST["snoEdit"];;
    $title=$_POST["titleEdit"];
    $description=$_POST["descriptionEdit"];
    $sql="UPDATE `notes` SET `Title` = '$title' , `Description`='$description' WHERE `notes`.`Sno` = $Sno";
    $result=mysqli_query($conn,$sql);
    if($result){
      $update=true;
    }
  }
  else{
    $title=$_POST["title"];
    $description=$_POST["description"];
    $sql="INSERT INTO `notes` (`Title`, `Description`, `DateTime`) VALUES ('$title', '$description', current_timestamp())";
    $result=mysqli_query($conn,$sql);
    
    if($result)
    {
        $insert=true;
    }
    else
    {
        echo "Record was not inserted successfully. <br>ERROR--->";
        echo mysqli_error($conn);
    }
  }

}
?>


<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    

  <title>PHP CRUD NOTES</title>
</head>

<body>
  

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModal">Edit Record</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form action="/CRUD/index.php" method="post">
          <input type="hidden" name="snoEdit" id="snoEdit">
      <div class="form-group">
        <label for="title">Title</label>
        <input type="title" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
      </div>
      <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Add Note</button>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
    </form>
        </div>
        
      </div>
    </div>
  </div>



  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Notes</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact Us</a>
        </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>
    </div>
  </nav>


  <?php

  if($insert)
  {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Note Added Successfully</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  ?>

<?php

if($update)
{
  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Note Updated Successfully</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
</div>';
}
?>

<?php

  if($delete)
  {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Note Deleted Successfully</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
  ?>



  <div class="container">
  <h2>Add a Note</h2>
    <form action="/CRUD/index.php" method="post">
      <div class="form-group">
        <label for="title">Title</label>
        <input type="title" class="form-control" id="title" name="title" aria-describedby="emailHelp">
      </div>
      <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Add Note</button>
    </form>
  </div>

  <div class="container my-3">
   
    <table class="table" id="myTable">
  <thead>
    <tr>
      <th scope="col">Sno</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php
      $sql="SELECT * FROM `notes`";
      $result=mysqli_query($conn,$sql);
      $no=0;
      while($row=mysqli_fetch_assoc($result))
    {
        $no++;
        echo "<tr>
        <th scope='row'>".$no."</th>
        <td>".$row['Title']."</td>
        <td>".$row['Description']."</td>
        <td>"."<button class='edit btn btn-sm btn-primary' id= ".$row['Sno'].">Edit</button> <button class='delete btn btn-sm btn-primary' id= d".$row['Sno'].">Delete</button>"."</td>
      </tr>";
    }
    ?>
    
  </tbody>
</table>
  </div>
  <hr>
  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
    crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
      let table = new DataTable('#myTable');
    </script>

    <script>
        edits=document.getElementsByClassName('edit');
        Array.from(edits).forEach((element)=>{
          element.addEventListener("click",(e)=>{
            console.log("edit");
            tr=e.target.parentNode.parentNode;
            title=tr.getElementsByTagName("td")[0].innerText;
            description=tr.getElementsByTagName("td")[1].innerText;
            console.log(title,description);
            titleEdit.value=title;
            descriptionEdit.value=description;
            snoEdit.value=e.target.id;
            $('#editModal').modal('toggle');
          })
        })

        deletes=document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element)=>{
          element.addEventListener("click",(e)=>{
            console.log("edit");
            sno=e.target.id.substr(1);
            if(confirm("Are you sure you want to delete this note?")){
              console.log("Yes");
              window.location=`/CRUD/index.php?delete=${sno}`
            }
            else{
              console.log("no");
            }
          })
        })

    </script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    -->
</body>

</html>