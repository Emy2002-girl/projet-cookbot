<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - CookBot | Conseils, Recettes et Astuces Culinaires</title>
    <link rel="stylesheet" href="blog-styles.css">
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <!-- Logo -->
            <div class="logo">
                <a href="homepage.php">
                    <div class="logo-icon">
                        <img src="images/cutlery.png" alt="" width="35" height="35">
                    </div>
                </a>
                <a href="homepage.php" style="text-decoration:none">
                    <span class="logo-text">
                        <span style="color:#10B981;">Cook</span>Bot
                    </span>
                </a>
            </div>
            
            <!-- Navigation Menu -->
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        Fonctionnalités
                        <span class="dropdown-arrow"></span>
                    </a>
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
                    <a href="blog.html" class="nav-link active">Blog</a>
                </li>
                <li class="nav-item">
                    <a href="pricing.html" class="nav-link">Tarification</a>
                </li>
            </ul>
            
            <div class="header-right">
                <span style="border: 1px solid gainsboro; padding: 0.5rem 0.75rem; border-radius: 18px;">FR</span>
                <span style="border: 1px solid gainsboro; padding: 0.5rem 0.75rem; border-radius: 18px;">
                    <img src="images/sun.png" alt="" width="17" height="17">
                </span>
                <a href="login.php" class="login-link">Se connecter</a>
                <a href="inscription.php" class="btn-primary">S'inscrire</a>
            </div>
        </nav>
    </header>

    <!-- Blog Hero Section -->
    <section class="blog-hero">
        <div class="blog-hero-container">
            <div class="blog-hero-content">
                <h1>Blog CookBot</h1>
                <p>Découvrez nos conseils, astuces et recettes pour révolutionner votre cuisine</p>
                <div class="blog-search">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Rechercher un article..." class="search-input">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Categories -->
    <section class="blog-categories">
        <div class="container">
            <div class="categories-list">
                <button class="category-btn active" data-category="all">Tous</button>
                <button class="category-btn" data-category="recettes">Recettes</button>
                <button class="category-btn" data-category="nutrition">Nutrition</button>
                <button class="category-btn" data-category="astuces">Astuces</button>
                <button class="category-btn" data-category="ingredients">Ingrédients</button>
                <button class="category-btn" data-category="techniques">Techniques</button>
            </div>
        </div>
    </section>

    <!-- Main Blog Content -->
    <main class="blog-main">
        <div class="container">
            <div class="blog-layout">
                <!-- Articles Grid -->
                <div class="articles-grid">
                    <!-- Featured Article -->
                    <article class="article-card featured" data-category="recettes">
                        <div class="article-image">
                            <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&h=400&fit=crop" alt="Recettes d'automne">
                            <div class="article-badge">À la une</div>
                        </div>
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-category">Recettes</span>
                                <span class="article-date">15 Janvier 2025</span>
                            </div>
                            <h2 class="article-title">10 Recettes d'Automne Réconfortantes avec l'IA</h2>
                            <p class="article-excerpt">Découvrez comment utiliser CookBot pour créer des plats chaleureux et savoureux qui réchauffent le cœur pendant les mois froids.</p>
                            <div class="article-footer">
                                <div class="author-info">
                                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=40&h=40&fit=crop&crop=face" alt="Marie Dubois" class="author-avatar">
                                    <span class="author-name">Marie Dubois</span>
                                </div>
                                <span class="read-time">5 min de lecture</span>
                            </div>
                        </div>
                    </article>

                    <!-- Regular Articles -->
                    <article class="article-card" data-category="nutrition">
                        <div class="article-image">
                            <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=400&h=250&fit=crop" alt="Nutrition équilibrée">
                        </div>
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-category">Nutrition</span>
                                <span class="article-date">12 Janvier 2025</span>
                            </div>
                            <h3 class="article-title">Comment Équilibrer ses Macronutriments</h3>
                            <p class="article-excerpt">Guide complet pour comprendre et optimiser votre apport en protéines, glucides et lipides.</p>
                            <div class="article-footer">
                                <div class="author-info">
                                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" alt="Dr. Antoine Martin" class="author-avatar">
                                    <span class="author-name">Dr. Antoine Martin</span>
                                </div>
                                <span class="read-time">8 min</span>
                            </div>
                        </div>
                    </article>

                    <article class="article-card" data-category="astuces">
                        <div class="article-image">
                            <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=250&fit=crop" alt="Astuces cuisine">
                        </div>
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-category">Astuces</span>
                                <span class="article-date">10 Janvier 2025</span>
                            </div>
                            <h3 class="article-title">5 Astuces pour Optimiser votre Temps en Cuisine</h3>
                            <p class="article-excerpt">Des conseils pratiques pour cuisiner plus efficacement et gagner du temps au quotidien.</p>
                            <div class="article-footer">
                                <div class="author-info">
                                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=40&h=40&fit=crop&crop=face" alt="Sophie Laurent" class="author-avatar">
                                    <span class="author-name">Sophie Laurent</span>
                                </div>
                                <span class="read-time">6 min</span>
                            </div>
                        </div>
                    </article>

                    <article class="article-card" data-category="ingredients">
                        <div class="article-image">
                            <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=400&h=250&fit=crop" alt="Ingrédients frais">
                        </div>
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-category">Ingrédients</span>
                                <span class="article-date">8 Janvier 2025</span>
                            </div>
                            <h3 class="article-title">Guide des Légumes de Saison</h3>
                            <p class="article-excerpt">Apprenez à choisir et utiliser les meilleurs légumes selon les saisons pour des plats savoureux.</p>
                            <div class="article-footer">
                                <div class="author-info">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&h=40&fit=crop&crop=face" alt="Thomas Rousseau" class="author-avatar">
                                    <span class="author-name">Thomas Rousseau</span>
                                </div>
                                <span class="read-time">7 min</span>
                            </div>
                        </div>
                    </article>

                    <article class="article-card" data-category="techniques">
                        <div class="article-image">
                            <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=250&fit=crop" alt="Techniques culinaires">
                        </div>
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-category">Techniques</span>
                                <span class="article-date">5 Janvier 2025</span>
                            </div>
                            <h3 class="article-title">Maîtriser les Cuissons de Base</h3>
                            <p class="article-excerpt">Les techniques fondamentales pour réussir vos cuissons et sublimer vos ingrédients.</p>
                            <div class="article-footer">
                                <div class="author-info">
                                    <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=40&h=40&fit=crop&crop=face" alt="Camille Moreau" class="author-avatar">
                                    <span class="author-name">Camille Moreau</span>
                                </div>
                                <span class="read-time">10 min</span>
                            </div>
                        </div>
                    </article>

                    <article class="article-card" data-category="recettes">
                        <div class="article-image">
                            <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=250&fit=crop" alt="Pizza maison">
                        </div>
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-category">Recettes</span>
                                <span class="article-date">3 Janvier 2025</span>
                            </div>
                            <h3 class="article-title">Pizza Maison : La Recette Parfaite</h3>
                            <p class="article-excerpt">Découvrez tous les secrets pour réaliser une pizza authentique à la maison, de la pâte à la garniture.</p>
                            <div class="article-footer">
                                <div class="author-info">
                                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=40&h=40&fit=crop&crop=face" alt="Lucas Petit" class="author-avatar">
                                    <span class="author-name">Lucas Petit</span>
                                </div>
                                <span class="read-time">12 min</span>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar -->
                <aside class="blog-sidebar">
                    <!-- Newsletter -->
                    <div class="sidebar-widget newsletter-widget">
                        <h3>Newsletter</h3>
                        <p>Recevez nos derniers articles et conseils culinaires</p>
                        <form class="newsletter-form">
                            <input type="email" placeholder="Votre email" required>
                            <button type="submit">S'abonner</button>
                        </form>
                    </div>

                    <!-- Popular Articles -->
                    <div class="sidebar-widget popular-widget">
                        <h3>Articles Populaires</h3>
                        <div class="popular-articles">
                            <article class="popular-article">
                                <img src="https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=80&h=60&fit=crop" alt="Article populaire">
                                <div class="popular-content">
                                    <h4>Les Erreurs à Éviter en Cuisine</h4>
                                    <span class="popular-date">28 Déc 2024</span>
                                </div>
                            </article>
                            <article class="popular-article">
                                <img src="https://images.unsplash.com/photo-1551782450-a2132b4ba21d?w=80&h=60&fit=crop" alt="Article populaire">
                                <div class="popular-content">
                                    <h4>Batch Cooking : Préparer ses Repas</h4>
                                    <span class="popular-date">25 Déc 2024</span>
                                </div>
                            </article>
                            <article class="popular-article">
                                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=80&h=60&fit=crop" alt="Article populaire">
                                <div class="popular-content">
                                    <h4>Cuisiner avec des Enfants</h4>
                                    <span class="popular-date">22 Déc 2024</span>
                                </div>
                            </article>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="sidebar-widget tags-widget">
                        <h3>Tags Populaires</h3>
                        <div class="tags-cloud">
                            <span class="tag">IA Culinaire</span>
                            <span class="tag">Recettes Faciles</span>
                            <span class="tag">Nutrition</span>
                            <span class="tag">Végétarien</span>
                            <span class="tag">Batch Cooking</span>
                            <span class="tag">Desserts</span>
                            <span class="tag">Cuisine Française</span>
                            <span class="tag">Healthy</span>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <!-- Pagination -->
    <section class="pagination-section">
        <div class="container">
            <div class="pagination">
                <button class="pagination-btn" disabled>
                    <i class="fas fa-chevron-left"></i>
                    Précédent
                </button>
                <div class="pagination-numbers">
                    <button class="pagination-number active">1</button>
                    <button class="pagination-number">2</button>
                    <button class="pagination-number">3</button>
                    <span class="pagination-dots">...</span>
                    <button class="pagination-number">10</button>
                </div>
                <button class="pagination-btn">
                    Suivant
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="blog-cta">
        <div class="container">
            <div class="cta-content">
                <h2>Prêt à révolutionner votre cuisine ?</h2>
                <p>Rejoignez des milliers d'utilisateurs qui cuisinent déjà avec l'intelligence artificielle</p>
                <a href="inscription.php" class="cta-button">
                    Commencer gratuitement
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background-color: #F3F4F6;">
        <div class="footer2">
            <div class="conta">
                <div class="logo">
                    <div class="cook">
                        <img src="images/cutlery.png" alt="" width="35" height="35">
                        <span class="logo-text" style="position: absolute;">
                            <span style="color:#10B981;">Cook</span>Bot
                        </span>
                    </div>
                </div>
                <div class="descrip3">Découvrez la cuisine intelligente</div>
            </div>
            <div class="conta propos">
                <h2>À propos</h2>
                <a href="blog.html">Blog</a><br>
                <a href="#">Contact</a><br>
            </div>
            <div class="conta produit">
                <h2>Produit</h2>
                <a href="pricing.html">Tarification</a><br>
                <a href="#">FAQ</a><br>
            </div>
            <div class="conta suiver">
                <h2>Suivez-nous</h2>
                <img src="images/Frame.png" alt="">
                <img src="images/Frame (1).png" alt="">
                <img src="images/Frame (2).png" alt="">
            </div>
            <div class="conta legal">
                <h2>Légal</h2>
                <a href="#">Conditions</a><br>
                <a href="#">Confidentialité</a><br>
            </div>
        </div>
        <div style="width: 100%; height: 0.5px; background: black; margin: 30px 0;"></div>
        <div class="copyright">© 2025 CookBot. Tous droits réservés.</div>
    </footer>

    <script src="blog-script.js"></script>
</body>
</html>
