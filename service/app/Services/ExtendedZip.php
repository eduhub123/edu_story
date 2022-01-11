<?php


namespace App\Services;


class ExtendedZip extends \ZipArchive
{

    // Member function to add a whole file system subtree to the archive
    public function addTree($dirname, $localname = '')
    {
        if ($localname) {
            $this->addEmptyDir($localname);
        }
        $this->_addTree($dirname, $localname);
    }

    // Internal function, to recurse
    protected function _addTree($dirname, $localname)
    {
        $dir = opendir($dirname);
        while (($filename = readdir($dir)) !== false) {
            // Discard . and ..
            if ($filename == '.' || $filename == '..') {
                continue;
            }

            // Proceed according to type
            $path      = $dirname . '/' . $filename;
            $localpath = $localname || $localname == "0" ? ($localname . '/' . $filename) : $filename;

            if (is_dir($path)) {
                // Directory: add & recurse
                $this->addEmptyDir($localpath);
                $this->_addTree($path, $localpath);
            } else {
                if (is_file($path)) {
                    // File: just add
                    $this->addFile($path, $localpath);
                }
            }
        }
        closedir($dir);
    }

    public static function zip($pathFile, $path)
    {
        ExtendedZip::zipTree($path, $pathFile, \ZipArchive::CREATE);
    }

    public static function zipTree($dirname, $zipFilename, $flags = 0, $localname = '')
    {
        $Zip = new self();
        $Zip->open($zipFilename, $flags);
        $Zip->addTree($dirname, $localname);
        $Zip->close();
    }

    public static function zipFile($pathFile, $zipFilename, $flags = \ZipArchive::CREATE, $localname = '')
    {
        $zip = new self();
        if ($zip->open($zipFilename, $flags) === true) {
            $zip->addFile($pathFile, $localname);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }
}
