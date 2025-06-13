document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const niveauCuisineSelect = document.getElementById('niveau-cuisine-select');
    const niveauInfo = document.getElementById('niveau-info');
    const niveauTitle = document.getElementById('niveau-title');
    const niveauDescription = document.getElementById('niveau-description');
    const niveauExamples = document.getElementById('niveau-examples');
    const ingredientSelect = document.getElementById('ingredient-select');
    const addIngredientBtn = document.getElementById('add-ingredient-btn');
    const selectedIngredientsDisplay = document.getElementById('selected-ingredients-display');
    const ustensileCheckboxes = document.querySelectorAll('input[name="ustensiles[]"]');
    const ingredientsCount = document.getElementById('ingredients-count');
    const ustensilesCount = document.getElementById('ustensiles-count');
    const generateBtn = document.getElementById('generate-recipes');
    const recipesResults = document.getElementById('recipes-results');
    const recipesContainer = document.getElementById('recipes-container');
    const recipeModal = document.getElementById('recipe-modal');
    const closeModal = document.querySelector('.close-modal');

    // Array pour stocker les ingrédients sélectionnés
    let selectedIngredients = [];

    // Informations sur les niveaux de cuisine
    const niveauxInfo = {
        'debutant': {
            title: '👶 Niveau Débutant',
            description: 'Parfait pour ceux qui commencent en cuisine ! Nous vous proposerons des recettes simples avec des techniques de base et des ingrédients faciles à trouver.',
            examples: [
                'Œufs brouillés, sandwich, salade simple',
                'Pâtes au beurre, riz nature',
                'Techniques : bouillir, faire revenir, mélanger',
                'Temps de préparation : généralement moins de 30 minutes'
            ]
        },
        'intermediaire': {
            title: '👨‍🍳 Niveau Intermédiaire',
            description: 'Vous avez quelques bases en cuisine et êtes prêt(e) à relever de nouveaux défis ! Nous inclurons des recettes avec des techniques moyennement complexes.',
            examples: [
                'Risotto, quiche, poulet rôti',
                'Sauces simples, gratins',
                'Techniques : mijoter, gratiner, réduire',
                'Temps de préparation : 30 minutes à 1 heure'
            ]
        },
        'avance': {
            title: '🧑‍🍳 Niveau Avancé',
            description: 'Vous maîtrisez bien la cuisine et aimez les défis culinaires ! Nous vous proposerons toutes nos recettes, y compris les plus complexes.',
            examples: [
                'Bouillabaisse, soufflé, coq au vin',
                'Sauces élaborées, techniques avancées',
                'Techniques : flamber, monter une sauce, tempérer',
                'Temps de préparation : peut dépasser 1 heure'
            ]
        }
    };

    // Gestionnaire pour le changement de niveau de cuisine
    if (niveauCuisineSelect) {
        niveauCuisineSelect.addEventListener('change', function() {
            const selectedLevel = this.value;
            
            if (selectedLevel && niveauxInfo[selectedLevel]) {
                const info = niveauxInfo[selectedLevel];
                
                niveauTitle.textContent = info.title;
                niveauDescription.textContent = info.description;
                
                // Créer la liste d'exemples
                niveauExamples.innerHTML = `
                    <h5>Exemples de recettes et techniques :</h5>
                    <ul>
                        ${info.examples.map(example => `<li>${example}</li>`).join('')}
                    </ul>
                `;
                
                niveauInfo.style.display = 'block';
            } else {
                niveauInfo.style.display = 'none';
            }
        });
    }

    // Fonction pour mettre à jour l'affichage des ingrédients
    function updateIngredientsDisplay() {
        selectedIngredientsDisplay.innerHTML = '';
        
        if (selectedIngredients.length === 0) {
            selectedIngredientsDisplay.classList.remove('has-ingredients');
            selectedIngredientsDisplay.classList.add('empty');
        } else {
            selectedIngredientsDisplay.classList.add('has-ingredients');
            selectedIngredientsDisplay.classList.remove('empty');
            
            selectedIngredients.forEach((ingredient, index) => {
                const badge = document.createElement('div');
                badge.className = 'ingredient-badge';
                badge.innerHTML = `
                    <span>${ingredient}</span>
                    <button type="button" class="remove-ingredient" data-index="${index}">×</button>
                `;
                selectedIngredientsDisplay.appendChild(badge);
            });
        }
        
        updateIngredientsCount();
    }

    // Fonction pour ajouter un ingrédient
    function addIngredient() {
        const selectedValue = ingredientSelect.value;
        
        if (selectedValue && !selectedIngredients.includes(selectedValue)) {
            selectedIngredients.push(selectedValue);
            updateIngredientsDisplay();
            
            // Réinitialiser le select
            ingredientSelect.value = '';
            addIngredientBtn.disabled = true;
        }
    }

    // Fonction pour supprimer un ingrédient
    function removeIngredient(index) {
        selectedIngredients.splice(index, 1);
        updateIngredientsDisplay();
    }

    // Fonction pour mettre à jour le compteur d'ingrédients
    function updateIngredientsCount() {
        if (ingredientsCount) {
            ingredientsCount.textContent = selectedIngredients.length;
        }
    }

    // Fonction pour mettre à jour le compteur d'ustensiles
    function updateUstensilesCount() {
        const checkedCount = Array.from(ustensileCheckboxes).filter(cb => cb.checked).length;
        if (ustensilesCount) {
            ustensilesCount.textContent = checkedCount;
        }
    }

    // Event listeners pour les ingrédients
    if (ingredientSelect) {
        ingredientSelect.addEventListener('change', function() {
            addIngredientBtn.disabled = !this.value || selectedIngredients.includes(this.value);
        });
    }

    if (addIngredientBtn) {
        addIngredientBtn.addEventListener('click', addIngredient);
        addIngredientBtn.disabled = true;
    }

    // Event listener pour supprimer les ingrédients
    if (selectedIngredientsDisplay) {
        selectedIngredientsDisplay.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-ingredient')) {
                const index = parseInt(e.target.getAttribute('data-index'));
                removeIngredient(index);
            }
        });
    }

    // Event listeners pour les ustensiles
    ustensileCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateUstensilesCount);
    });

    // Initialiser les compteurs
    updateIngredientsDisplay();
    updateUstensilesCount();

    // Fonction pour collecter les données du formulaire
    function collectFormData() {
        const niveauCuisine = niveauCuisineSelect.value;
        
        const selectedUstensiles = Array.from(ustensileCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        const duree = document.getElementById('duree-select').value;
        const typeRepas = document.getElementById('type-repas-select').value;

        return {
            niveau_cuisine: niveauCuisine,
            ingredients: selectedIngredients,
            ustensiles: selectedUstensiles,
            duree: duree,
            type_repas: typeRepas
        };
    }

    // Fonction pour valider les données
    function validateFormData(data) {
        if (!data.niveau_cuisine) {
            alert('Veuillez sélectionner votre niveau de cuisine.');
            return false;
        }
        if (data.ingredients.length === 0) {
            alert('Veuillez sélectionner au moins un ingrédient.');
            return false;
        }
        if (data.ustensiles.length === 0) {
            alert('Veuillez sélectionner au moins un ustensile.');
            return false;
        }
        if (!data.duree) {
            alert('Veuillez sélectionner une durée de préparation.');
            return false;
        }
        if (!data.type_repas) {
            alert('Veuillez sélectionner un type de repas.');
            return false;
        }
        return true;
    }

    // Fonction pour afficher les résultats
    function displayRecipes(recipes) {
        recipesContainer.innerHTML = '';

        if (!recipes || recipes.length === 0) {
            recipesContainer.innerHTML = `
                <div class="no-recipes-message">
                    <h3>Aucune recette trouvée</h3>
                    <p>Essayez de modifier vos critères de recherche ou d'ajouter plus d'ingrédients.</p>
                </div>
            `;
            return;
        }

        recipes.forEach(recipe => {
            const recipeCard = createRecipeCard(recipe);
            recipesContainer.appendChild(recipeCard);
        });
    }

    // Fonction pour créer une carte de recette
    function createRecipeCard(recipe) {
        const card = document.createElement('div');
        card.className = 'recipe-card';

        // Afficher un conseil si disponible
        const conseilHtml = recipe.conseil_niveau ? 
            `<div style="background-color: #fef3c7; color: #92400e; padding: 8px; border-radius: 6px; font-size: 12px; margin-bottom: 10px;">
                💡 ${recipe.conseil_niveau}
            </div>` : '';

        card.innerHTML = `
            <div class="recipe-image">
                <div class="recipe-placeholder">🍽️</div>
            </div>
            <div class="recipe-info">
                ${conseilHtml}
                <h3>${recipe.TITRE}</h3>
                <p>${recipe.DESCRIPTION}</p>
                <div class="recipe-details">
                    <div class="recipe-detail-item">
                        <span>⏱️</span>
                        <span>Préparation: ${recipe.TEMPS_PREPARATION}min</span>
                    </div>
                    <div class="recipe-detail-item">
                        <span>🔥</span>
                        <span>Cuisson: ${recipe.TEMPS_CUISSON}min</span>
                    </div>
                    <div class="recipe-detail-item">
                        <span>📊</span>
                        <span>Difficulté: ${recipe.DIFFICULTE}</span>
                    </div>
                    <div class="recipe-detail-item">
                        <span>🍽️</span>
                        <span>Type: ${recipe.TYPE_REPAS}</span>
                    </div>
                </div>
                <button class="view-recipe-btn" onclick="showRecipeModal(${recipe.ID_RECETTE})">
                    Voir la recette complète
                </button>
            </div>
        `;

        return card;
    }

    // Fonction pour afficher la modal de recette
    window.showRecipeModal = function(recipeId) {
        const recipe = getRecipeDetails(recipeId);
        
        const modalContent = document.getElementById('modal-recipe-content');
        modalContent.innerHTML = `
            <div class="modal-image">🍽️</div>
            <h2>${recipe.TITRE}</h2>
            <div class="modal-description">${recipe.DESCRIPTION}</div>
            
            <div class="modal-details">
                <p><span>⏱️</span> Temps de préparation: ${recipe.TEMPS_PREPARATION} minutes</p>
                <p><span>🔥</span> Temps de cuisson: ${recipe.TEMPS_CUISSON} minutes</p>
                <p><span>📊</span> Difficulté: ${recipe.DIFFICULTE}</p>
                <p><span>🍽️</span> Type de repas: ${recipe.TYPE_REPAS}</p>
            </div>

            <div class="modal-ingredients">
                <h3>Ingrédients</h3>
                <ul>
                    ${recipe.ingredients.map(ing => `<li>${ing}</li>`).join('')}
                </ul>
            </div>

            <div class="modal-instructions">
                <h3>Instructions</h3>
                <div>${recipe.INSTRUCTIONS}</div>
            </div>

            <div class="modal-actions">
                <button class="save-recipe-btn">💾 Sauvegarder</button>
                <button class="print-recipe-btn" onclick="window.print()">🖨️ Imprimer</button>
            </div>
        `;

        recipeModal.style.display = 'flex';
    };

    // Fonction pour obtenir les détails d'une recette (simulation)
    function getRecipeDetails(recipeId) {
        const recipes = {
            1: {
                ID_RECETTE: 1,
                TITRE: "Omelette aux fines herbes",
                DESCRIPTION: "Une omelette simple et délicieuse aux fines herbes fraîches",
                TEMPS_PREPARATION: 5,
                TEMPS_CUISSON: 10,
                DIFFICULTE: "Novice",
                TYPE_REPAS: "petit-déjeuner",
                ingredients: ["3 œufs", "2 cuillères à soupe de lait", "Sel et poivre", "Fines herbes fraîches", "1 cuillère à soupe de beurre"],
                INSTRUCTIONS: `1. Cassez les œufs dans un bol et battez-les avec le lait.
2. Assaisonnez avec du sel et du poivre.
3. Ajoutez les fines herbes hachées.
4. Faites chauffer le beurre dans une poêle.
5. Versez les œufs battus et laissez cuire 2-3 minutes.
6. Pliez l'omelette en deux et servez immédiatement.`
            },
            2: {
                ID_RECETTE: 2,
                TITRE: "Pâtes à l'ail et à l'huile d'olive",
                DESCRIPTION: "Un plat italien simple et savoureux",
                TEMPS_PREPARATION: 10,
                TEMPS_CUISSON: 15,
                DIFFICULTE: "Novice",
                TYPE_REPAS: "déjeuner",
                ingredients: ["400g de pâtes", "4 gousses d'ail", "6 cuillères à soupe d'huile d'olive", "Persil frais", "Parmesan râpé", "Sel et poivre"],
                INSTRUCTIONS: `1. Faites cuire les pâtes selon les instructions du paquet.
2. Pendant ce temps, émincez finement l'ail.
3. Faites chauffer l'huile d'olive dans une grande poêle.
4. Ajoutez l'ail et faites-le dorer légèrement.
5. Égouttez les pâtes et ajoutez-les à la poêle.
6. Mélangez bien, ajoutez le persil et le parmesan.
7. Assaisonnez et servez chaud.`
            }
        };

        return recipes[recipeId] || recipes[1];
    }

    // Fermer la modal
    if (closeModal) {
        closeModal.addEventListener('click', function() {
            recipeModal.style.display = 'none';
        });
    }

    // Fermer la modal en cliquant à l'extérieur
    if (recipeModal) {
        recipeModal.addEventListener('click', function(e) {
            if (e.target === recipeModal) {
                recipeModal.style.display = 'none';
            }
        });
    }

    // Gestionnaire pour le bouton de génération
    if (generateBtn) {
        generateBtn.addEventListener('click', function() {
            const formData = collectFormData();
            
            console.log('Données collectées:', formData);
            
            if (!validateFormData(formData)) {
                return;
            }

            // Afficher un message de chargement personnalisé selon le niveau
            const niveauTexts = {
                'debutant': 'Nous cherchons des recettes simples parfaites pour débuter !',
                'intermediaire': 'Recherche de recettes adaptées à votre niveau intermédiaire...',
                'avance': 'Exploration de toutes nos recettes, y compris les plus sophistiquées !'
            };

            recipesResults.style.display = 'block';
            recipesContainer.innerHTML = `
                <div class="loading-message">
                    <h3>🔍 Recherche de recettes en cours...</h3>
                    <p>${niveauTexts[formData.niveau_cuisine] || 'Recherche en cours...'}</p>
                </div>
            `;

            // Appel réel à l'API PHP
            fetch('search_recipes.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                console.log('Réponse du serveur:', data);
                
                // Gérer les deux formats de réponse possibles
                const recipes = data.recipes || data;
                displayRecipes(recipes);
                
                // Faire défiler vers les résultats
                recipesResults.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
                recipesContainer.innerHTML = `
                    <div class="error-message">
                        <h3>❌ Erreur lors de la recherche</h3>
                        <p>${error.message}</p>
                        <p>Veuillez vérifier votre connexion et réessayer.</p>
                    </div>
                `;
            });
        });
    }

    // Ajouter des animations au chargement
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observer tous les conteneurs
    document.querySelectorAll('.contain').forEach(container => {
        container.style.opacity = '0';
        container.style.transform = 'translateY(20px)';
        container.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(container);
    });
});