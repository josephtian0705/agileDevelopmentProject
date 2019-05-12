package com.legaxus.pkaik;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class RegisterActivity extends AppCompatActivity {
    EditText username, email, password, confirmPassword,ic;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);

        getSupportActionBar().setTitle(R.string.text_register);

        username = findViewById(R.id.usernameRegister);
        email = findViewById(R.id.emailEditTextRegister);
        password = findViewById(R.id.passwordRegister);
        confirmPassword = findViewById(R.id.confirmPasswordEditText);
        ic = findViewById(R.id.icEditTextRegister);

    }



    public void registerBtnClicked(View view) {
        String register_username = username.getText().toString().trim();
        String register_email = email.getText().toString().trim();
        String register_password = password.getText().toString().trim();
        String register_confirm_password = confirmPassword.getText().toString().trim();
        String register_ic = ic.getText().toString().trim();

        if(register_password.equals(register_confirm_password)){

            APIService request = RetrofitClient.getClient(APIService.BASE_URL).create(APIService.class);

            Call<Response> call = request.register(register_username,register_email,register_password,register_ic);

            call.enqueue(new Callback<Response>() {
                @Override
                public void onResponse(Call<Response> call, retrofit2.Response<Response> response) {
                    boolean error = false;
                    if (response.body() != null) {
                        error = response.body().getError();

                        if(!error){
                            Toast.makeText(getApplicationContext(),"Successfully registered",Toast.LENGTH_SHORT).show();
                            finish();
                        }
                        else{
                            Toast.makeText(getApplicationContext(),response.body().getError_msg(),Toast.LENGTH_LONG).show();
                        }
                    }
                    else{
                        Toast.makeText(getApplicationContext(),"Error",Toast.LENGTH_LONG).show();
                    }
                }

                @Override
                public void onFailure(Call<Response> call, Throwable t) {
                    Toast.makeText(getApplicationContext(),"Please check your internet connection",Toast.LENGTH_LONG).show();
                }
            });

        }
        else{
            Toast.makeText(getApplicationContext(),"Password must be same as confirm password",Toast.LENGTH_LONG).show();
        }
    }
}
