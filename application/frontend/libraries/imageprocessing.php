<?
class UnsupportedImageFormatException extends Exception
{
	public function __construct($message = "The specified image file format is unsupported.", $code = 0)
	{
			parent::__construct($message, $code);
	}
}

class InvalidConfigurationParameterException extends Exception
{
	public function __construct($message = "The specified configuration parameter is invalid.", $code = 0)
	{
			parent::__construct($message, $code);
	}
}

class ImageInfo
{
	public $path;
	public $format;
	public $width;
	public $height;
	public $relevantColors;
	public $relevantColorRanges;
}

class ImageProcessing
{
	private $config = array('gamutRanges' 					  => 3,
							'maxRadialPixelValue' 			  => 5,
							'minimumColorOccurencePercentage' => 0.4,
							'maxRelevantColors' 			  => 10,
							);

	private static $instance = null;
	public function __construct() {}

	private static function getInstance()
	{
		if(!isset(self::$instance))
			self::$instance = new ImageProcessing();

		return self::$instance;
	}

	public static function SetConfigurationParameter($key, $value)
	{
		if(!isset(self::getInstance()->config[$key]))
			throw new InvalidConfigurationParameterException();

		self::getInstance()->config[$key] = $value;
	}

	public static function GetImageInfo($imagePath)
	{
		if(!preg_match("/(\.png|\.jpeg|\.jpg)$/ui", $imagePath))
			throw new UnsupportedImageFormatException();

		$imageFormat 		 =  preg_match("/\.png$/ui", $imagePath)? 'png' : 'jpeg';
		$imageCreationMethod = 'imagecreatefrom' . $imageFormat;

		$image 		 = $imageCreationMethod($imagePath);
		$imageWidth  = imagesx($image);
		$imageHeight = imagesy($image);

		$colorRangeOccurences 		 = array();
		$colorOccurencesWithinRanges = array();

		// gather info on individual pixels
		for($x = 0; $x < $imageWidth; $x++)
		{
			for($y = 0; $y < $imageHeight; $y++)
			{
				$pixelColor = '' . imagecolorat($image, $x, $y);
				$r = (int)(($pixelColor >> 16) & 0xFF);
				$g = (int)(($pixelColor >>  8) & 0xFF);
				$b = (int)($pixelColor & 0xFF);

				$colorKey = $r . ',' . $g . ',' . $b;
				$rangeKey = self::getColorGamutRange($r) . ',' . self::getColorGamutRange($g) . ',' . self::getColorGamutRange($b);

				if(!isset($colorRangeOccurences[$rangeKey]))
				{
					$colorRangeOccurences[$rangeKey] = 0;
					$colorOccurencesWithinRanges[$rangeKey] = array();
				}

				if(!isset($colorOccurencesWithinRanges[$rangeKey][$colorKey]))
				{
					$colorOccurencesWithinRanges[$rangeKey][$colorKey] = 0;
				}
				
				$pixelValue = self::getPixelValue($imageWidth, $imageHeight, $x, $y);
				$colorOccurencesWithinRanges[$rangeKey][$colorKey] += $pixelValue;
				$colorRangeOccurences[$rangeKey] 				   += $pixelValue;
			}
		}

		arsort($colorRangeOccurences);

		$minimumOccurences 	 = floor($imageWidth * $imageHeight * self::getInstance()->config['minimumColorOccurencePercentage'] / 100);
		$ellectedColorRanges = array();
		$colorsEllected		 = 0;

		foreach($colorRangeOccurences as $rgb => $occurences)
		{
			if($colorsEllected >= self::getInstance()->config['maxRelevantColors'])
				break;

			if($occurences >= $minimumOccurences)
			{
				arsort($colorOccurencesWithinRanges[$rgb]);
				$rangeColorsByRelevance = array_keys($colorOccurencesWithinRanges[$rgb]);

				$ellectedColorRanges[$rgb] = $rangeColorsByRelevance[0];
			}

			$colorsEllected++;
		}

		$imageInfo = new ImageInfo();
		$imageInfo->path 				= $imagePath;
		$imageInfo->format 				= $imageFormat;
		$imageInfo->width 				= $imageWidth;
		$imageInfo->height 				= $imageHeight;
		$imageInfo->relevantColors 		= array_values($ellectedColorRanges);
		$imageInfo->relevantColorRanges = array_keys($ellectedColorRanges);

		return $imageInfo;
	}

	private static function getColorGamutRange($colorValue)
	{
		$sensitivity = floor(255 / self::getInstance()->config['gamutRanges']);

		// ensure the correct number of intervals
		if($colorValue == 255 & 255 % self::getInstance()->config['gamutRanges'] == 0)
			$colorValue = 254;

		return floor($colorValue / $sensitivity) * $sensitivity;
	}

	private static function getPixelValue($imageWidth, $imageHeight, $pixelX, $pixelY)
	{
		$xCenter = $imageWidth / 2;
		$xCenterOffset = abs($pixelX - $xCenter);
		$xValue = ($xCenter - $xCenterOffset) * self::getInstance()->config['maxRadialPixelValue'] / $xCenter;

		$yCenter = $imageHeight / 2;
		$yCenterOffset = abs($pixelY - $yCenter);
		$yValue = ($yCenter - $yCenterOffset) * self::getInstance()->config['maxRadialPixelValue'] / $yCenter;

		return max(round(($xValue + $yValue) / 2), 1);
	}
}
?>