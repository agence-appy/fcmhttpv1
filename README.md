# Laravel FCM Http V1 API Package

 A [Laravel](https://laravel.com/) package that allow you tu use the new FCM Http V1 API.
 
 ## Installation
 
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
