package com.legaxus.pkaik;

import android.content.Context;
import android.content.Intent;
import android.support.annotation.NonNull;
import android.support.v7.widget.CardView;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import java.util.List;

public class SurveyListAdapter extends RecyclerView.Adapter<SurveyListAdapter.ViewHolder> {
    List<Survey> surveyList;
    Context context;

    public SurveyListAdapter(List<Survey> surveyList,Context context){
        this.surveyList = surveyList;
        this.context = context;
    }


    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup viewGroup, int i) {
        View mView = LayoutInflater.from(viewGroup.getContext()).inflate(R.layout.survey_list_layout,viewGroup,false);

        return new ViewHolder(mView);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder viewHolder, int i) {
        viewHolder.tvSurveyTitle.setText(surveyList.get(i).getSurvey_title());
        viewHolder.tvSurveyDate.setText(surveyList.get(i).getSurvey_date());
    }

    @Override
    public int getItemCount() {
        return surveyList.size();
    }

    class ViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        TextView tvSurveyTitle,tvSurveyDate;
        CardView cvParent;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);

            tvSurveyTitle = itemView.findViewById(R.id.tvSurveyTitle);
            tvSurveyDate = itemView.findViewById(R.id.tvSurveyDate);

            cvParent = itemView.findViewById(R.id.cvParent);

            cvParent.setOnClickListener(this);
        }

        @Override
        public void onClick(View v) {
            Intent resultIntent = new Intent(context, SurveyActivity.class);
            resultIntent.putExtra("survey",surveyList.get(getAdapterPosition()));
            context.startActivity(resultIntent);
        }
    }
}
