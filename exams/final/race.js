$(function() {

    createModal();

    $("button.add").on("click", function(e) {
        $("#modalBox").css("display", "block");
    })

    $("button.save").on("click", function(e) {
        
        var raceDetails = {
            "raceID": $("#raceID").val(),
            "raceDate": $("#raceDate").val(),
            "startTime": $("#startTime").val(),
            "password": $("#password").val(),
            "raceLocation": $("#raceLocation").val(),
        };

        $.ajax({
            type: "POST",
            url: "index.php",
            dataType: "json",
            contentType: "application/json",
            data: JSON.stringify(raceDetails),
            success: function(data, status) {
                console.log("Got data", data);
            },
            error: function(err) {
                console.log("Didn't get data", err);
            },
            complete: function() {
                // Do this last
                $("#modalBox").css("display", "none");
            }
        });
    })

    // Make a global variable
    var racesData;

    $.ajax({
        type: "GET",
        url: "index.php",
        dataType: "json",
        success: function(data, status) {
            racesData = data;
            console.log("Got data", racesData);

            for (var r in racesData) {
                var race_details = racesData[r];

                $("#races table tbody").append(
                    $("<tr>")
                    .append($("<td>")
                        .html(race_details.race_id))
                    .append($("<td>")
                        .html(race_details.from_date))
                    .append($("<td>")
                        .html(race_details.to_date))
                    .append($("<td>")
                        .html(race_details.type_name))
                    .append(
                        $("<button>")
                        .append($("<img>")
                            .attr("src", "img/racing-actions-cancel.png"))
                    ))
            }

        },
        error: function(err) {
            console.log("Didn't get data", err);
        }
    });

})

function createModal() {
    // Get the modal

    // Get the button that opens the modal
    var btn = document.getElementById("openButton");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        $("#modalBox").css("display", "none");
    }

    var modal = document.getElementById('modalBox');

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            $("#modalBox").css("display", "none");
        }
    }
}

