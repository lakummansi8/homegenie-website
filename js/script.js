const menuToggle = document.querySelector(".menuToggle");
const links = document.querySelector(".links");

if (menuToggle && links) {

    menuToggle.addEventListener("click", function () {

        links.classList.toggle("mobile-menu");

    });

}