# MiniCrm client
PHP library implementing the MiniCRM API https://minicrm.hu

## @todo
- error handling (400,401,404,405,500)
- pager (minicrm API on GET requests only shows 100 elements, need to use 'Pager=x' in parameters to get the 'x' page
- getcontact() query based on fieldnames

## How to use
To create a MiniCRM client, you need to pass a Guzzle client to the constructor.

You will need:
- an API key generated on your MiniCRM admin site
- the System ID of your MiniCRM site
- the basic address of the MiniCRM site, and whether you want to use it in test mode or not
  - Test Mode URL: https://r3-test.minicrm.hu
  - Live URL: https://r3.minicrm.hu

```
<?php

$systemId = 'YOUR_SYSTEM_ID';
$apiKey = 'YOUR_API_KEY';
$baseUri = 'YOUR_URL';
$client = new \GuzzleHttp\Client();

$miniCrm = new Cheppers\MiniCrm\MiniCrmClient($client);
$miniCrm->setOptions([
    'baseUri' => $baseUri,
    'apiKey' => $apiKey,
    'systemId' => $systemId
]);

try {
    $categories = $miniCrm->getCategories();
}
catch (\Cheppers\MiniCrm\MiniCrmClientException $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;

    exit(1);
}
catch (\GuzzleHttp\Exception\GuzzleException $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;

    exit(1);
}

foreach ($categories as $category) {
    echo $category, PHP_EOL;
}

```
