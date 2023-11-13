
$("#dl-btn, #print-btn").click(function(event) {
    $.ajax({
        url:"/create_pdf",
        type: "POST",
        data: {
            object_type: window.location.pathname, 
            id: $("input[name='ID']").val()
        }
    }).done({
        
    })
    // event.preventDefault();
});

