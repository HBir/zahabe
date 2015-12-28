/*Globala variabler*/
var newMvs = 0;
var oldList = 0;
var newList = 0;




function refreshPage(type) {
    /*Kollar om några nya inlägg har lagts till och uppdaterar sidan asynkront ifall så är fallet*/
    $.get("ajaxMV.php", function (data) {
        var newList = (data.match(/<li/g) || []).length;
        setCookie("MVAmount", newList, 50);
        if (newList - oldList > 0) {
            $("#MVs").html(data);
            
            /*Här hanteras uppdateringsmeddelande för nya inlägg*/
            if (oldList != 0 && type != "add" && document.hasFocus() == false) {

                newMvs = newMvs + (newList - oldList);
                document.title = "(" + newMvs + ") Minns vi den gången Zahabe";

                for (i = 1; i <= newMvs; i++) {
                    $("#MVs li:nth-child(" + i + ")").css('background-color', '#f1f1f1');
                }
            }
            oldList = newList;
        }
    });
}

$(window).focus(function() {
    /*Tar bort uppdateringsmeddelanden när sidan får fokus*/
    document.title = "Minns vi den gången Zahabe";
    newMvs = 0;
});

$(window).blur(function () {
    /*Tar bort uppdateringsmarkeringar när sidan tappar fokus*/
    $( "#MVs li" ).css('background-color', 'transparent');
});

$(document).ready(function() {
    /*Lägger till nya MVs asynkront*/
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
            /*Visar ett felmeddelande beroende på vilken HTTP-statuskod skickas tillbaka*/
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

$(window).load(function () {
    /*Init*/
    oldList = document.getElementById("MVs").getElementsByTagName("li").length;
    var cookiedList = getCookie("MVAmount");

    for (i = 1; i <= oldList - cookiedList; i++) {
        $("#MVs li:nth-child(" + i + ")").css('background-color', '#f1f1f1');
    }

    setInterval("refreshPage('')", 5000);
});


function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}