class CookBotApp {
    constructor() {
        this.currentStep = 0;
        this.formData = {
            preferences: '',
            servings: '',
            time: '',
            level: '',
            dietary: '',
        };
        this.selectedIngredients = [];
        this.isGenerating = false;
        this.useFallback = true; // Activer le mode fallback par défaut
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.showHeroSection();
    }

    bindEvents() {
        // Bouton commencer
        document.getElementById('startBtn').addEventListener('click', () => {
            this.startForm();
        });

        // Bouton retour du téléphone
        document.getElementById('phoneBackBtn').addEventListener('click', () => {
            this.previousStep();
        });

        // Nouvelle recherche
        document.getElementById('newSearchBtn').addEventListener('click', () => {
            this.resetApp();
        });
    }

    showHeroSection() {
        document.getElementById('mainContent').style.display = 'block';
        document.getElementById('resultsSection').style.display = 'none';
        document.getElementById('progressSteps').innerHTML = '';
        document.getElementById('phoneContent').innerHTML = '';
    }

    startForm() {
        this.currentStep = 0;
        this.renderProgressSteps();
        this.renderCurrentStep();
    }

    renderProgressSteps() {
        const steps = [
            'Préférences',
            'Portions',
            'Temps',
            'Niveau',
            'Régime',
            'Ingrédients'
        ];

        const progressHTML = steps.map((step, index) => `
            <div class="step ${index <= this.currentStep ? 'active' : ''}">
                <div class="step-number">${index + 1}</div>
                <div class="step-label">${step}</div>
            </div>
        `).join('');

        document.getElementById('progressSteps').innerHTML = progressHTML;
    }

    renderCurrentStep() {
        const phoneContent = document.getElementById('phoneContent');
        
        switch(this.currentStep) {
            case 0:
                phoneContent.innerHTML = this.renderPreferencesStep();
                break;
            case 1:
                phoneContent.innerHTML = this.renderServingsStep();
                break;
            case 2:
                phoneContent.innerHTML = this.renderTimeStep();
                break;
            case 3:
                phoneContent.innerHTML = this.renderLevelStep();
                break;
            case 4:
                phoneContent.innerHTML = this.renderDietaryStep();
                break;
            case 5:
                phoneContent.innerHTML = this.renderIngredientsStep();
                break;
        }

        this.bindStepEvents();
    }

    renderPreferencesStep() {
        return `
            <div class="form-step">
                <h3>Quelles sont vos préférences culinaires ?</h3>
                <textarea 
                    id="preferences" 
                    placeholder="Décrivez ce que vous aimez cuisiner, vos saveurs préférées..."
                    rows="4"
                >${this.formData.preferences}</textarea>
                <button class="btn btn-primary" onclick="app.nextStep()">Continuer</button>
            </div>
        `;
    }

    renderServingsStep() {
        const options = [
            '1 personne',
            '2 personnes', 
            '3-4 personnes',
            '5-6 personnes',
            'Plus de 6 personnes'
        ];

        return `
            <div class="form-step">
                <h3>Pour combien de personnes ?</h3>
                <div class="options-grid">
                    ${options.map(option => `
                        <button class="option-btn ${this.formData.servings === option ? 'selected' : ''}" 
                                data-value="${option}">
                            ${option}
                        </button>
                    `).join('')}
                </div>
                <button class="btn btn-primary" onclick="app.nextStep()">Continuer</button>
            </div>
        `;
    }

    renderTimeStep() {
        const options = [
            'Moins de 15 minutes',
            '15-30 minutes',
            '30-60 minutes', 
            '1-2 heures',
            'Plus de 2 heures'
        ];

        return `
            <div class="form-step">
                <h3>Combien de temps avez-vous ?</h3>
                <div class="options-grid">
                    ${options.map(option => `
                        <button class="option-btn ${this.formData.time === option ? 'selected' : ''}" 
                                data-value="${option}">
                            ${option}
                        </button>
                    `).join('')}
                </div>
                <button class="btn btn-primary" onclick="app.nextStep()">Continuer</button>
            </div>
        `;
    }

