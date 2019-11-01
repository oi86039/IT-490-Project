<?php
  //curl_exec ( resource $ch ) : mixed
  require 'vendor/autoload.php';
  use \Mailjet\Resources;
  $mj = new \Mailjet\Client('7b19a3dba62d0e21400302140515772e','50ff09d1196ae315b00310a86305b97f',true,['version' => 'v3.1']);
  $body = [
    'Messages' => [
      [
        'From' => [
          'Email' => "ars66@njit.edu",
          'Name' => "anwar"
        ],
        'To' => [
          [
            'Email' => "entermatrixxx@gmail.com",
            'Name' => "omar"
          ]
        ],
        'Subject' => "Greetings from Mailjet.",
        'TextPart' => "My first Mailjet email",
        'HTMLPart' => "<h3>Dear passenger 1, welcome please click the verify link.<a href='192.168.15.7/IT490/IT-490/verify.php?user=omar'>Skyscanner</a>!</h3><br />Take Flight!",
        'CustomID' => "AppGettingStartedTest"
      ]
    ]
  ];
  $response = $mj->post(Resources::$Email, ['body' => $body]);
  $response->success() && var_dump($response->getData());
?>

