package com.legaxus.pkaik;

import com.google.gson.annotations.SerializedName;

public class Response {
    @SerializedName("error")
    private Boolean error;

    @SerializedName("error_msg")
    private String error_msg;

    @SerializedName("id")
    private int id;

    public Response(Boolean error, String error_msg,int id) {
        this.error = error;
        this.error_msg = error_msg;
        this.id = id;
    }

    public Boolean getError() {
        return error;
    }

    public void setError(Boolean error) {
        this.error = error;
    }

    public String getError_msg() {
        return error_msg;
    }

    public void setError_msg(String error_msg) {
        this.error_msg = error_msg;
    }

    public void setId(int id){
        this.id = id;
    }

    public int getId(){
        return id;
    }
}
