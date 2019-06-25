package com.legaxus.pkaik;

import android.content.Intent;
import android.content.SharedPreferences;
import android.support.constraint.ConstraintLayout;
import android.support.design.internal.FlowLayout;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.CheckBox;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;

public class SurveyActivity extends AppCompatActivity {
    List<DetailesOfSurveyItem> DetailsArrayList;

    private Survey surveyData;
    LinearLayout ly_addcontrol;
    ConstraintLayout ly_main;
    int user_id,form_id;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_survey);

        ly_main = findViewById(R.id.ly_main);
        ly_addcontrol = findViewById(R.id.ly_addcontrol);

        surveyData = (Survey)getIntent().getSerializableExtra("survey");

        form_id = surveyData.getSurvey_id();

        SharedPreferences sharedPreferences = getSharedPreferences(LoginActivity.PREFERENCES_NAME, MODE_PRIVATE);
        user_id = sharedPreferences.getInt(LoginActivity.USER_ID_PREFERENCES, 0);


        JsonParse();
    }

    private void JsonParse() {
        if (!surveyData.getSurvey_json().equals("") && !surveyData.getSurvey_json().equals("[]")) {
            try {

                JSONArray jsonArray = new JSONArray(surveyData.getSurvey_json());

                if (jsonArray != null && jsonArray.length() > 0) {
                    DetailsArrayList = new ArrayList<>();

                    for (int i = 0; i < jsonArray.length(); i++) {
                        JSONObject jsonObject = jsonArray.getJSONObject(i);

                        DetailesOfSurveyItem detailesOfSurveyItem = new DetailesOfSurveyItem();

                        if (jsonObject.has("type") && !jsonObject.isNull("type"))
                            detailesOfSurveyItem.setType(jsonObject.get("type").toString());
                        else
                            detailesOfSurveyItem.setType("");

                        if (jsonObject.has("label") && !jsonObject.isNull("label"))
                            detailesOfSurveyItem.setLable(jsonObject.get("label").toString());
                        else
                            detailesOfSurveyItem.setLable("");


                        if (detailesOfSurveyItem.getType().equals("text")) {
                            if (jsonObject.has("value") && !jsonObject.isNull("value"))
                                detailesOfSurveyItem.setValues(jsonObject.get("value").toString());
                            else
                                detailesOfSurveyItem.setValues("");
                        } else if (detailesOfSurveyItem.getType().equals("checkbox-group")) {
                            if (jsonObject.has("values") && !jsonObject.isNull("values")) {

                                JSONArray values_array = jsonObject.getJSONArray("values");

                                List<ValuesItem> valuesItemslist = new ArrayList<>();
                                for (int j = 0; j < values_array.length(); j++) {
                                    ValuesItem valuesdata = new ValuesItem();

                                    JSONObject jsonObject_value = values_array.getJSONObject(j);

                                    if (jsonObject_value.has("label") && !jsonObject_value.isNull("label"))
                                        valuesdata.setValue(jsonObject_value.get("label").toString());
                                    else
                                        valuesdata.setValue("");

                                    if (jsonObject_value.has("selected") && !jsonObject_value.isNull("selected"))
                                        valuesdata.setSelected(jsonObject_value.getBoolean("selected"));
                                    else
                                        valuesdata.setSelected(false);

                                    valuesItemslist.add(valuesdata);
                                }

                                detailesOfSurveyItem.setValuesItems(valuesItemslist);
                            } else {
                                detailesOfSurveyItem.setValuesItems(null);
                            }

                        } else if (detailesOfSurveyItem.getType().equals("radio-group")) {
                            if (jsonObject.has("values") && !jsonObject.isNull("values")) {
                                JSONArray values_array = jsonObject.getJSONArray("values");

                                List<ValuesItem> valuesItemslist = new ArrayList<>();
                                for (int j = 0; j < values_array.length(); j++) {

                                    ValuesItem valuesdata = new ValuesItem();

                                    JSONObject jsonObject_value = values_array.getJSONObject(j);

                                    if (jsonObject_value.has("label") && !jsonObject_value.isNull("label"))
                                        valuesdata.setValue(jsonObject_value.get("label").toString());
                                    else
                                        valuesdata.setValue("");

                                    if (jsonObject_value.has("selected") && !jsonObject_value.isNull("selected"))
                                        valuesdata.setSelected(jsonObject_value.getBoolean("selected"));
                                    else
                                        valuesdata.setSelected(false);

                                    valuesItemslist.add(valuesdata);
                                }
                                detailesOfSurveyItem.setValuesItems(valuesItemslist);
                            } else {
                                detailesOfSurveyItem.setValuesItems(null);
                            }
                        } else if (detailesOfSurveyItem.getType().equals("header")) {
                            if (jsonObject.has("subtype") && !jsonObject.isNull("subtype"))
                                detailesOfSurveyItem.setSubtype(jsonObject.get("subtype").toString());
                            else
                                detailesOfSurveyItem.setSubtype("");
                        }

                        DetailsArrayList.add(detailesOfSurveyItem);
                    }

                    setdetailslist(DetailsArrayList);


                }

            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
    }

    private void setdetailslist(List<DetailesOfSurveyItem> DetailsArrayList) {

        ly_addcontrol.removeAllViews();

        for (int i = 0; i < DetailsArrayList.size(); i++) {

            final DetailesOfSurveyItem data = DetailsArrayList.get(i);

            if (data.getType().equalsIgnoreCase("header")) {
                LayoutInflater inflater = getLayoutInflater();
                View view = inflater.inflate(R.layout.view_header, ly_main, false);

                CustomTextView txt_header_lable = (CustomTextView) view.findViewById(R.id.txt_header_lable);

                switch (data.getSubtype()) {
                    case "h1":
                        txt_header_lable.setTextSize(getResources().getDimension(R.dimen.font_14));
                        txt_header_lable.setTextColor(getResources().getColor(android.R.color.black, getTheme()));
                        break;
                    case "h2":
                        txt_header_lable.setTextSize(getResources().getDimension(R.dimen.font_12));
                        txt_header_lable.setTextColor(getResources().getColor(android.R.color.black, getTheme()));
                        break;
                    case "h13":
                        txt_header_lable.setTextSize(getResources().getDimension(R.dimen.font_10));
                        txt_header_lable.setTextColor(getResources().getColor(R.color.colorPrimary, getTheme()));
                        break;
                }
                txt_header_lable.setText(data.getLable());

                ly_addcontrol.addView(view);
            }

            if (data.getType().equalsIgnoreCase("radio-group")) {
                LayoutInflater inflater = getLayoutInflater();
                View view = inflater.inflate(R.layout.view_radiobutton, ly_main, false);

                CustomTextView txt_radio_lable = (CustomTextView) view.findViewById(R.id.txt_radio_lable);

                txt_radio_lable.setText(data.getLable());

                RadioGroup radio_group = (RadioGroup) view.findViewById(R.id.radio_group);
                RadioGroup.LayoutParams layoutParams = new RadioGroup.LayoutParams(RadioGroup.LayoutParams.MATCH_PARENT, RadioGroup.LayoutParams.WRAP_CONTENT);

                if (data.getValuesItems() != null) {
                    final List<ValuesItem> values = data.getValuesItems();
                    for (int k = 0; k < values.size(); k++) {

                        final ValuesItem valuesItem = values.get(k);

                        final float scale = this.getResources().getDisplayMetrics().density;
                        RadioButton R_button = new RadioButton(this);
                        R_button.setId(k);
                        R_button.setPadding(R_button.getPaddingLeft() + (int) (10.0f * scale + 0.5f),
                                R_button.getPaddingTop() + (int) (5.0f * scale + 0.5f),
                                R_button.getPaddingRight(),
                                R_button.getPaddingBottom() + (int) (5.0f * scale + 0.5f));
                        R_button.setButtonDrawable(R.drawable.custom_radio_plan);
                        R_button.setText(valuesItem.getValue());
                        R_button.setChecked(valuesItem.isSelected());
                        R_button.setLayoutParams(layoutParams);

                        radio_group.addView(R_button);

                        radio_group.setOnCheckedChangeListener(new RadioGroup.OnCheckedChangeListener() {
                            @Override
                            public void onCheckedChanged(RadioGroup group, int checkedId) {

                                if (data.getValuesItems() != null) {
                                    final List<ValuesItem> values = data.getValuesItems();
                                    for (int k = 0; k < values.size(); k++) {

                                        final ValuesItem valuesItem = values.get(k);

                                        if (k == checkedId) {
                                            valuesItem.setSelected(true);
                                        } else {
                                            valuesItem.setSelected(false);
                                        }
                                    }
                                }
                            }
                        });

                    }

                }
                ly_addcontrol.addView(view);
            }


            if (data.getType().equalsIgnoreCase("text")) {
                LayoutInflater inflater = getLayoutInflater();
                View view = inflater.inflate(R.layout.view_edittext, ly_main, false);

                CustomTextView txt_edittext_lable = (CustomTextView) view.findViewById(R.id.txt_edittext_lable);
                CustomEditTextView edittext = (CustomEditTextView) view.findViewById(R.id.edittext);

                txt_edittext_lable.setText(data.getLable());
                edittext.setText(data.getValues());

                edittext.setId(i);
                edittext.addTextChangedListener(new TextWatcher() {
                    @Override
                    public void beforeTextChanged(CharSequence s, int start, int count, int after) {

                    }

                    @Override
                    public void onTextChanged(CharSequence s, int start, int before, int count) {

                        data.setValues(s.toString());
                    }

                    @Override
                    public void afterTextChanged(Editable s) {

                    }
                });

                ly_addcontrol.addView(view);

            }

            if (data.getType().equalsIgnoreCase("checkbox-group")) {
                LayoutInflater inflater = getLayoutInflater();
                View view = inflater.inflate(R.layout.view_checkbox, ly_main, false);


                CustomTextView txt_checkbox_lable = (CustomTextView) view.findViewById(R.id.txt_checkbox_lable);
                FlowLayout ly_checkbox = (FlowLayout) view.findViewById(R.id.ly_checkbox);
                FlowLayoutView flowlayout_activity = (FlowLayoutView) view.findViewById(R.id.flowlayout_activity);

                txt_checkbox_lable.setText(data.getLable());

                if (data.getValuesItems() != null) {
                    final List<ValuesItem> values = data.getValuesItems();

                    for (int l = 0; l < values.size(); l++) {

                        /*LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(ViewGroup.LayoutParams.FILL_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT);
                        params.leftMargin = 123;
                        li.addView(cb, params);*/

                        ValuesItem valuesItem = values.get(l);
                        final float scale = this.getResources().getDisplayMetrics().density;
                        CheckBox chk_box = new CheckBox(this);
                        chk_box.setId(l);
                        //chk_box.setPadding(5,5,5,5);
                        chk_box.setPadding(chk_box.getPaddingLeft() + (int) (10.0f * scale + 0.5f),
                                chk_box.getPaddingTop(),
                                chk_box.getPaddingRight() + (int) (10.0f * scale + 0.5f),
                                chk_box.getPaddingBottom());
                        chk_box.setButtonDrawable(R.drawable.custom_checkbox_plan);
                        chk_box.setText(valuesItem.getValue());
                        chk_box.setChecked(valuesItem.isSelected());


                        chk_box.setId(l);
                        chk_box.setOnClickListener(new View.OnClickListener() {
                            @Override
                            public void onClick(View v) {

                                ValuesItem valuesItem = values.get(v.getId());

                                if (valuesItem.isSelected())
                                    valuesItem.setSelected(false);
                                else
                                    valuesItem.setSelected(true);
                            }
                        });

                        flowlayout_activity.addView(chk_box);
                    }
                }

                ly_addcontrol.addView(view);
            }
        }
    }


    private void SendJsonform() {
        if (DetailsArrayList != null && DetailsArrayList.size() > 0) {
            Log.e("Data ==>", "" + DetailsArrayList.toString());

            try {

                JSONArray jsonArray = new JSONArray();

                for (int i = 0; i < DetailsArrayList.size(); i++) {

                    JSONObject jsonObject = new JSONObject();

                    final DetailesOfSurveyItem data = DetailsArrayList.get(i);

                    if (data.getType().equalsIgnoreCase("radio-group")) {

                        jsonObject.put("type", "radio-group");
                        jsonObject.put("label", "" + data.getLable());

                        List<ValuesItem> valuesItemslist = data.getValuesItems();

                        if (valuesItemslist != null && valuesItemslist.size() > 0) {
                            JSONArray jsonArray_value = new JSONArray();

                            for (int k = 0; k < valuesItemslist.size(); k++) {
                                ValuesItem valuesItem = valuesItemslist.get(k);

                                JSONObject jsonObject_value = new JSONObject();

                                jsonObject_value.put("label", valuesItem.getValue());
                                jsonObject_value.put("selected", valuesItem.isSelected());

                                jsonArray_value.put(jsonObject_value);
                            }

                            jsonObject.put("values", jsonArray_value);
                        }
                    }

                    if (data.getType().equalsIgnoreCase("text")) {

                        jsonObject.put("type", "text");
                        jsonObject.put("subtype", "text");
                        jsonObject.put("value", "" + data.getValues());
                        jsonObject.put("label", "" + data.getLable());

                    }

                    if (data.getType().equalsIgnoreCase("header")) {
                        jsonObject.put("type", "header");
                        jsonObject.put("subtype", "" + data.getSubtype());
                        jsonObject.put("label", "" + data.getLable());
                    }

                    if (data.getType().equalsIgnoreCase("checkbox-group")) {

                        jsonObject.put("type", "checkbox-group");
                        jsonObject.put("label", "" + data.getLable());

                        List<ValuesItem> valuesItemslist = data.getValuesItems();

                        if (valuesItemslist != null && valuesItemslist.size() > 0) {
                            JSONArray jsonArray_value = new JSONArray();

                            for (int k = 0; k < valuesItemslist.size(); k++) {
                                ValuesItem valuesItem = valuesItemslist.get(k);

                                JSONObject jsonObject_value = new JSONObject();

                                jsonObject_value.put("label", valuesItem.getValue());
                                jsonObject_value.put("selected", valuesItem.isSelected());

                                jsonArray_value.put(jsonObject_value);
                            }

                            jsonObject.put("values", jsonArray_value);
                        }
                    }

                    jsonArray.put(jsonObject);
                }

                submitSurveyToServer(jsonArray.toString());

            } catch (JSONException e) {
                e.printStackTrace();
            }
        } else {
            Toast.makeText(getApplicationContext(),"Error",Toast.LENGTH_SHORT).show();
        }
    }

    private void submitSurveyToServer(String jsonForm) {
        APIService apiService = RetrofitClient.getClient(APIService.BASE_URL).create(APIService.class);
        Call<Response> call = apiService.answerSurvey(jsonForm,user_id,form_id);
        call.enqueue(new Callback<Response>() {
            @Override
            public void onResponse(Call<Response> call, retrofit2.Response<Response> response) {
                if (response.body() != null && !response.body().getError()) {
                    Toast.makeText(getApplicationContext(),"Survey successfully submitted",Toast.LENGTH_LONG).show();

                    finish();
                }
                else{
                    if (response.body() != null) {
                        Toast.makeText(getApplicationContext(),response.body().getError_msg(),Toast.LENGTH_LONG).show();
                    }
                }
            }

            @Override
            public void onFailure(Call<Response> call, Throwable t) {
                Toast.makeText(getApplicationContext(),t.getMessage(),Toast.LENGTH_LONG).show();
                Log.d("TEST",t.getMessage());
            }
        });
    }

    public void submitSurvey(View view) {
        SendJsonform();
    }
}
