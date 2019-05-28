package com.legaxus.pkaik;

import java.util.List;

public class GetPostsResponse {
    private boolean error;
    private List<Posts> postsList;

    public GetPostsResponse(boolean error, List<Posts> postsList) {
        this.error = error;
        this.postsList = postsList;
    }

    public boolean isError() {
        return error;
    }

    public void setError(boolean error) {
        this.error = error;
    }

    public List<Posts> getPostsList() {
        return postsList;
    }

    public void setPostsList(List<Posts> postsList) {
        this.postsList = postsList;
    }
}