    renderLevelStep() {
        const options = [
            'Débutant',
            'Intermédiaire',
            'Avancé',
            'Expert'
        ];

        return `
            <div class="form-step">
                <h3>Quel est votre niveau de cuisine ?</h3>
                <div class="options-grid">
                    ${options.map(option => `
                        <button class="option-btn ${this.formData.level === option ? 'selected' : ''}" 
                                data-value="${option}">
                            ${option}
                        </button>
                    `).join('')}
                </div>
                <button class="btn btn-primary" onclick="app.nextStep()">Continuer</button>
            </div>
        `;
    }

    renderDietaryStep() {
        const options = [
            'Aucune',
            'Végétarien',
            'Végétalien',
            'Sans gluten',
            'Sans lactose',
            'Halal',
            'Casher'
        ];

        return `
            <div class="form-step">
                <h3>Avez-vous des restrictions alimentaires ?</h3>
                <div class="options-grid">
                    ${options.map(option => `
                        <button class="option-btn ${this.formData.dietary === option ? 'selected' : ''}" 
                                data-value="${option}">
                            ${option}
                        </button>
                    `).join('')}
                </div>
                <button class="btn btn-primary" onclick="app.nextStep()">Continuer</button>
            </div>
        `;
    }

    renderIngredientsStep() {
        const commonIngredients = [
            'Tomates', 'Oignons', 'Ail', 'Carottes', 'Pommes de terre',
            'Courgettes', 'Poivrons', 'Champignons', 'Épinards', 'Brocolis',
            'Poulet', 'Bœuf', 'Porc', 'Saumon', 'Thon', 'Œufs',
            'Lait', 'Fromage', 'Beurre', 'Huile d\'olive', 'Riz', 'Pâtes'
        ];

        return `
            <div class="form-step">
                <h3>Quels ingrédients avez-vous ?</h3>
                <div class="ingredients-container">
                    <div class="selected-ingredients">
                        ${this.selectedIngredients.map(ingredient => `
                            <span class="ingredient-tag">
                                ${ingredient}
                                <button onclick="app.removeIngredient('${ingredient}')">&times;</button>
                            </span>
                        `).join('')}
                    </div>
                    <div class="ingredients-grid">
                        ${commonIngredients.map(ingredient => `
                            <button class="ingredient-btn ${this.selectedIngredients.includes(ingredient) ? 'selected' : ''}" 
                                    data-ingredient="${ingredient}">
                                ${ingredient}
                            </button>
                        `).join('')}
                    </div>
                </div>
                <button class="btn btn-primary" onclick="app.generateRecipes()" 
                        ${this.isGenerating ? 'disabled' : ''}>
                    ${this.isGenerating ? 'Génération...' : 'Générer mes recettes'}
                </button>
                
                ${this.useFallback ? `
                <div class="fallback-option">
                    <label>
                        <input type="checkbox" id="useFallbackCheck" ${this.useFallback ? 'checked' : ''}>
                        Utiliser des recettes de démonstration (sans API)
                    </label>
                </div>
                ` : ''}
            </div>
        `;
    }

