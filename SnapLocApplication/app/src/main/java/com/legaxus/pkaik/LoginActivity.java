package com.legaxus.pkaik;

import android.content.Intent;
import android.content.SharedPreferences;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.Toast;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class LoginActivity extends AppCompatActivity {
    EditText email, password;
    public final static String USER_ID_PREFERENCES="com.legaxus.pkaik.PREFERENCES_USER_ID";
    public final static String KEEP_ME_LOGIN_PREFERENCES="com.legaxus.pkaik.PREFERENCES_KEEP_ME_LOGIN";
    public final static String PREFERENCES_NAME="com.example.snaplocapp.PREFERENCES";
    SharedPreferences sharedPreferences;
    CheckBox cbKeepMeLogIn;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        email = findViewById(R.id.emailLogin);
        password = findViewById(R.id.passwordLogin);

        cbKeepMeLogIn = findViewById(R.id.cbKeepMeLogIn);

        sharedPreferences = getSharedPreferences(PREFERENCES_NAME,MODE_PRIVATE);

        if(sharedPreferences.getBoolean(KEEP_ME_LOGIN_PREFERENCES,false)){
            Intent i = new Intent(getApplicationContext(),MainActivity.class);
            startActivity(i);
        }

    }

    public void gotoregisterClick(View view) {

        Intent i = new Intent(this,RegisterActivity.class);
        startActivity(i);
    }

    public void login(View view) {
        String email_login = email.getText().toString().trim();
        String password_login = password.getText().toString().trim();

        APIService request = RetrofitClient.getClient(APIService.BASE_URL).create(APIService.class);

        Call<com.legaxus.pkaik.Response> call = request.login(email_login,password_login);

        call.enqueue(new Callback<com.legaxus.pkaik.Response>() {
            @Override
            public void onResponse(Call<com.legaxus.pkaik.Response> call, retrofit2.Response<com.legaxus.pkaik.Response> response) {
                boolean error = false;
                if (response.body() != null) {
                    error = response.body().getError();

                    if(!error){
                        Toast.makeText(getApplicationContext(),"Successfully login",Toast.LENGTH_SHORT).show();

                        sharedPreferences.edit().putInt(USER_ID_PREFERENCES,response.body().getId()).apply();

                        if(cbKeepMeLogIn.isChecked()){
                            sharedPreferences.edit().putBoolean(KEEP_ME_LOGIN_PREFERENCES,true).apply();
                        }

                        Intent i = new Intent(getApplicationContext(),MainActivity.class);
                        startActivity(i);
                    }
                    else{
                        Toast.makeText(getApplicationContext(),response.body().getError_msg(),Toast.LENGTH_LONG).show();
                    }
                }
                else{
                    Toast.makeText(getApplicationContext(),"Unknown error occured",Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<com.legaxus.pkaik.Response> call, Throwable t) {
                Toast.makeText(getApplicationContext(),"Please check your internet connection",Toast.LENGTH_LONG).show();
            }
        });
    }
}


