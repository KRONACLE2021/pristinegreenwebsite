<?php
$recaptchaToken = $_POST['g-recaptcha-response'];
$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array(
    'secret' => '6Lcwf_4pAAAAAAz7noRKWEcFW-5UT_O_QLHUiS5f', // Replace with your Secret key
    'response' => $recaptchaToken
);

$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$responseData = json_decode($response);

if (!$responseData->success) {
    // Handle reCAPTCHA verification failure
    die('reCAPTCHA verification failed.');
}

// Proceed with registration process
// Process $_POST data, validate, and store in database

// Redirect or respond with success message
?>


<?php

// Include Google Cloud dependencies using Composer
// composer require google/cloud-recaptcha-enterprise
require 'vendor/autoload.php';

use Google\Cloud\RecaptchaEnterprise\V1\RecaptchaEnterpriseServiceClient;
use Google\Cloud\RecaptchaEnterprise\V1\Event;
use Google\Cloud\RecaptchaEnterprise\V1\Assessment;
use Google\Cloud\RecaptchaEnterprise\V1\TokenProperties\InvalidReason;

/**
* Create an assessment to analyze the risk of a UI action.
* @param string $siteKey The key ID for the reCAPTCHA key (See https://cloud.google.com/recaptcha-enterprise/docs/create-key)
* @param string $token The user's response token for which you want to receive a reCAPTCHA score. (See https://cloud.google.com/recaptcha-enterprise/docs/create-assessment#retrieve_token)
* @param string $project Your Google Cloud project ID
*/
function create_assessment(
   string $siteKey,
   string $token,
   string $project
): void {
// TODO: To avoid memory issues, move this client generation outside
// of this example, and cache it (recommended) or call client.close()
// before exiting this method.
$client = new RecaptchaEnterpriseServiceClient();
$projectName = $client->projectName($project);

   $event = (new Event())
       ->setSiteKey($siteKey)
       ->setToken($token);

   $assessment = (new Assessment())
       ->setEvent($event);

   try {
       $response = $client->createAssessment(
           $projectName,
           $assessment
       );

       // You can use the score only if the assessment is valid,
       // In case of failures like re-submitting the same token, getValid() will return false
       if ($response->getTokenProperties()->getValid() == false) {
           printf('The CreateAssessment() call failed because the token was invalid for the following reason: ');
           printf(InvalidReason::name($response->getTokenProperties()->getInvalidReason()));
       } else {
           printf('The score for the protection action is:');
           printf($response->getRiskAnalysis()->getScore());

           // Optional: You can use the following methods to get more data about the token
           // Action name provided at token generation.
           // printf($response->getTokenProperties()->getAction() . PHP_EOL);
           // The timestamp corresponding to the generation of the token.
           // printf($response->getTokenProperties()->getCreateTime()->getSeconds() . PHP_EOL);
           // The hostname of the page on which the token was generated.
           // printf($response->getTokenProperties()->getHostname() . PHP_EOL);
       }
   } catch (exception $e) {
       printf('CreateAssessment() call failed with the following error: ');
       printf($e);
   }
}

// TODO(Developer): Replace the following before running the sample
create_assessment(
   'YOUR_RECAPTCHA_KEY',
   'YOUR_USER_RESPONSE_TOKEN',
   'YOUR_GOOGLE_CLOUD_PROJECT_ID'
);
?>