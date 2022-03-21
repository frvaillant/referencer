document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('nav ul li a');
    for (let i = 0; i < navLinks.length; i++) {
        const href = navLinks[i].getAttribute('href');
        const pageUrl = document.location.href

        if (pageUrl.indexOf(href) !== -1 && navLinks[i].getAttribute('id') !== 'main-link') {
            navLinks[i].classList.add('active')
        }
    }
})
