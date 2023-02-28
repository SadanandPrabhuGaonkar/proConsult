<?php
namespace Concrete\Package\FormidableFull\Src\Helpers;

use \League\Flysystem\Filesystem;
use \League\Flysystem\AdapterInterface;
use \League\Flysystem\Adapter\Local as Adapter;
use \Concrete\Core\File\ValidationService;
use \Concrete\Core\File\Importer;
use Core;

class FileImporter extends Importer {

    public function getError($code) {
        $im = new Importer();
        return $im->getErrorMessage($code);
    }

    public function import($pointer, $filename = false, $dir = false, $prefix = NULL) {

        if ($filename == false) $filename = basename($pointer);

        $fh = new ValidationService();        
        if (!$fh->file($pointer)) return array('error' => $this->getError(Importer::E_FILE_INVALID));
        if (!$fh->extension($filename))  return array('error' => $this->getError(Importer::E_FILE_INVALID_EXTENSION));

        $fi = Core::make('helper/file');
        $sanitizedFilename = $fi->sanitize($filename);
        $filesystem = new Filesystem(new Adapter(DIR_FILES_UPLOADED_STANDARD));        
        try {
            $src = fopen($pointer, 'rb');
            $filesystem->writeStream($dir.$sanitizedFilename, $src, array(
                'visibility' => AdapterInterface::VISIBILITY_PUBLIC,
                'mimetype' => Core::make('helper/mime')->mimeFromExtension($fi->getExtension($sanitizedFilename))
            ));
        } 
        catch (\Exception $e) {
            return array('error' => $this->getError(self::E_FILE_UNABLE_TO_STORE));
        }
        return array(
            'success' => true,
            'file' => $sanitizedFilename,
        );
    }

    public function delete($filename, $dir = false) {

        $fh = new ValidationService(); 
        if (!$fh->file(DIR_FILES_UPLOADED_STANDARD.$dir.$filename)) return array('error' => $this->getError(Importer::E_FILE_INVALID));
        if (!$fh->extension($filename)) return array('error' => $this->getError(Importer::E_FILE_INVALID_EXTENSION));

        $filesystem = new Filesystem(new Adapter(DIR_FILES_UPLOADED_STANDARD));        
        try {
            $filesystem->delete($dir.$filename);
        } 
        catch (\Exception $e) {
            return array('error' => t('Unable to remove file'));
        }
        return array(
            'success' => true,
        );
    }
}
