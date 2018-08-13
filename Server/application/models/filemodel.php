<?php

class FileModel extends CI_Model {

	function __construct()
	{
		parent::__construct();
        $this->load->helper(array('file'));
	}
    
    function create_dir($path) {
        if (!is_dir($path)) {
            mkdir($path);
        }
    }
    
    function create_file($path, $filename) {
        $this->create_dir($path);
    
        $file = fopen($path . "/".$filename, "w");
        fclose($file);
    }
    
    function do_upload($resource_field_name, $path) {
        if (empty($_REQUEST['picture'])) {
            return null;
        }
        $this->create_dir($path);
        $filename = time() . '.jpg';
        $file = fopen($path . "/".$filename, "w");
        $data = base64_decode($_REQUEST['picture']);
        fwrite($file, $data);
        fclose($file);
        return $filename;
    }
    
    function remove($path) {
        if (is_file($path)) {
            unlink($path);
        }
    }
    
    function remove_all($path) {
        if (is_file($path)) {
            unlink($path);
        } elseif (is_dir($path)) {
            $dir = opendir($path);
            while ($file = readdir($dir)) {
                if ($file != "." && $file != "..") {
                    /* remove all recursively */
                    remove_all($path . "/" . $file);
                }
            }
            closedir($dir);
            rmdir($path);
        } else {
            return FALSE;
        }
    }
}