(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./resources/js/main.js":
/*!******************************!*\
  !*** ./resources/js/main.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $("#get_prize").submit(function (e) {
    e.preventDefault();
    var api_token = $('#api_token').val();

    if (api_token === undefined || api_token === '') {
      alert("Please try again latter");
      return false;
    }

    $.ajax({
      type: "PUT",
      url: "/api/get-prize",
      dataType: 'json',
      headers: {
        "Authorization:": "Bearer " + api_token
      },
      beforeSend: function beforeSend() {
        alert('beforeSend');
      },
      success: function success(data) {
        response = jQuery.parseJSON(data);
        console.log('success');
        console.log(response);
      },
      complete: function complete() {
        console.log('complete');
      },
      error: function error(xhr, status, _error) {
        console.log(_error, xhr); // alert(status);
      }
    });
    return false;
  });
});

/***/ })

}]);