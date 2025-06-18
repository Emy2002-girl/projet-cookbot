<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarification - CookBot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color:black;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: black;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
        }

        .logo img {
            width: 35px;
            height: 35px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: black;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .nav-links a:hover {
            opacity: 0.8;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-outline {
            padding: 0.5rem 1rem;
            border: 2px solid white;
            color: black;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            background: white;
            color: #667eea;
        }

        .btn-primary {
            padding: 0.5rem 1rem;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 4rem 0;
            color: black;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .billing-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .toggle-switch {
            position: relative;
            width: 60px;
            height: 30px;
            background: rgba(255,255,255,0.3);
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .toggle-switch.active {
            background: #10B981;
        }

        .toggle-slider {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .toggle-switch.active .toggle-slider {
            transform: translateX(30px);
        }

        .billing-label {
            font-weight: 600;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .billing-label.active {
            opacity: 1;
        }

        .discount-badge {
            background: #10B981;
            color: black;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        /* Pricing Cards */
        .pricing-section {
            padding: 2rem 0 4rem;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .pricing-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .pricing-card.popular {
            border: 3px solid #10B981;
            transform: scale(1.05);
        }

        .pricing-card.popular::before {
            content: "Le plus populaire";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            background: #10B981;
            color: black;
            text-align: center;
            padding: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .pricing-card.popular .card-content {
            margin-top: 1rem;
        }

        .plan-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .plan-description {
            color: #666;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .price {
            display: flex;
            align-items: baseline;
            margin-bottom: 2rem;
        }

        .price-amount {
            font-size: 3rem;
            font-weight: 800;
            color: #333;
        }

        .price-currency {
            font-size: 1.2rem;
            font-weight: 600;
            color: #666;
            margin-right: 0.25rem;
        }

        .price-period {
            font-size: 1rem;
            color: #666;
            margin-left: 0.25rem;
        }

        .price-original {
            text-decoration: line-through;
            color: #999;
            font-size: 1.2rem;
            margin-left: 1rem;
        }

        .cta-button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: black;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 2rem;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .pricing-card.popular .cta-button {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }

        .pricing-card.popular .cta-button:hover {
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
        }

        .features-list {
            list-style: none;
        }

        .features-list li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .features-list li i {
            color: #10B981;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .features-list li.unavailable {
            opacity: 0.5;
        }

        .features-list li.unavailable i {
            color: #ccc;
        }

        /* FAQ Section */
        .faq-section {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 4rem 0;
            margin-top: 2rem;
        }

        .faq-title {
            text-align: center;
            color: black;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
        }

        .faq-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
        }

        .faq-item {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .faq-question {
            color: black;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .faq-answer {
            color: rgba(255,255,255,0.9);
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            background: rgba(0,0,0,0.2);
            color: black;
            text-align: center;
            padding: 2rem 0;
            margin-top: 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .pricing-grid {
                grid-template-columns: 1fr;
            }

            .pricing-card.popular {
                transform: none;
            }

            .nav-links {
                display: none;
            }

            .faq-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pricing-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .pricing-card:nth-child(1) { animation-delay: 0.1s; }
        .pricing-card:nth-child(2) { animation-delay: 0.2s; }
        .pricing-card:nth-child(3) { animation-delay: 0.3s; }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav container">
            <a href="#" class="logo">
                <img src="images/cutlery.png" alt="CookBot">
                <span><span style="color: #10B981;">Cook</span>Bot</span>
            </a>
            
            <ul class="nav-links">
                <li><a href="#fonctionnalites">Fonctionnalités</a></li>
                <li><a href="#tarification">Tarification</a></li>
                <li><a href="#blog">Blog</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            
            <div class="nav-buttons">
                <a href="login.php" class="btn-outline">Se connecter</a>
                <a href="inscription.php" class="btn-primary">S'inscrire</a>
            </div>
        </nav>
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
            <p>&copy; 2024 CookBot. Tous droits réservés. Fait avec ❤️ pour les passionnés de cuisine.</p>
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
