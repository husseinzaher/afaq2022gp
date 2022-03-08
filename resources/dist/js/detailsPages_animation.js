let skill_reviewSec = document.querySelector('.skill_review');
gsap.timeline({
        scrollTrigger: {
            trigger: skill_reviewSec.querySelector('.membership'),
            start: 'top bottom',
            end: '+=100 bottom',
            scrub: 2
        }
    })
    .fromTo(skill_reviewSec.querySelector('.membership .main-title'), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" })
    .fromTo(skill_reviewSec.querySelector('.membership .skill-review-items'), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" })
    .fromTo(skill_reviewSec.querySelector('.vision'), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" })
    .fromTo(skill_reviewSec.querySelector('.msg'), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" })
gsap.timeline({
        scrollTrigger: {
            trigger: skill_reviewSec.querySelector('.goals'),
            start: 'top bottom',
            end: '+=300 bottom',
            scrub: 2
        }
    })
    .fromTo(skill_reviewSec.querySelector('.title'), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" })
    .fromTo(skill_reviewSec.querySelector('.goal-list'), { y: "100%", opacity: "0" }, { y: "0%", opacity: "1" })