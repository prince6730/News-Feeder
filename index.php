<?php require_once('include/top.php');?>
</head>

<body>
	<?php require_once('include/header.php');
      $number_of_posts = 3;
      
      if(isset($_GET['page'])){
          $page_id = $_GET['page'];
      }
      else{
          $page_id = 1;
      }
      
      if(isset($_GET['cat'])){
          $cat_id = $_GET['cat'];
          $cat_query = "SELECT * FROM categories WHERE id = $cat_id";
          $cat_run = mysqli_query($con, $cat_query);
          $cat_row = mysqli_fetch_array($cat_run);
          $cat_name = $cat_row['category']; 
      }
      
      if(isset($_POST['search'])){
          $search = $_POST['search-title'];
          $all_posts_query = "SELECT * FROM posts WHERE status = 'publish'";
          $all_posts_query .= " and tags LIKE '%$search%'";
          $all_posts_run = mysqli_query($con, $all_posts_query);
          $all_posts = mysqli_num_rows($all_posts_run);
          $total_pages = ceil($all_posts / $number_of_posts);
          $posts_start_from = ($page_id - 1) * $number_of_posts;
      }
      else{
          $all_posts_query = "SELECT * FROM posts WHERE status = 'publish'";
      if(isset($cat_name)){
          $all_posts_query .= " and categories = '$cat_name'";
      }
       $all_posts_run = mysqli_query($con, $all_posts_query);
       $all_posts = mysqli_num_rows($all_posts_run);
       $total_pages = ceil($all_posts / $number_of_posts);
       $posts_start_from = ($page_id - 1) * $number_of_posts;
    }
      
   ?>

	<div class="jumbotron">
		<div class="container">
			<div id="details">
				<h1>Custom <span>Post</span></h1>
				<p>Here you put your own tag line to make it more attractive</p>
			</div>
		</div>
		<img src="img/top1%20image.jpg" alt="Top images">
	</div>

	<section>
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<?php 
             $slider_query = "SELECT * FROM posts WHERE status = 'publish' ORDER BY id DESC LIMIT 5";
             $slider_run = mysqli_query($con,$slider_query);
             if(mysqli_num_rows($slider_run) > 0){
                 $count = mysqli_num_rows($slider_run);
          ?>
					<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
						<!-- Indicators -->
						<ol class="carousel-indicators">
							<?php
                for($i = 0; $i < $count; $i++){
                    if($i == 0){
                        echo "<li data-target='#carousel-example-generic' data-slide-to='".$i."' class='active'></li>";
                    }
                    else{
                      echo "<li data-target='#carousel-example-generic' data-slide-to='".$i."'></li>";  
                    }
                }
                 
              ?>
						</ol>

						<!-- Wrapper for slides -->
						<div class="carousel-inner" role="listbox">
							<?php
      $check = 0;
       while($slider_row = mysqli_fetch_array($slider_run)){
           $slider_id = $slider_row['id'];
           $slider_image = $slider_row['image'];
           $slider_title = $slider_row['title'];
           $check = $check + 1;
           if($check == 1){
               echo "<div class='item active'>";
           }
           else{
              echo "<div class='item'>"; 
           }
      ?>
							<a href="post.php?post_id=<?php echo $slider_id;?>"><img
									src="img/<?php echo $slider_image;?>"></a>
							<div class="carousel-caption">
								<h2><?php echo $slider_title;?></h2>
							</div>
						</div>
						<?php } ?>
					</div>

					<!-- Controls -->
					<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>

				<!--This is for the comments which are attached with sidebar on the top -->
				<?php
       }
       if(isset($_POST['search'])){
           $search = $_POST['search-title'];
           $query = "SELECT * FROM posts WHERE status = 'publish'";
           $query .= " and tags LIKE '%$search%'"; 
           $query .= " ORDER BY id DESC LIMIT $posts_start_from,      $number_of_posts";
       }
       else{
           $query = "SELECT * FROM posts WHERE status = 'publish'";
           if(isset($cat_name)){
             $query .= " and categories = '$cat_name'";   
           } 
            $query .= " ORDER BY id DESC LIMIT $posts_start_from,      $number_of_posts";
       }  
         $run = mysqli_query($con,$query);
           if(mysqli_num_rows($run) > 0){
              while($row = mysqli_fetch_array($run)){
                $id = $row['id'];
                $date = getdate($row['date']);
                $day = $date['mday'];
                $month = $date['month'];
                $year = $date['year'];
                $title = $row['title'];
                $author = $row['author'];
                $author_image = $row['author_image'];
                $image = $row['image'];
                $categories = $row['categories'];
                $tags = $row['tags'];
                $post_data = $row['post_data'];
                $views = $row['views'];
                $status = $row['status'];
       ?>

				<div class="post">
					<div class="row">
						<div class="col-md-2 post-date">
							<div class="day"><?php echo $day;?></div>
							<div class="month"><?php echo $month;?></div>
							<div class="year"><?php echo $year;?></div>
						</div>

						<div class="col-md-8 post-title">
							<a href="post.php?post_id=<?php echo $id;?>">
								<h2><?php echo $title;?></h2>
							</a>
							<p>Written by:<span><?php echo ucfirst($author);?></span></p>
						</div>

						<div class="col-md-2 profile-picture">
							<img src="img/<?php echo $author_image;?>" alt="Profile Picture" class="img-circle">
						</div>
					</div>

					<a href="post.php?post_id=<?php echo $id;?>">
						<img src="img/<?php echo $image;?>" alt="Post Image">
					</a>

					<div class="desc">
						<?php 
            $post_data = strip_tags($post_data);
            if (strlen($post_data) > 300) {

        // truncate string
          $stringCut = substr($post_data, 0, 300);
          $endPoint = strrpos($stringCut, ' ');

        //if the string doesn't contain any space then it will cut without word basis.
        $post_data = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $post_data .= '.....';
       }
        echo $post_data;
        ?>
					</div>

					<a href="post.php?post_id=<?php echo $id;?>" class="btn btn-primary">Read More</a>

					<div class="bottom">
						<span class="first"><i class="fa fa-folder"></i> <a
								href="#"><?php echo ucfirst($categories);?></a></span>|
						<span class="sec"><i class="fa fa-comment"></i> <a href="#"> Comment</a></span>
					</div>
				</div>

				<?php
         } 
       }
       else{
          echo "<center><h2>No Post Available</h2></center>";
        }
      ?>

				<nav id="pagination">
					<ul class="pagination">
						<?php
        for($i = 1; $i <= $total_pages; $i++){
            echo "<li class='".($page_id == $i ? 'active':'')."'><a href='index.php?page=".$i."&".(isset($cat_name)?"cat=$cat_id":"")."'>$i</a></li>";
        }
      ?>
					</ul>
				</nav>
			</div>

			<div class="col-md-4">
				<?php require_once('include/sidebar.php');?>
			</div>
		</div>
		</div>
	</section>
	<?php require_once('include/footer.php');?>