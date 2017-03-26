<?php 
/**
* @author wiwin savitri
*/
class Images
{
	private $db;

	function __construct($connect)
	{
		$this->db = $connect;
	}

	/*
	* compress image
	*/
	function compressImage($source_url,$destination_url,$quality)
	{
		$info = getimagesize($source_url);

		if ($info['mime'] == 'image/jpeg') 
		{
			$image = imagecreatefromjpeg($source_url);
		}
		elseif ($info['mime'] == 'image/png') 
		{
			$image = imagecreatefrompng($source_url);
		}

		imagejpeg($image, $destination_url, $quality);
		return $destination_url;
	}

	/*
	* insert data to database
	*/
	public function createImg($imgName)
	{
		try
		{
			$stmt = $this->db->prepare("
				INSERT INTO 
					`gallery` 
						(`url`) 
					VALUES 
						(:fname)
				");
			$stmt->bindparam(":fname", $imgName);
			$stmt->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			return false;
		}
	}

	/*
	* get all data
	*/
	public function index()
	{
		try
		{
			$stmt = $this->db->prepare("
					SELECT 
						* 
					FROM 
						`gallery`
					");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			return false;
		}
	}

	/*
	* destroy image
	*/
	public function destroy($id)
	{
		try
		{
			//destroy image from directory
			$stmt_select = $this->db->prepare("
						SELECT 
							`url` 
						FROM 
							`gallery` 
						WHERE 
							`id` = :id
						");
			$stmt_select->execute(array(":id" => $id));
			$rowImg = $stmt_select->fetch(PDO::FETCH_ASSOC);
			unlink("images/".$rowImg['url']);

			//destroy image from database
			$stmt_delete = $this->db->prepare("
						DELETE FROM 	
							`gallery` 
						WHERE 	
							`id` = :id
						");
			$stmt_delete->bindparam(":id", $id);
			$stmt_delete->execute();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			return false;
		}
	}
}
?>