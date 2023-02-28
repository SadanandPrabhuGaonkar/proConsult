<?php
namespace Application\Concrete\Helpers;

use Concrete\Core\Entity\File\File;
use Concrete\Theme\Concrete\PageTheme;
use Core;

class ImageHelper
{

    public static function getAbsPathFromDoctrineFileObj($img)
    {
        $file_storage       = $img->getFileStorageLocationObject();
        $path1              =$file_storage->getConfigurationObject()->getRootPath();
        $path_2             =$img->getFileResource()->getPath();
        $orig_file_abs_path = $path1 .'/'.$path_2;
        return $orig_file_abs_path;
    }

    public static function getAbsPathFromImageUrl($url_path)
    {
        //get thumb path form thumb url path
        $obj_parms = parse_url($url_path);
        $thumb_path = $obj_parms['path'];
        $cache_thumb_abs_path = $_SERVER['DOCUMENT_ROOT'].$thumb_path;
        return $cache_thumb_abs_path;
    }

    public function getThumbnail($image, $width = 1800, $height = 1800, $crop = false)
    {
        /** @var File $file */
        /** @var \Concrete\Core\File\Image\BasicThumbnailer $ih */
        $ih = Core::make('helper/image');
        
        if ($image && $image instanceof File) {
            $image = $ih->getThumbnail($image, $width, $height, $crop);
            if ($image) {
                $thumbnail = $image->src;
            }
        }
        if (!$thumbnail) {
            $thumbnail = BASE_URL . PageTheme::getSiteTheme()->getThemeURL() . '/dist/images/placeholder.jpg';
        }
        return $thumbnail;
    }

}