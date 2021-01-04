<?php require_once('include/top.php');?>

<?php
  if(!isset($_SESSION['username'])){
    header('Location: login.php');
}

$session_username = $_SESSION['username'];

 if(isset($_GET['delete'])){
     $delete_id = $_GET['delete'];
     if($_SESSION['role'] == 'admin'){
        $delete_check_query = "SELECT * FROM posts WHERE id = $delete_id";
        $delete_check_run = mysqli_query($con, $delete_check_query);
     }
     else if($_SESSION['role'] == 'author'){
        $delete_check_query = "SELECT * FROM posts WHERE id = $delete_id and author = '$session_username'";
        $delete_check_run = mysqli_query($con, $delete_check_query);
     }
     if(mysqli_num_rows($delete_check_run) > 0){
        $delete_query = "DELETE FROM `posts` WHERE `posts`.`id` = $delete_id";
              
       if(mysqli_query($con, $delete_query)){
           $msg = "Post has Been Deleted";
        }
      else{
         $error = "Post has not been Deleted";
      }
}
   else{
       header('location:index.php');
   }
}

if(isset($_POST['checkboxes'])){
    
    foreach($_POST['checkboxes'] as $user_id){
        
        $bulk_option = $_POST['bulk-options'];
        
        if($bulk_option == 'delete'){  
            
            $bulk_delete_query = "DELETE FROM `posts` WHERE    `posts`.`id` = $user_id";
            
            mysqli_query($con, $bulk_delete_query);
        }
        else if($bulk_option == 'publish'){
            
            $bulk_author_query = "UPDATE `posts` SET `status` = 'publish' WHERE `posts`.`id` = $user_id";
            
            mysqli_query($con, $bulk_author_query);
        }
        else if($bulk_option == 'draft'){
            
            $bulk_admin_query = "UPDATE `posts` SET `status` = 'draft' WHERE `posts`.`id` = $user_id";
            
            mysqli_query($con, $bulk_admin_query);
               
        }
    }
}
?>
</head>
<body>
   
<div id="wrapper">
<?php require_once('include/header.php'); ?>
    
 <div class="container-fluid body-section">
    <div class="row">
       <div class="col-md-3">
         <?php require_once('include/sidebar.php'); ?>
       </div>
           
        <div class="col-md-9">
            <h1><i class="fa fa-file"></i> Posts <small>View All Posts </small></h1><hr> 
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-tachometer"></i> Dashboard</a></li>
              <li class="active"><i class="fa fa-file"></i> Posts</li>
            </ol> 
            
            <?php
              if($_SESSION['role'] == 'admin'){
                 $query = "SELECT * FROM posts ORDER BY id DESC";
                 $run = mysqli_query($con, $query);
              }
              else if($_SESSION['role'] == 'author'){
                  $query = "SELECT * FROM posts WHERE author = '$session_username' ORDER BY id DESC";
                  $run = mysqli_query($con, $query);
              }
              if(mysqli_num_rows($run) > 0){
            ?>
            
        <form action="" method="post">  
         <div class="row">
            <div class="col-sm-8">
                  <div class="row">
                     <div class="col-xs-4">
                        <div class="form-group">
                           <select name="bulk-options" id="" class="form-control">
                              <option value="delete">Delete</option>
                               <option value="publish">Publish</option> 
                               <option value="draft">Draft</option>       
                            </select>
                        </div> 
                      </div>
                      <div class="col-xs-8">
                           <input type="submit" class="btn btn-success" value="Apply">  
                           <a href="add-post.php" class="btn btn-primary">Add New</a>   
                      </div>
                 </div>
             </div>
            </div>
            <?php
                if(isset($error)){
                    echo "<span class='pull-right' style='color:red'>
                            $error</span>";
                }
                else if(isset($msg)){
                    echo "<span class='pull-right' style='color:green'>
                            $msg</span>";
                 }
            ?>
           
          <table class="table table-hover table-bordered table-striped">
              <thread>
                  <tr>
                      <th><input type="checkbox" id="selectallboxes"></th>
                      <th>Sr #</th>
                      <th>Date</th>
                      <th>Title</th>
                      <th>author</th>
                      <th>Image</th>
                      <th>Categories</th>
                      <th>Views</th>
                      <th>Status</th>
                      <th>Edit</th>
                      <th>Delete</th>
                  </tr>
              </thread>
              <tbody>
                 <?php
                   while($row = mysqli_fetch_array($run)){
                       $id = $row['id'];
                       $title = $row['title'];
                       $author = $row['author'];
                       $views = $row['views'];
                       $categories = $row['categories'];
                       $image = $row['image'];
                       $status = $row['status'];
                       $date = getdate($row['date']);
                       $day = $date['mday'];
                       $month = substr($date['month'],0,3);
                       $year = $date['year'];
                  ?>
                  <tr>
                      <td><input type="checkbox" class="checkboxes" name="checkboxes[]" value="<?php echo $id;?>"></td>
                      <td><?php echo $id;?></td>
                      <td><?php echo "$day $month $year";?></td>
                      <td><?php echo "$title";?></td>
                      <td><?php echo $author;?></td>
                      <td><img src="img/<?php echo $image;?>" width="30px;"></td>
                      
                      <td><?php echo $categories;?></td> 
                      <td><?php echo $views;?></td>
                      <td><span style="color:<?php
                         if($status == 'publish'){
                             echo 'green';
                         }
                         else if($status == 'draft'){
                             echo 'red';
                         }
                          ?>"><?php echo ucfirst($status);?></span></td>
                      <td><a href="edit-post.php?edit=<?php echo $id;?>">
                      <i class="fa fa-pencil"></i></a></td>
                      <td><a href="posts.php?delete=<?php echo $id;?>"><i class="glyphicon glyphicon-remove"></i></a></td>
                  </tr>
                  <?php } ?>
           </tbody>
          </table>
          
          <?php
            }
            else{
               echo "<center><h2>No User Available Now</h2></center>";
            }
          ?>
          </form>
        </div>
    </div>
</div>
    
<?php require_once('include/footer.php'); ?>