<?php 
if(isset($_POST["imageuploader"])){
	$check = getimagesize($_FILES["image"]["tmp_name"]);
	if ($check!== false){
		$data 	= file_get_contents($_FILES['image']['tmp_name']);
		$base64 = base64_encode($data);
		
		
		$updateImage = $odb->prepare("UPDATE `items` SET `image` = ? WHERE `id` = ?");
		$updateImage->BindValue(1, $base64);
		$updateImage->BindValue(2, $_POST['item']);
		$updateImage->execute();
		$success = true;
	}else{
		$errors[] = "Please add an image.";
	}
}

if ($link == 'nope'){
	if(isset($errors)){
		if(empty($errors)){
			if($success){
				echo '<div class="alert alert-success">';
				echo '<button type="button" class="close" data-dismiss="alert">×</button>';
				echo '<strong>You have successfully posted a news article. Visit <a href="http://www.tibiamarket.net">the index page </a> to view it!</strong><br>';
				echo '</div>';	
			}				
		}else{
			echo '<div class="alert alert-danger">';
			echo '<button type="button" class="close" data-dismiss="alert">×</button>';
			echo '<strong>Oh snap!</strong><br>';
			foreach($errors as $error){
				echo '-'.$error.'<br />';
			}
			echo '</div>';
		}
	}
?>

<div class="widget-box">
	<div class="widget-title">
		<h5>Update Images - Working</h5>
	</div>
	<div class="widget-content">
		<form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
			<div class="form-group">
				<label class="col-sm-4 control-label">Image</label>
				<div class="col-sm-8">
					<select name="item">
						<?php
							$getAllItems = $odb->prepare("SELECT * FROM `items`");
							$getAllItems->execute();

							while($record = $getAllItems->fetch(PDO::FETCH_ASSOC)){ ?>
								<option value="<?php _echo($record['id']);?>"> <?php _echo($record['name']);?> </option>							
							<?php } ?>													
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-4 control-label">Upload</label>
				<div class="col-sm-8">
					<input type="file" name="image">
					<span class="help-block text-left">Uploading anything but accurate images will result in a demotion.</span>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" name="imageuploader" class="btn btn-primary btn-sm">Update Image</button>
			</div>
		</form>
	</div>
</div>
<?php	} else { 
			echo "Someone is where they're not suppose to be...";
		}
?>


