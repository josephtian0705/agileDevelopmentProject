package com.legaxus.pkaik;

import android.app.Notification;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Build;
import android.support.v4.app.NotificationCompat;
import android.support.v4.app.TaskStackBuilder;
import android.util.Log;
import android.widget.Toast;

import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

import java.util.Random;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class NotificationService extends FirebaseMessagingService {

    public void onMessageReceived(RemoteMessage remoteMessage){
        super.onMessageReceived(remoteMessage);

        showingNotification(remoteMessage.getNotification().getTitle(),remoteMessage.getNotification().getBody());

    }

    public void showingNotification(String notificationTitle, String notificationBody){

        NotificationManager notificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
        String NOTIFICATION_CHANNEL_ID = "com.example.user.test";

        // Create an Intent for the activity you want to start
        Intent resultIntent = new Intent(this, SurveyActivity.class);
        resultIntent.putExtra("survey_url",notificationBody);
        // Create the TaskStackBuilder and add the intent, which inflates the back stack
        TaskStackBuilder stackBuilder = TaskStackBuilder.create(this);
        stackBuilder.addNextIntentWithParentStack(resultIntent);
        // Get the PendingIntent containing the entire back stack
        PendingIntent resultPendingIntent =
                stackBuilder.getPendingIntent(0, PendingIntent.FLAG_UPDATE_CURRENT);


        if(Build.VERSION.SDK_INT >= Build.VERSION_CODES.O){

            //setting up notification channel
            NotificationChannel notificationChannel = new NotificationChannel(NOTIFICATION_CHANNEL_ID, "Notification",
                    notificationManager.IMPORTANCE_DEFAULT);


            //set the detail of notification
            notificationChannel.setDescription("Project Notification Channel");
            notificationChannel.enableLights(true);
            notificationChannel.setLightColor(Color.BLUE);
            notificationManager.createNotificationChannel(notificationChannel);

        }

        //Build the notification
        NotificationCompat.Builder notificationBuilder = new NotificationCompat.Builder(this,NOTIFICATION_CHANNEL_ID);


        //set the style of notification
        notificationBuilder.setAutoCancel(true).
                setDefaults(Notification.DEFAULT_ALL).
                setWhen(System.currentTimeMillis()).
                setSmallIcon(R.drawable.ic_launcher_foreground).
                setContentTitle(notificationTitle).
                setContentText(notificationBody).
                setContentInfo("Info")
                .setContentIntent(resultPendingIntent);

        notificationManager.notify(new Random().nextInt(),notificationBuilder.build());
    }


    public void onNewToken(String s) {
        super.onNewToken(s);

        APIService apiService = RetrofitClient.getClient(APIService.BASE_URL).create(APIService.class);

        Call<Response> call = apiService.postsToken(s);
        call.enqueue(new Callback<Response>() {
            @Override
            public void onResponse(Call<Response> call, retrofit2.Response<Response> response) {
                if(response.body().getError()){
                    Toast.makeText(getApplicationContext(),response.body().getError_msg(),Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<Response> call, Throwable t) {
                Log.d("ERROR FOR DEBUG",t.getMessage());
            }
        });
    }
}

