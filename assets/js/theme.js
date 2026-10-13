/*
 * EterCraft : petites interactions du thème, sans dépendance.
 * - la navigation devient opaque dès qu'on défile ;
 * - les éléments [data-reveal] apparaissent en douceur quand ils entrent à l'écran ;
 * - un bouton .copy-ip copie l'adresse du serveur et l'indique pendant 2 secondes.
 */
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('siteNavbar');

    if (navbar) {
        const update = () => navbar.classList.toggle('scrolled', window.scrollY > 24);
        update();
        window.addEventListener('scroll', update, { passive: true });
    }

    const reveals = document.querySelectorAll('[data-reveal]');

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    entry.target.style.transitionDelay = `${Math.min(index * 60, 300)}ms`;
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        reveals.forEach((element) => observer.observe(element));
    } else {
        reveals.forEach((element) => element.classList.add('revealed'));
    }

    document.querySelectorAll('.copy-ip').forEach((button) => {
        const label = button.querySelector('.copy-label');
        const original = label.textContent;

        button.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(button.dataset.copy);
                label.textContent = button.dataset.copied;
            } catch (error) {
                label.textContent = button.dataset.copy;
            }
            setTimeout(() => (label.textContent = original), 2000);
        });
    });
});
