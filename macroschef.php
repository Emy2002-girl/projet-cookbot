<?php
session_start();
$user_logged_in = isset($_SESSION['user_id']);
$user_email = $user_logged_in ? $_SESSION['user_email'] : '';
$user_name = $user_logged_in ? ($_SESSION['user_name'] ?? '') : '';
$user_prenom = $user_logged_in ? ($_SESSION['user_prenom'] ?? '') : '';
$initial = $user_logged_in ? strtoupper(substr($user_prenom, 0, 1)) : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MacrosChef - CookBot</title>
    <link rel="stylesheet" href="macroschef.css">
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
                            <div class="dropdown-icon icon-macros">💪</div>
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
                <span style="border: 1px solid gainsboro; padding: 0.5rem 0.75rem; border-radius: 18px;">FR</span>
                <span style="border: 1px solid gainsboro; padding: 0.5rem 0.75rem; border-radius: 18px;"><img src="images/sun.png" alt="" width="17" height="17" margin-top="5px"></span>
                
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
    <section class="hero">
        <div class="container">
            <div class="description">
                <div class="flex">
                <h2>💪 MacrosChef</h2></div>
                <p class="desc">
                Atteignez Vos <br>
                Macros
                <span style="color:#059669"> Facilement !</span>
                </p>
                <p class="desc1">
                Lorsque vous avez du mal à atteindre vos macros, MacrosChef est là pour vous aider. Vous pouvez générer des recettes spécialement conçues pour vous aider à atteindre vos objectifs en macronutriments. Fini les devinettes ou les difficultés à trouver le bon équilibre !
                </p>
            </div>
            <div class="image" style="width:80%"><img src="images/macros.png" alt="" srcset="" width="100%" height="auto"></div>
        </div>
    </section>
    <main>
        <!-- Section 1: Macronutriments -->
        <div class="contain">
            <div class="etapes">
                <div class="case">1</div>
                <h2 style="padding-top: 8px;">Sélectionnez le macronutriment cible que vous souhaitez atteindre.</h2>
                <p style="padding-top: 8px;">Indiquez à MacrosChef la quantité en grammes de glucides, de protéines et de graisses que votre repas devrait contenir.</p>
                <p style="padding-top: 18px;">MacrosChef créera la recette parfaite pour atteindre vos objectifs en macronutriments !</p>
            </div>
            <div class="ingredients-selector">
                <div class="macronutrients-input">
                    <label for="glucides">Glucides </label>
                    <input type="number" id="glucides" name="glucides" min="0" required>
                    <div><span>grammes</span></div>
                </div>
                <div class="macronutrients-input">
                    <label for="proteines">Protéines </label>
                    <input type="number" id="proteines" name="proteines" min="0" required>
                    <div><span>grammes</span></div>
                </div>
                <div class="macronutrients-input">
                    <label for="lipides">Lipides </label>
                    <input type="number" id="lipides" name="lipides" min="0" required>
                    <div><span>grammes</span></div>
                </div>
            </div>
        </div>
        <!-- Section 2: Type de repas -->
        <div class="contain">
            <div class="etapes">
                <div class="case">2</div>
                <h2 style="padding-top: 8px;">Sélectionnez le repas que vous souhaitez préparer.</h2>
                <p style="padding-top: 8px;">Vous pouvez choisir le petit-déjeuner, le déjeuner, le goûter ou le dîner.</p>
                <p style="padding-top: 18px;">MacrosChef vous recommandera ensuite une recette adaptée au repas que vous avez sélectionné.</p>
            </div>
            <div class="type-repas">
                <select name="type_repas" id="type-repas-select" style="width:80%">
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
        
        <!-- Section 3: Besoins alimentaires -->
        <div class="contain">
            <div class="etapes">
                <div class="case">3</div>
                <h2 style="padding-top: 8px;">Sélectionnez vos besoins alimentaires.</h2>
                <p style="padding-top: 8px;">Vous pouvez choisir parmi Végétarien, Pescétarien, Végétalien, Sans Gluten, Sans Produits Laitiers, Régime Cétogène et Paléo.</p>
                <p style="padding-top: 18px;">MacrosChef vous recommandera ensuite une recette adaptée au régime que vous avez sélectionné.</p>
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
        
        <!-- Section 4: Générer -->
        <div class="contain">
            <div class="etapes">
                <div class="case">4</div>
                <h2 style="padding-top: 8px;">Générez votre recette.</h2>
                <p style="padding-top: 8px;">Appuyez sur le bouton Générer et attendez que la magie opère.</p>
                <p style="padding-top: 18px;line-height:27px">En un clic, vous pouvez enregistrer votre recette dans le livre de cuisine ou l'ajouter à la liste de
                    courses. Et si vous souhaitez commander les ingrédients en ligne, vous pouvez les ajouter à votre
                    panier AmazonFresh ou InstaCart !</p>
            </div>
            <div class="generate-section">
                <button id="generate-recipes" class="generate-btn">
                    Générer votre recette 🧑‍🍳
                </button>
            </div> 
        </div>
        
        <!-- Section des résultats -->
        <div id="recipes-results" class="recipes-results">
            <h2 class="recipes-title">Recettes trouvées pour vous</h2>
            <div id="recipes-container"></div>
        </div>
    </main>
    
    <!-- Modal pour afficher une recette complète -->
    <div id="recipe-modal" class="recipe-modal">
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
            <div class="conta propos">
                <h2>à propos</h2>
                <a href="#">Blog</a><br>
                <a href="#">Contacte</a><br>
            </div>
            <div class="conta produit">
                <h2>Produit</h2>
                <a href="#">Tarification</a><br>
                <a href="#">FAQ</a><br>
            </div>
            <div class="conta suiver">
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
        <div style="width: 100%;height: 0.5px; background:black; margin: 30px 0;"></div>
        <div class="copyright">© 2025 CookBot. Tous droits réservés.</div>
    </section>
    
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Référence aux éléments du formulaire
            const generateButton = document.getElementById("generate-recipes");
            const glucidesInput = document.getElementById("glucides");
            const proteinesInput = document.getElementById("proteines");
            const lipidesInput = document.getElementById("lipides");
            const typeRepasSelect = document.getElementById("type-repas-select");
            const besoinAlimentairesSelect = document.getElementById("besoin-alimentaires-select");
            const recipesResults = document.getElementById("recipes-results");
            const recipesContainer = document.getElementById("recipes-container");
            const recipeModal = document.getElementById("recipe-modal");
            const modalContent = document.getElementById("modal-recipe-content");
            const closeModal = document.querySelector(".close-modal");
            
            // Script pour le menu utilisateur
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
            
            // Fermer la modal quand on clique sur X
            if (closeModal) {
                closeModal.addEventListener("click", () => {
                    recipeModal.style.display = "none";
                });
            }
            
            // Fermer la modal quand on clique en dehors
            window.addEventListener("click", (event) => {
                if (event.target === recipeModal) {
                    recipeModal.style.display = "none";
                }
            });
            
            // Gestionnaire d'événement pour le bouton de génération
            if (generateButton) {
                generateButton.addEventListener("click", () => {
                    // Validation des entrées
                    const glucides = parseInt(glucidesInput.value);
                    const proteines = parseInt(proteinesInput.value);
                    const lipides = parseInt(lipidesInput.value);
                    const typeRepas = typeRepasSelect.value;
                    const besoinAlimentaire = besoinAlimentairesSelect.value;
                    
                    if (isNaN(glucides) || isNaN(proteines) || isNaN(lipides) || glucides <= 0 || proteines <= 0 || lipides <= 0) {
                        alert("Veuillez entrer des valeurs valides pour les macronutriments");
                        return;
                    }
                    
                    // Afficher un indicateur de chargement
                    recipesContainer.innerHTML = '<div class="loading">Recherche de recettes...</div>';
                    recipesResults.style.display = "block";
                    
                    // Envoyer la requête au serveur
                    fetch("macroschef_process.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            glucides: glucides,
                            proteines: proteines,
                            lipides: lipides,
                            type_repas: typeRepas,
                            besoin_alimentaire: besoinAlimentaire,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message && !data.success) {
                            recipesContainer.innerHTML = `<div class="error">${data.message}</div>`;
                            return;
                        }
                        
                        if (!data.success || data.recipes.length === 0) {
                            recipesContainer.innerHTML = `
                                <div class="no-results">
                                    <p>Aucune recette ne correspond exactement à vos critères.</p>
                                    <p>Essayez d'ajuster vos macronutriments ou de sélectionner un autre type de repas.</p>
                                </div>
                            `;
                            return;
                        }
                        
                        // Afficher les résultats
                        displayRecipes(data.recipes);
                    })
                    .catch(error => {
                        console.error("Erreur:", error);
                        recipesContainer.innerHTML = `<div class="error">Une erreur est survenue lors de la recherche.</div>`;
                    });
                });
            }
            
            // Fonction pour afficher les recettes
            function displayRecipes(recipes) {
                recipesContainer.innerHTML = "";
                
                recipes.forEach(recipe => {
                    // Calculer la correspondance en pourcentage
                    const targetGlucides = parseInt(glucidesInput.value);
                    const targetProteines = parseInt(proteinesInput.value);
                    const targetLipides = parseInt(lipidesInput.value);
                    
                    let matchPercentage = recipe.match_percentage || 0;
                    if (!matchPercentage) {
                        const glucidesMatch = 100 - Math.min(100, Math.abs(recipe.GLUCIDES - targetGlucides) / targetGlucides * 100);
                        const proteinesMatch = 100 - Math.min(100, Math.abs(recipe.PROTEINES - targetProteines) / targetProteines * 100);
                        const lipidesMatch = 100 - Math.min(100, Math.abs(recipe.LIPIDES - targetLipides) / targetLipides * 100);
                        matchPercentage = Math.round((glucidesMatch + proteinesMatch + lipidesMatch) / 3);
                    }
                    
                    // Créer la carte de recette
                    const recipeCard = document.createElement("div");
                    recipeCard.className = "recipe-card";
                    recipeCard.innerHTML = `
                        <h3 class="recipe-title">${recipe.TITRE}</h3>
                        <div class="recipe-meta">
                            <span class="recipe-difficulty">${recipe.DIFFICULTE}</span>
                            <span class="recipe-time">${recipe.TEMPS_PREPARATION + recipe.TEMPS_CUISSON} min</span>
                        </div>
                        <div class="recipe-match">
                            <div class="match-percentage">${matchPercentage}%</div>
                            <div class="match-text">correspondance</div>
                        </div>
                        <div class="recipe-macros">
                            <div class="macro">
                                <span class="macro-value">${recipe.GLUCIDES}g</span>
                                <span class="macro-label">Glucides</span>
                            </div>
                            <div class="macro">
                                <span class="macro-value">${recipe.PROTEINES}g</span>
                                <span class="macro-label">Protéines</span>
                            </div>
                            <div class="macro">
                                <span class="macro-value">${recipe.LIPIDES}g</span>
                                <span class="macro-label">Lipides</span>
                            </div>
                        </div>
                        <button class="view-recipe-btn">Voir la recette</button>
                    `;
                    
                    // Ajouter un gestionnaire d'événement pour afficher les détails
                    const viewButton = recipeCard.querySelector(".view-recipe-btn");
                    viewButton.addEventListener("click", () => {
                        showRecipeDetails(recipe);
                    });
                    
                    recipesContainer.appendChild(recipeCard);
                });
            }
            
            // Fonction pour afficher les détails d'une recette
            function showRecipeDetails(recipe) {
                // Formater les ingrédients
                let ingredientsList = "";
                if (recipe.ingredients_details && recipe.ingredients_details.length > 0) {
                    ingredientsList = '<ul class="ingredients-list">';
                    recipe.ingredients_details.forEach(ingredient => {
                        ingredientsList += `<li>${ingredient.QUANTITE} ${ingredient.UNITE} de ${ingredient.NOM}</li>`;
                    });
                    ingredientsList += "</ul>";
                } else if (recipe.INGREDIENTS) {
                    const ingredients = recipe.INGREDIENTS.split(',');
                    ingredientsList = '<ul class="ingredients-list">';
                    ingredients.forEach(ingredient => {
                        ingredientsList += `<li>${ingredient.trim()}</li>`;
                    });
                    ingredientsList += "</ul>";
                }
                
                // Formater les instructions
                let instructionsList = "";
                if (recipe.INSTRUCTIONS) {
                    const instructions = recipe.INSTRUCTIONS.split('\n');
                    instructionsList = '<ol class="instructions-list">';
                    instructions.forEach(instruction => {
                        if (instruction.trim() !== "") {
                            instructionsList += `<li>${instruction.trim()}</li>`;
                        }
                    });
                    instructionsList += "</ol>";
                }
                
                // Remplir la modal
                modalContent.innerHTML = `
                    <h2>${recipe.TITRE}</h2>
                    <div class="recipe-details">
                        <div class="recipe-info">
                            <p><strong>Difficulté:</strong> ${recipe.DIFFICULTE}</p>
                            <p><strong>Temps de préparation:</strong> ${recipe.TEMPS_PREPARATION} min</p>
                            <p><strong>Temps de cuisson:</strong> ${recipe.TEMPS_CUISSON} min</p>
                            <p><strong>Portions:</strong> ${recipe.PORTIONS || 1}</p>
                        </div>
                        <div class="recipe-macros-detail">
                            <h3>Macronutriments par portion</h3>
                            <p><strong>Calories:</strong> ${recipe.CALORIES || 'N/A'} kcal</p>
                            <p><strong>Glucides:</strong> ${recipe.GLUCIDES}g</p>
                            <p><strong>Protéines:</strong> ${recipe.PROTEINES}g</p>
                            <p><strong>Lipides:</strong> ${recipe.LIPIDES}g</p>
                            <p><strong>Fibres:</strong> ${recipe.FIBRES || 'N/A'}g</p>
                        </div>
                    </div>
                    <div class="recipe-content">
                        <h3>Ingrédients</h3>
                        ${ingredientsList}
                        <h3>Instructions</h3>
                        ${instructionsList}
                    </div>
                    <div class="recipe-actions">
                        <button class="save-recipe">Sauvegarder la recette</button>
                        <button class="add-to-shopping">Ajouter à la liste de courses</button>
                    </div>
                `;
                
                // Ajouter des gestionnaires d'événements pour les boutons d'action
                const saveButton = modalContent.querySelector(".save-recipe");
                if (saveButton) {
                    saveButton.addEventListener("click", () => {
                        saveRecipe(recipe.ID_RECETTE);
                    });
                }
                
                const shoppingButton = modalContent.querySelector(".add-to-shopping");
                if (shoppingButton) {
                    shoppingButton.addEventListener("click", () => {
                        addToShoppingList(recipe.ID_RECETTE);
                    });
                }
                
                // Afficher la modal
                recipeModal.style.display = "flex";
            }
            
            // Fonction pour sauvegarder une recette (à implémenter)
            function saveRecipe(recipeId) {
                alert("Fonctionnalité à implémenter: Sauvegarder la recette " + recipeId);
            }
            
            // Fonction pour ajouter à la liste de courses (à implémenter)
            function addToShoppingList(recipeId) {
                alert("Fonctionnalité à implémenter: Ajouter la recette " + recipeId + " à la liste de courses");
            }
        });
    </script>
</body>
</html>
