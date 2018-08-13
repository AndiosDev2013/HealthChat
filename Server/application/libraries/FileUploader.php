<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class FileUploader {

	public $save_path				= '/uploads/';
    public $max_file_size_in_bytes  = 2147483647;
    public $extension_whitelist     = array("jpg", "gif", "png", "mp4");
    public $valid_chars_regex       = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';
    public $file_name               = '';
    public $error                   = '';
    
    public function __construct($props = array())
    {
		if (count($props) > 0)
		{
			$this->save_path = getcwd() . (empty($props['save_path']) ? $this->save_path : $props['save_path']);
			$this->max_file_size_in_bytes = ($props['max_file_size_in_bytes'] > 0) ? $props['max_file_size_in_bytes'] : $this->max_file_size_in_bytes;
			$this->extension_whitelist = $props['extension_whitelist'];
		}
        else
        {
			$this->save_path = getcwd() . '/uploads/videos/';
			$this->max_file_size_in_bytes = 2147483647;
			$this->extension_whitelist = array("jpg", "gif", "png", "mp4");
        }
    }
    
    public function do_upload($upload_name = 'Filedate')
    {
        $MAX_FILENAME_LENGTH = 260;
        $file_extension = "";
        $uploadErrors = array(
            0=>"There is no error, the file uploaded with success",
            1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
            2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
            3=>"The uploaded file was only partially uploaded",
            4=>"No file was uploaded",
            6=>"Missing a temporary folder"
        );
        
        
        // Validate the upload
        if (!isset($_FILES[$upload_name])) {
//            $this->HandleError("No upload found in \$_FILES for " . $upload_name);
            $this->error = "No upload found in \$_FILES for " . $upload_name;
			return FALSE;
        } else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
//            $this->HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
            $this->error = $uploadErrors[$_FILES[$upload_name]["error"]];
			return FALSE;
        } else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
//            $this->HandleError("Upload failed is_uploaded_file test.");
            $this->error = "Upload failed is_uploaded_file test.";
			return FALSE;
        } else if (!isset($_FILES[$upload_name]['name'])) {
//            $this->HandleError("File has no name.");
            $this->error = "File has no name.";
			return FALSE;
        }
        
        // Validate the file size (Warning: the largest files supported by this code is 2GB)
        $file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
        if (!$file_size || $file_size > $this->max_file_size_in_bytes) {
//            $this->HandleError("File exceeds the maximum allowed size");
            $this->error = "File exceeds the maximum allowed size";
			return FALSE;
        }
        
        if ($file_size <= 0) {
//            $this->HandleError("File size outside allowed lower bound");
            $this->error = "File size outside allowed lower bound";
			return FALSE;
        }
        
        // Validate file name (for our purposes we'll just remove invalid characters)
        $this->file_name = preg_replace('/[^'.$this->valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
        if (strlen($this->file_name) == 0 || strlen($this->file_name) > $MAX_FILENAME_LENGTH) {
//            $this->HandleError("Invalid file name");
            $this->error = "Invalid file name";
			return FALSE;
        }
        
        // Validate that we won't over-write an existing file
        if (file_exists($this->save_path . $this->file_name)) {
//            $this->HandleError("File with this name already exists");
			return FALSE;
        }
        
        // Validate file extension
        $path_info = pathinfo($_FILES[$upload_name]['name']);
        $file_extension = $path_info["extension"];
        $is_valid_extension = false;
        if (count($this->extension_whitelist) > 0) {
        foreach ($this->extension_whitelist as $extension) {
            if (strcasecmp($file_extension, $extension) == 0) {
                $is_valid_extension = true;
                break;
            }
        }
        }
        else
        {
            $is_valid_extension = true;
        }

        if (!$is_valid_extension) {
//            $this->HandleError("Invalid file extension");
            $this->error = "Invalid file extension";
			return FALSE;
        }
        
        // create file name
        $this->file_name = $this->random_string(10) . '.' . $file_extension;
        
        // Validate file contents (extension and mime-type can't be trusted)
        if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $this->save_path.$this->file_name)) {
//            $this->HandleError("File could not be saved.");
            $this->error = "File could not be saved.";
			return FALSE;
        }

		return TRUE;
    }
    
    function random_string($length) {
        $Rstring = "";
    
        $randomcode = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'A', 'B', 'C', 'd', 'E', 'F', 'G', 'H', 'x', 'J', 'K', 'b', 'M', 'N', 'y', 'P', 'r', 'R', 'S', 'T', 'u', 'V', 'W', 'X', 'Y', 'Z');
    
        mt_srand((double) microtime() * 1000000);
        return mt_rand();
        for ($i = 1; $i <= $length; $i++)
        {
            $Rstring .= $randomcode[mt_rand(1, count($randomcode))];
        }
        return $Rstring;
    }
    
    function HandleError($message) {
    	echo $message;
    }
}

/* End of file FileUploader.php */