    bindStepEvents() {
        // Gestion des boutons d'options
        document.querySelectorAll('.option-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const value = e.target.dataset.value;
                const step = this.getStepKey();
                
                // Désélectionner les autres boutons
                document.querySelectorAll('.option-btn').forEach(b => b.classList.remove('selected'));
                e.target.classList.add('selected');
                
                this.formData[step] = value;
            });
        });

        // Gestion des ingrédients
        document.querySelectorAll('.ingredient-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const ingredient = e.target.dataset.ingredient;
                this.toggleIngredient(ingredient);
            });
        });

        // Gestion du textarea
        const textarea = document.getElementById('preferences');
        if (textarea) {
            textarea.addEventListener('input', (e) => {
                this.formData.preferences = e.target.value;
            });
        }
        
        // Gestion de l'option fallback
        const fallbackCheck = document.getElementById('useFallbackCheck');
        if (fallbackCheck) {
            fallbackCheck.addEventListener('change', (e) => {
                this.useFallback = e.target.checked;
            });
        }
    }

    getStepKey() {
        const keys = ['preferences', 'servings', 'time', 'level', 'dietary'];
        return keys[this.currentStep];
    }

    toggleIngredient(ingredient) {
        const index = this.selectedIngredients.indexOf(ingredient);
        if (index > -1) {
            this.selectedIngredients.splice(index, 1);
        } else {
            this.selectedIngredients.push(ingredient);
        }
        this.renderCurrentStep();
    }

    removeIngredient(ingredient) {
        this.toggleIngredient(ingredient);
    }

    nextStep() {
        if (this.currentStep < 5) {
            this.currentStep++;
            this.renderProgressSteps();
            this.renderCurrentStep();
        }
    }

    previousStep() {
        if (this.currentStep > 0) {
            this.currentStep--;
            this.renderProgressSteps();
            this.renderCurrentStep();
        }
    }

    async generateRecipes() {
        if (this.isGenerating) return;
        
        this.isGenerating = true;
        this.renderCurrentStep();
        
        // Afficher la modal de chargement
        document.getElementById('loadingModal').style.display = 'flex';
        
        try {
            // Si le mode fallback est activé, utiliser directement les recettes de fallback
            if (this.useFallback) {
                console.log('Mode fallback activé, utilisation des recettes de démonstration');
                setTimeout(() => {
                    const fallbackRecipes = this.getFallbackRecipes();
                    this.showResults(fallbackRecipes);
                }, 1500); // Simuler un délai pour l'expérience utilisateur
                return;
            }
            
            console.log('Envoi des données:', {
                preferences: this.formData.preferences,
                servings: this.formData.servings,
                time: this.formData.time,
                level: this.formData.level,
                dietary: this.formData.dietary,
                ingredients: this.selectedIngredients,
            });

            // Définir un timeout pour la requête
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 secondes de timeout

            const response = await fetch('generate_recipes.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    preferences: this.formData.preferences,
                    servings: this.formData.servings,
                    time: this.formData.time,
                    level: this.formData.level,
                    dietary: this.formData.dietary,
                    ingredients: this.selectedIngredients,
                }),
                signal: controller.signal
            });

            clearTimeout(timeoutId);
            console.log('Statut de la réponse:', response.status);

            // Vérifier si la requête a réussi
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Erreur HTTP:', response.status, errorText);
                throw new Error(`Erreur HTTP: ${response.status} - ${errorText}`);
            }

            const data = await response.json();
            console.log('Données reçues:', data);
            
            // Vérifier si la réponse contient une erreur
            if (data.error) {
                throw new Error(data.error);
            }

            // S'assurer que data.recipes existe
            if (!data.recipes || !Array.isArray(data.recipes)) {
                throw new Error('Format de réponse invalide: recipes manquant ou incorrect');
            }

            this.showResults(data.recipes);
        } catch (error) {
            console.error('Erreur complète:', error);
            
            // En cas d'erreur, utiliser les recettes de fallback
            console.log('Utilisation des recettes de fallback suite à une erreur');
            const fallbackRecipes = this.getFallbackRecipes();
            this.showResults(fallbackRecipes);
            
            // Afficher un message d'erreur discret
            const loadingMessage = document.getElementById('loadingMessage');
            if (loadingMessage) {
                loadingMessage.innerHTML = 'Utilisation des recettes de démonstration suite à une erreur de connexion...';
            }
        } finally {
            this.isGenerating = false;
        }
    }

    getFallbackRecipes() {
        // Recettes de fallback basées sur les ingrédients disponibles
        const fallbackRecipes = [
            {
                title: 'Omelette aux fines herbes',
                description: 'Une omelette simple et délicieuse, parfaite pour un repas rapide',
                ingredients: [
                    '3 œufs frais',
                    '2 cuillères à soupe de lait',
                    '1 cuillère à soupe de beurre',
                    'Fines herbes fraîches (persil, ciboulette)',
                    'Sel et poivre'
                ],
                instructions: [
                    'Battez les œufs avec le lait dans un bol',
                    'Assaisonnez avec sel et poivre',
                    'Faites chauffer le beurre dans une poêle antiadhésive',
                    'Versez les œufs et laissez cuire 2-3 minutes',
                    'Ajoutez les fines herbes et pliez l\'omelette',
                    'Servez immédiatement'
                ],
                prepTime: '5 minutes',
                cookTime: '5 minutes',
                servings: this.formData.servings || '2 personnes',
                difficulty: 'Facile',
                tips: [
                    'Ne cuisez pas trop l\'omelette, elle doit rester moelleuse',
                    'Utilisez une poêle antiadhésive pour un meilleur résultat'
                ]
            },
            {
                title: 'Salade composée fraîche',
                description: 'Une salade colorée et nutritive avec les ingrédients de saison',
                ingredients: [
                    'Salade verte mélangée',
                    'Tomates cerises',
                    'Concombre',
                    'Carottes râpées',
                    'Vinaigrette à l\'huile d\'olive',
                    'Graines de tournesol'
                ],
                instructions: [
                    'Lavez et essorez la salade',
                    'Coupez les tomates cerises en deux',
                    'Émincez le concombre',
                    'Disposez tous les légumes dans un saladier',
                    'Ajoutez la vinaigrette juste avant de servir',
                    'Parsemez de graines de tournesol'
                ],
                prepTime: '10 minutes',
                cookTime: '0 minute',
                servings: this.formData.servings || '2-3 personnes',
                difficulty: 'Très facile',
                tips: [
                    'Ajoutez la vinaigrette au dernier moment',
                    'Variez les légumes selon la saison'
                ]
            }
        ];
        
        // Filtrer selon les restrictions alimentaires
        if (this.formData.dietary === 'Végétalien') {
            return fallbackRecipes.filter(recipe => !recipe.title.includes('Omelette'));
        }
        
        return fallbackRecipes;
    }

    showResults(recipes) {
        // Masquer la modal de chargement
        document.getElementById('loadingModal').style.display = 'none';
        
        // Masquer le contenu principal et afficher les résultats
        document.getElementById('mainContent').style.display = 'none';
        document.getElementById('resultsSection').style.display = 'block';
        
        // Générer le HTML des recettes
        const recipesHTML = recipes.map(recipe => `
            <div class="recipe-card">
                <div class="recipe-header">
                    <h3>${recipe.title}</h3>
                    <span class="recipe-difficulty">${recipe.difficulty}</span>
                </div>
                <p class="recipe-description">${recipe.description}</p>
                
                <div class="recipe-meta">
                    <span>⏱️ Préparation: ${recipe.prepTime}</span>
                    <span>🔥 Cuisson: ${recipe.cookTime}</span>
                    <span>👥 ${recipe.servings}</span>
                </div>
                
                <div class="recipe-section">
                    <h4>Ingrédients</h4>
                    <ul class="ingredients-list">
                        ${recipe.ingredients.map(ingredient => `<li>${ingredient}</li>`).join('')}
                    </ul>
                </div>
                
                <div class="recipe-section">
                    <h4>Instructions</h4>
                    <ol class="instructions-list">
                        ${recipe.instructions.map(instruction => `<li>${instruction}</li>`).join('')}
                    </ol>
                </div>
                
                ${recipe.tips && recipe.tips.length > 0 ? `
                    <div class="recipe-section">
                        <h4>💡 Conseils du chef</h4>
                        <ul class="tips-list">
                            ${recipe.tips.map(tip => `<li>${tip}</li>`).join('')}
                        </ul>
                    </div>
                ` : ''}
            </div>
        `).join('');
        
        document.getElementById('recipesGrid').innerHTML = recipesHTML;
    }

    resetApp() {
        this.currentStep = 0;
        this.formData = {
            preferences: '',
            servings: '',
            time: '',
            level: '',
            dietary: '',
        };
        this.selectedIngredients = [];
        this.isGenerating = false;
        this.showHeroSection();
    }
}

// Initialiser l'application
const app = new CookBotApp();