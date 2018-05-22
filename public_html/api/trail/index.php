<?php
/**
 * Created by PhpStorm.
 * User: Gusli
 * Date: 5/21/2018
 * Time: 3:06 PM
 */
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/jwt.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\AbqOutside\ {
	Trail
};

/**
 * Trail API
 **/
//verify the session, if it is not active, start it
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/outside.ini");
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
//sanitize input (id ~profileId)
	$trailId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	//$trailAdddress=
	$trailAscent = filter_input(INPUT_GET, "profileActivationToken", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileEmail = filter_input(INPUT_GET, "profileEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileUserName = filter_input(INPUT_GET, "profileUserName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$trailExternalId;
	$trailImage = filter_input(INPUT_GET, "trailImage", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$trailLat = filter_input(INPUT_GET, "trailLat", FILTER_SANITIZE_NUMBER_FLOAT);
	$trailLength = filter_input(INPUT_GET, "trailLat", FILTER_SANITIZE_NUMBER_FLOAT);
	$trailLocation = filter_input(INPUT_GET, "trailLocation", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$trailLong;
	$trailName = filter_input(INPUT_GET, "profileActivationToken", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$trailRating;
	$trailSummary = filter_input(INPUT_GET, "profileActivationToken", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	$distance = filter_input(INPUT_GET, "distance", FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$userLat = filter_input(INPUT_GET, "userLat", FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$userLong = filter_input(INPUT_GET, "userLong", FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	if($method === "GET") {
//set XSRF cookie
		setXsrfCookie();
		if(empty($id) === false) {
			$trail = Trail::getTrailByTrailId($pdo, $id);
			if($trail !== null) {
				$reply->data = $trail;
			}

		} else if(empty($trailName) === false) {
			$trail = Trail::getTrailByDistance($pdo, $*********;
			if($trail !== null) {
				$reply->data = $trail;
			}

		} catch(Exception $exception) {
			$reply->status = $exception->getCode();
			$reply->message = $exception->getMessage();
			$reply->trace = $exception->getTraceAsString();
		} catch(TypeError $typeError) {
			$reply->status = $typeError->getCode();
			$reply->message = $typeError->getMessage();
		}
// In these lines, the Exceptions are caught and the $reply object is updated with the data from the caught exception. Note that $reply->status will be updated with the correct error code in the case of an Exception.
header("Content-type: application/json");
// sets up the response header.
if($reply->data === null) {
	unset($reply->data);
}
echo json_encode($reply);