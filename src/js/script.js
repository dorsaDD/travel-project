//clicking the menu button might toggle the visibility of the navigation bar. 
/*-------For the menu ------ */
let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.header .navbar');

menu.onclick = () =>{
   menu.classList.toggle('fa-times');
   navbar.classList.toggle('active');
};

//Ensures that if the user scrolls, the navigation menu closes automatically.
window.onscroll = () =>{
   menu.classList.remove('fa-times');
   navbar.classList.remove('active');
};

//Input Length Restriction & Dynamic Validation
document.querySelectorAll('input[type="number"]').forEach(inputNumber => {
   inputNumber.oninput = () =>{
      if(inputNumber.value.length > inputNumber.maxLength) inputNumber.value = inputNumber.value.slice(0, inputNumber.maxLength);
   };
});
/*------- swiper in home page ------*/
var swiper = new Swiper(".home-slider", {
   loop:true,
   navigation: {
   nextEl: ".swiper-button-next",
   prevEl: ".swiper-button-prev",
   },
});



/*----- load more btn in the package and other parts -----------*/
let loadMoreBtn = document.querySelector('.packages .load-more .btn');
let currentItem = 3;

loadMoreBtn.onclick = () =>{
   let boxes = [...document.querySelectorAll('.packages .box-container .box')];
   for (var i = currentItem; i < currentItem + 3; i++){
      boxes[i].style.display = 'inline-block';
   };
   currentItem += 3;
   if(currentItem >= boxes.length){
      loadMoreBtn.style.display = 'none';
   }
}

document.addEventListener("DOMContentLoaded", function () {
   new Swiper(".reviews-slider", {
      grabCursor: true,
      loop: true,
      autoHeight: true,
      spaceBetween: 20,
      breakpoints: {
            0: {
               slidesPerView: 1,
            },
            700: {
               slidesPerView: 2,
            },
            1000: {
               slidesPerView: 3,
            },
      },
   });
});
