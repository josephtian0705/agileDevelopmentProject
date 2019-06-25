package com.legaxus.pkaik;

import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class SurveyListActivity extends Fragment {
    RecyclerView rvSurvey;
    SwipeRefreshLayout swipeContainerSurveyList;
    public SurveyListActivity(){

    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return getLayoutInflater().inflate(R.layout.activity_survey_list,container,false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        rvSurvey = view.findViewById(R.id.rvSurvey);
        swipeContainerSurveyList = view.findViewById(R.id.swipeContainerSurveyList);

        getData();

        swipeContainerSurveyList.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                getData();
            }
        });

    }

    public void getData(){
        APIService apiService = RetrofitClient.getClient(APIService.BASE_URL).create(APIService.class);

        Call<GetSurveyResponse> call = apiService.loadSurvey();
        call.enqueue(new Callback<GetSurveyResponse>() {
            @Override
            public void onResponse(Call<GetSurveyResponse> call, Response<GetSurveyResponse> response) {
                if (response.body() != null && !response.body().isError()) {
                    SurveyListAdapter historyListAdapter = new SurveyListAdapter(response.body().getSurveyList(), getContext());
                    rvSurvey.setAdapter(historyListAdapter);
                    rvSurvey.setLayoutManager(new LinearLayoutManager(getContext()));

                    swipeContainerSurveyList.setRefreshing(false);
                }
                else
                    Toast.makeText(getContext(),"Error",Toast.LENGTH_LONG).show();
            }

            @Override
            public void onFailure(Call<GetSurveyResponse> call, Throwable t) {
                Log.d("TEST",t.getMessage());
            }
        });
    }
}
