package com.legaxus.pkaik;

import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentStatePagerAdapter;

public class ViewPagerAdapter extends FragmentStatePagerAdapter {

    public ViewPagerAdapter(FragmentManager fm) {
        super(fm);
    }

    @Override
    public Fragment getItem(int i) {
        switch(i){
            case 0: return new HistoryActivity();
            case 1: return new SurveyListActivity();
            default: return null;
        }
    }

    @Override
    public int getCount() {
        return 2;
    }


}
