<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookBot - MealPlanChef</title>
    <link rel="stylesheet" href="mealplanchef.css">
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
                        <a href="#" class="dropdown-item">
                            <div class="dropdown-icon icon-pantry">🥘</div>
                            <span class="dropdown-text">PantryChef</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <div class="dropdown-icon icon-master">👨‍🍳</div>
                            <span class="dropdown-text">MasterChef</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <div class="dropdown-icon icon-macros">🍌</div>
                            <span class="dropdown-text">MacrosChef</span>
                        </a>
                        
                        <a href="#" class="dropdown-item">
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
                <span style="border: 1px solid gainsboro; padding: 0.5rem 0.75rem; border-radius: 18px;">FR</span>
                <span style="border: 1px solid gainsboro; padding: 0.5rem 0.75rem; border-radius: 18px;"><img src="images/sun.png" alt="" width="17" height="17" margin-top="5px"></span>
                <a href="login.php" class="login-link" >Se connecter</a>
                <a href="inscription.php" class="btn-primary">S'inscrire</a>
            </div>
        </nav>
    </header>
    <section class="hero">
        <div class="container">
            <div class="description">
                <div class="flex">
                <h2>📅MealPlanChef</h2></div>
                <p class="desc">
                Un Nutritionniste Comme<span style="color:#059669">Vous n'avez Jamais Vu !</span>
                </p>
                <p class="desc1">
                Végétalien ? Paléo ? Keto ? Vous essayez de développer vos muscles ? Vous essayez de manger plus sainement ? Aucun problème. Laissez MealPlanChef faire le travail difficile. MealPlanChef génère des plans alimentaires pour votre semaine pour vous aider à atteindre votre objectif ou votre régime alimentaire.
                </p>
            </div>
            <div class="image"><img src="images/mealplan.avif" alt="" srcset="" width="100%" height="auto" style="border-radius:20%;box-shadow: #BDBDBD 10px 7px 0px"></div>
        </div>
    </section>
    <main>
        <!-- Section 1: Ingrédients avec interface badges -->
        <div class="contain">
            <div class="etapes">
                <div class="case">1</div>
                <h2 style="padding-top: 8px;">Rendez votre Plan Alimentaire personnel.</h2>
                <p style="padding-top: 8px;">MealPlanChef n'est pas une solution générique.</p>
                <p style="padding-top: 18px;">Chaque Plan Alimentaire est personnalisé en fonction de votre Sexe, de votre Âge et de vos Mesures Corporelles.</p>
            </div>
            <div class="ingredients-selector">
                <label for="genre-select">Quel est votre genre :</label><br>
                <select name="genre" id="genre-select" style="margin-top:0px;margin-left:0px">
                    <option value="homme">Homme</option>
                    <option value="femme">Femme</option>
                </select>
                <label for="hauteur">Hauteur(cm) :</label><br>
                <input type="number" id="hauteur" class="input" name="hauteur" min="0" required><br>
                <label for="poids">Poids (kilos) :</label><br>
                <input type="number" id="poids" class="input" name="poids" min="0" required><br>
                <label for="age">Âge :</label><br>
                <input type="number" id="age" class="input" name="age" min="0" required>
            </div>
        </div>
        <div class="contain">
            <div class="etapes">
                <div class="case">2</div>
                <h2 style="padding-top: 8px;">Sélectionnez votre Objectif.</h2>
                <p style="padding-top: 8px;">Vous pouvez choisir de Manger Sainement, de Prendre de la Masse Musculaire ou de Perdre du Poids.</p>
                <p style="padding-top: 18px;">MealPlanChef vous recommandera alors un Plan Alimentaire adapté à l'objectif que vous avez choisi.</p>
            </div>
            <div class="type-repas">
                <select name="type_repas" id="type-repas-select">
                    <option value="maintien">Manger sainement</option>
                    <option value="perte_poids">Perdre du poids</option>
                    <option value="prise_muscle">Prendre du muscle</option>
                </select>
            </div>
        </div>
        <!-- Section 2: Ustensiles -->
        <div class="contain">
            <div class="etapes">
                <div class="case">3</div>
                <h2 style="padding-top: 8px;">Sélectionnez votre Niveau d'Activité Quotidienne.</h2>
                <p style="padding-top: 8px;">Choisissez l'option la plus adaptée à votre mode de vie actuel.</p>
                <p style="padding-top:18px;line-height:27px">Cela informera MealPlanChef du bon niveau de calories pour votre Plan Alimentaire.</p>
            </div>
            <div class="ustensiles-checkboxes">
                <select name="niveau_activite" id="niveau-activité-select">
                    <option value="sedentaire">Sédentaire</option>
                    <option value="legerement_actif">Légèrement actif</option>
                    <option value="moderement_actif">Modérément actif</option>
                    <option value="tres_actif">Très actif</option>
                    <option value="extremement_actif">Extrêmement actif</option>
                </select>
            </div>
        </div>
        <div class="contain">
            <div class="etapes">
                <div class="case">4</div>
                <h2 style="padding-top: 8px;">Sélectionnez vos besoins alimentaires.</h2>
                <p style="padding-top: 8px;">Vous pouvez choisir parmi Végétarien, Pescétarien, Végétalien, Sans Gluten, Sans Produits Laitiers, Keto et Paléo.</p>
                <p style="padding-top:18px;line-height:27px">MealPlanChef vous recommandera alors un Plan Alimentaire adapté au régime que vous avez choisi.</p>
            </div>
            <div class="ustensiles-checkboxes">
                <select name="besoin-alimentaires" id="besoin-alimentaires-select">
                    <option value="standard">Standard</option>
                    <option value="Végétarien">Végétarien</option>
                    <option value="Pescétarien">Pescétarien</option>
                    <option value="Végétalien">Végétalien</option>
                    <option value="Sans gluten">Sans gluten</option>
                    <option value="Sans produits laitiers">Sans produits laitiers</option>
                    <option value="Keto">Keto</option>
                    <option value="Paléo">Paléo</option>
                </select>
            </div>
        </div>

        <!-- Section 3: Durée -->
        <div class="contain">
            <div class="etapes">
                <div class="case">5</div>
                <h2 style="padding-top: 8px;">Sélectionnez la durée du Plan Alimentaire.</h2>
                <p style="padding-top: 8px;">Avez-vous besoin d'une rapide cure détox le week-end ou d'un plan pour toute la semaine ?</p>
                <p style="padding-top:18px">MealPlanChef vous recommandera un Plan Alimentaire pour autant de jours que nécessaire.</p>
            </div>
            <div class="duree">
                <label for="duree" style="padding-left: 5px;font-size: 20px;font-weight: 600;">Entrer Votre durée :</label><br>
                <input type="number" name="duree" id="duree" value="7" min="1" max="30">
            </div>
        </div>
        <div class="contain">
            <div class="generer">
                <div class="case">6</div>
                <h2 style="padding-top: 8px;">Générez votre Plan Alimentaire.</h2>
                <p style="padding-top: 8px;">Appuyez sur le bouton Générer et attendez que la magie opère.</p>
                <p style="padding-top: 18px;line-height:27px">En un seul clic, vous pouvez sauvegarder votre plan de repas dans le Livre de Recettes ou ajouter chaque recette à la Liste de Courses. Et si vous souhaitez commander les ingrédients du plan de repas en ligne, vous pouvez également ajouter tous les ingrédients à votre panier AmazonFresh ou InstaCart !</p>
            </div>
            <div class="generate-section">
                <button id="generate-recipes" class="generate-btn">
                    Générer votre plan alimentaire 🧑
                </button>
            </div> 
        </div>
        <!-- Section des résultats -->
        <div id="recipes-results" class="recipes-results" style="display: none;">
            <h2 class="recipes-title">Votre plan alimentaire personnalisé</h2>
            <div id="recipes-container"></div>
        </div>
    </main>

    <!-- Modal pour afficher une recette complète -->
    <div id="recipe-modal" class="recipe-modal" style="display: none;">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="modal-recipe-content"></div>
        </div>
    </div>
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
                <a href="conf">Confidentialité</a><br>
            </div>
        </div>
        <div style=" width: 100%;height: 0.5px; background:black; margin: 30px 0;"></div>
        <div class="copyright">© 2025 CookBot. Tous droits réservés.</div>
    </section>
    <script src="mealplanchef.js"></script>
</body>
</html>
