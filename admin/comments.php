<?php require_once('include/top.php');?>

<?php
  if(!isset($_SESSION['username'])){
    header('Location: login.php');
}
   else if(isset($_SESSION['username']) && $_SESSION['role'] == 'author'){
     header('Location: index.php');
}


$session_username = $_SESSION['username'];

 if(isset($_GET['delete'])){
     $delete_id = $_GET['delete'];
     $delete_check_query = "SELECT * FROM comments WHERE id = $delete_id";
     $delete_check_run = mysqli_query($con, $delete_check_query);
     if(mysqli_num_rows($delete_check_run) > 0){
        $delete_query = "DELETE FROM `comments` WHERE `comments`.`id` = $delete_id";
     
     if(isset($_SESSION['username']) && $_SESSION['role'] == 'admin'){
         
       if(mysqli_query($con, $delete_query)){
           $msg = "Comment has Been Deleted";
        }
      else{
         $error = "Comment has not been Deleted";
      }
    }
  }
   else{
       header('location:index.php');
   }
}


if(isset($_GET['approve'])){
     $approve_id = $_GET['approve'];
     $approve_check_query = "SELECT * FROM comments WHERE id = $approve_id";
     $approve_check_run = mysqli_query($con, $approve_check_query);
     if(mysqli_num_rows($approve_check_run) > 0){
        $approve_query = "UPDATE `comments` SET `status` = 'approve' WHERE `comments`.`id` = $approve_id";
     
     if(isset($_SESSION['username']) && $_SESSION['role'] == 'admin'){
         
       if(mysqli_query($con, $approve_query)){
           $msg = "Comment has been Approved";
        }
      else{
         $error = "Comment has not been Aprroved";
      }
    }
  }
   else{
       header('location:index.php');
   }
}

if(isset($_GET['unapprove'])){
     $unapprove_id = $_GET['unapprove'];
     $unapprove_check_query = "SELECT * FROM comments WHERE id = $unapprove_id";
     $unapprove_check_run = mysqli_query($con, $unapprove_check_query);
     if(mysqli_num_rows($unapprove_check_run) > 0){
        $unapprove_query = "UPDATE `comments` SET `status` = 'pending' WHERE `comments`.`id` = $unapprove_id";
     
     if(isset($_SESSION['username']) && $_SESSION['role'] == 'admin'){
         
       if(mysqli_query($con, $unapprove_query)){
           $msg = "Comment has been Unapproved";
        }
      else{
         $error = "Comment has not been Unaprroved";
      }
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
            
            $bulk_delete_query = "DELETE FROM `comments` WHERE `comments`.`id` = $user_id";
            
            mysqli_query($con, $bulk_delete_query);
        }
        else if($bulk_option == 'approve'){
            
            $bulk_author_query = "UPDATE `comments` SET `status` = 'approve' WHERE `comments`.`id` = $user_id";
            
            mysqli_query($con, $bulk_author_query);
        }
        else if($bulk_option == 'pending'){
            
            $bulk_admin_query = "UPDATE `comments` SET `status` = 'pending' WHERE `comments`.`id` = $user_id";
            
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
            <h1><i class="fa fa-comments"></i> Comments <small>View All Comments </small></h1><hr> 
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-tachometer"></i> Dashboard</a></li>
              <li class="active"><i class="fa fa-comments"></i> Comments</li>
            </ol> 
            
       <?php
         if(isset($_GET['reply'])){
            $reply_id = $_GET['reply'];
            $reply_check = "SELECT * FROM comments WHERE post_id = $reply_id";
            $reply_check_run = mysqli_query($con, $reply_check);
            if(mysqli_num_rows($reply_check_run) > 0){
            if(isset($_POST['reply'])){
            $comment_data = $_POST['comment'];
            if(empty($comment_data)){
               $comment_msg = "Comment Must be filled";
            }
            else{
              $get_user_data = "SELECT * FROM users WHERE username = '$session_username'";
              $get_user_run = mysqli_query($con, $get_user_data);
              $get_user_row = mysqli_fetch_array($get_user_run);                             
              $date = time();
              $first_name = $get_user_row['first_name'];
              $last_name = $get_user_row['last_name'];
              $full_name = "$first_name $last_name";
              $email = $get_user_row['email'];
              $image = $get_user_row['image'];
                             
              $insert_comment_query = "INSERT INTO comments(date,name,
                    username,post_id,email,image,comment,status) VALUES
                    ('$date','$full_name','$session_username',
                    '$reply_id','$email','$image','$comment_data',
                    'approve')";
                             
                    if(mysqli_query($con, $insert_comment_query)){
                        $comment_msg = "Comment has been Submitted";
                
                          header('location: comments.php');
                    }
                    else{
                        $comment_error = "Comment has not been Submitted";
                    }
                             
                 }
               } 
            ?>
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="comment">Comment:*</label>
                            <?php
                              if(isset($comment_msg)){
                                  echo "<span class='pull-right' style='color:green;'>$comment_msg</span>";
                              }
                            else if(isset($comment_error)){
                                  echo "<span class='pull-right' style='color:red;'>$comment_error</span>";
                              }
                            ?>
                            <textarea name="comment" id="comment" cols="30" rows="10" placeholder="Your Comment should be here" class="form-control"></textarea>
                        </div>
                        <input type="submit" name="reply" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <hr>
            
            <?php
                  }
              }
             $query = "SELECT * FROM comments ORDER BY id DESC";
             $run = mysqli_query($con, $query);
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
                               <option value="approve">Approve</option> 
                               <option value="pending">Unapprove</option>       
                            </select>
                        </div> 
                      </div>
                      <div class="col-xs-8">
                           <input type="submit" class="btn btn-success" value="Apply">  
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
                      <th>Username</th>
                      <th>Comments</th>
                      <th>Status</th>
                      <th>Approve</th>
                      <th>Unapprove</th>
                      <th>Reply</th>
                      <th>Delete</th>
                  </tr>
              </thread>
              <tbody>
                 <?php
                   while($row = mysqli_fetch_array($run)){
                       $id = $row['id'];
                       $username = $row['username'];
                       $status = $row['status'];
                       $comment = $row['comment'];
                       $post_id = $row['post_id'];
                       $date = getdate($row['date']);
                       $day = $date['mday'];
                       $month = substr($date['month'],0,3);
                       $year = $date['year'];
                  ?>
                  <tr>
                      <td><input type="checkbox" class="checkboxes" name="checkboxes[]" value="<?php echo $id;?>"></td>
                      <td><?php echo $id;?></td>
                      <td><?php echo "$day $month $year";?></td>
                      <td><?php echo $username;?></td>
                      <td><?php echo $comment;?></td>
                      <td><span style="color:<?php 
                          if($status == 'approve'){
                              echo 'green';
                          }
                          else if($status == 'pending'){
                           echo 'red';
                         }
                      ?>"><?php echo ucfirst($status);?></span></td>
                      <td><a href="comments.php?approve=<?php echo $id;?>">Approve</a></td>
                      <td><a href="comments.php?unapprove=<?php echo $id;?>">Unapprove</a></td>
                      
                      <td><a href="comments.php?reply=<?php echo $post_id;?>"><i class="fa fa-reply"></i></a></td>
                      <td><a href="comments.php?delete=<?php echo $id;?>"><i class="fa fa-times"></i></a></td>
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