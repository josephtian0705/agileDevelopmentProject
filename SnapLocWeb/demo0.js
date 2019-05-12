jQuery(function($) {

  var fields = [
    {
      label: 'Star Rating',
      attrs: {
        type: 'starRating'
      },
      icon: '🌟'
    }
  ];

 var templates = {
    starRating: function(fieldData) {
      return {
        field: '<span id="'+fieldData.name+'">',
        onRender: function() {
          $(document.getElementById(fieldData.name)).rateYo({rating: 5.0});
        }
      };
    }
  };

  var typeUserAttrs = {
    text: {
      className: {
        label: 'Class',
        options: {
          'red form-control': 'Red',
          'green form-control': 'Green',
          'blue form-control': 'Blue'
        },
        style: 'border: 1px solid red'
      }
    }
  };

  // test disabledAttrs
  var disabledAttrs = ['placeholder'];

  var fbOptions = {
    subtypes: {
      text: ['datetime-local']
    },
    onSave: function(e, formData) {
      $('.render-wrap').formRender({
        formData: formData,
        templates: templates
      });
      window.sessionStorage.setItem('formData', JSON.stringify(formData));
      var temp_id_input = $('#survey_id_input').val();
      var survey_title_to_post = $('#survey_title_input').val();
      if(temp_id_input==""){
        $.post("addSurvey.php",
        {
          survey : formData,
          survey_title : survey_title_to_post
        },
        function(data, status){
          if(data=="1"){
            alert("Successfully added survey");
            location.href="survey.php";
          }
          else {
            alert("Fail to add survey");
          }
        });
      }
      else{
        $.post("updateSurvey.php",
        {
          survey : formData,
          survey_id : temp_id_input,
          survey_title : survey_title_to_post
        },
        function(data, status){
          if(data=="1")
            alert("Successfully updated survey");
          else {
            alert("Fail to update survey");
          }
        });
      }
    },
    stickyControls: {
      enable: true
    },
    sortableControls: true,
    fields: fields,
    templates: templates,
    typeUserAttrs: typeUserAttrs,
    disableInjectedStyle: false,
    disableFields: ['autocomplete'],
    disabledActionButtons: ['data']
  };

  window.sessionStorage.removeItem('formData');
  var formData = window.sessionStorage.getItem('formData');
  var editing = true;

  if (formData) {
    fbOptions.formData = JSON.parse(formData);
  }


  var setFormData = '[]';


  var formBuilder = $('.build-wrap').formBuilder(fbOptions);
  var fbPromise = formBuilder.promise;

  var id_input = $('#survey_id_input').val();
  if(id_input!=""){
    $.post("getSurvey.php",
    {
      survey_id : id_input
    },
    function(data, status){
      if(data!="0"){
        var temp_object = JSON.parse(data);

        formBuilder.actions.setData(temp_object.jsonForm);
        $('#survey_title_input').val(temp_object.title);
      }
    });
  }

  /*$("#btnLoadYeap").click(function(){
    formBuilder.actions.setData(setFormData);
  });*/

  fbPromise.then(function(fb) {
    var apiBtns = {
      showData: fb.actions.showData,
      clearFields: fb.actions.clearFields,
      getData: function() {
        console.log(fb.actions.getData());
      },
      setData: function() {
        fb.actions.setData(setFormData);
      },
      addField: function() {
        var field = {
            type: 'text',
            class: 'form-control',
            label: 'Text Field added at: ' + new Date().getTime()
          };
        fb.actions.addField(field);
      },
      removeField: function() {
        fb.actions.removeField();
      },
      testSubmit: function() {
        var formData = new FormData(document.forms[0]);
        console.log('Can submit: ', document.forms[0].checkValidity());
        // Display the key/value pairs
        console.log('FormData:', formData);
        for(var pair of formData.entries()) {
           console.log(pair[0]+ ': '+ pair[1]);
        }
      },
      resetDemo: function() {
        window.sessionStorage.removeItem('formData');
        location.reload();
      }
    };

    Object.keys(apiBtns).forEach(function(action) {

      if(document.getElementById(action)){
        document.getElementById(action).addEventListener('click', function(e) {
          apiBtns[action]();
        });
      }
    });

    if(document.getElementById('setLanguage')){
      document.getElementById('setLanguage')
      .addEventListener('change', function(e) {
        fb.actions.setLang(e.target.value);
      });
    }

    $(".btn-danger").css('color','white');
    $(".btn-primary").css('color','white');
  });


});
