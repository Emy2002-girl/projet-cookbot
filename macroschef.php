<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookBot - Pantrychef</title>
    <link rel="stylesheet" href="pantrychef.css">
    <link rel="stylesheet" href="recipe-styles.css">
    <link rel="stylesheet" href="ingredients.css">
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
                            <div class="dropdown-icon icon-macros">💪</div>
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
        </div>
        </nav>
    </header>
    <section class="hero">
        <div class="container">
            <div class="description">
                <div class="flex">
                <h2>💪 MacrosChef</h2></div>
                <p class="desc">
                Atteignez Vos Macros<span style="color:#059669"> Facilement !</span>
                </p>
                <p class="desc1">
                Lorsque vous avez du mal à atteindre vos macros, MacrosChef est là pour vous aider. Vous pouvez générer des recettes spécialement conçues pour vous aider à atteindre vos objectifs en macronutriments. Fini les devinettes ou les difficultés à trouver le bon équilibre !
                </p>
            </div>
            <div class="image"><img src="images/super.jpg" alt="" srcset="" width="100%" height="auto" style="border-radius:20%;box-shadow: #BDBDBD 10px 7px 0px"></div>
        </div>
    </section>
        <main>
                <!-- Section 1: Ingrédients avec interface badges -->
                <div class="contain">
                    <div class="etapes">
                        <div class="case">1</div>
                        <h2 style="padding-top: 8px;">Sélectionnez le macronutriment cible que vous souhaitez atteindre.</h2>
                        <p style="padding-top: 8px;">Indiquez à MacrosChef la quantité en grammes de glucides, de protéines et de graisses que votre repas devrait contenir.</p>
                        <p style="padding-top: 18px;">MacrosChef créera la recette parfaite pour atteindre vos objectifs en macronutriments !</p>
                    </div>
                    <div class="ingredients-selector">
                        <div class="macronutrients-input">
                            <label for="glucides">Glucides</label><br>
                            <input type="number" id="glucides" name="glucides" min="0" required>
                            <div><span>grammes</span></div>
                        </div>
                        <div class="macronutrients-input">
                            <label for="proteines">Protéines (g)</label><br>
                            <input type="number" id="proteines" name="proteines" min="0" required>
                        </div>
                        <div class="macronutrients-input">
                            <label for="lipides">Lipides (g)</label><br>
                            <input type="number" id="lipides" name="lipides" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="contain">
                    <div class="etapes">
                        <div class="case">2</div>
                        <h2 style="padding-top: 8px;">Sélectionnez le repas que vous souhaitez préparer.</h2>
                        <p style="padding-top: 8px;">Vous pouvez choisir le petit-déjeuner, le déjeuner, le goûter ou le dîner.</p>
                        <p style="padding-top: 18px;">MacrosChef vous recommandera ensuite une recette adaptée au repas que vous avez sélectionné.</p>
                    </div>
                    <div class="type-repas">
                        <select name="type_repas" id="type-repas-select">
                            <option value="">-- Sélectionnez un type de repas --</option>
                            <option value="petit-déjeuner">Petit-déjeuner</option>
                            <option value="déjeuner">Déjeuner</option>
                            <option value="dîner">Dîner</option>
                            <option value="collation">Collation</option>
                            <option value="dessert">Dessert</option>
                            <option value="apéritif">Apéritif</option>
                        </select>
                    </div>
                </div>
            <div class="contain">
                <div class="etapes">
                    <div class="case">4</div>
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
                <div class="contain">
                    <div class="generer">
                         <div class="case">5</div>
                            <h2 style="padding-top: 8px;">Générez votre recette.</h2>
                            <p style="padding-top: 8px;">Appuyez sur le bouton Générer et attendez que la magie opère.</p>
                            <p style="padding-top: 18px;line-height:27px">En un clic, vous pouvez enregistrer votre recette dans le livre de cuisine ou l'ajouter à la liste de
                                courses. Et si vous souhaitez commander les ingrédients en ligne, vous pouvez les ajouter à votre
                                panier AmazonFresh ou InstaCart !</p>
                       </div>
                     <div class="generate-section">
                        <button id="generate-recipes" class="generate-btn">
                        Générer votre recette 🧑
                        </button>
                      </div> 
                </div>
             <!-- Section des résultats -->
            <div id="recipes-results" class="recipes-results" style="display: none;">
                <h2 class="recipes-title">Recettes trouvées pour vous</h2>
                <div id="recipes-container"></div>
            </div>
        </main>
    </div>

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
            <a href="conf">Confidentalité</a><br>
          </div>
    </div>
    <div style=" width: 100%;height: 0.5px; background:black; margin: 30px 0;"></div>
    <div class="copyright">© 2025 CookBot. Tous droits réservés.</div>
    </section>
    <script src="pantrychef.js"></script>
</body>
</html>