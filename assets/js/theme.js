/*
 * EterCraft : petites interactions du thème, sans dépendance.
 * - la navigation devient opaque dès qu'on défile ;
 * - les éléments [data-reveal] apparaissent en douceur quand ils entrent à l'écran ;
 * - un bouton .copy-ip copie l'adresse du serveur et l'indique pendant 2 secondes.
 * - mosaïque : le halo des cartes [data-spotlight] suit la souris ;
 * - vitrine : survoler ou cliquer un titre [data-showcase-tab] affiche son panneau.
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

    document.querySelectorAll('[data-spotlight]').forEach((card) => {
        card.addEventListener('pointermove', (event) => {
            const box = card.getBoundingClientRect();
            card.style.setProperty('--x', `${event.clientX - box.left}px`);
            card.style.setProperty('--y', `${event.clientY - box.top}px`);
        });
    });

    document.querySelectorAll('[data-showcase]').forEach((showcase) => {
        const tabs = showcase.querySelectorAll('[data-showcase-tab]');
        const panels = showcase.querySelectorAll('[data-showcase-panel]');

        const select = (index) => {
            tabs.forEach((tab) => tab.classList.toggle('active', tab.dataset.showcaseTab === index));
            panels.forEach((panel) => panel.classList.toggle('active', panel.dataset.showcasePanel === index));
        };

        tabs.forEach((tab) => {
            ['mouseenter', 'focus', 'click'].forEach((type) => {
                tab.addEventListener(type, () => select(tab.dataset.showcaseTab));
            });
        });
    });
});
