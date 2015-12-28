
var newMvs = 0;
var oldList = 0;
var newList = 0;

function refreshPage(type) {
    $.get("ajaxMV.php", function (data) {
        var newList = (data.match(/<li/g) || []).length;

        if (newList - oldList > 0) {
            if (oldList != 0 && type != "add" && document.hasFocus() == false) {
                /*Alert goes here*/

                console.log(newList - oldList);

                newMvs = newMvs + (newList - oldList);
                document.title = "(" + newMvs + ") Minns vi den gången Zahabe";
            }
            $("#MVs").html(data);
            oldList = newList;
        }
    });
}
$(window).focus(function() {
    document.title = "Minns vi den gången Zahabe";
    newMvs = 0;
});
$(document).ready(function() {
    $("#MVform").submit(function(e) {

        var url = "add.php";

        $.ajax({
            type: "POST",
            url: url,
            data: $("#MVform").serialize(),
            success: function(data) {
                document.getElementById("nyruta").value = '';
                $('#errorspace').html("");
                refreshPage("add");
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                switch (errorThrown) {
                    case "Not Acceptable":
                        $('#errorspace').html("...inte förstod");
                        break;
                    case "Conflict":
                        $('#errorspace').html("...försökte duplicera sin död");
                        break;
                    case "Forbidden":
                        $('#errorspace').html("...hittade det förbjudna");
                        break;
                    case "Unauthorized":
                        $('#errorspace').html("...gjorde bort sig totalt");
                        break;
                    default:
                        $('#errorspace').html("...fick " + errorThrown);
                }
            }
        });
        e.preventDefault();
    });
});

$(window).load(function() {
    //refreshPage("");
    setInterval("refreshPage('')", 5000);
});