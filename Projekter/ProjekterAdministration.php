<html>
<head>
<title>PHP projekterscript administration</title>
</head>
<body>
<div id="upload_page" style="height: 100%; width: 80%" align="center">

<form action="" method="post" enctype="multipart/form-data">
<label for="file">Billedfil som skal uploades: </label>
<input type="file" name="file" id="file" />
<br />
<label for="interval">Hvor lang tid billedet skal vises: </label>
<input type="text" name="interval" id="interval" size="10" />
<br />
<label for="checkbox">Skal billedet v&aelig;re aktivt fra start? </label>
<input type="checkbox" name="activate" value="yes" />
<input type="submit" name="upload" value="Upload" />
</form>

<script type="text/javascript">
function showImage(imagePath, title) 
{
	var width  = 800;
	var height = 600;
	var left   = (screen.width  - width) / 2;
	var top    = (screen.height - height) / 2;
	var params = 'width=' + width + ', height=' + height;
	params += ', top=' + top + ', left=' + left;
	params += ', directories=no';
	params += ', location=no';
	params += ', menubar=no';
	params += ', resizable=no';
	params += ', scrollbars=no';
	params += ', status=no';
	params += ', toolbar=no';
	params += ', titlebar=no';
	
	var imageView = window.open('','', params);
	imageView.document.writeln('<html><head><title>' + title + '</title>');
	imageView.document.writeln('<script language="JavaScript"> function closeWindow() { self.close(); } <'+'/script>');
	imageView.document.writeln('</head><body onClick="closeWindow()" style="margin: 0; padding: 0;"><center>');
	imageView.document.writeln('<img src="' + imagePath + '" width="' + width + '" height="' + height + '" border="0" />');
	<?php //imageView.document.writeln('<'+'input type="button" onClick="closeWindow()" value="Close Image">'); ?>
	imageView.document.writeln('</center></body></html>');
	
	imageView.focus();
	return false;
}
</script>

<?php
/**
 * Creates the necessary tables in the SQLite database, if they doesn't exist already.
 * Throws an exception if the creation of the database was unsuccessful. This could happen if the webserver doesn't have write access in the given directory.
 * If an exception is thrown the webserver halts further execution and returns an error to the user.
 */
try {
	/* 
	 * SQLite locking mechanism is described in http://www.sqlite.org/lockingv3.html#transaction_control
	 * and http://www.sqlite.org/faq.html#q5.
	 * Summary: Reading creates a shared lock (on the entire database file), 
	 * 			writing locks the entire database file for the duration of the update.
	 */
			
	$dbcon = new PDO('sqlite:upload.db');
	$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
	$sql = "CREATE TABLE IF NOT EXISTS upload(id INTEGER PRIMARY KEY AUTOINCREMENT, imagepath TEXT UNIQUE, sequence INTEGER, interval INTEGER NOT NULL DEFAULT 20, active BOOLEAN NOT NULL DEFAULT 0);";
	$dbcon->exec($sql);
	$sql = "CREATE TABLE IF NOT EXISTS timestamp(time INTEGER PRIMARY KEY, id INTEGER, FOREIGN KEY(id) REFERENCES upload(id) ON DELETE CASCADE ON UPDATE SET NULL);";
	$dbcon->exec($sql);
	
	$sql = "CREATE TRIGGER IF NOT EXISTS insert_sequence AFTER INSERT ON upload FOR EACH ROW
			BEGIN
				UPDATE upload SET sequence = (SELECT MAX(sequence) FROM upload) + 1 WHERE id = new.id AND active = 1 AND sequence IS NULL;
			END;";
	$dbcon->exec($sql);
	
	$sql = "CREATE TRIGGER IF NOT EXISTS update_sequence AFTER UPDATE ON upload FOR EACH ROW
			BEGIN
				UPDATE upload SET sequence = (SELECT MAX(sequence) FROM upload) + 1 WHERE id = new.id AND active = 1 AND sequence IS NULL;
			END;";
	$dbcon->exec($sql);
}
catch (PDOException $e) {
	echo "Could not establish connection to the database.";
	echo "This is unfortunately a fatal error that cannot be ignored and further execution has been halted." . "<br />";
	echo "Please contact an administrator immediately";
	echo "If you don't know any administrators, please visit the contact page." . "<br />";
	echo "<br />" . "Please give the administrator the following information:" . "<br />";
	echo $e;
	die();
}
?>

