//ajax to update post status

$("select[id^='post']").on('change', function() {
  $.post("updatePostStatus.php",
    {
      post_id:$(this).attr('id'),
      status:$(this).val()
    },
    function(data, status){

    }
  );
});

$("select[id^='user']").on('change', function() {
  $.post("updateUserStatus.php",
    {
      user_id:$(this).attr('id'),
      status:$(this).val()
    },
    function(data, status){

    }
  );
});

//ajax to delete the survey
function deleteSurvey(id){
  if(confirm("Are you sure you want to delete the selected survey?")){
    $.post("deleteSurvey.php",
      {
        survey_id:id,
      },
      function(data, status){
        alert(data);
        location.href="survey.php";
      }
    );
  }
}

//ajax to delete the post
function deletePost(id){
  if(confirm("Are you sure you want to delete the selected post?")){
    $.post("deletePost.php",
      {
        post_id:id,
      },
      function(data, status){
        alert(data);
        location.href="posts.php";
      }
    );
  }
}

//ajax to delete the post
function deleteUser(id){
  if(confirm("Are you sure you want to delete the selected user?")){
    $.post("deleteUser.php",
      {
        user_id:id,
      },
      function(data, status){
        alert(data);
        location.href="home.php";
      }
    );
  }
}

function deleteSurveyResult(id){
  if(confirm("Are you sure you want to delete the selected user?")){
    $.post("deleteSurveyResult.php",
      {
        survey_result_id:id,
      },
      function(data, status){
        alert(data);
        location.href="survey_result.php";
      }
    );
  }
}

function loadEditUser(username,email,ic,id){
  $('#edit_username').val(username);
  $('#edit_email').val(email);
  $('#edit_ic').val(ic);
  $('#edit_id').val(id);
  $('#edit_old_username').val(username);
  $('#edit_old_email').val(email);
  $('#edit_old_ic').val(ic);
}
