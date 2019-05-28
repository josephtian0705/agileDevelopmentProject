package com.legaxus.pkaik;

import com.google.gson.annotations.SerializedName;

public class Posts {
    @SerializedName("post_id")
    private int post_id;

    @SerializedName("post_comments")
    private String post_comments;

    @SerializedName("post_image_url")
    private String post_image_url;

    @SerializedName("post_date")
    private String post_date;

    @SerializedName("post_status")
    private String post_status;

    @SerializedName("admin_email")
    private String admin_email;

    public Posts(int post_id, String post_comments, String post_image_url, String post_date, String post_status, String admin_email) {
        this.post_id = post_id;
        this.post_comments = post_comments;
        this.post_image_url = post_image_url;
        this.post_date = post_date;
        this.post_status = post_status;
        this.admin_email = admin_email;
    }

    public int getPost_id() {
        return post_id;
    }

    public void setPost_id(int post_id) {
        this.post_id = post_id;
    }

    public String getPost_comments() {
        return post_comments;
    }

    public void setPost_comments(String post_comments) {
        this.post_comments = post_comments;
    }

    public String getPost_image_url() {
        return post_image_url;
    }

    public void setPost_image_url(String post_image_url) {
        this.post_image_url = post_image_url;
    }

    public String getPost_date() {
        return post_date;
    }

    public void setPost_date(String post_date) {
        this.post_date = post_date;
    }

    public String getPost_status() {
        return post_status;
    }

    public void setPost_status(String post_status) {
        this.post_status = post_status;
    }

    public String getAdmin_email() {
        return admin_email;
    }

    public void setAdmin_email(String admin_email) {
        this.admin_email = admin_email;
    }
}
