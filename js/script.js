function changeLPSP() {
    var x = document.getElementById("LF");
    if (x.style.display === "block") {
      x.style.display = "none";
    }
    var x = document.getElementById("SF");
    if (x.style.display === "none") {
      x.style.display = "block";
    }
}
function changeSPLP() {
    var x = document.getElementById("LF");
    if (x.style.display === "none") {
      x.style.display = "block";
    }
    var x = document.getElementById("SF");
    if (x.style.display === "block") {
      x.style.display = "none";
    }
}
function showrefcd() {
  var x = document.getElementById("arefcd");
    x.style.display = "none";
  
  var x = document.getElementById("refcd");
  if (x.style.display === "none") {
    x.style.display = "block";
  }
}
