package com.legaxus.pkaik;

import android.Manifest;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.ActivityCompat;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.RadioButton;
import android.widget.TextView;
import android.widget.Toast;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class AddPostActivity extends AppCompatActivity {
    LocationManager mLocationManager;
    double latitude = 360, longitude = 360;
    ImageView iv;
    TextView ivInstruc;
    String imgUrl = "", comments = "";
    char type = '+';
    int user_id = 0;
    Bitmap bitmap;
    int id = 0;
    EditText etComments;
    RadioButton rbPositive, rbNegative;
    Button btnSubmit;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_add_post);

        btnSubmit = findViewById(R.id.button);

        iv = findViewById(R.id.imageView);
        ivInstruc = findViewById(R.id.tvImageInstruction);
        etComments = findViewById(R.id.editText);
        rbPositive = findViewById(R.id.rbPositive);
        rbNegative = findViewById(R.id.rbNegative);

        SharedPreferences sharedPreferences = getSharedPreferences(LoginActivity.PREFERENCES_NAME,MODE_PRIVATE);
        user_id = sharedPreferences.getInt(LoginActivity.USER_ID_PREFERENCES,0);

        mLocationManager = (LocationManager) getSystemService(LOCATION_SERVICE);
    }




    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        switch (requestCode) {
            case 100: {
                if (grantResults.length > 0
                        && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    if (ActivityCompat.checkSelfPermission
                            (this, Manifest.permission.ACCESS_FINE_LOCATION)
                            != PackageManager.PERMISSION_GRANTED ) {
                        return;
                    }
                    if(mLocationManager!=null) {
                        submitData();
                    }
                }
                break;
            }
            case 101:{
                if (grantResults.length > 0
                        && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    Intent chooseImageIntent = ImagePicker.getPickImageIntent(this);
                    startActivityForResult(chooseImageIntent,100);
                }
                break;
            }
        }
    }

    public void snapshot(View view) {

        if(ActivityCompat.checkSelfPermission(this, Manifest.permission.CAMERA) != PackageManager.PERMISSION_GRANTED
        && ActivityCompat.checkSelfPermission(this, Manifest.permission.WRITE_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED) {
            requestPermissions(new String[]{Manifest.permission.CAMERA,Manifest.permission.WRITE_EXTERNAL_STORAGE}, 101);
        }
        else{
            Intent chooseImageIntent = ImagePicker.getPickImageIntent(this);
            startActivityForResult(chooseImageIntent,100);
        }
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        switch(requestCode) {
            case 100:
                if (resultCode == RESULT_OK) {
                    bitmap = ImagePicker.getImageFromResult(this, resultCode, data);
                    iv.setImageBitmap(bitmap);
                    if(ivInstruc.getVisibility()==View.VISIBLE){
                        ivInstruc.setVisibility(View.GONE);
                    }
                }

                break;
        }
    }

    public void submit(View view) {
        if (mLocationManager != null) {
            submitData();
        }

    }

    private final LocationListener mLocationListener = new LocationListener() {
        @Override
        public void onLocationChanged(Location location) {
            latitude = location.getLatitude();
            longitude = location.getLongitude();
        }

        @Override
        public void onStatusChanged(String provider, int status, Bundle extras) {

        }

        @Override
        public void onProviderEnabled(String provider) {

        }

        @Override
        public void onProviderDisabled(String provider) {

        }
    };


    public boolean requestLocation(){
        boolean gps_enabled = false,network_enabled = false;
        try{
            gps_enabled = mLocationManager.isProviderEnabled(LocationManager.GPS_PROVIDER);
        }
        catch(Exception ex){
        }
        try {
            network_enabled = mLocationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER);
        }
        catch(Exception ex){

        }

        if(!gps_enabled && !network_enabled)
            return false;

        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            requestPermissions(new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, 100);
            return false;
        }
        else {
            if (gps_enabled) {
                mLocationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0,
                        0, mLocationListener);
                try {
                    latitude = mLocationManager.getLastKnownLocation(LocationManager.GPS_PROVIDER).getLatitude();
                    longitude = mLocationManager.getLastKnownLocation(LocationManager.GPS_PROVIDER).getLongitude();
                }
                catch(NullPointerException ex){

                }
            }
            if (network_enabled) {
                mLocationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, 0,
                        0, mLocationListener);
                try {
                    latitude = mLocationManager.getLastKnownLocation(LocationManager.NETWORK_PROVIDER).getLatitude();
                    longitude = mLocationManager.getLastKnownLocation(LocationManager.NETWORK_PROVIDER).getLongitude();
                }
                catch(NullPointerException ex){

                }
            }
        }

        return true;
    }

    public void submitData(){
        if (requestLocation()) {

            comments = etComments.getText().toString();
            if (rbPositive.isChecked()) {
                type = '+';
            } else {
                type = '-';
            }


            APIService apiService = RetrofitClient.getClient(APIService.BASE_URL).create(APIService.class);


            Call<Response> call = apiService.posts(imgUrl, comments, type, latitude, longitude, user_id);
            call.enqueue(new Callback<Response>() {
                @Override
                public void onResponse(Call<Response> call, retrofit2.Response<Response> response) {

                    if (response.body() != null) {
                        boolean error = response.body().getError();
                        if (!error) {
                            id = response.body().getId();


                            File f = new File(getCacheDir(), id + ".png");
                            try {
                                f.createNewFile();
                            } catch (IOException e) {
                                e.printStackTrace();
                            }
                            ByteArrayOutputStream bos = new ByteArrayOutputStream();
                            bitmap.compress(Bitmap.CompressFormat.PNG, 0 /*ignored for PNG*/, bos);
                            byte[] bitmapdata = bos.toByteArray();

                            //write the bytes in file
                            FileOutputStream fos = null;
                            try {
                                fos = new FileOutputStream(f);

                                fos.write(bitmapdata);
                                fos.flush();
                                fos.close();
                            } catch (FileNotFoundException e) {
                                e.printStackTrace();
                            } catch (IOException e) {
                                e.printStackTrace();
                            }

                            RequestBody mFile = RequestBody.create(MediaType.parse("image/*"), f);
                            MultipartBody.Part fileToUpload = MultipartBody.Part.createFormData("file", f.getName(), mFile);
                            RequestBody filename = RequestBody.create(MediaType.parse("text/plain"), f.getName());


                            APIService apiService2 = RetrofitClient.getClient(APIService.BASE_URL).create(APIService.class);


                            Call<Response> call2 = apiService2.postImage(fileToUpload, filename);
                            call2.enqueue(new Callback<Response>() {
                                @Override
                                public void onResponse(Call<Response> call, retrofit2.Response<Response> response) {
                                    Toast.makeText(getApplicationContext(), "Success", Toast.LENGTH_SHORT).show();
                                }

                                @Override
                                public void onFailure(Call<Response> call, Throwable t) {
                                    Toast.makeText(getApplicationContext(), "Error", Toast.LENGTH_SHORT).show();
                                }
                            });
                        } else {
                            Toast.makeText(getApplicationContext(), response.body().getError_msg(), Toast.LENGTH_SHORT).show();
                        }
                    }
                }

                @Override
                public void onFailure(Call<Response> call, Throwable t) {
                    Toast.makeText(getApplicationContext(), t.getMessage(), Toast.LENGTH_SHORT).show();

                }
            });
        }
        else{
            Toast.makeText(getApplicationContext(),"Please enable your location service for tracking of the post location",Toast.LENGTH_LONG).show();
        }

    }

}
