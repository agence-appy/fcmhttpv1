# Laravel FCM Http V1 API Package

 A [Laravel](https://laravel.com/) package that lets you use the new FCM Http V1 API and send push notifications with ease.
 
 ## Summary
 1. [Install](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#install)
    - [Firebase](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#firebase)
    - [Laravel](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#laravel)
    - [Laravel PWA](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#laravel-pwa)
 2. [Usage](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#usage)
    - [Topics](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#firebase)
      - [Subscribe](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#subscribe)
      - [Unsubscribe](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#unsubscribe)
      - [List subscriptions](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#list-subscriptions)
    - [Notification](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#notification)
      - [Send to unique token](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#send-to-unique-token)
      - [Send to topic](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#send-to-topic)
 
 ## Install
If your firebase project is already setup, you can skip that part and go to the [Usage section](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#usage) section.  

The installation will take two steps. First we will build and manage the firebase project through the Firebase Console. Then we will see how you can implement the Firebase FCM Http V1 in your awesome Laravel project.


### Firebase

1. Go to the [Firebase console](https://console.firebase.google.com/u/0/).

2. Create a project  
![Capture d’écran 2022-06-30 143010](https://user-images.githubusercontent.com/92929363/177950500-c2ab7f98-1593-461e-82c5-7d2065474e74.png)
3. Add a name    
![Capture d’écran 2022-07-08 102739](https://user-images.githubusercontent.com/92929363/177950903-4b0ade29-2ce4-423f-980c-299444549030.png)
4. Choose if you want to enable Analytics and create the project.
5. Add a Web App  
![Capture d’écran 2022-07-08 103535](https://user-images.githubusercontent.com/92929363/177952387-b80d53e3-53f4-45b4-9050-e849b58e4e24.png)
6. Add an app nickname   
![Capture d’écran 2022-07-08 103625](https://user-images.githubusercontent.com/92929363/177952640-df8a5b86-7ce6-483e-9baf-a97751343378.png)
7. Go into the project settings of the app, switch to the Service accounts tab then click on Generate new private key. It will download a json file containing credentials for your app.
8. Go to project settings, cloud messaging tab and enable CloudMessaging API ( click on the 3 dots on the right, Manage API in Google Cloud Console, and enable the API)  
![Capture d’écran 2022-07-08 142946](https://user-images.githubusercontent.com/92929363/177992435-e29223f7-6189-4052-baa1-c0455b2cc092.png)
9. Refresh firebase console page, a server key will be displayed under the Cloud Messaging API. (in Cloud Messaging tab)

Firebase configuration is now completed.


### Laravel

1. Put the downloaded json at the root of the project. (JSON downloaded at step 7 of Firebase configuration)  
![Capture d’écran 2022-07-08 144029](https://user-images.githubusercontent.com/92929363/177993938-910ddac2-0472-45f3-9c30-3568e0e0244b.png)
2. Go to Firebase Console -> Project Settings -> General and watch firebaseConfig.  
![Capture d’écran 2022-07-08 144454](https://user-images.githubusercontent.com/92929363/177994579-978d7fbc-5d23-4302-a66e-9d86edb8eb76.png)
3. Assign values to the .env variables

```env
FCM_API_KEY="<firebase apiKey>"
FCM_AUTH_DOMAIN="<firebase authDomain>"
FCM_PROJECT_ID="<firebase projectId>"
FCM_STORAGE_BUCKET="<firebase storageBucket>"
FCM_MESSAGIN_SENDER_ID="<firebase messagingSenderId>"
FCM_APP_ID="<firebase appId>"
FCM_JSON="<name of the json file downloaded at firebase step 7 install>"
FCM_API_SERVER_KEY=<api server key step 8-9 of firebase install>
```
4. Package installation
```
composer require appy/fcmhttpv1
```

5. Register the provider in config/app.php

```php
Appy\FcmHttpV1\FcmProvider::class,
```

6. Publish config file
```
php artisan vendor:publish --tag=fcmhttpv1 --ansi --force
```

### Laravel PWA
1. Please follow this [tutorial](https://github.com/silviolleite/laravel-pwa) to configure Laravel PWA.

2. Create a file "firebase-messaging-sw.js" at public folder of your project.

```js
// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
firebase.initializeApp({
  apiKey: 'api-key',
  authDomain: 'project-id.firebaseapp.com',
  databaseURL: 'https://project-id.firebaseio.com',
  projectId: 'project-id',
  storageBucket: 'project-id.appspot.com',
  messagingSenderId: 'sender-id',
  appId: 'app-id',
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
```
## Usage

### Topics

Topics are used to make groups of device tokens. They will allow you to send notification directly to the topic where users are registered in.

#### Subscribe

To subscribe tokens to a topic :

```php
use Appy\FcmHttpV1\FcmTopicHelper;

$tokens = ["first token", ... , "last token"];
FcmTopicHelper::subscribeToTopic($tokens, "myTopic");
```
#### Unsubscribe

```php
use Appy\FcmHttpV1\FcmTopicHelper;

$tokens = ["first token", ... , "last token"];
FcmTopicHelper::unsubscribeToTopic($tokens, "myTopic");
```

#### List subscriptions

```php
use Appy\FcmHttpV1\FcmTopicHelper;

$token = "your awesome device token";
FcmTopicHelper::getTopicsByToken($token);

```

## Notification

You can send notification to specific user or to topics.

### Send to unique token
```php
use Appy\FcmHttpV1\FcmNotification;

$notif = new FcmNotification();
$notif->setTitle("Title")->setBody("Message here")->setIcon("icon.png")->setToken("put device token here")->setClickAction("/news")->send();

```

### Send to topic
```php
use Appy\FcmHttpV1\FcmNotification;

$notif = new FcmNotification();
$notif->setTitle("Title")->setBody("Message here")->setIcon("icon.png")->setTopic("general_topic")->setClickAction("/news")->send();

```

