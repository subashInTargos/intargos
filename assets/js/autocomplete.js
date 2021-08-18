var base_url = "http://localhost/ParcelX/Portal/";

$(".autocomplete-username").focus(function(){
    $.ajax({
        url: base_url+"Autocomplete/username",           
        dataType: "json",
        type: "POST",
        success: function (data)
        {
            $('.autocomplete-username').typeahead({ source: data });
        }
    });
});

$(".autocomplete-businessname").focus(function(){
    $.ajax({
        url: base_url+"Autocomplete/businessname",           
        dataType: "json",
        type: "POST",
        success: function (data)
        {
            $('.autocomplete-businessname').typeahead({ source: data });
        }
    });
});