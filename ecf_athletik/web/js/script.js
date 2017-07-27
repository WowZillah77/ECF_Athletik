var INPUT_TIME = document.getElementsByClassName('time'); //On chope toute les classes ".time"
for (var i = 0; i < INPUT_TIME.length; i++) {
    INPUT_TIME[i].addEventListener("keyup", calcPoint);  //On applique à notre prise (c.f. variable.js) un event a chaque touche qui lance calcPoint (c.f. function.js)
    INPUT_TIME[i].addEventListener("blur", ajaxObject);

}
