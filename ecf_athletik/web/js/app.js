$(document).foundation()


/* function Select course*/
$(document).on("change", "#select", function() {
    $("#selectcourse").attr( "action","/signedup/"+$(this).val() );
});
/* function Coeff and calculation of the points*/
$(document).on("click", ".resultForm", function() {
    var meetingid = document.getElementById('meetingid').innerHTML;
    var meeting = document.getElementById('meetingyear').innerHTML;
    var athlete = document.getElementById('athleteyear').innerHTML;
    var athleteid = document.getElementById('athleteid').innerHTML;
    console.log(meeting);
    console.log(athlete);
    var id = $(this).attr("id");
    var age=meeting-athlete;
    console.log(age);
    var coeff;
    if (age <= 11){
        coeff = 1.5;
    } else if(age <= 13){
        coeff = 1.42;
    } else if(age <= 15){
        coeff = 1.35;
    } else if(age <= 17){
        coeff = 1.21;
    } else if(age <= 19){
        coeff = 1.18;
    } else if(age <= 22){
        coeff = 1.09;
    } else if(age <= 40){
        coeff = 1;
    } else {
        coeff = 1.35;
    }
    var time =document.getElementById("time"+id).value;
    console.log(time);
    var points= Math.round((1000/time)*coeff);
    document.getElementById("point"+id).innerHTML=points;
    $.ajax({

        url: '',
        type: 'POST',
        data:{time : time, points:points, athleteid: athleteid, meetingid: meetingid}


    });
});
