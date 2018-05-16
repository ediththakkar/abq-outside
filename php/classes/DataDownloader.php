<?php

namespace Edu\Cnm\AbqOutside;
require_once("autoload.php");
//require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Ramsey\Uuid\Uuid;
require_once (dirname(__DIR__, 1) . "\lib\uuid.php");

class DataDownloader {
	/**
	 * https://www.hikingproject.com/data/get-trails?lat=40.0274&lon=-105.2519&maxDistance=10&key=200265
	 * two options: GetTrails, GetTrailsbyTrailId
	 *   method Get trails:
	 *
	 *Required Args:
	 * key: your private key ; Lat (4decimal) ; Lon (4decimal)
	 *
	 *Optional Args:
	 * maxDistance - Max distance, in miles, from lat, lon. Default: 30. Max: 200
	 * maxResults - Max number of trails to return. Default: 10. Max: 500.
	 * sort - Values can be 'quality', 'distance'. Default: quality.
	 * minLength - Min trail length, in miles. Default: 0 (no minimum).
	 * minStars - Min star rating, 0-4. Default: 0.
	 *
	 * URL:  basics + get-trails (method) + ? + "Lat=" + latitude + "Lon=" + longitude + "&" (ampersand) + "maxDistance=" + distance + "&"(ampersand)+ "key=" + private key from HP API
	 * EX: "https://www.hikingproject.com/data/get-trails" + "?" + "lat=" + "40.0274" + "&" + "lon=" + "-105.2519" + "&" + "maxDistance=" + "10" +"&" + "key=" + "200265121-1809e265008042f9977e435839863103"
	 * EX: https://www.hikingproject.com/data/get-trails?lat=40.0274&lon=-105.2519&maxDistance=10&key=200265121-1809e265008042f9977e435839863103
	 *
	 *   method GetTrailsById:
	 *
	 * Required Arguments:
	 * key - Your private key
	 * ids - one or more trail IDs, separated by commas
	 *
	 * URL:  basics + get-trails-by-id (method) + ? + "ids=" + trailExternalId (s) --char7 seperated by commas + "&" ampersand + "key=" + private key from HP API
	 * EX: "https://www.hikingproject.com/data/get-trails-by-id" + "?" + "ids=7000108,7002175,7005207,7001726,7005428" + "&" + "key=200265121-1809e265008042f9977e435839863103"
	 *      https://www.hikingproject.com/data/get-trails-by-id?ids=7000108,7002175,7005207,7001726,7005428&key=200265121-1809e265008042f9977e435839863103
	 *
	 * |||||||
	 */

//	public static function craftUrl() {
//	};

	public static function pullTrails() {
		$trailsX = null;
		$urlG = "https://www.hikingproject.com/data/get-trails?lat=35.085470&lon=-106.649072&maxDistance=200&maxResults=500&key=200265121-1809e265008042f9977e435839863103";
//		try {
//			$features = DataDownloader::readDataJson($urlG);
//		} catch
//		}
		$trailsX = self::readDataJson($urlG);
		//var_dump($trailsX);
//		$pdo = getEncryptedSqlConnection("/etc/apache2/capstone-mysql/outside.ini");

		foreach($trailsX as $value) {
			//var_dump($value);
			$trailId = generateUuidV4();
			$trailExternalId = $value->id;
//			//$trailAddress = $value->attributes->ADDRESS;
			$trailAddress = "outdoors";
//			if isEmpty($value->imgMedium)
			$trailImage = $value->imgMedium;
			$trailName = $value->name;
			$trailLocation = $value->location;
			$trailLat = (float)$value->latitude;
			$trailLong = (float)$value->longitude;
			$trailLength = (float)$value->length;
			$trailSummary = $value->summary;
			$trailAscent = (int)$value->ascent;
			$trailRating = (float)$value->stars;
			try {
			$trail = new Trail($trailId, $trailAddress, $trailAscent, $trailExternalId, $trailImage, $trailLat, $trailLength, $trailLocation, $trailLong, $trailName, $trailRating, $trailSummary);
			var_dump($trail);
//				$trail->insert($pdo);
			} catch(\TypeError $typeError) {
				echo("Gus");
			}
		}
	}

	/** @param $url
	 *  $url and go from there. just straight up craft this and use it
	 */
	public static function readDataJson($url) {

		$context = stream_context_create(["http" => ["ignore_errors" => true, "method" => "GET"]]);
		try {
			//file-get-contents returns file in string context
			if(($jsonData = file_get_contents($url, null, $context)) === false) {
				throw(new \RuntimeException("cannot connect to city server"));
			}
			//decode the Json file
			$jsonConverted = json_decode($jsonData);
			//format
			$jsonFeatures = $jsonConverted->trails;
			//create array from the converted Json file
			$trailsX = \SplFixedArray::fromArray($jsonFeatures);
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($trailsX);
	}
}

echo DataDownloader::pullTrails().PHP_EOL;