let sections = document.querySelectorAll('section');
let sidebar = document.querySelector(".sidebar");
let buttonToggler = document.querySelector('nav button');
let sidebar_overlay = sidebar.querySelector('.sidebar-overlay');
let navLink = document.querySelectorAll('.sidebar .sidebar-content a');
//--- navbar tasks
window.addEventListener('scroll', () => {
    let navbar = document.getElementById('nav')
    let sidebar = document.querySelector('.sidebar');
    let scrollPosition = document.documentElement.scrollTop;
    this.scrollY >= 150 ? navbar.classList.add("navScroll") : navbar.classList.remove("navScroll");
    this.scrollY >= 150 ? sidebar.classList.add("navScroll") : sidebar.classList.remove("navScroll");
    sections.forEach(section => {
        if (scrollPosition >= section.offsetTop - section.offsetHeight * 0.25 && scrollPosition < section.offsetTop + section.offsetHeight - section.offsetHeight * 0.25) {
            let currentId = section.getAttribute('id');
            removeActive();
            AddActive(currentId);
        }
    })
})
let removeActive = () => {
    let navList = document.querySelectorAll('.navbar .navbar-nav .nav-link');
    let sidebarNav = document.querySelectorAll('.sidebar .sidebar-content .nav-link');
    navList.forEach(item => {
        item.classList.remove('active')
    })
    sidebarNav.forEach(item => {
        item.classList.remove('active')
    })
}
let AddActive = (id) => {
    if (id) {
        let Navselector = `.navbar .navbar-nav a[href="#${id}"]`;
        let Sideselector = `.sidebar .sidebar-content a[href="#${id}"]`;
        document.querySelector(Navselector).classList.add('active');
        document.querySelector(Sideselector).classList.add('active');
    } else {
        return;
    }

}
sidebar_overlay.addEventListener("click", () => {
    sidebar.classList.remove('active')
})
buttonToggler.addEventListener("click", () => {
    sidebar.classList.toggle('active')
})
navLink.forEach(item => {
        item.addEventListener('click', () => {
            sidebar.classList.remove('active')
        })
    })
    //------------------