<?php
/*
 * Provides:
 * getRandomString($length);
 */
include getcwd() . '/../' . 'functions.inc';

/*
 * Provides:
 * resample($targetFile, $sourceFile, $newWidth, $newHeight);
 * resize($targetFile, $sourceFile, $newWidth, $newHeight);
 */
include getcwd() . '/../' . 'ImageLibrary.inc';


class InvalidFileType extends Exception { };
class FileTooBig extends Exception { };
class NoUniqueName extends Exception { };
static $MAX_FILESIZE = 5242880;
static $IMAGE_TYPES = array("gif", "png", "jpeg");
static $INPUT = "file";
static $LOG_FILE = "upload.log";
static $DEBUG_LOG = "upload_debug.log";
static $UPLOAD_DIR = "uploaded_files";
static $THUMBNAIL_DIR = "thumbnails";
static $THUMBNAIL_PREFIX = "thumb_";
static $HTTP_ROOT = "http://dikulan.dk/"; /* Remember trailing slash */
static $RELATIVE_PATH = "DIKULAN/Projekter"; /* $HTTP_ROOT . $RELATIVE_PATH = full path */


try {
	$dbcon = new PDO('sqlite:upload.db');
	$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$default_image_name = "default.jpg";
	$default_image_path = $HTTP_ROOT . $RELATIVE_PATH . "/" . $UPLOAD_DIR . "/" . $default_image_name;
			
	$sql = "INSERT OR IGNORE INTO upload (id, imagepath, sequence, interval, active) VALUES (?, ?, ?, ?, ?);";
	$stmt = $dbcon->prepare($sql);
	$stmt->execute(array("0", $default_image_path, "0", "0", "NULL")); /* REQUIRED for trigger to work! */
}
catch (PDOException $e) {
	echo "Could not establish connection to the database.";
	echo "This is unfortunately a fatal error that cannot be ignored and further execution has been halted." . "<br />";
	echo "Please contact an administrator immediately";
	echo "If you don't know any administrators, please visit the contact page." . "<br />";
	echo "<br />" . "Please give the administrator the following information:" . "<br />";
	echo $e;
	die();
}


/**
 * Checks the uploaded file for correct file type and size.
 * The valid file types are defined in $IMAGE_TYPES.
 * The file must not exteed $MAX_FILESIZE.
 * @global $MAX_FILESIZE, $IMAGE_TYPES, $INPUT
 * @throws FileTooBig
 * @throws InvalidFileType
 */
function checkUploadedFile() {
	global $MAX_FILESIZE, $IMAGE_TYPES, $INPUT;
	$FOUND = 0;
	
	foreach ($IMAGE_TYPES as $value) {
	    if($_FILES[$INPUT]["type"] == "image/" . $value) {
	    	$FOUND = 1;
	    	break;
	    }
	}
	if ($FOUND) {
		if ($_FILES[$INPUT]["size"] > $MAX_FILESIZE) {
			throw new FileTooBig("The uploaded file is too big.", 1002);
		}
	}
	else {
		throw new InvalidFileType("Invalid file type.", 1001);
	}
}

/**
 * Inserts the path to the new image in the database.
 * We here assume that the given path is unique (by giving each image a unique name), i.e. there doesn't exist an other image with the given path.
 * @global $INPUT, $_FILES, $_REQUEST, $DEBUG_LOG, $HTTP_ROOT, $RELATIVE_PATH
 * @param string $path The absolute path to the image file.
 */
