# Laravel FCM Http V1 API Package

 A [Laravel](https://laravel.com/) package that allow you tu use the new FCM Http V1 API and send push notification easily.  
 
 ## Summary
 1. [Install](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#install)
    - [Firebase](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#firebase)
    - [Laravel](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#laravel)
 2. [Usage](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#usage)
    - [Topics](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#firebase)
      - [Subscribe](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#subscribe)
      - [Unsubscribe](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#unsubscribe)
      - [List subscriptions](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#list-subscriptions)
    - [Notification](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#notification)
      - [Send to unique token](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#send-to-unique-token)
      - [Send to topic](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#send-to-topic)
 
 ## Install
If your firebase project is already configured, you can skip this part and go to the [Usage](https://github.com/agence-appy/fcmhttpv1/edit/master/README.md#usage) section.  

The installation will be in 2 steps. First we're gonna create and manage the firebase project through the [Firebase Console](https://console.firebase.google.com/u/0/). Then we'll see how to implement the Firebase FCM Http V1 in your awesome Laravel project.


### Firebase

1. Go to the [Firebase console](https://console.firebase.google.com/u/0/).

2. Create a project  
![Capture d’écran 2022-06-30 143010](https://user-images.githubusercontent.com/92929363/177950500-c2ab7f98-1593-461e-82c5-7d2065474e74.png)
3. Add a name    
![Capture d’écran 2022-07-08 102739](https://user-images.githubusercontent.com/92929363/177950903-4b0ade29-2ce4-423f-980c-299444549030.png)
4. Choose if you enable Analytics and create the project.  
5. Add a Web App  
![Capture d’écran 2022-07-08 103535](https://user-images.githubusercontent.com/92929363/177952387-b80d53e3-53f4-45b4-9050-e849b58e4e24.png)
6. Add an app nickname   
![Capture d’écran 2022-07-08 103625](https://user-images.githubusercontent.com/92929363/177952640-df8a5b86-7ce6-483e-9baf-a97751343378.png)
7. Go into the project settings of the app and switch to the **Service accounts** tab and click on **Generate new private key**. It will download a json file containing credentials for your app.
8. Go to project settings, cloud messaging tabs and enable CloudMessaging API ( click on the 3 dots a the righ, Manage API in Google Cloud Console, and enable the API)  
![Capture d’écran 2022-07-08 142946](https://user-images.githubusercontent.com/92929363/177992435-e29223f7-6189-4052-baa1-c0455b2cc092.png)
9. Refresh firebase console page, you will show a server key under the Cloud Messaging API. ( in Cloud Messaging tab)

Firebase configuration is now completed.


### Laravel

1. Put the downloaded json at the root of project. ( JSON downloaded at step 7 of Firebase configuration )  
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
Appy\FcmHttpV1\AppyProvider::class,
```

6. Publish config file
```
php artisan vendor:publish --tag=appyfcmhttpv1 --ansi --force
```

## Usage

### Topics

Topics are used to make groups of device tokens. They will allow you to send notification directly to the topic where users are registered in.

#### Subscribe

The subscribeToTopic method take 2 arguments :
  - tokens (array) The tokens you want to subscribe to the topic. Max 999 at same time (firebase limit)
  - topic (string) Your awesome topic.
  
To subscribe tokens to a topic :

```php
use Appy\FcmHttpV1\Classes\AppyFcmHttpV1;

$tokens = ["first token", ... , "last token"];
AppyFcmHttpV1::subscribeToTopic($tokens, "myfirsttopic");
```
#### Unsubscribe

```php
use Appy\FcmHttpV1\Classes\AppyFcmHttpV1;

$tokens = ["first token", ... , "last token"];
AppyFcmHttpV1::unsubscribeToTopic($tokens, "myfirsttopic");
```

#### List subscriptions

```php
$token = "your awesome user device token";
AppyFcmHttpV1::getTopicsByUser($token);
```

## Notification

You can send notification to specific user or to topics.

### Send to unique token
```php
use Appy\FcmHttpV1\Classes\AppyNotification;

$notif = new AppyNotification();
$notif->setTitle("Best title")->setBody("Awesome description, really.")->setIcon("icon.png")->setToken("your user token")->send();
```

### Send to topic
```php
use Appy\FcmHttpV1\Classes\AppyNotification;
$notif = new AppyNotification();
$notif->setTitle("Best title")->setBody("Awesome description, really.")->setIcon("icon.png")->setTopic("awesome")->send();
```

