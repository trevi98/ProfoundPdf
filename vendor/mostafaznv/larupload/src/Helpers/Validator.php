<?php

namespace Mostafaznv\Larupload\Helpers;

use Exception;
use Mostafaznv\Larupload\LaruploadEnum;

class Validator
{
    /**
     * Validate naming methods
     *
     * @param string $value
     * @throws Exception
     */
    public static function namingMethodIsValid(string $value)
    {
        $namingMethods = [LaruploadEnum::SLUG_NAMING_METHOD, LaruploadEnum::HASH_FILE_NAMING_METHOD, LaruploadEnum::TIME_NAMING_METHOD];

        if (!in_array($value, $namingMethods)) {
            throw new Exception("Naming method [$value] is not valid. valid methods: [" . implode(', ', $namingMethods) . "]");
        }
    }

    /**
     * Validate image processing library
     *
     * @param string $value
     * @throws Exception
     */
    public static function imageProcessingLibraryIsValid(string $value)
    {
        $imageLibrary = [LaruploadEnum::GD_IMAGE_LIBRARY, LaruploadEnum::IMAGICK_IMAGE_LIBRARY, LaruploadEnum::GMAGICK_IMAGE_LIBRARY];

        if (!in_array($value, $imageLibrary)) {
            throw new Exception("Image processing library [$value] is not valid. valid librarys: [" . implode(', ', $imageLibrary) . "]");
        }
    }

    /**
     * Validate style
     *
     * @param string $name
     * @param array $type
     * @param string|null $mode
     * @param int|null $width
     * @param int|null $height
     * @throws Exception
     */
    public static function styleIsValid(string $name, array $type = [], string $mode = null, int $width = null, int $height = null)
    {
        self::modeIsValid($mode);

        // validate name
        if (is_numeric($name)) {
            throw new Exception("Style name [$name] is numeric. please use string name for your style");
        }

        // validate width and height
        if ($mode == LaruploadEnum::CROP_STYLE_MODE) {
            if ($height === null or $height === 0) {
                throw new Exception('Height is required when you are in crop mode');
            }

            if ($width === null or $width === 0) {
                throw new Exception('Width is required when you are in crop mode');
            }
        }

        // validate type
        if (!empty($type)) {
            $types = [LaruploadEnum::IMAGE_STYLE_TYPE, LaruploadEnum::VIDEO_STYLE_TYPE];

            if (count(array_intersect($type, $types)) != count($type)) {
                throw new Exception('Style type [' . implode(', ', $type) . '] is not valid. valid types: [' . implode(', ', $types) . ']');
            }
        }
    }

    /**
     * Validate stream styles
     *
     * @param string $name
     * @param int|null $width
     * @param int|null $height
     * @param $audioBitrate
     * @param $videoBitrate
     * @throws Exception
     */
    public static function streamIsValid(string $name, int $width, int $height, $audioBitrate, $videoBitrate)
    {
        if (ctype_alnum($name) === false) {
            throw new Exception('stream name [' . $name . '] should be an alpha numeric string');
        }

        if($width <= 0) {
            throw new Exception('width [' . $width . '] should be a positive number');
        }

        if($height <= 0) {
            throw new Exception('height [' . $height . '] should be a positive number');
        }

        self::numericBitrateRule('audioBitrate', $audioBitrate);
        self::numericBitrateRule('videoBitrate', $videoBitrate);
    }

    /**
     * Validate mode
     *
     * @param string|null $mode
     * @throws Exception
     */
    public static function modeIsValid(string $mode = null)
    {
        if ($mode) {
            $modes = [
                LaruploadEnum::LANDSCAPE_STYLE_MODE, LaruploadEnum::PORTRAIT_STYLE_MODE, LaruploadEnum::CROP_STYLE_MODE,
                LaruploadEnum::EXACT_STYLE_MODE, LaruploadEnum::AUTO_STYLE_MODE
            ];

            if (!in_array($mode, $modes)) {
                throw new Exception("Style mode [$mode] is not valid. valid modes: [" . implode(', ', $modes) . "]");
            }
        }
    }

    /**
     * Validate Bitrate
     *
     * @param $attribute
     * @param $value
     * @throws Exception
     */
    protected static function numericBitrateRule($attribute, $value)
    {
        $units = ['k', 'm'];
        $value = str_ireplace($units, '', $value);

        if (!is_numeric($value)) {
            throw new Exception($attribute . ' is not a valid bitrate');
        }
    }
}