function insertFile($path) {
	global $INPUT, $DEBUG_LOG, $HTTP_ROOT, $RELATIVE_PATH;
	
	try {
		$dbcon = new PDO('sqlite:upload.db');
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		if (isset($_POST["activate"]) && $_POST["activate"] == "yes") {
			$activate = 1;
		} else {
			$activate = 0;
		}
		if (isset($_POST["interval"]) && is_numeric($_POST["interval"])) {
			$sql = "INSERT INTO upload (imagepath, interval, active) VALUES (?, ?, ?);";
			$stmt = $dbcon->prepare($sql);
			$stmt->execute(array($path, $_POST["interval"], $activate));
		}
		else {
			$sql = "INSERT INTO upload (imagepath, active) VALUES (?, ?);";
			$stmt = $dbcon->prepare($sql);
			$stmt->execute(array($path, $activate));
		}
		
		$error_array = $dbcon->errorInfo();
		if ($error_array[1] != "") {
			$fh = fopen($DEBUG_LOG, 'a');
			$str = date('Y/m/d - H:i:s') . "PDO::errorInfo():";
			fwrite($fh, $str . "\n");
			fclose($fh);
			file_put_contents($DEBUG_LOG, serialize($error_array));
			
			echo "<br />";
			echo "Could not insert image information into the database file.";
			echo "Please reupload the file. If you have received this error several times before please contact an administrator";
			echo "If you don't know any administrators, please visit the contact page." . "<br />";
		}
	}
	catch (PDOException $e) {
		echo "Could not establish connection to the database.";
		echo "This is unfortunately a fatal error that cannot be ignored and further execution has been halted." . "<br />";
		echo "Please contact an administrator immediately";
		echo "If you don't know any administrators, please visit the contact page." . "<br />";
		echo "<br />" . "Please give the administrator the following information:" . "<br />";
		echo $e;
	}
}

/**
 * Moves the uploaded file from the temporary location to the upload directory.
 * The file is given a new name consisting of a random generated string encoded in base 62 and the file extension.
 * Writes to the log file where the image is stored.
 * @param integer $times The number of times we've tried to move the file to the upload directory.
 * @global $IMAGE_TYPES, $INPUT, $_FILES, $LOG_FILE, $UPLOAD_DIR, $THUMBNAIL_DIR, $THUMBNAIL_PREFIX
 * @throws NoUniqueName
 */
function moveFile($times = 0) {
	global $IMAGE_TYPES, $INPUT, $LOG_FILE, $UPLOAD_DIR, $THUMBNAIL_DIR, $THUMBNAIL_PREFIX, $HTTP_ROOT, $RELATIVE_PATH;
	
	if ($times > 25) {
		throw new NoUniqueName("Too many files have been uploaded so the new file could not get a unique ID.", 1003);
	}
	
	$ext = pathinfo($_FILES[$INPUT]["name"], PATHINFO_EXTENSION);
	$newName = getRandomString(20) . "." . $ext;
	
	if (file_exists($UPLOAD_DIR . "/" . $newName)) {
		moveFile(++$times);
	}
	else {
		$fh = fopen($LOG_FILE, 'a');
		$fileUploadPath = $UPLOAD_DIR . "/" . $newName;
		$str = "Stored in: " . $fileUploadPath . "\n";
		fwrite($fh, $str . "\n");
		fclose($fh);
		
		if (!file_exists($UPLOAD_DIR . '/' . $THUMBNAIL_DIR)) {
			mkdir($UPLOAD_DIR . '/' . $THUMBNAIL_DIR, 750, true);
		}
		
		move_uploaded_file($_FILES[$INPUT]["tmp_name"], $fileUploadPath);
		$ImageLibrary = new ImageLibrary();
		$ImageLibrary->resample($UPLOAD_DIR . '/' . $THUMBNAIL_DIR . '/' . $THUMBNAIL_PREFIX . $newName, $fileUploadPath, 64, 64); //Create a thumbnail of size 64 by 64.
		
		$newPath = $HTTP_ROOT . $RELATIVE_PATH . '/' . $UPLOAD_DIR . '/' . $newName;
		insertFile($newPath);
	}
}

/**
 * If a file hasn't been uploaded we do nothing.
 * If a file has been uploaded we start by checking file type and size.
 * If an error has occurred we return this to the user and stop further execution.
 * If no error has occurred we write information about the uploaded file in a log 
 * and tries to move it to the upload directory.
 */
