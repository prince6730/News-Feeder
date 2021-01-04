<?php require_once('include/top.php');

if(!isset($_SESSION['username'])){
    header('Location:login.php');
  }
else if(isset($_SESSION['username']) && $_SESSION['role'] == 'author'){
     header('Location: index.php');
  }

if(isset($_GET['edit'])){
    $edit_id = $_GET['edit'];
}

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    
    if(isset($_SESSION['username']) and $_SESSION['role'] == 'admin'){
        
      $delete_query = "DELETE FROM categories WHERE id = '$delete_id'";
       if(mysqli_query($con, $delete_query)){
        $delete_msg = "Category has been Deleted"; 
    }
    else{
        $delete_error = "Category has not been Deleted";
    }
  }
}

if(isset($_POST['submit'])){
    $cat_name = mysqli_real_escape_string($con, strtolower($_POST['cat-name']));
    
    if(empty($cat_name)){
        $error = "Categories Must be Field";
    }
  else{  
    $check_query = "SELECT * FROM categories WHERE category = '$cat_name'";
    $check_run = mysqli_query($con, $check_query);
    if(mysqli_num_rows($check_run) > 0){
        
        $error = "Category Already Exit";
    }
    else{
        $insert_query = "INSERT INTO categories (category) VALUES ('$cat_name')";
        if(mysqli_query($con, $insert_query)){
            
            $msg = "Categories has been Added";
        }
        else{
            $error ="Categories has not been Added";
        }
     }
   }
}

if(isset($_POST['update'])){
    $cat_name = mysqli_real_escape_string($con, strtolower($_POST['cat-name']));
    
    if(empty($cat_name)){
        $up_error = "Categories Must be Field";
    }
  else{  
    $check_query = "SELECT * FROM categories WHERE category = '$cat_name'";
    $check_run = mysqli_query($con, $check_query);
    if(mysqli_num_rows($check_run) > 0){
        
        $up_error = "Category Already Exit";
    }
    else{
        $update_query = "UPDATE `categories` SET `category` =                 '$cat_name' WHERE `categories`.`id` = $edit_id";
        if(mysqli_query($con, $update_query)){
            
            $up_msg = "Categories has been Updated";
        }
        else{
            $up_error ="Categories has not been Updated";
        }
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
            <h1><i class="fa fa-folder-open"></i> Categories<small>Different Categories </small></h1><hr> 
            <ol class="breadcrumb">
                <li><a href="index.html"><i class="fa fa-tachometer"></i> Dashboard</a></li> 
              <li class="active"><i class="fa fa-folder-open"></i> Categories</li>
            </ol> 
            
           <div class="row">
              <div class="col-md-6">
                <form action="" method="post">
                        <div class="form-group">
                            <label for="category">Category Name:</label>
                            <?php
                              if(isset($msg)){
                                  echo "<span class='pull-right' style='color:green;'>$msg</span>";
                              }
                            else if(isset($error)){
                                  echo "<span class='pull-right' style='color:red;'>$error</span>";
                              }
                            ?>
                            <input type="text" placeholder="Category Name" class="form-control" name="cat-name">
                        </div>
                        <input type="submit" value="Add Category" name="submit" class="btn btn-primary">
                        
                    </form>
                    
                <?php
                 if(isset($_GET['edit'])){
                  $edit_check_query = "SELECT * FROM categories WHERE id = $edit_id";
                  $edit_check_run = mysqli_query($con,$edit_check_query);
                  if(mysqli_num_rows($edit_check_run) > 0){
                      
                      $edit_row = mysqli_fetch_array($edit_check_run);
                         $up_category = $edit_row['category'];
                  
                 ?>
               <hr>
                    
              <form action="" method="post">
                <div class="form-group">
                   <label for="category">Update Category Name:</label>
                  <?php
                 if(isset($up_msg)){
                   echo "<span class='pull-right' style='color:green;'>
                           $up_msg</span>";
                }
                else if(isset($up_error)){
                   echo "<span class='pull-right' style='color:red;'>
                   $up_error</span>";
                }
            ?>
            <input type="text" value="<?php echo $up_category;?>" placeholder="Category Name" class="form-control" name="cat-name">
        </div>
            <input type="submit" value="Update Category" name="update" class="btn btn-primary">
                        
    </form>
         <?php }
           }
          ?>          
        </div>
         <div class="col-md-6">
              <?php
                $get_query = "SELECT * FROM categories ORDER BY id DESC";
                $get_run = mysqli_query($con, $get_query);
                if(mysqli_num_rows($get_run) > 0){
                    
                if(isset($delete_msg)){
                  echo "<span class='pull-right' style='color:green;'>
                          $delete_msg</span>";
                }
                else if(isset($delete_error)){
                    echo "<span class='pull-right' style='color:red;'>
                         $delete_error</span>";
                }
                         
              ?>
             <table class="table table-hover table-bordered table-striped">
               <thread>
                <tr>
                     <th>Sr #</th>
                    <th>Category Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
              </thread>
            <tbody>
                <?php
                   while($get_row = mysqli_fetch_array($get_run)){
                     
                       $category_id = $get_row['id'];
                       $category_name = $get_row['category'];
                ?>
                  <tr>
                    <td><?php echo $category_id;?></td>
                    <td><?php echo ucfirst($category_name);?></td>
                    
                    <td><a href="categories.php?edit=<?php echo $category_id;?>"><i class="fa fa-pencil"></i></a></td>
                    <td><a href="categories.php?delete=<?php echo $category_id;?>"><i class="fa fa-times"></i></a></td>
                 </tr>
                 <?php } ?> 
             </tbody>
           </table>
           <?php
             }
            else{
               echo "<center><h3>No Categories Found</h3></center>";
             }
           ?>
              </div>
            </div>
        </div>
    </div>
</div>
    
<?php require_once('include/footer.php'); ?>