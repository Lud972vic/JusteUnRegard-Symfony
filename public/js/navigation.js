var prevScrollpos = window.pageYOffset;
window.onscroll = function () {
    var currentScrollPos = window.pageYOffset;
    if (prevScrollpos > currentScrollPos) {
        document.getElementById("navbarhaut").style.top = "0";
        document.getElementById("navbarbas").style.bottom = "0";
    } else {
        document.getElementById("navbarhaut").style.top = "-100px";
        document.getElementById("navbarbas").style.bottom = "-100px";
    }
    prevScrollpos = currentScrollPos;
}
