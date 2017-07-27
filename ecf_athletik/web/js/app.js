$(document).foundation();


/* function Select course*/
$(document).on("click", "#select", function() {
    $("#selectcourse").attr( "action","/signedup/"+$(this).val() );
});
/* function Coeff and calculation of the points*/
function calcPoint() {

    var meetingid = this.parentNode.parentNode.parentNode.querySelector('.meetingid').innerHTML;
    var meeting = this.parentNode.parentNode.parentNode.querySelector('.meetingyear').innerHTML;
    var athlete = this.parentNode.parentNode.parentNode.querySelector('.athleteyear').innerHTML;
    var athleteid = this.parentNode.parentNode.parentNode.querySelector('.athleteid').innerHTML;
    console.log(meeting);
    console.log(athlete);
    var id = $(this).attr("id");
    var age = meeting - athlete;
    console.log(age);
    var coeff;
    if (age <= 11) {
        coeff = 1.5;
    } else if (age <= 13) {
        coeff = 1.42;
    } else if (age <= 15) {
        coeff = 1.35;
    } else if (age <= 17) {
        coeff = 1.21;
    } else if (age <= 19) {
        coeff = 1.18;
    } else if (age <= 22) {
        coeff = 1.09;
    } else if (age <= 40) {
        coeff = 1;
    } else {
        coeff = 1.35;
    }
    var time = this.value;
    console.log(time);
    var points = Math.round((1000 / time) * coeff);
    this.parentNode.parentNode.parentNode.querySelector('.classpoint').innerHTML = points;


}
    function ajaxObject() {
        var meetingid = this.parentNode.parentNode.parentNode.querySelector('.meetingid').innerHTML;
        var athleteid = this.parentNode.parentNode.parentNode.querySelector('.athleteid').innerHTML;
        var points = this.parentNode.parentNode.parentNode.querySelector('.classpoint').innerHTML;
        var time = this.value;
        $.ajax({

            url: '',
            type: 'POST',
            data: {time: time, points: points, athleteid: athleteid, meetingid: meetingid}
        });
    }