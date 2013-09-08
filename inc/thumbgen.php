<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function thumbgen($filename,$width,$height)
{
    if(!isset($filename) || empty($filename) || !extension_loaded('gd'))
        die();

    if(!file_exists(basePath.'/inc/images/'.$filename))
        die();

    list($breite, $hoehe, $type) = getimagesize(basePath.'/inc/images/'.$filename);
    $neueBreite = empty($width) || $width <= 1 ? $breite : convert::ToInt($width);
    $neueHoehe = empty($height) || $height <= 1 ? $hoehe*$neueBreite/$breite : convert::ToInt($height);

    if(!cache_thumbgen || Cache::check_binary('thumbgen_file_'.$filename.'_'.$neueBreite.'_'.$neueHoehe.'_'.$type))
    {
        if(extension_loaded('imagick') && use_imagick) //Use Imagick
        {
            function scaleImage($x,$y,$cx,$cy)
            {
                list($nx,$ny)=array($x,$y);
                if ($x>=$cx || $y>=$cx)
                {
                    if ($x>0) $rx=$cx/$x;
                    if ($y>0) $ry=$cy/$y;

                    if ($rx>$ry)
                        $r=$ry;
                    else
                        $r=$rx;

                    $nx=intval($x*$r);
                    $ny=intval($y*$r);
                }

                return array($nx,$ny);
            }

            $imagick = new Imagick(); //New Objekt
            $imagick->readImage(basePath.'/inc/images/'.$filename);

            if(empty($height) || $height <= 1 || empty($width) || $width <= 1)
                list($newX,$newY)=scaleImage($imagick->getImageWidth(), $imagick->getImageHeight(), $neueBreite, $neueHoehe);
            else
            {
                $newX = $neueBreite;
                $newY = $neueHoehe;
            }


            $imagick->thumbnailImage($newX,$newY);

            switch($type)
            {
                case 1: ## GIF ##
                    header("Content-Type: image/gif");
                    $imagick->setFormat('GIF');
                break;
                default:
                case 2: ## JPEG ##
                    header("Content-Type: image/jpeg");
                    $imagick->setFormat('JPEG');
                break;
                case 3: ## PNG ##
                    header("Content-Type: image/png");
                    $imagick->setFormat('PNG');
                break;
            }

            $bin=$imagick->getimage();

            if(cache_thumbgen)
                @Cache::set_binary('thumbgen_file_'.$filename.'_'.$neueBreite.'_'.$neueHoehe.'_'.$type, $bin, 'inc/images/'.$filename);

            $imagick->clear(); unset($imagick);
            exit($bin);
        }
        else //Use GD
        {
            header("Cache-Control: private, max-age=10800, pre-check=10800");
            header("Pragma: private");
            header("Expires: " . date(DATE_RFC822,strtotime(" 2 day")));
            $neuesBild = imagecreatetruecolor($neueBreite, $neueHoehe); $bin = null;
            switch($type)
            {
                case 1: ## GIF ##
                    header("Content-Type: image/gif");
                    @imagecolortransparent($neuesBild, @imagecolorallocate($neuesBild, 0, 0, 0));
                    $altesBild = @imagecreatefromgif(basePath.'/inc/images/'.$filename);
                    @imagecopyresampled($neuesBild, $altesBild,0,0,0,0, $neueBreite, $neueHoehe, $breite, $hoehe);

                    ob_start();
                    @imagegif($neuesBild);
                    $bin = ob_get_contents();
                    ob_end_clean();
                break;
                default:
                case 2: ## JPEG ##
                    header("Content-Type: image/jpeg");
                    $altesBild = @imagecreatefromjpeg(basePath.'/inc/images/'.$filename);
                    @imagecopyresampled($neuesBild, $altesBild,0,0,0,0, $neueBreite, $neueHoehe, $breite, $hoehe);

                    ob_start();
                    @imagejpeg($neuesBild, null, 100);
                    $bin = ob_get_contents();
                    ob_end_clean();
                break;
                case 3: ## PNG ##
                    header("Content-Type: image/png");
                    @imagecolortransparent($neuesBild, @imagecolorallocate($neuesBild, 0, 0, 0));
                    @imagealphablending($neuesBild, false);
                    @imagesavealpha($neuesBild, true);
                    $altesBild = @imagecreatefrompng(basePath.'/inc/images/'.$filename);
                    @imagecopyresampled($neuesBild, $altesBild,0,0,0,0, $neueBreite, $neueHoehe, $breite, $hoehe);

                    ob_start();
                    @imagepng($neuesBild);
                    $bin = ob_get_contents();
                    ob_end_clean();
                break;
            }

            if(cache_thumbgen)
                Cache::set_binary('thumbgen_file_'.$filename.'_'.$neueBreite.'_'.$neueHoehe.'_'.$type, $bin, 'inc/images/'.$filename);

            if(is_resource($altesBild))
                imagedestroy($altesBild);

            if(is_resource($neuesBild))
                imagedestroy($neuesBild);
        }

        exit($bin);
    }
    else
    {
        header("Cache-Control: private, max-age=10800, pre-check=10800");
        header("Pragma: private");
        header("Expires: " . date(DATE_RFC822,strtotime(" 2 day")));
        switch($type)
        {
            case 1: ## GIF ##
                header("Content-Type: image/gif");
            break;
            default:
            case 2: ## JPEG ##
                header("Content-Type: image/jpeg");
            break;
            case 3: ## PNG ##
                header("Content-Type: image/png");
            break;
        }

        exit(Cache::get_binary('thumbgen_file_'.$filename.'_'.$neueBreite.'_'.$neueHoehe.'_'.$type));
    }
}