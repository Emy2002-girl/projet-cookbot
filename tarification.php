<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarification - CookBot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="tarification.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <!-- Logo -->
            <div class="logo">
                <a href="homepage.php"><div class="logo-icon"><img src="images/cutlery.png" alt="" width="35" height="35"></div></a>
                <a href="homepage.php" style="text-decoration:none"><span class="logo-text"><span style="color:#10B981;">Cook</span>Bot</span></a>
            </div>
            <!-- Navigation Menu -->
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        Fonctionnalités
                        <span class="dropdown-arrow"></span>
                    </a>
                    
                    <!-- Dropdown Menu -->
                    <div class="dropdown-menu">
                        <a href="pantrychef.php" class="dropdown-item">
                            <div class="dropdown-icon icon-pantry">🥘</div>
                            <span class="dropdown-text">PantryChef</span>
                        </a>
                        <a href="masterchef.php" class="dropdown-item">
                            <div class="dropdown-icon icon-master">👨‍🍳</div>
                            <span class="dropdown-text">MasterChef</span>
                        </a>
                        
                        <a href="macroschef.php" class="dropdown-item">
                            <div class="dropdown-icon icon-macros">🍌</div>
                            <span class="dropdown-text">MacrosChef</span>
                        </a>
                        
                        <a href="mealplanchef.php" class="dropdown-item">
                            <div class="dropdown-icon icon-meal">📅</div>
                            <span class="dropdown-text">MealPlanChef</span>
                        </a>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a href="tarification.php" class="nav-link">Tarification</a>
                </li>

                <li class="nav-item">
                    <a href="blog.php" class="nav-link">Blog</a>
                </li>
            </ul>
            <div class="header-right">
                <span style="border: 1px solid gainsboro; padding: 0.5rem 0.75rem; border-radius: 18px;">FR</span>
                <span style="border: 1px solid gainsboro;padding: 0.7rem 0.75rem;border-radius: 18px;display: flex;justify-content: center;"><img src="images/sun.png" alt="" width="17" height="17" margin-top="5px"></span>
                <a href="login.php" class="login-link">Se connecter</a>
                <a href="inscription.php" class="btn-primary">S'inscrire</a>
            </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Choisissez votre plan</h1>
            <p>Débloquez tout le potentiel de CookBot avec nos plans flexibles. Commencez gratuitement et évoluez selon vos besoins culinaires.</p>
            
            <div class="billing-toggle">
                <span class="billing-label active" id="monthlyLabel">Mensuel</span>
                <div class="toggle-switch" id="billingToggle">
                    <div class="toggle-slider"></div>
                </div>
                <span class="billing-label" id="yearlyLabel">Annuel</span>
                <span class="discount-badge">-20%</span>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing-section">
        <div class="container">
            <div class="pricing-grid">
                <!-- Plan Gratuit -->
                <div class="pricing-card">
                    <div class="card-content">
                        <h3 class="plan-name">Gratuit</h3>
                        <p class="plan-description">Parfait pour découvrir CookBot et ses fonctionnalités de base</p>
                        
                        <div class="price">
                            <span class="price-currency">€</span>
                            <span class="price-amount">0</span>
                            <span class="price-period">/mois</span>
                        </div>
                        
                        <button class="cta-button">Commencer gratuitement</button>
                        
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> 5 recettes générées par jour</li>
                            <li><i class="fas fa-check"></i> Accès à PantryChef</li>
                            <li><i class="fas fa-check"></i> Recettes de base</li>
                            <li><i class="fas fa-check"></i> Support communautaire</li>
                            <li class="unavailable"><i class="fas fa-times"></i> MacrosChef avancé</li>
                            <li class="unavailable"><i class="fas fa-times"></i> MealPlanChef</li>
                            <li class="unavailable"><i class="fas fa-times"></i> Support prioritaire</li>
                        </ul>
                    </div>
                </div>

                <!-- Plan Pro (Populaire) -->
                <div class="pricing-card popular">
                    <div class="card-content">
                        <h3 class="plan-name">Pro</h3>
                        <p class="plan-description">Idéal pour les passionnés de cuisine qui veulent plus de fonctionnalités</p>
                        
                        <div class="price">
                            <span class="price-currency">€</span>
                            <span class="price-amount monthly-price">19</span>
                            <span class="price-amount yearly-price" style="display: none;">15</span>
                            <span class="price-period monthly-period">/mois</span>
                            <span class="price-period yearly-period" style="display: none;">/mois</span>
                            <span class="price-original yearly-original" style="display: none;">€19</span>
                        </div>
                        
                        <button class="cta-button">Commencer l'essai gratuit</button>
                        
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> Recettes illimitées</li>
                            <li><i class="fas fa-check"></i> Tous les chefs (Pantry, Master, Macros)</li>
                            <li><i class="fas fa-check"></i> MealPlanChef (plans de repas)</li>
                            <li><i class="fas fa-check"></i> Restrictions alimentaires avancées</li>
                            <li><i class="fas fa-check"></i> Sauvegarde de recettes</li>
                            <li><i class="fas fa-check"></i> Liste de courses automatique</li>
                            <li><i class="fas fa-check"></i> Support par email</li>
                        </ul>
                    </div>
                </div>

                <!-- Plan Premium -->
                <div class="pricing-card">
                    <div class="card-content">
                        <h3 class="plan-name">Premium</h3>
                        <p class="plan-description">Pour les chefs professionnels et les familles nombreuses</p>
                        
                        <div class="price">
                            <span class="price-currency">€</span>
                            <span class="price-amount monthly-price">39</span>
                            <span class="price-amount yearly-price" style="display: none;">31</span>
                            <span class="price-period monthly-period">/mois</span>
                            <span class="price-period yearly-period" style="display: none;">/mois</span>
                            <span class="price-original yearly-original" style="display: none;">€39</span>
                        </div>
                        
                        <button class="cta-button">Commencer l'essai gratuit</button>
                        
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> Tout du plan Pro</li>
                            <li><i class="fas fa-check"></i> Comptes familiaux (jusqu'à 6)</li>
                            <li><i class="fas fa-check"></i> Nutritionniste IA personnalisé</li>
                            <li><i class="fas fa-check"></i> Intégration courses en ligne</li>
                            <li><i class="fas fa-check"></i> Recettes vidéo exclusives</li>
                            <li><i class="fas fa-check"></i> Analyses nutritionnelles détaillées</li>
                            <li><i class="fas fa-check"></i> Support prioritaire 24/7</li>
                            <li><i class="fas fa-check"></i> Accès anticipé aux nouvelles fonctionnalités</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2 class="faq-title">Questions fréquentes</h2>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <h3 class="faq-question">Puis-je changer de plan à tout moment ?</h3>
                    <p class="faq-answer">Oui, vous pouvez passer à un plan supérieur ou inférieur à tout moment. Les changements prennent effet immédiatement et nous ajustons votre facturation en conséquence.</p>
                </div>
                
                <div class="faq-item">
                    <h3 class="faq-question">Y a-t-il un essai gratuit ?</h3>
                    <p class="faq-answer">Oui ! Tous nos plans payants incluent un essai gratuit de 14 jours. Aucune carte de crédit n'est requise pour commencer.</p>
                </div>
                
                <div class="faq-item">
                    <h3 class="faq-question">Que se passe-t-il si j'annule mon abonnement ?</h3>
                    <p class="faq-answer">Vous conservez l'accès à toutes les fonctionnalités premium jusqu'à la fin de votre période de facturation, puis votre compte revient automatiquement au plan gratuit.</p>
                </div>
                
                <div class="faq-item">
                    <h3 class="faq-question">Les recettes sont-elles adaptées aux régimes spéciaux ?</h3>
                    <p class="faq-answer">Absolument ! CookBot prend en charge plus de 15 types de régimes alimentaires, incluant végétarien, végétalien, keto, paléo, sans gluten, et bien d'autres.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 CookBot. Tous droits réservés.</p>
        </div>
    </footer>

    <script>
        // Toggle entre facturation mensuelle et annuelle
        const billingToggle = document.getElementById('billingToggle');
        const monthlyLabel = document.getElementById('monthlyLabel');
        const yearlyLabel = document.getElementById('yearlyLabel');
        const monthlyPrices = document.querySelectorAll('.monthly-price');
        const yearlyPrices = document.querySelectorAll('.yearly-price');
        const monthlyPeriods = document.querySelectorAll('.monthly-period');
        const yearlyPeriods = document.querySelectorAll('.yearly-period');
        const yearlyOriginals = document.querySelectorAll('.yearly-original');

        let isYearly = false;

        billingToggle.addEventListener('click', function() {
            isYearly = !isYearly;
            
            if (isYearly) {
                billingToggle.classList.add('active');
                monthlyLabel.classList.remove('active');
                yearlyLabel.classList.add('active');
                
                monthlyPrices.forEach(price => price.style.display = 'none');
                yearlyPrices.forEach(price => price.style.display = 'inline');
                monthlyPeriods.forEach(period => period.style.display = 'none');
                yearlyPeriods.forEach(period => period.style.display = 'inline');
                yearlyOriginals.forEach(original => original.style.display = 'inline');
            } else {
                billingToggle.classList.remove('active');
                monthlyLabel.classList.add('active');
                yearlyLabel.classList.remove('active');
                
                monthlyPrices.forEach(price => price.style.display = 'inline');
                yearlyPrices.forEach(price => price.style.display = 'none');
                monthlyPeriods.forEach(period => period.style.display = 'inline');
                yearlyPeriods.forEach(period => period.style.display = 'none');
                yearlyOriginals.forEach(original => original.style.display = 'none');
            }
        });

        // Animation au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observer les cartes de pricing
        document.querySelectorAll('.pricing-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease-out';
            observer.observe(card);
        });

        // Gestion des boutons CTA
        document.querySelectorAll('.cta-button').forEach(button => {
            button.addEventListener('click', function() {
                const planName = this.closest('.pricing-card').querySelector('.plan-name').textContent;
                
                if (planName === 'Gratuit') {
                    window.location.href = 'inscription.php';
                } else {
                    // Rediriger vers la page d'inscription avec le plan sélectionné
                    window.location.href = `inscription.php?plan=${planName.toLowerCase()}`;
                }
            });
        });
    </script>
</body>
</html>
