<?php
session_start();
$user_logged_in = isset($_SESSION['user_id']);
$user_email = $user_logged_in ? ($_SESSION['user_email'] ?? '') : '';
$user_name = $user_logged_in ? ($_SESSION['user_name'] ?? '') : '';
$user_prenom = $user_logged_in ? ($_SESSION['user_prenom'] ?? '') : '';
$initial = $user_logged_in ? strtoupper(substr($user_prenom, 0, 1)) : '';
 ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CookBot MasterChef - Votre assistant culinaire IA qui crée des recettes personnalisées">
    <title>CookBot MasterChef - IA Culinaire</title>
    <link rel="stylesheet" href="masterchef.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                <a href="homepage.php">
                    <div class="logo-icon">
                        <img src="images/cutlery.png" alt="Logo CookBot" width="35" height="35">
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
                    <a href="#" class="nav-link">Tarification</a>
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">Blog</a>
                </li>
            </ul>
            
            <div class="header-right">
                <span class="header-btn">FR</span>
                <span class="head-btn" style=" border: 1px solid gainsboro;padding: 0.7rem 0.75rem;border-radius: 18px;display: flex;align-items: center;justify-content: center;min-width: 40px;">
                    <img src="images/sun.png" alt="Thème clair" width="17" height="17">
                </span>
                
                <?php if ($user_logged_in): ?>
                <!-- Menu utilisateur -->
                <div class="user-dropdown">
                    <div class="user-button" id="userButton">
                        <div class="user-avatar"><?php echo $initial; ?></div>
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

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-grid">
                    <!-- Left Content -->
                    <div class="hero-left">
                        <div class="hero-content">
                            <div class="badge">🍳 MasterChef IA</div>
                            <h1 class="hero-title">
                                Libérez votre
                                <br>
                                <span class="gradient-text">Créativité Culinaire</span>
                                <br>
                                <span class="accent-text">Au-delà de l'Imagination !</span>
                            </h1>
                            <p class="hero-description">
                                MasterChef est votre compagnon culinaire intelligent. Décrivez vos envies, vos ingrédients et vos contraintes, et laissez notre IA créer des recettes personnalisées parfaitement adaptées à vos besoins !
                            </p>
                            <button class="btn btn-primary" id="startBtn">Commencer à cuisiner</button>
                        </div>
                        <!-- Progress Steps -->
                        <div class="progress-steps" id="progressSteps">
                            <!-- Steps will be generated by JavaScript -->
                        </div>
                    </div>

                    <!-- Right Content - Phone Mockup -->
                    <div class="hero-right">
                        <!-- Decorative Elements -->
                        <div class="decoration decoration-1">🍕</div>
                        <div class="decoration decoration-2">🥕</div>
                        <div class="decoration decoration-3">🍓</div>
                        <img src="images/casserole-pot.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Formulaire de génération de recettes -->
        <form id="recipe-generator-form">
            <div class="contain">
                <div class="etapes">
                    <div class="case">1</div>
                    <h2 style="padding-top: 8px;">Inspirez MasterChef avec vos goûts ou une recette spécifique.</h2>
                    <p style="padding-top: 8px;">Dites à MasterChef ce que vous avez envie de manger et regardez la magie opérer. Vous avez envie d'une Lasagne à la sauce d'agneau ? Ou d'une soupe Kimchi ?</p>
                    <p style="padding-top: 18px;">MasterChef élaborera la recette parfaite qui correspond le mieux à votre description.</p>
                </div>
                <div class="recipe-selector">
                    <div id="selected-recipe-display" class="generate-recipe">
                        <input type="text" 
                               name="recipe" 
                               id="generate-recipe" 
                               placeholder="Ex: Pâtes carbonara,Tarte aux pommes..."
                               required>
                    </div>
                </div>
            </div>
            <!-- Section 2: Nombre de portions -->
            <div class="contain">
                <div class="etapes">
                    <div class="case">2</div>
                    <h2 style="padding-top: 8px;">Sélectionnez le nombre de portions.</h2>
                    <p style="padding-top: 8px;">Cuisinez-vous pour vous-même ou pour de nombreux invités ?</p>
                    <p style="padding-top: 18px;">MasterChef générera une recette avec la bonne quantité d'ingrédients pour obtenir le nombre de portions souhaité.</p>
                </div>
                <div class="nombre-portions">
                    <input type="number" 
                           name="portion" 
                           id="portion" 
                           min="1" 
                           max="20" 
                           value="2"
                           placeholder="Nombre de portions"
                           required>
                </div>
            </div>
            <!-- Section 3: Durée -->
            <div class="contain">
                <div class="etapes">
                    <div class="case">3</div>
                    <h2 style="padding-top: 8px;">Sélectionnez le temps dont vous disposez.</h2>
                    <p style="padding-top: 8px;">Choisissez 5 minutes si vous êtes pressé ou plus si vous avez le temps.</p>
                    <p style="padding-top:18px;line-height:27px">Cela empêchera MasterChef de vous recommander des recettes qui prennent trop de temps à préparer.</p>
                </div>
                <div class="duree">
                    <select name="duree" id="duree-select" style="width:100%" class="select1" required>
                        <option value="">-- Sélectionnez une durée --</option>
                        <option value="15">⚡ Moins de 15 minutes</option>
                        <option value="30" selected>🕐 15 à 30 minutes</option>
                        <option value="45">🕑 30 à 45 minutes</option>
                        <option value="60">🕒 45 minutes à 1 heure</option>
                        <option value="90">🕓 1 à 1h30</option>
                        <option value="120">🕔 Plus de 1h30</option>
                    </select>
                </div>
            </div>

            <!-- Section 4: Niveau -->
            <div class="contain">
                <div class="etapes">
                    <div class="case">4</div>
                    <h2 style="padding-top: 8px;">Sélectionnez votre niveau de compétence.</h2>
                    <p style="padding-top: 8px;">Peu importe que vous soyez débutant ou chef étoilé Michelin.</p>
                    <p style="padding-top:18px">MasterChef vous recommandera des recettes adaptées à votre niveau de compétence.</p>
                </div>
                <div class="niveau-cuisine">
                    <select name="niveau_cuisine" id="niveau-cuisine-select" class="select1" required>
                        <option value="">-- Sélectionnez votre niveau --</option>
                        <option value="debutant" selected>🌱 Débutant</option>
                        <option value="intermediaire">👨‍🍳 Intermédiaire</option>
                        <option value="avance">⭐ Expert</option>
                    </select>
                </div>
            </div>

            <!-- Section 5: Restrictions alimentaires -->
            <div class="contain">
                <div class="etapes">
                    <div class="case">5</div>
                    <h2 style="padding-top: 8px;">Sélectionnez vos besoins alimentaires.</h2>
                    <p style="padding-top: 8px;">Vous pouvez choisir parmi Végétarien, Pescétarien, Végétalien, Sans Gluten, Sans Produits Laitiers, Régime Cétogène et Paléo.</p>
                    <p style="padding-top: 18px;">MasterChef vous recommandera ensuite une recette adaptée au régime que vous avez sélectionné.</p>
                </div>
                <div class="besoin-alimentaires">
                    <select name="besoin-alimentaires" id="besoin-alimentaires-select" class="select1">
                        <option value="standard" selected>🍽️ Aucune restriction</option>
                        <option value="besoin1">🥬 Végétarien</option>
                        <option value="besoin2">🐟 Pescétarien</option>
                        <option value="besoin3">🌱 Végétalien</option>
                        <option value="besoin4">🥛 Sans produits laitiers</option>
                        <option value="besoin5">🌾 Sans gluten</option>
                        <option value="besoin6">🥑 Cétogène</option>
                        <option value="besoin7">🦴 Paléo</option>
                    </select>
                </div>
            </div>

            <!-- Section 6: Génération -->
            <div class="contain">
                <div class="generer">
                    <div class="case">6</div>
                    <h2 style="padding-top: 8px;">Générez votre recette.</h2>
                    <p style="padding-top: 8px;">Appuyez sur le bouton Générer et attendez que la magie opère.</p>
                    <p style="padding-top: 18px;line-height:27px">En un clic, vous pouvez enregistrer votre recette dans le livre de cuisine ou l'ajouter à la liste de courses. Et si vous souhaitez commander les ingrédients en ligne, vous pouvez les ajouter à votre panier AmazonFresh ou InstaCart !</p>
                </div>
                <div class="generate-section">
                    <button id="generate-recipes" class="generate-btn">
                        🧑‍🍳 Générer votre recette
                    </button>
                    <div class="form-summary" id="form-summary" style="display: none;">
                        <!-- Résumé des critères sélectionnés -->
                    </div>
                </div> 
            </div>
        </form>
    </main>

    <footer>
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
    </footer>

    <!-- Modal pour afficher les recettes (en dehors du flux normal) -->
    <div id="recipe-modal" class="recipe-modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="modal-recipe-content"></div>
        </div>
    </div>
    <script src="masterchef.js"></script>
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

