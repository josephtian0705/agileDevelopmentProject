package com.legaxus.pkaik;

import com.google.gson.annotations.SerializedName;

import java.util.List;

public class GetSurveyResponse {
    @SerializedName("error")
    private boolean error;

    @SerializedName("surveyList")
    private List<Survey> surveyList;

    public GetSurveyResponse(boolean error, List<Survey> surveyList) {
        this.error = error;
        this.surveyList = surveyList;
    }

    public boolean isError() {
        return error;
    }

    public void setError(boolean error) {
        this.error = error;
    }

    public List<Survey> getSurveyList() {
        return surveyList;
    }

    public void setSurveyList(List<Survey> surveyList) {
        this.surveyList = surveyList;
    }
}
