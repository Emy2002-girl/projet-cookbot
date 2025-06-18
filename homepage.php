<?php
session_start();
$user_logged_in = isset($_SESSION['user_id']);
$user_email = $user_logged_in ? ($_SESSION['user_email'] ?? '') : '';
$user_name = $user_logged_in ? ($_SESSION['user_name'] ?? '') : '';
$user_prenom = $user_logged_in ? ($_SESSION['user_prenom'] ?? '') : '';
$initial = '';
if ($user_logged_in && !empty($user_prenom)) {
    $initial = strtoupper(substr(trim($user_prenom), 0, 1));
} elseif ($user_logged_in && !empty($user_name)) {
    $initial = strtoupper(substr(trim($user_name), 0, 1));
} elseif ($user_logged_in) {
    $initial = strtoupper(substr($user_email, 0, 1));
}
if (empty($initial)) {
    $initial = '?';
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookBot - Mangez bon, mangez sain, vivez mieux</title>
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=task_alt" />
    <style>
        /* Style pour le menu utilisateur */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .user-button {
            display: flex;
            align-items: center;
            gap: 8px;
            border-radius: 24px;
            padding: 6px 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-button:hover {
            background-color: #e5e7eb;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 40%;
            background-color: #10b981;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        .user-menu {
            position: absolute;
            right: 0;
            top: 45px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            width: 220px;
            z-index: 100;
            overflow: hidden;
            display: none;
        }

        .user-menu.active {
            display: block;
        }

        .user-menu-header {
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        .user-menu-name {
            font-weight: 500;
            margin-bottom: 4px;
        }

        .user-menu-email {
            font-size: 14px;
            color: #6b7280;
        }

        .user-menu-items {
            padding: 8px 0;
        }

        .user-menu-item {
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #374151;
            transition: background-color 0.2s;
        }

        .user-menu-item:hover {
            background-color: #f9fafb;
        }

        .user-menu-item.logout {
            color: #dc2626;
        }

        .user-menu-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
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
                
                <?php if ($user_logged_in): ?>
                <!-- Menu utilisateur -->
                <div class="user-dropdown">
                <div class="user-button" id="userButton">
                <div class="user-avatar" title="<?php echo htmlspecialchars($user_prenom . ' ' . $user_name); ?>">
                            <?php echo htmlspecialchars($initial); ?>
                        </div>
                  </div>
                    <div class="user-menu" id="userMenu">
                        <div class="user-menu-header">
                            <div class="user-menu-name"><?php echo htmlspecialchars($user_prenom . ' ' . $user_name); ?></div>
                            <div class="user-menu-email"><?php echo htmlspecialchars($user_email); ?></div>
                        </div>
                        <div class="user-menu-items">
                            <a href="parametres.php" class="user-menu-item">
                                <div class="user-menu-icon">⚙️</div>
                                <span>Paramètres</span>
                            </a>
                            <a href="cuisine.php" class="user-menu-item">
                                <div class="user-menu-icon">🍳</div>
                                <span>Cuisine</span>
                            </a>
                            <a href="logout.php" class="user-menu-item logout">
                                <div class="user-menu-icon">🚪</div>
                                <span>Se déconnecter</span>
                            </a>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <a href="login.php" class="login-link">Se connecter</a>
                <a href="inscription.php" class="btn-primary">S'inscrire</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Mangez bon,<br>mangez sain,<br>vivez mieux.</h1>
            <div class="hero-subtitle">Finis les repas répétitifs !</div>
            <p class="hero-description">
                Avec nos recettes personnalisées par IA, vos plannings de repas simplifiés et nos
            </p>
            <p class="hero-description">
                idées toujours nouvelles, vous ne comptez plus les soirs où tout le monde se régale.
            </p>
            <p class="hero-stats">Plus d'1 million de dîners déjà sauvés.</p>
            <a href="#" class="btn-hero">
                Commencez gratuitement
                <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12,5 19,12 12,19"></polyline>
                </svg>
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="features-container">
            <div class="feature-icon"><img src="images/features.png" alt=""></div>
            <p class="feature-label">FONCTIONNALITÉS</p>
            <h2>Plus que des recettes</h2>
            <p class="features-description">CookBot, c'est simplement votre nouveau chef personnel.</p>
        </div>
    </section>

    <!-- PantryChef Section -->
    <section class="pantry-chef">
        <div class="pantry-chef-container">
            <div class="pantry-chef-content">
                <div class="pantry-chef-icon"><img src="images/Overlay.png" alt="" width="56" height="56"></div>
                <h3>PantryChef</h3>
                <p>
                    Découvrez le pouvoir de cuisiner avec ce que vous avez déjà avec PantryChef ! Il suffit de saisir
                    les
                    ingrédients de votre garde-manger et laissez notre application générer une délicieuse recette pour
                    vous.
                </p>
                <p>
                    Dites adieu aux aliments gaspillés et à l'argent dépensé inutilement. Commencez à cuisiner plus
                    intelligemment avec PantryChef dès aujourd'hui !
                </p>
                <a href="#" class="btn-primary">Découvrir PantryChef</a>
            </div>
            <div class="pantry-chef-image">
                <img src="images/8ff69d3694943e573ba0caa41b526166.jpg" alt="Fresh groceries and vegetables in shopping bag">
            </div>
        </div>
    </section>
    <section class="master-chef">
        <div class="master-chef-container">
            <div class="master-chef-image">
                <img src="images/istockphoto-1347785460-612x612.jpg" alt="Fresh groceries and vegetables in shopping bag" width="95%" height="25rem">
            </div>
            <div class="master-chef-content">
                <div class="master-chef-icon">
                    <img src="images/Overlay (1).png" alt="" width="56" height="56">
                </div>
                <h3>MasterChef</h3>
                <p>
                    Masterchef est le compagnon de cuisine ultime pour toute personne cherchant à améliorer ses
                    compétences culinaires.
                </p>
                <p>
                    Dites adieu aux recherches interminables pour trouver la recette parfaite. Masterchef vous aide à
                    trouver la recette spécifique que vous cherchez, à la modifier en fonction de vos besoins
                    diététiques ou à générer une nouvelle recette en fonction de vos envies. Avec Masterchef, vous
                    aurez accès à des recettes personnalisées, saines et délicieuses qui conviennent à votre style de vie.
                </p>
                <p>Cuisinez plus intelligemment, pas plus difficilement avec Masterchef.</p>
                <a href="#" class="btn-primary">Découvrir MasterChef</a>
            </div>
        </div>
    </section>
    <section class="pantry-chef">
        <div class="pantry-chef-container">
            <div class="pantry-chef-content">
                <div class="pantry-chef-icon"><img src="images/Overlay (3).png" alt="" width="56" height="56"></div>
                <h3>MacrosChef</h3>
                <p>
                    MacrosChef est l'outil parfait pour toute personne cherchant à atteindre ses objectifs en matière de
                    macronutriments tout en satisfaisant ses papilles gustatives.
                </p>
                <p>
                    Générez des recettes personnalisées en fonction de vos objectifs spécifiques en matière de
                    macronutriments et de vos restrictions alimentaires.
                </p>
                <p>Dites adieu à la conjecture de la préparation des repas et profitez de repas parfaitement équilibrés
                et nutritifs chaque jour avec MacrosChef.</p>
                <a href="#" class="btn-primary">Découvrir MacrosChef</a>
            </div>
            <div class="pantry-chef-image">
                <img src="images/3d6f6b248c90312b6adf0889a9415aa4.jpg" alt="Fresh groceries and vegetables in shopping bag">
            </div>
        </div>
    </section>
    <section class="master-chef">
        <div class="master-chef-container">
            <div class="master-chef-image">
                <img src="images/cae8e8eee4198a78549836a37c20d3be.jpg" alt="Fresh groceries and vegetables in shopping bag">
            </div>
            <div class="master-chef-content">
                <div class="master-chef-icon">
                    <img src="images/Overlay (2).png" alt="" width="56" height="56">
                </div>
                <h3>MealPlanChef</h3>
                <p>
                    MealPlanChef est la solution ultime de planification de repas pour toute personne cherchant à
                    atteindre ses objectifs de remise en forme tout en appréciant des repas délicieux et nutritifs.
                </p>
                <p>
                    Avec ses fonctionnalités de personnalisation, vous pouvez créer un plan de repas qui correspond à
                    vos objectifs de remise en forme et à vos exigences alimentaires spécifiques, que ce soit pour une
                    journée, une semaine ou même un mois.
                </p>
                <p>Dites adieu aux tracas de la planification des repas et profitez de repas parfaitement équilibrés qui
                soutiennent vos objectifs de santé et de bien-être. Commencez dès aujourd'hui avec MealPlanChef!</p>
                <a href="#" class="btn-primary">Découvrir MealPlanChef</a>
            </div>
        </div>
    </section>
    <section class="features">
        <div class="features-container">
            <div class="feature-icon"><img src="images/testimonials.png" alt=""></div>
            <p class="feature-label">TÉMOIGNAGES</p>
            <h2>Ce que les gens disent de CookBot</h2>
            <p class="features-description">Écoutez directement nos clients.</p>
        </div>
    </section>
    <section class="testimonials">
        <div class="container">
            <!-- Testimonials Grid -->
            <div class="testimonials-grid">
                <!-- Testimonial 1 -->
                <div class="testimonial-card">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "Recettes délicieuses et faciles à suivre ! Ma famille adore mes nouveaux plats."
                    </p>
                    <div class="author">
                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=60&h=60&fit=crop&crop=face"
                            alt="Marie L." class="author-avatar">
                        <div class="author-info">
                            <h4 class="author-name">Marie L.</h4>
                            <span class="author-location">Paris</span>
                        </div>
                    </div>
                    <div class="recipe-tag">Coq au Vin</div>
                </div>
    
                <!-- Testimonial 2 -->
                <div class="testimonial-card">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "Parfait pour débuter en cuisine ! Les explications sont très claires."
                    </p>
                    <div class="author">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&h=60&fit=crop&crop=face"
                            alt="Thomas M." class="author-avatar">
                        <div class="author-info">
                            <h4 class="author-name">Thomas M.</h4>
                            <span class="author-location">Lyon</span>
                        </div>
                    </div>
                    <div class="recipe-tag">Ratatouille</div>
                </div>
    
                <!-- Testimonial 3 -->
                <div class="testimonial-card">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "Site magnifique avec des recettes authentiques. Mes invités sont toujours impressionnés !"
                    </p>
                    <div class="author">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=60&h=60&fit=crop&crop=face"
                            alt="Sophie D." class="author-avatar">
                        <div class="author-info">
                            <h4 class="author-name">Sophie D.</h4>
                            <span class="author-location">Marseille</span>
                        </div>
                    </div>
                    <div class="recipe-tag">Bouillabaisse</div>
                </div>
    
                <!-- Testimonial 4 -->
                <div class="testimonial-card">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "Les desserts sont incroyables ! J'ai enfin réussi ma tarte tatin."
                    </p>
                    <div class="author">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=60&h=60&fit=crop&crop=face"
                            alt="Antoine R." class="author-avatar">
                        <div class="author-info">
                            <h4 class="author-name">Antoine R.</h4>
                            <span class="author-location">Tours</span>
                        </div>
                    </div>
                    <div class="recipe-tag">Tarte Tatin</div>
                </div>
    
                <!-- Testimonial 5 -->
                <div class="testimonial-card">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "Mes enfants adorent cuisiner avec moi grâce à vos recettes simples et amusantes."
                    </p>
                    <div class="author">
                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=60&h=60&fit=crop&crop=face"
                            alt="Camille B." class="author-avatar">
                        <div class="author-info">
                            <h4 class="author-name">Camille B.</h4>
                            <span class="author-location">Nice</span>
                        </div>
                    </div>
                    <div class="recipe-tag">Crêpes</div>
                </div>
    
                <!-- Testimonial 6 -->
                <div class="testimonial-card">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "Excellent pour apprendre la cuisine française traditionnelle. Très bien expliqué !"
                    </p>
                    <div class="author">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=60&h=60&fit=crop&crop=face"
                            alt="Lucas P." class="author-avatar">
                        <div class="author-info">
                            <h4 class="author-name">Lucas P.</h4>
                            <span class="author-location">Bordeaux</span>
                        </div>
                    </div>
                    <div class="recipe-tag">Cassoulet</div>
                </div>
            </div>
    
            <!-- Call to Action -->
            <div class="cta-section">
                <h3 class="cta-title">Vous aussi, partagez votre expérience !</h3>
                <p class="cta-text">Rejoignez notre communauté et laissez votre avis</p>
                <button class="cta-button" id="ctaButton">
                    <i class="fas fa-comment-dots"></i>
                    Laisser un témoignage
                </button>
            </div>
        </div>
    </section>
    <section class="lafamille">
        <div class="cont">
        <div class="container1">
            <div class="descript">
        <h2 class="tite">Pour Toute la famille</h2>
        <p class="decr">
            CookBote est la solution culinaire idéale pour toute la famille, grâce
            à son approche pratique, savoureuse et équilibrée, permettant de
            préparer facilement des repas délicieux et sains qui répondent à des
            goûts divers et garantissent à tous une expérience culinaire délicieuse.
        </p>
    </div>
        <div class="container2">
            <div class="desc2">
            <img src="images/CookUp pour enfants.png" alt="" srcset="">
            <h2>Enfants</h2>
            <p>Recettes spéciales enfant, ce
            qu’il aime avec plein de nutrition.</p>
        </div>
        <div class="desc2">
            <img src="images/CookUp pour adultes.png" alt="" srcset="">
            <h2>Adultes</h2>
            <p>Calculez votre besoin énergétique
            journalier sans plus attendre.</p>
        </div>
    </div>
    <div class="abonne">S'abonner</div>
</div>
<div class="container1">
    <img src="images/une femme et sa fille dans la cuisine.png" alt="" srcset="" width="320px" height="auto" class="famille">
</div>
</div>
    </section>
    <section class="features">
        <div class="features-container">
            <div class="feature-icon"><img src="images/credit_card.png" alt=""></div>
            <p class="feature-label">TARIFICATION</p>
            <h2>Des fonctionnalités incroyables à petit prix</h2>
            <p class="features-description">Savourez une cuisine délicieuse avec ChefGPT et faites partie de la communauté de plus de
            6000000 amateurs de nourriture satisfaits !</p>
            <div class="tariff">
                <button style="background-color: #10b981;
                border: none;
                padding: 15px;
                font-weight: 300;
                color: white;
                font-size: 20px;
                border-radius: 6px 0px 0px 6px;">Mensuel</button>
                <button style="background-color: #F5F5F5;
                border: none;
                padding: 15px;
                color: black;
                margin: -7px;
                font-size: 20px;
                font-weight: 300;
                border-radius: 0px 6px 6px 0px;">Annuel</button>
            </div>
    </section>
    <section class="tarifi">
        <div class="abonnement">
        <div class="free contt">
            <h2>Basic <span style="color: #757575;
                font-weight: 400;
                font-size: 12px;">GRATUIT POUR TOUJOURS</span></h2>
            <p style="color:#9CA3AF">Aucune carte de crédit requise</p>
            <p style="
            font-size: 36px;
            font-weight: bold;
        ">0 MAD<span style="
            font-weight: 100;
        ">/mensuel</span></p>
            <div class="flex"><span class="material-symbols-outlined">task_alt</span>10 générations mensuelles.</div>
            <div class="flex"><span class="material-symbols-outlined">task_alt</span>Plans de repas jusqu'à 3 jours</div>
            <div class="flex"><span class="material-symbols-outlined">task_alt</span>Sauvegarder 5 recettes dans le livre de cuisine</div>
             <div class="flex"><span class="material-symbols-outlined">task_alt</span>Sauvegarder 5 recettes dans la liste de courses</div>
        </p>
        <button class="btn-abon">Commencer</button>
        </div>
        <div class="pro contt">
        <h2>Pro</h2>
        <p>Pour ceux qui ont besoin d'un chef personnel numérique</p>
        <p style="
                font-size: 36px;
                font-weight: bold;
            ">50 MAD<span style="
                font-weight: 100;
            ">/mensuel</span></p>
        <div class="flex"><span class="material-symbols-outlined">task_alt</span>Générations illimitées.</div>
        <div class="flex"><span class="material-symbols-outlined">task_alt</span>Mode historique</div>
        <div class="flex"><span class="material-symbols-outlined">task_alt</span>Plans de repas jusqu'à 30 jours</div>
        <div class="flex"><span class="material-symbols-outlined">task_alt</span>Suivi quotidien du plan de repas</div>
        <div class="flex"><span class="material-symbols-outlined">task_alt</span>Livre de cuisine et listes de courses illimités</div>
        <div class="btn-pro">Commencer</div>
    </div>
    </div>
    </section>
    <section class="footer">
        <h2 class="tit">Le compagnon parfait pour votre cuisine.</h2>
        <h2 class="titt">Inscrivez-vous gratuitement dès aujourd'hui.</h2>
        <button class="commencerr">Commencer gratuitement</button>
    </section>
    <section style="background-color: #F3F4F6;">
        <div class="footer2">
        <div class="conta">
            <div class="logo">
                <div class="cook"><img src="images/cutlery.png" alt="" width="35" height="35" >
                <span class="logo-text" style="position: absolute;"><span style="color:#10B981;">Cook</span>Bot</span></div>
            </div>
            <div class="descrip3">Découvrez la cuisine intelligente</div>
        </div>
          <div class=" conta propos">
            <h2>à propos</h2>
            <a href="#">Blog</a><br>
            <a href="#">Contacte</a><br>
          </div>
          <div class=" conta produit">
            <h2>Produit</h2>
            <a href="#">Tarification</a><br>
            <a href="#">FAQ</a><br>
          </div>
          <div class=" conta suiver">
            <h2>Suiver-nous</h2>
            <img src="images/Frame.png" alt="">
            <img src="images/Frame (1).png" alt="">
            <img src="images/Frame (2).png" alt="">
          </div>
          <div class="conta legal">
            <h2>Legal</h2>
            <a href="condition">Conditions</a><br>
            <a href="conf">Confidentalité</a><br>
          </div>
    </div>
    <div style=" width: 100%;height: 0.5px; background:black; margin: 30px 0;"></div>
    <div class="copyright">© 2025 CookBot. Tous droits réservés.</div>
    </section>
    <script src="script.js"></script>
    <script>
        // Script pour le menu utilisateur
        document.addEventListener('DOMContentLoaded', function() {
            const userButton = document.getElementById('userButton');
            const userMenu = document.getElementById('userMenu');
            
            if (userButton) {
                userButton.addEventListener('click', function() {
                    userMenu.classList.toggle('active');
                });
                
                // Fermer le menu si on clique ailleurs
                document.addEventListener('click', function(event) {
                    if (!userButton.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.remove('active');
                    }
                });
            }
        });
    </script>
</body>

</html>
