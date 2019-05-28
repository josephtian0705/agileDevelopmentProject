package com.legaxus.pkaik;

import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.Multipart;
import retrofit2.http.POST;
import retrofit2.http.Part;
import retrofit2.http.Query;

public interface APIService {
    String BASE_URL = "http://malaysianow.today/SnapLocApp2/";

    @FormUrlEncoded
    @POST("post.php")
    Call<Response> posts(@Field("post_image_url") String imageUrl,
                         @Field("post_comments") String comments,
                         @Field("post_type") char type,
                         @Field("latitude") double latitude,
                         @Field("longitude") double longitude,
                         @Field("user_id") int user_id);
    @Multipart
    @POST("uploadImage.php")
    Call<Response> postImage(@Part MultipartBody.Part image,@Part("file") RequestBody name);

    @GET("getHistory.php")
    Call<GetPostsResponse> loadPosts(@Query("user_id") int user_id);

    @FormUrlEncoded
    @POST("postToken.php")
    Call<Response> postsToken(@Field("token") String token);

    @GET("getSurvey.php")
    Call<GetSurveyResponse> loadSurvey();

    @FormUrlEncoded
    @POST("register.php")
    Call<Response> register(@Field("username") String username, @Field("email")String email,
                            @Field("password")String password,@Field("ic") String ic);

    @FormUrlEncoded
    @POST("login.php")
    Call<Response> login(@Field("email")String email,
                         @Field("password")String password);

    @FormUrlEncoded
    @POST("addSurveyResult.php")
    Call<Response> answerSurvey(@Field("jsonForm")String jsonForm, @Field("user_id") int user_id, @Field("form_id") int form_id);
}
