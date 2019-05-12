package com.legaxus.pkaik;

import android.content.Intent;
import android.content.SharedPreferences;
import android.support.annotation.NonNull;
import android.support.design.widget.BottomNavigationView;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;


public class MainActivity extends AppCompatActivity implements ViewPager.OnPageChangeListener, BottomNavigationView.OnNavigationItemSelectedListener {
    ViewPager viewPager;
    BottomNavigationView bottomNavigationView;
    ViewPagerAdapter viewPagerAdapter;
    SharedPreferences sharedPreferences;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        viewPager = findViewById(R.id.viewPager);
        viewPagerAdapter = new ViewPagerAdapter(getSupportFragmentManager());
        viewPager.setAdapter(viewPagerAdapter);
        viewPager.addOnPageChangeListener(this);
        viewPager.setOffscreenPageLimit(2);
        bottomNavigationView = findViewById(R.id.bottomNavigationView);
        bottomNavigationView.setOnNavigationItemSelectedListener(this);

        sharedPreferences = getSharedPreferences(LoginActivity.PREFERENCES_NAME,MODE_PRIVATE);
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {

        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.custom_menu, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()){
            case R.id.logout:
                Intent i = new Intent(getApplicationContext(),LoginActivity.class);
                startActivity(i);
                sharedPreferences.edit().putBoolean(LoginActivity.KEEP_ME_LOGIN_PREFERENCES,false).apply();
                finish();
        }
        return true;
    }

    @Override
    public void onPageScrolled(int i, float v, int i1) {

    }

    @Override
    public void onPageSelected(int i) {
        switch (i){
            case 0:
                bottomNavigationView.setSelectedItemId(R.id.posts);
                break;
            case 1:
                bottomNavigationView.setSelectedItemId(R.id.surveys);
                break;
        }
    }

    @Override
    public void onPageScrollStateChanged(int i) {

    }

    @Override
    public boolean onNavigationItemSelected(@NonNull MenuItem menuItem) {
        switch (menuItem.getItemId()){
            case R.id.posts:
                viewPagerAdapter.notifyDataSetChanged();
                            viewPager.setCurrentItem(0);
                            if(getSupportActionBar()!=null)
                                getSupportActionBar().setTitle(R.string.text_my_post);
                            break;
            case R.id.surveys:
                viewPagerAdapter.notifyDataSetChanged();
                            viewPager.setCurrentItem(1);
                            if(getSupportActionBar()!=null)
                                getSupportActionBar().setTitle(R.string.text_surveys);
                            break;
        }
        return true;
    }
}
