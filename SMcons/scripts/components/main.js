
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
            if (entry.target.classList.contains('stats-section')) startCounters();
        }
    });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal, .stats-section').forEach(el => observer.observe(el));


function startCounters() {
    document.querySelectorAll('.counter-value').forEach(counter => {
        if (counter.dataset.done) return;
        const target = +counter.getAttribute('data-target');
        let current = 0;
        const inc = target / 50;
        const update = () => {
            if (current < target) {
                current += inc;
                counter.innerText = Math.ceil(current);
                setTimeout(update, 30);
            } else { counter.innerText = target; counter.dataset.done = true; }
        };
        update();
    });
}


$(document).ready(function () {
    const $slider = $('.project-slider').slick({
        slidesToShow: 3,
        dots: true,
        arrows: false,
        infinite: false,
        responsive: [
            { breakpoint: 992, settings: { slidesToShow: 2 } },
            { breakpoint: 768, settings: { slidesToShow: 1 } }
        ]
    });

    $('.filter-btn').on('click', function () {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        const filter = $(this).data('filter');
        $slider.slick('slickUnfilter');
        if (filter !== 'all') $slider.slick('slickFilter', '.' + filter);
    });
});


const menuBtn = document.getElementById('menuBtn');
const fullMenu = document.getElementById('fullMenu');
const nav = document.getElementById('mainNav');

menuBtn.addEventListener('click', () => {
    menuBtn.classList.toggle('open');
    fullMenu.classList.toggle('active');
    nav.classList.toggle('menu-open');
    document.body.style.overflow = fullMenu.classList.contains('active') ? 'hidden' : 'auto';
});

window.addEventListener('scroll', () => {
    window.scrollY > 50 ? nav.classList.add('scrolled') : nav.classList.remove('scrolled');
});

const translations = {
    "en": {
        "nav-projects": "OUR PROJECTS",
        "nav-legacy": "OUR LEGACY",
        "hero-title": "Redefining<br>Luxury Landscapes.",
        "hero-sub": "The Future of Living",
        "hero-desc": "We don't just build buildings, but a new lifestyle that is a perfect blend of modern design and durability.",
        "hero-btn": "View Portfolio",
        "stat-years": "Years of Legacy",
        "stat-landmarks": "Landmarks",
        "stat-area": "Sq.Ft. Developed",
        "about-title": "Legacy Built with <span class='text-gold'>Excellence</span>.",
        "about-sub": "Nagpur's Pride",
        "about-desc": "S&M Infra is a new name for luxury living in Nagpur. We don't just build concrete structures, we prepare a strong framework of dreams.",
        "about-btn": "Discover More"
    }
    
};