try {
	if (isset($_POST['upload']) && $_FILES[$INPUT]["size"] > 0) {
		checkUploadedFile();
		
		if ($_FILES[$INPUT]["error"] > 0) {
			echo "Return Code: " . $_FILES[$INPUT]["error"] . "<br />";
		}
		else {
			$fh = fopen($LOG_FILE, 'a');
			$str = "";
			$str .= "Upload: " . $_FILES[$INPUT]["name"] . "\n";
			$str .= "Type: " . $_FILES[$INPUT]["type"] . "\n";
			$str .= "Size: " . ($_FILES[$INPUT]["size"] / 1024 / 1024) . " Mbytes\n";
			$str .= "Temp file: " . $_FILES[$INPUT]["tmp_name"] . "\n";
			fwrite($fh, $str);
			fclose($fh);
			
			moveFile();
		}
	}
}
catch (InvalidFileType $e) {
	echo "The uploaded file does not have a valid file type. You can only upload files in these formats:";
	foreach ($IMAGE_TYPES as $value) {
	    echo "<br />" . "image/" . $value;
	}
	
	echo "<br />" . "<br />" . "Please upload an image file in the one of the given formats.";
}
catch (FileTooBig $e) {
	$size = $MAX_FILESIZE / 1024 / 1024;
	if ($size > 1) { $size = $size . " MegaBytes"; } else { $size = $size . "MegaByte"; }
	
	echo "You cannot upload files more than " . $size . ".<br />";
	echo "Try resizing the image before uploading it.";
}
catch (NoUniqueName $e) {
	$fh = fopen($LOG_FILE, 'a');
	$str = "Error message: " . $e;
	fwrite($fh, $str . "\n");
	fclose($fh);
	
	echo $e; /* This is the error message for the exception. */
	echo "<br />" . "Try reuploading the file. If that does not help you'll have to contact an administrator before continuing.";
	echo "<br />" . "If you don't know any administrators, please visit the contact page.";
}


/*
 * The previous sections was used to upload files and check the uploaded file.
 * The next section is for deleting uploaded files.
 */

/**
 * Deletes an item in the database from the imagepath (this is a unique field in the database).
 */
