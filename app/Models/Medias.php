<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $MediatypeId
 * @property int    $CrUserId
 * @property string $MediaName
 * @property string $MediaDesc
 * @property string $FilePath
 */
class Medias extends Model
{
    private static $_instance = null;

    /**
     * @return NewsImg
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'medias';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'MediatypeId', 'MediaName', 'MediaDesc', 'FilePath', 'CrUserId'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'int', 'MediatypeId' => 'int', 'MediaName' => 'string', 'MediaDesc' => 'string', 'FilePath' => 'string', 'CrUserId' => 'int'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [

    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    // Scopes...

    // Functions ...
    protected static function storage()
    {
        return new Medias();
    }

    public function add($data)
    {
        return self::storage()->add($data);
    }

    public function getYDir()
    {
        return self::storage()->getYDir();
    }

    public function getYMonth($y)
    {
        return self::storage()->getYMonth($y);
    }

    public function getYMDay($y, $m)
    {
        return self::storage()->getYMDay($y, $m);
    }

    public function getFiles($y, $m, $d){
        return self::storage()->getFiles($y, $m, $d);
    }
    public static function createDir($dir)
    {
        if ($dir) {
            // check exists dir
            if (!is_dir($dir)) {
                // create dir
                @chown($dir, 'apache');
                @chmod($dir, 0777);
                return mkdir($dir, 0777, true);
            }
        }
        return null;
    }
    function renameFileExist($folderPath, $fileName)
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileName  = str_replace('.'. $extension, '', $fileName);
        $fileName  .= '.' . $extension;
        return $fileName;
    }
    public static function imageResize($src, $dst, $width, $height, $crop = 0)
    {
        //tăng kích thước ảnh 60x60 lên 140x140 cho tin đăng
        if($width==60) { $width=140; }
        if($height==60){ $height=140; }

        $fileInfo = getimagesize($src);
        if (!list($w, $h) = $fileInfo) return null;
        $type = $fileInfo['mime'];
        switch ($type) {
            case 'image/bmp':
                $img = imagecreatefromwbmp($src);
                break;
            case 'image/gif':
                $img = imagecreatefromgif($src);
                break;
            case 'image/jpeg':
                $img = imagecreatefromjpeg($src);
                imageinterlace($img, 1);
                break;
            case 'image/png':
                $img = imagecreatefrompng($src);
                imageinterlace($img, 1);
                break;
            default :
                return null;
        }

        // resize
        if ($crop) {
            if ($w < $width or $h < $height) return null;
            /*
            $ratio = max($width / $w, $height / $h);
            $h     = $height / $ratio;
            $x     = ($w - $width / $ratio) / 2;
            $w     = $width / $ratio;
            */
            $originalAspect = $w / $h;
            $thumbAspect = $width / $height;
            if ($originalAspect >= $thumbAspect) {
                // If image is wider than thumbnail (in aspect ratio sense)
                $nh = $height;
                $nw = $w / ($h / $height);
            } else {
                // If the thumbnail is wider than the image
                $nw = $width;
                $nh = $h / ($w / $width);
            }

            $x  = 0;
            $y  = 0;
            $dx = 0 - ($nw - $width) / 2; // Center the image horizontally
            $dy = 0 - ($nh - $height) / 2; // Center the image vertically

            $new = imagecreatetruecolor($width, $height);

            $width = $nw;
            $height = $nh;
        } else {
            if ($w < $width and $h < $height) return null;
            $ratio  = min($width / $w, $height / $h);
            $width  = $w * $ratio;
            $height = $h * $ratio;
            $x      = 0;
            $y      = 0;
            $dx     = 0;
            $dy     = 0;

            $new = imagecreatetruecolor($width, $height);
        }

        // preserve transparency
        if ($type == "image/gif" or $type == "image/png") {
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        imagecopyresampled($new, $img, $dx, $dy, $x, $y, $width, $height, $w, $h);

        switch ($type) {
            case 'image/bmp':
                imagewbmp($new, $dst);
                break;
            case 'image/gif':
                imagegif($new, $dst);
                break;
            case 'image/jpeg':
                imageinterlace($new, 1);
                imagejpeg($new, $dst, 85);
                break;
            case 'image/png':
                imageinterlace($new, 1);
                imagepng($new, $dst, 9);  // bool imagepng ( resource $image [, mixed $to [, int $quality [, int $filters ]]] ) Compression level: from 0 (no compression) to 9
                break;
        }
        imagedestroy($img);
        imagedestroy($new);
        return true;
    }
    // Relations ...
}
