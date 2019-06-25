package com.legaxus.pkaik;

import com.google.gson.annotations.SerializedName;

import java.io.Serializable;

public class Survey implements Serializable {
    @SerializedName("survey_id")
    private int survey_id;

    @SerializedName("survey_title")
    private String survey_title;

    @SerializedName("survey_json")
    private String survey_json;

    @SerializedName("survey_date")
    private String survey_date;


    public Survey(int survey_id, String survey_title, String survey_json,String survey_date) {
        this.survey_id = survey_id;
        this.survey_title = survey_title;
        this.survey_json = survey_json;
        this.survey_date = survey_date;
    }

    public int getSurvey_id() {
        return survey_id;
    }

    public void setSurvey_id(int survey_id) {
        this.survey_id = survey_id;
    }

    public String getSurvey_title() {
        return survey_title;
    }

    public void setSurvey_title(String survey_title) {
        this.survey_title = survey_title;
    }

    public String getSurvey_json() {
        return survey_json;
    }

    public void setSurvey_json(String survey_json) {
        this.survey_json = survey_json;
    }

    public String getSurvey_date() {
        return survey_date;
    }

    public void setSurvey_date(String survey_date) {
        this.survey_date = survey_date;
    }
}