if(isset($_POST['delete']) && in_array("Delete", $_POST)) {
	try {
		$dbcon = new PDO('sqlite:upload.db');
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (PDOException $e) {
		echo "Could not establish connection to the database.";
		echo "This is unfortunately a fatal error that cannot be ignored and further execution has been halted." . "<br />";
		echo "Please contact an administrator immediately";
		echo "If you don't know any administrators, please visit the contact page." . "<br />";
		echo "<br />" . "Please give the administrator the following information:" . "<br />";
		echo $e;
		return;
	}

	$arrPOST = array_values($_POST);
	$index = array_search("Delete", $arrPOST);
	
	if (!($index === FALSE)) {
		$arrPOST[$index];
		$imagePath = $arrPOST[$index + 1];

		try {
			$sql = "BEGIN TRANSACTION;";
			$dbcon->exec($sql);
			
			$sql = "SELECT id, sequence, interval FROM upload WHERE imagepath = ?";
			$stmt = $dbcon->prepare($sql);
			$stmt->execute(array($imagePath));
			$result = $stmt->fetchAll();
			
			$upload_id = $result[0][0];
			$upload_sequence = $result[0][1];
			$upload_interval = $result[0][2];
			
			$sql = "DELETE FROM upload WHERE imagepath = ?";
			$stmt = $dbcon->prepare($sql);
			$stmt->execute(array($imagePath));
				
			$sql = "SELECT time, id FROM timestamp LIMIT 1";
			$stmt = $dbcon->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll();

			if(!empty($result)) {
				$timestamp_time = $result[0][0];
				$timestamp_id = $result[0][1];
				
				if($upload_id == $timestamp_id) {
					$sql = "SELECT id, imagepath, sequence, interval, active FROM upload WHERE sequence = ?";
					$stmt = $dbcon->prepare($sql);
					$stmt->execute(array($upload_sequence + 1));
					$result = $stmt->fetchAll();

					if(!empty($result)) {
						$sql = "UPDATE timestamp SET time = ?, id = ?";
						$stmt = $dbcon->prepare($sql);
						$stmt->execute(array(time() + $result[0][3], $result[0][0]));
					}
					else {
						$sql = "DELETE FROM timestamp"; //empty the database, so the first index is chosen next time
						$stmt = $dbcon->prepare($sql);
						$stmt->execute();
					}
				}
			}
				
			updateSequenceNumbering($dbcon);
			
			$sql = "END TRANSACTION;";
			$dbcon->exec($sql);
		}
		catch (PDOException $e) {
			$sql = "ROLLBACK TRANSACTION;";
			$dbcon->exec($sql);

			echo "A fatal error occured in the database that cannot be ignored, and further execution has been halted." . "<br />";
			echo "Please contact an administrator immediately";
			echo "If you don't know any administrators, please visit the contact page." . "<br />";
			echo "<br />" . "Please give the administrator the following information:" . "<br />";
			echo $e;
		}
	}
}

/*
 * The previous sections was used to delete files.
 * The next section is used to update uploaded files.
 */

/**
 * Updates the database with the new information posted on the site.
 */
if (isset($_POST['update'])){
	try {
		$dbcon = new PDO('sqlite:upload.db');
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (PDOException $e) {
		echo "Could not establish connection to the database.";
		echo "This is unfortunately a fatal error that cannot be ignored and further execution has been halted." . "<br />";
		echo "Please contact an administrator immediately";
		echo "If you don't know any administrators, please visit the contact page." . "<br />";
		echo "<br />" . "Please give the administrator the following information:" . "<br />";
		echo $e;
		return;
	}
	
	try {
		$intervalIndexes = array_values(preg_grep("/^interval[0-9]+/", array_keys($_POST)));
		$sequenceIndexes = array_values(preg_grep("/^sequence[0-9]+/", array_keys($_POST)));
		$imagepathIndexes = array_values(preg_grep("/^imagePath[0-9]+/", array_keys($_POST)));
		
		if(count($intervalIndexes) !== count($sequenceIndexes) && count($intervalIndexes) !== count($imagepathIndexes)) {
			echo '<font color="red">Error:</font> The update query does not match the expected input format. Please try again.';
			echo "<br />" . "If the problem persists please contact an administrator.";
			echo "If you don't know any administrators, please visit the contact page." . "<br />";
			echo "<br />" . "Please give the administrator the following information:" . "<br />";
			echo "<br />"; echo 'Query1:'; echo '<pre>'; print_r($intervalIndexes); echo '</pre>';
			echo "<br />"; echo 'Query2:'; echo '<pre>'; print_r($sequenceIndexes); echo '</pre>';
			echo "<br />"; echo 'Query3:'; echo '<pre>'; print_r($imagepathIndexes); echo '</pre>';
			return;
		}
		$max = count($intervalIndexes);
		
		$sql = "BEGIN TRANSACTION;";
		$dbcon->exec($sql);
		
		$sql = "UPDATE upload SET interval = ?, sequence = ? WHERE imagepath = ?;";
		$sql_active = "UPDATE upload SET interval = ?, sequence = NULL, active = 1 WHERE imagepath = ?;";
		$sql_inactive = "UPDATE upload SET interval = ?, sequence = NULL, active = 0 WHERE imagepath = ?;";
		$stmt = $dbcon->prepare($sql);
		$stmt_active = $dbcon->prepare($sql_active);
		$stmt_inactive = $dbcon->prepare($sql_inactive);
		
		for($i = 0; $i < $max; $i++) {
			if(is_numeric($_POST[$intervalIndexes[$i]]) && $_POST[$intervalIndexes[$i]] > 0) {
				$interval = $_POST[$intervalIndexes[$i]];
				$sequence = $_POST[$sequenceIndexes[$i]];
				$imagepath = $_POST[$imagepathIndexes[$i]];
				
				if(preg_match("/S([^A-Za-z0-9]|æ)t aktiv/", $sequence)) {
					$stmt_active->execute(array($interval, $imagepath));
				}
				else if(preg_match("/S([^A-Za-z0-9]|æ)t inaktiv/", $sequence)) {
					$stmt_inactive->execute(array($interval, $imagepath));
				}
				else {
					$stmt->execute(array($interval, $sequence, $imagepath));
				}
			}
			else {
				echo '<p style="color:red; font-size: 20px;">The interval must be a positive number.<p>';
				return;
			}
		}
		
		updateSequenceNumbering($dbcon);
		
		$sql = "END TRANSACTION;";
		$dbcon->exec($sql);
	}
	catch (PDOException $e) {
		$sql = "ROLLBACK TRANSACTION;";
		$dbcon->exec($sql);
		
		echo "A fatal error occured in the database that cannot be ignored, and further execution has been halted." . "<br />";
		echo "Please contact an administrator immediately";
		echo "If you don't know any administrators, please visit the contact page." . "<br />";
		echo "<br />" . "Please give the administrator the following information:" . "<br />";
		echo $e;
	}
}

/**
 *
 * Update the sequence numbering.
 * 
 * @param PDO $dbcon The connection to add and execute statements to.
 */
function updateSequenceNumbering($dbcon) {
	$sql = "SELECT id FROM upload WHERE id > 0 AND active = 1 ORDER BY sequence ASC, id ASC";
	$stmt = $dbcon->prepare($sql);
	$stmt->execute();
	$result = $stmt->fetchAll();

	$sql = "UPDATE upload SET sequence = ? WHERE id = ?;";
	$stmt = $dbcon->prepare($sql);

	$i = 1;
	foreach ($result as $row) {
		$id = $row[0];
		$stmt->execute(array($i, $id));
		$i++;
	}
}

/*
 * The previous sections was used to update files.
 * The next section is used to view the uploaded files.
 */

/**
 * Displays a list of all uploaded images.
 * If there are no uploaded images an empty table is shown.
 */
try {
	$dbcon = new PDO('sqlite:upload.db');
	$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "SELECT id, imagepath, sequence, interval, active FROM upload WHERE id>0 ORDER BY active DESC, sequence ASC, id ASC";
	$stmt = $dbcon->prepare($sql);
	$stmt->execute();
	$result = $stmt->fetchAll();
	
	echo '<div id="informationForm">';
	echo '<form action="" method="post" enctype="multipart/form-data">';
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="5" style="margin-left: auto; margin-right: auto; text-align: center;">';
	echo '<tr>';
	echo '<th width="60">Thumbnail</td>';
	echo '<th width="20">Interval<br/ >(i sekunder)</td>';
	echo '<th width="10">R&aelig;kkef&oslash;lge</td>';
	echo '<th width="10">Delete</td>';
	echo '</tr>';
	
	$dropdown_menu_items = "";
	foreach ($result as $row) {
		$sequence = $row[2];
		$active = $row[4];
		if ($sequence != "" && $active == 1) {
			$dropdown_menu_items .= '<option>' . $sequence . '</option>';
		}
	}
	if ($dropdown_menu_items == "") {
		$dropdown_menu_items = '<option>' . 'Ingen aktive billeder' . '</option>';
	} else {
		$dropdown_menu_items .= '<option>' . 'S&aelig;t inaktiv' . '</option>';
	}
	
	$i = 0;
	foreach ($result as $row) {
		$id = $row[0]; $imagepath = $row[1]; $sequence = $row[2]; $interval = $row[3]; $active = $row[4];
		$fileName = basename($imagepath);
		$thumbPath = $HTTP_ROOT . $RELATIVE_PATH . '/' . $UPLOAD_DIR . '/' . $THUMBNAIL_DIR. '/' . $THUMBNAIL_PREFIX . $fileName;
		
		echo '<tr>';
		echo '<form action="" method="post" enctype="multipart/form-data">';
		
		echo '<td>' . '<a href="#"><img name="imagePath" src=' . $thumbPath . ' onClick="showImage(' . "'" . $imagepath . "', '" . $fileName . "'" . ');" /></a>' . '</td>';
		echo '<td>' . '<input name="interval' . $i . '" type="text" size="10" value="' . $interval . '" />' . '</td>';
		if ($active) {
			$this_menu_items = str_replace('<option>' . $sequence . '</option>', '<option selected="yes">' . $sequence . '</option>', $dropdown_menu_items);
			echo '<td>' . '<select name="sequence' . $i . '">' . $this_menu_items . '</select>' . '</td>';
		}
		else {
			echo '<td>' . '<select name="sequence' . $i . '">';
			echo '<option>Inaktiv</option>';
			echo '<option>S&aelig;t aktiv</option>';
			echo '</select>' . '</td>';
		}
		
		echo '<td><input type="submit" name="delete" value="Delete" /></td>';
		echo '<input type="hidden" name="imagePath' . $i . '" value="' . $imagepath . '" />';
		echo '</from>';
		
		echo '</tr>';
		
		$i++;
	}
	
	echo '</table>';
	echo '<input type="submit" name="update" value="Update" />';
	echo '</from>';
	echo '</div>';
}
catch (PDOException $e) {
	echo "Could not establish connection to the database.";
	echo "This is unfortunately a fatal error that cannot be ignored and further execution has been halted." . "<br />";
	echo "Please contact an administrator immediately";
	echo "If you don't know any administrators, please visit the contact page." . "<br />";
	echo "<br />" . "Please give the administrator the following information:" . "<br />";
	echo $e;
	die();
}


?>

</div>
</body>
</html>















