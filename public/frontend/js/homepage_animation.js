// variables
let header = document.querySelector(".mainHeader");
let candidtesSec = document.querySelector(".candidates");
let briefSec = document.querySelector(".brief");
let electoral_programSec = document.querySelector(".electoral_program");
let electoral_targetSec = document.querySelector(".electoral_target");
let afaqGroupSec = document.querySelector(".afaqGroup");
let afaqTweetSec = document.querySelector(".afaqTweet");
let eventDetailSec = document.querySelector(".eventDetails");
let tech_supportSec = document.querySelector(".tech_support");
gsap.timeline({
        scrollTrigger: {
            trigger: header,
            start: "+=600 center",
            end: "bottom center",
            scrub: 2,
        }
    })
    .to(header.querySelector(".carousel"), { opacity: "0" });
gsap.timeline({
        scrollTrigger: {
            trigger: candidtesSec,
            start: "top bottom",
            end: "+=100 bottom",
            scrub: 2,
        }
    })
    .fromTo(candidtesSec.querySelector(".main_sec_title"), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" })
    .fromTo(candidtesSec.querySelector(".owl-carousel"), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" }, "-=0.5");
gsap.timeline({
        scrollTrigger: {
            trigger: briefSec,
            start: "top bottom",
            end: "+=200 bottom",
            scrub: 2,
        }
    })
    .fromTo(briefSec.querySelector(".main_sec_title"), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" })
    .fromTo(briefSec.querySelector("p"), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" }, "-=0.5");
gsap.timeline({
        scrollTrigger: {
            trigger: electoral_programSec,
            start: "top bottom",
            end: "+=100 bottom",
            scrub: 2,
        }
    })
    .fromTo(electoral_programSec.querySelector(".main_sec_title"), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" })
    .fromTo(electoral_programSec.querySelector(".owl-carousel"), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" }, "-=0.5");
gsap.timeline({
        scrollTrigger: {
            trigger: electoral_targetSec,
            start: "top bottom",
            end: "+=100 bottom",
            scrub: 2,
        }
    })
    .fromTo(electoral_targetSec.querySelector(".main_sec_title"), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" })
    .fromTo(electoral_targetSec.querySelector(".owl-carousel"), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" }, "-=0.5");
gsap.timeline({
        scrollTrigger: {
            trigger: afaqGroupSec,
            start: "top bottom",
            end: "+=100 bottom",
            scrub: 2,
        }
    })
    .fromTo(afaqGroupSec.querySelector(".main_sec_title"), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" })
    .fromTo(afaqGroupSec.querySelector(".owl-carousel"), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" }, "-=0.5");
gsap.timeline({
        scrollTrigger: {
            trigger: afaqTweetSec,
            start: "top bottom",
            end: "+=100 bottom",
            scrub: 2,
        }
    })
    .fromTo(afaqTweetSec.querySelector("h2"), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" })
    .fromTo(afaqTweetSec.querySelector(".qouat_container"), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" }, "-=0.5");
gsap.timeline({
        scrollTrigger: {
            trigger: eventDetailSec,
            start: "top bottom",
            end: "+=500 bottom",
            scrub: 2
        }
    })
    .fromTo(eventDetailSec.querySelector(".carousel"), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" }, "-=0.5");
gsap.timeline({
        scrollTrigger: {
            trigger: tech_supportSec,
            start: "top bottom",
            end: "+=500 bottom",
            scrub: 2,
        }
    })
    .fromTo(tech_supportSec.querySelector(".form_container"), { x: "100%", opacity: "0" }, { x: "0%", opacity: "1" })
    .fromTo(tech_supportSec.querySelector("img"), { x: "-100%", opacity: "0" }, { x: "0%", opacity: "1" }, "-=0.5");