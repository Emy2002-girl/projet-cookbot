
        // Animation au scroll
        function animateOnScroll() {
            const cards = document.querySelectorAll('.testimonial-card');
            const ctaSection = document.querySelector('.cta-section');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('animate');
                        }, index * 100);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            cards.forEach(card => {
                observer.observe(card);
            });

            if (ctaSection) {
                observer.observe(ctaSection);
            }
        }

        // Animation des étoiles au hover
        function setupStarAnimations() {
            const starContainers = document.querySelectorAll('.stars');

            starContainers.forEach(container => {
                const stars = container.querySelectorAll('i');

                container.addEventListener('mouseenter', () => {
                    stars.forEach((star, index) => {
                        setTimeout(() => {
                            star.style.transform = 'scale(1.3) rotate(15deg)';
                        }, index * 50);
                    });
                });

                container.addEventListener('mouseleave', () => {
                    stars.forEach(star => {
                        star.style.transform = 'scale(1) rotate(0deg)';
                    });
                });
            });
        }

        // Animation du bouton CTA avec effet ripple
        function setupButtonAnimation() {
            const ctaButton = document.getElementById('ctaButton');

            if (ctaButton) {
                ctaButton.addEventListener('click', function (e) {
                    // Créer l'effet ripple
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');

                    this.appendChild(ripple);

                    // Supprimer le ripple après l'animation
                    setTimeout(() => {
                        if (ripple.parentNode) {
                            ripple.remove();
                        }
                    }, 600);

                    // Action du bouton (exemple)
                    alert('Merci ! Votre témoignage sera bientôt ajouté.');
                });
            }
        }

        // Animation des cartes au hover
        function setupCardHoverEffects() {
            const cards = document.querySelectorAll('.testimonial-card');

            cards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    if (window.innerWidth > 768) {
                        this.style.transform = 'translateY(-8px) scale(1.02)';
                    }
                });

                card.addEventListener('mouseleave', function () {
                    if (window.innerWidth > 768) {
                        this.style.transform = 'translateY(0) scale(1)';
                    }
                });
            });
        }

        // Initialisation quand le DOM est chargé
        document.addEventListener('DOMContentLoaded', function () {
            // Petite pause pour s'assurer que tout est chargé
            setTimeout(() => {
                animateOnScroll();
                setupStarAnimations();
                setupButtonAnimation();
                setupCardHoverEffects();
            }, 100);
        });

        // Gestion du redimensionnement
        window.addEventListener('resize', () => {
            const cards = document.querySelectorAll('.testimonial-card');
            cards.forEach(card => {
                if (window.innerWidth <= 768) {
                    card.style.transform = 'none';
                }
            });
        });
