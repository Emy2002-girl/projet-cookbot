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
        </div>
        </nav>
    </header>
    <section class="hero">
        <div class="container">
            <div class="description">
                <div class="flex">
                <img src="images/Overlay (1).png" style="width:20px,height:20px">
                <h2>PantryChef</h2></div>
                <p class="desc">
                Où les Ingrédients se Transforment en<span style="color:#059669"> Chef-d'œuvre !</span>
                </p>
                <p class="desc1">
                Vous avez un garde-manger bien garni mais aucune inspiration pour les recettes ?
                PantryChef est le génie de la cuisine qui transforme vos ingrédients de base en délices
                gastronomiques. Fini les dilemmes du dîner, place aux plats délicieux !
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
                        <h2 style="padding-top: 8px;">Ajoutez les ingrédients que vous avez à la maison.</h2>
                        <p style="padding-top: 8px;">Vous pouvez choisir des ingrédients dans la liste ou dans votre inventaire enregistré.</p>
                        <p style="padding-top: 18px;">Les ingrédients sélectionnés apparaîtront sous forme de badges que vous pourrez facilement supprimer.</p>
                    </div>
                    <div class="ingredients-selector">
                        <!-- Affichage des ingrédients sélectionnés -->
                        <div id="selected-ingredients-display" class="ingredients-badges">
                            <!-- Les badges apparaîtront ici -->
                        </div>
                        
                        <!-- Sélecteur d'ingrédients -->
                        <div class="ingredient-search-container">
                            <select id="ingredient-select" class="ingredient-search">
                                <option value="">Rechercher ou ajouter un ingrédient</option>
                                <optgroup label="Légumes">
                                    <option value="Tomate">Tomate</option>
                                    <option value="Oignon">Oignon</option>
                                    <option value="Ail">Ail</option>
                                    <option value="Carotte">Carotte</option>
                                    <option value="Pomme de terre">Pomme de terre</option>
                                    <option value="Courgette">Courgette</option>
                                    <option value="Aubergine">Aubergine</option>
                                    <option value="Poivron rouge">Poivron rouge</option>
                                    <option value="Champignons">Champignons</option>
                                    <option value="Épinards">Épinards</option>
                                    <option value="Haricots verts">Haricots verts</option>
                                </optgroup>
                                <optgroup label="Protéines">
                                    <option value="Œuf">Œuf</option>
                                    <option value="Poulet">Poulet</option>
                                    <option value="Bœuf haché">Bœuf haché</option>
                                    <option value="Thon en conserve">Thon en conserve</option>
                                    <option value="Saumon">Saumon</option>
                                    <option value="Crevettes">Crevettes</option>
                                </optgroup>
                                <optgroup label="Féculents">
                                    <option value="Riz">Riz</option>
                                    <option value="Pâtes">Pâtes</option>
                                    <option value="Farine">Farine</option>
                                    <option value="Quinoa">Quinoa</option>
                                    <option value="Lentilles">Lentilles</option>
                                    <option value="Pain">Pain</option>
                                </optgroup>
                                <optgroup label="Produits laitiers">
                                    <option value="Lait">Lait</option>
                                    <option value="Beurre">Beurre</option>
                                    <option value="Fromage râpé">Fromage râpé</option>
                                    <option value="Yaourt nature">Yaourt nature</option>
                                    <option value="Mozzarella">Mozzarella</option>
                                    <option value="Parmesan">Parmesan</option>
                                    <option value="Cheese">Cheese</option>
                                </optgroup>
                                <optgroup label="Fruits">
                                    <option value="Citron">Citron</option>
                                    <option value="Avocat">Avocat</option>
                                    <option value="Banane">Banane</option>
                                    <option value="Pomme">Pomme</option>
                                </optgroup>
                                <optgroup label="Condiments & Épices">
                                    <option value="Huile d'olive">Huile d'olive</option>
                                    <option value="Sel">Sel</option>
                                    <option value="Poivre">Poivre</option>
                                    <option value="Pepper">Pepper</option>
                                    <option value="Vinaigre balsamique">Vinaigre balsamique</option>
                                    <option value="Sauce soja">Sauce soja</option>
                                    <option value="Miel">Miel</option>
                                    <option value="Sucre">Sucre</option>
                                </optgroup>
                            </select>
                            <button type="button" id="add-ingredient-btn" class="add-ingredient-btn">Ajouter</button>
                        </div>
                    </div>
                </div>
                <div class="contain">
                    <div class="etapes">
                        <div class="case">2</div>
                        <h2 style="padding-top: 8px;">Sélectionnez le repas que vous souhaitez cuisiner.</h2>
                        <p style="padding-top: 8px;">Vous pouvez choisir le petit-déjeuner, le déjeuner, le goûter ou le dîner.</p>
                        <p style="padding-top: 18px;">PantryChef vous recommandera alors une recette adaptée au repas que vous avez choisi.</p>
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
                <!-- Section 2: Ustensiles -->
                <div class="contain">
                    <div class="etapes">
                        <div class="case">3</div>
                        <h2 style="padding-top: 8px;">Sélectionnez les ustensiles de cuisine que vous avez.</h2>
                        <p style="padding-top: 8px;">Choisissez les ustensiles de cuisine que vous avez ou que vous souhaitez utiliser.</p>
                        <p style="padding-top:18px;line-height:27px">Cela empêchera PantryChef de vous recommander des recettes utilisant des ustensiles que vous
                        n'avez pas ou que vous ne souhaitez pas utiliser.</p>
                    </div>
                    <div class="ustensiles-checkboxes">
                        <div class="ustensiles-grid">
                            <label class="ustensile-checkbox">
                                <input type="checkbox" name="ustensiles[]" value="Plaque de cuisson">
                                <span class="checkmark"></span>
                                <span class="ustensile-name">Plaque de cuisson</span>
                            </label>
                            <label class="ustensile-checkbox">
                                <input type="checkbox" name="ustensiles[]" value="Four">
                                <span class="checkmark"></span>
                                <span class="ustensile-name">Four</span>
                            </label>
                            <label class="ustensile-checkbox">
                                <input type="checkbox" name="ustensiles[]" value="Poêle">
                                <span class="checkmark"></span>
                                <span class="ustensile-name">Micro-ondes</span>
                            </label>
                            <label class="ustensile-checkbox">
                                <input type="checkbox" name="ustensiles[]" value="Casserole">
                                <span class="checkmark"></span>
                                <span class="ustensile-name">Friteuse à air</span>
                            </label>
                            <label class="ustensile-checkbox">
                                <input type="checkbox" name="ustensiles[]" value="Couteau">
                                <span class="checkmark"></span>
                                <span class="ustensile-name">Machine sous vide</span>
                            </label>
                            <label class="ustensile-checkbox">
                                <input type="checkbox" name="ustensiles[]" value="Mixeur">
                                <span class="checkmark"></span>
                                <span class="ustensile-name">Mixeur</span>
                            </label>
                            <label class="ustensile-checkbox">
                                <input type="checkbox" name="ustensiles[]" value="Fouet">
                                <span class="checkmark"></span>
                                <span class="ustensile-name">Robot culinaire</span>
                            </label>
                            <label class="ustensile-checkbox">
                                <input type="checkbox" name="ustensiles[]" value="Barbecue">
                                <span class="checkmark"></span>
                                <span class="ustensile-name">Cuiseur lent</span>
                            </label>
                            <label class="ustensile-checkbox">
                                <input type="checkbox" name="ustensiles[]" value="Barbecue">
                                <span class="checkmark"></span>
                                <span class="ustensile-name">Autocuiseur</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Durée -->
                <div class="contain">
                    <div class="etapes">
                        <div class="case">4</div>
                        <h2 style="padding-top: 8px;">Sélectionnez le temps dont vous disposez.</h2>
                        <p style="padding-top: 8px;">Sélectionnez le temps maximum que vous souhaitez consacrer à la préparation.</p>
                        <p style="padding-top:18px">Cela empêchera PantryChef de vous recommander des recettes qui prennent trop de temps à
                        préparer.</p>
                    </div>
                    <div class="duree">
                        <select name="duree" id="duree-select" style="width:130%">
                            <option value="">-- Sélectionnez une durée --</option>
                            <option value="15">Moins de 15 minutes</option>
                            <option value="30">15 à 30 minutes</option>
                            <option value="45">30 à 45 minutes</option>
                            <option value="60">45 minutes à 1 heure</option>
                            <option value="90">1 à 1h30</option>
                            <option value="120">Plus de 1h30</option>
                        </select>
                    </div>
                </div>

                <!-- Section 4: Type de repas -->
                
            <div class="contain">
                    <div class="etapes">
                        <div class="case">5</div>
                        <h2 style="padding-top: 8px;">Sélectionnez votre niveau de compétence.</h2>
                        <p style="padding-top: 8px;">Peu importe que vous soyez un novice ou un chef étoilé au Michelin.</p>
                        <p style="padding-top: 18px;">PantryChef vous recommandera des recettes adaptées à votre niveau de compétence.</p>
                    </div>
                    <div class="niveau-cuisine">
                        <select name="niveau_cuisine" id="niveau-cuisine-select">
                            <option value="">-- Sélectionnez votre niveau --</option>
                            <option value="debutant">Débutant</option>
                            <option value="intermediaire">Intermédiaire</option>
                            <option value="avance">Expert</option>
                        </select>
                    </div>
            </div>
                <div class="contain">
                    <div class="generer">
                         <div class="case">6</div>
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
    <script src="script1.js"></script>
</body>
</html>