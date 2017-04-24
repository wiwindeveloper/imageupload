<?php 
include_once 'db/connect.php'; //include configuration to database
include_once 'class/Images.php'; //include class for image manipulation
$cImage = new Images($connect);

/*
* Upload process
*/
if (isset($_POST['btn-upload'])) 
{
	$imgFile    = $_FILES['fileImg']['name'];
	$tmp_dir    = $_FILES['fileImg']['tmp_name'];
	$imgSize    = $_FILES['fileImg']['size'];
	$type       = $_FILES['fileImg']['type'];
	$error      = $_FILES['fileImg']['error'];

	if ($error > 0) 
	{
	  	$errMsg = "<div class='alert alert-warning mg-alert'>
	              <strong>Warning!!! </strong> 
	                ".$error." 
	              </div>";
	}
	else if (empty($imgFile)) 
	{
	  	$errMsg =  "<div class='alert alert-warning mg-alert'>
	              <strong>Warning!!! </strong> 
	                Please, select a file!
	              </div>";
	}
	else if ($imgSize > 2000000) 
	{
	  	$errMsg =  "<div class='alert alert-warning mg-alert'>
	                <strong>Warning!!! </strong> 
	                  File size must lest than 2 Mb.
	              </div>";
	}
	else if (($type == 'image/gif') ||
	          ($type == 'image/jpeg') ||
	          ($type == 'image/png') ||
	          ($type == 'image/pjpeg')) 
	{
	  $time     = time();
	  $imgExt   = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));
	  $imgName  = "image"."_".$time.".".$imgExt;

	  $url        = "images/".$imgName;
	  $uploadFile = $cImage->compressImage($tmp_dir,$url, 80);
	}
	else
	{
	  $errMsg =  "<div class='alert alert-warning mg-alert'>
	                <strong>Warning!!! </strong> 
	                  Uploaded image should be jpg, gif or png.
	              </div>";
	}

	if (!isset($errMsg)) 
	{
	  	$stmtImg  = $cImage->createImg($imgName);

		if (!$stmtImg) 
		{
			$errMsg  = "<div class='alert alert-danger mg-alert'>
		                <strong>Sorry!!! </strong> 
		                  Failed to upload image.
		                </div>";
		    header("Refresh:5; url=addNew.php");
		}
		else
		{
		    $successMsg = "<div class='alert alert-success mg-alert'>
		                    <strong>Success!!! </strong> 
		                      Image has uploaded.
		                  </div>";
		    header("Refresh:5; url=addNew.php");
		}
	}
}
include_once 'header.php';
?>
<body>
<?php include_once 'navbar.php';?>
<div class="container">
	<div class="page-header">
		<h1>Upload an Image</h1>
	</div>

	<!--Alert-->
    <div class="col-md-12">
	    <?php
	    	if (isset($errMsg)) 
	    	{
	      		echo $errMsg;
	    	}
	    	else if (isset($stmtImg)) 
	    	{
	      		echo $successMsg;
	    	}
	    ?>
    </div>
    <!-- /.Alert-->

	<div class="row">
		<div class="col-md-12">
			<form method="post" enctype="multipart/form-data">
				<div class="col-md-8">
					<div class="form-group">
				        <div class="input-group">
				            <span class="input-group-btn">
				                <span class="btn btn-default btn-file">
				                    Browse… <input type="file" id="imgInp" name="fileImg">
				                </span>
				            </span>
				            <input type="text" class="form-control" readonly>
				        </div>
				        <img id='img-upload'/>
			    	</div>
				</div>
			    <div class="col-md-6">
			    	<input type="submit" value="Upload" class="btn btn-primary" name="btn-upload">
			    </div>
			</form>
		</div>
	</div>
</div>	
<?php include_once 'footer.php';?>
<script type="text/javascript">
/*
* upload image
*/
$(document).ready( function() 
{
    $(document).on('change', '.btn-file :file', function() {
	var input = $(this),
	label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
	input.trigger('fileselect', [label]);
	});

	$('.btn-file :file').on('fileselect', function(event, label) {
		    
		var input = $(this).parents('.input-group').find(':text'),
		    log = label;
		    
		    if( input.length ) {
		        input.val(log);
		    } else {
		        if( log ) alert(log);
		    }
	    
	});
	
	function readURL(input) {
		if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        
	        reader.onload = function (e) {
	            $('#img-upload').attr('src', e.target.result);
	        }
	        
	        reader.readAsDataURL(input.files[0]);
	    }
	}

	$("#imgInp").change(function(){
	    readURL(this);
	}); 	
});
</script>
</body>
</html>