$(document).ready(function() {
    $("#urlForm").submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting the traditional way

        const url = $("#url").val();
        const element = $("#element").val();

        $.ajax({
            type: "POST",
            url: "process.php",
            data: {
                url: url,
                element: element
            },
            success: function(response) {
                $('#responseArea').text(response.message).show();
                $("#responseArea").html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error occurred:", errorThrown); // Log the error to console for more detailed inspection
                $("#responseArea").html("An error occurred. Please check the console for more details.");
            }
        });
    });
});
