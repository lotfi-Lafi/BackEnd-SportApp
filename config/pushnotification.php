<?php

return [
  'gcm' => [
      'priority' => 'high',
      'dry_run' => true,
      'apiKey' => 'AAAAqyAkYnE:APA91bGeKs2GT74IG_jCauw7EevaRZJ77CojxCRd3QpbyZ6smEmfjU451iS0ZuhdBUCKpy21KYAi8EENiCJL_AP-vaXL8jJdoH9uNb3g-jVtYWJO4G1kEyLaae4dRAuY3o7OXERLkL_c',
  ],
  'fcm' => [
        'priority' => 'high',
        'dry_run' => true,
        'apiKey' => 'AAAAqyAkYnE:APA91bGeKs2GT74IG_jCauw7EevaRZJ77CojxCRd3QpbyZ6smEmfjU451iS0ZuhdBUCKpy21KYAi8EENiCJL_AP-vaXL8jJdoH9uNb3g-jVtYWJO4G1kEyLaae4dRAuY3o7OXERLkL_c',
  ],
  'apn' => [
      'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
      'passPhrase' => '1234', //Optional
      'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
      'dry_run' => true
  ]
];