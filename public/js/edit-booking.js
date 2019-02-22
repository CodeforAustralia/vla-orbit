var services = [];
var current_date = new Date();
var current_month = current_date.getMonth() + 1;
var current_year = current_date.getFullYear();
var current_service = '';

$(document).ready(function() {
    printBooking();
});

function printBooking() {
    $("#printBooking").click(function () {

        var printFrame = document.createElement('iframe');
        printFrame.name = "printFrame";
        printFrame.style.position = "absolute";
        printFrame.style.zIndex = "1000000";
        printFrame.style.top = "0px";
        printFrame.style.backgroundColor = "white";
        printFrame.style.width = "100%";
        printFrame.style.height = "100%";
        $(".page-container").hide();
        document.body.appendChild(printFrame);
        var frameDoc = (printFrame.contentWindow) ? printFrame.contentWindow : (printFrame.contentDocument.document) ? printFrame.contentDocument.document : printFrame.contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><body onload="window.print()">' +
            $('#SelectMatchLabel').html() + '<br>' +
            $('#clientInformation').html() + '<br>' +
            $('#bookingInformation').html() + '<br>' +
            $('#extraInformation').html() + '<br>' +
            '</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            document.body.removeChild(printFrame);
            $(".page-container").show();
        }, 500);
        return false;
    });
}