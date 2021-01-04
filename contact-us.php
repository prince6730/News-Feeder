<?php 
//error_reporting(0);
require_once('include/top.php'); ?>
  </head>
  <body>
<?php require_once('include/header.php'); ?>
    
    <div class="jumbotron">
        <div class="container">
            <div id="details">
                <h1>Contact <span>Us</span></h1>
                <p>We are available 24x7 so you feel free to contact us.</p>
            </div>
        </div>
        <img src="img/top1%20image.jpg" alt="Top images">
    </div>
    
    <section>
      <div class="container">
        <div class="row">
          <div class="col-md-8">
             <div class="row">
               <div class="col-md-12">
                <iframe width="100%" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%&amp;height=400&amp;hl=en&amp;q=Mumbai%20Mumbai+(Mumbai)&amp;t=&amp;z=12&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe> <a href='https://www.symptoma.com/en/info/covid-19'>Google Maps</a> <script type='text/javascript' src='https://embedmaps.com/google-maps-authorization/script.js?id=d81ff381770ad9e5f0f17100a011b8375eb6e9e9'></script>
              </div>
              
      <div class="col-md-12 contact-form">
        <?php
          if(isset($_POST['submit'])){
            $name = mysqli_real_escape_string($con, $_POST['name']);                  
            $email = mysqli_real_escape_string($con, $_POST['email']);
                                                        
            $website = mysqli_real_escape_string($con, $_POST['website']);                                                 
            $comment = mysqli_real_escape_string($con, $_POST['comment']);                                                
                    
              $to = "princesingh6730gmail.com";
              $header = "From: $name<$email>";
              $subject = "Message from $name";
                    
              $message = "Name: $name \n\n Email: $email \n\n
                    Website: $website \n\n Message: $comment";
                    
             if(empty($name) or empty($email) or empty($comment)){
                 $error = "All Fields are Required";
              }
             else{
                 if(mail($to,$subject,$message,$header)){
                     $msg = "Message has been Sent";
                 }
                 else{
                     $error = "Message has not been Sent";
                 }
                        
              }       
            }
          ?>
               <h2>Contact Form</h2><hr>
                <form action="" method="post">
                    <div class="form-group">
                       <label for="full-name">Full Name*:</label>
                       <?php
                       if(isset($error)){
                         echo "<span style='color:red;' class='pull-right'>$error</span>";
                       }
                        else if(isset($msg)){
                          echo "<span style='color:green;' class='pull-right'>$msg</span>";
                       }
                       ?>  
                       <input type="text" id="full-name" class="form-control" placeholder="Full Name" name="name"> 
                    </div>
                    
                    <div class="form-group">
                       <label for="email">Email*:</label>
                       <input type="email" id="email" class="form-control" placeholder="Email" name="email"> 
                    </div>
                    
                    <div class="form-group">
                       <label for="website">Website:</label>
                       <input type="text" id="website" class="form-control" placeholder="Website" name="website"> 
                    </div>
                    
                    <div class="form-group">
                       <label for="message">Message:</label>
                       <textarea id="" cols="30" rows="10" class="form-control" placeholder="Your message should be here" name="comment"></textarea>
                    </div>
                    
                    <input type="submit" name="submit" value="submit" class="btn btn-primary">
                    
                </form>
           </div>
      </div>
    </div>
    
    <div class="col-md-4">
     <?php require_once('include/sidebar.php'); ?>
    </div>
   </div>
  </div>
 </section>
  <?php require_once('include/footer.php'); ?>