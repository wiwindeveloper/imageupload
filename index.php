<?php 
include_once 'db/connect.php'; //include configuration to database
include_once 'class/Images.php'; //include class for image manipulation
$cImage = new Images($connect);

/*
* get id image to delete process
*/
if (isset($_GET['delete_id'])) 
{
	$id = $_GET['delete_id'];

	if ($cImage->destroy($id)) 
	{
		$msg = "<div class='alert alert-info mg-alert'>
	              <strong>Success!!! </strong> 
	                Images has deleted.
	            </div>";
	}
	else
	{
		$msg = "<div class='alert alert-danger mg-alert'>
	              <strong>Sorry!!! </strong> 
	                Failed to delete image.
	            </div>";
	}
}

include_once 'header.php'; //include header
?>
<body>

<!-- Top Navigation-->
<?php include_once 'navbar.php';?> 
<!-- /.Top Navigation-->

<div class="container">
	<div class="page-header">
		<h1>Gallery</h1>
	</div>

	<!-- Alert-->
	<div class="col-md-12">
		<?php
			if (isset($msg)) 
			{
				echo $msg;
				header("Refresh:5; url=index.php");
			}
		?>
	</div>
	<!-- /.Alert-->

	<div class="row">
		<div class="col-md-12">
			<a type="button" class="btn btn-primary" href="addNew.php">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    Add New
            </a>
		</div>
		<div class="col-md-12">
			<?php
				/*
				* Show all image from database
				*/
				$stmt_show = $cImage->index();
				while ($rowShow = $stmt_show->fetch(PDO::FETCH_ASSOC)) 
				{
					?>
						<div class="contimg">
							<img src="images/<?php echo $rowShow['url']; ?>" class="image">
							<div class="middle">
								<div class="text">
									<a href="?delete_id=<?php echo $rowShow['id'];?>">
										Delete
									</a>
								</div>
							</div>
						</div>
					<?php
				}
			?>
		</div>
	</div>
</div>	
</body>
</html>