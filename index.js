//main pg
window.onscroll = function(){scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 150 || document.documentElement.scrollTop > 20   ) {
    document.getElementById("top").style.display = "block";
  } else {
    document.getElementById("top").style.display = "none";
  }
}
function topFunction() {
  document.documentElement.scrollTop = 10; 
}

//buton click redirect
// const btn = document.getElementById("sub");
// btn.addEventListener("click", function() {
//   location.href = "./reglo/register.html";
// });


//buton click redirect
