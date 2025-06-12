<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MacrosChef - Générateur de Recettes</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    color: #333;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

header {
    text-align: center;
    margin-bottom: 40px;
    color: white;
}

header h1 {
    font-size: 3rem;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

header p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.form-container {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.form-container h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #4a5568;
    font-size: 1.5rem;
}

.macros-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.macro-input {
    text-align: center;
}

.macro-input label {
    display: block;
    font-weight: 600;
    margin-bottom: 10px;
    color: #4a5568;
    font-size: 1.1rem;
}

.input-group {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: #f7fafc;
    border-radius: 12px;
    padding: 15px;
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
}

.input-group:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.input-group input {
    border: none;
    background: transparent;
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    width: 80px;
    text-align: center;
    outline: none;
}

.unit {
    color: #718096;
    font-size: 0.9rem;
    font-weight: 500;
}

.calories-display {
    text-align: center;
    margin-bottom: 30px;
    padding: 20px;
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 12px;
    border: 2px solid #e2e8f0;
}

.calories-info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.calories-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 1.1rem;
}

.calories-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
}

.meal-type {
    margin-bottom: 30px;
}

.meal-type label {
    display: block;
    font-weight: 600;
    margin-bottom: 10px;
    color: #4a5568;
    font-size: 1.1rem;
}

.meal-type select {
    width: 100%;
    padding: 15px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    background: #f7fafc;
    color: #4a5568;
    outline: none;
    transition: all 0.3s ease;
}

.meal-type select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.dietary-needs {
    margin-bottom: 30px;
}

.dietary-needs label {
    display: block;
    font-weight: 600;
    margin-bottom: 10px;
    color: #4a5568;
    font-size: 1.1rem;
}

.dietary-needs select {
    width: 100%;
    padding: 15px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    background: #f7fafc;
    color: #4a5568;
    outline: none;
    transition: all 0.3s ease;
}

.dietary-needs select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.generate-btn {
    width: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 18px 30px;
    border-radius: 12px;
    font-size: 1.2rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.generate-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.generate-btn:active {
    transform: translateY(0);
}

.loading {
    text-align: center;
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #e2e8f0;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.results {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.results h3 {
    color: #4a5568;
    margin-bottom: 25px;
    text-align: center;
    font-size: 1.8rem;
}

.recipe-card {
    border: 2px solid #e2e8f0;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.recipe-card:hover {
    border-color: #667eea;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
}

.recipe-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
}

.recipe-description {
    color: #718096;
    margin-bottom: 15px;
    line-height: 1.6;
}

.dietary-badge {
    display: inline-block;
    background: #ebf4ff;
    color: #4c51bf;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 15px;
    border: 1px solid #c3dafe;
}

.recipe-macros {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.macro-badge {
    background: #f7fafc;
    border-radius: 8px;
    padding: 10px;
    text-align: center;
    border: 1px solid #e2e8f0;
}

.macro-value {
    font-size: 1.2rem;
    font-weight: 700;
    color: #667eea;
}

.macro-label {
    font-size: 0.8rem;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.recipe-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #4a5568;
}

.recipe-instructions {
    background: #f7fafc;
    border-radius: 10px;
    padding: 20px;
    margin-top: 15px;
}

.recipe-instructions h4 {
    color: #4a5568;
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.recipe-instructions p {
    line-height: 1.8;
    color: #2d3748;
    white-space: pre-line;
}

.error {
    background: #fed7d7;
    color: #c53030;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    border: 2px solid #feb2b2;
}

.hidden {
    display: none;
}

@media (max-width: 768px) {
    .container {
        padding: 15px;
    }
    
    header h1 {
        font-size: 2rem;
    }
    
    .form-container {
        padding: 25px;
    }
    
    .macros-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .recipe-macros {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .recipe-details {
        grid-template-columns: 1fr;
    }
}
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🍽️ MacrosChef</h1>
            <p>Atteignez Vos Macros Facilement !</p>
        </header>

        <main>
            <div class="form-container">
                <h2>Sélectionnez le macronutriment cible que vous souhaitez</h2>
                
                <form id="macrosForm">
                    <div class="macros-grid">
                        <div class="macro-input">
                            <label for="glucides">Glucides :</label>
                            <div class="input-group">
                                <input type="number" id="glucides" name="glucides" min="0" max="200" value="50" required>
                                <span class="unit">grammes</span>
                            </div>
                        </div>

                        <div class="macro-input">
                            <label for="proteines">Protéines :</label>
                            <div class="input-group">
                                <input type="number" id="proteines" name="proteines" min="0" max="100" value="30" required>
                                <span class="unit">grammes</span>
                            </div>
                        </div>

                        <div class="macro-input">
                            <label for="lipides">Lipides :</label>
                            <div class="input-group">
                                <input type="number" id="lipides" name="lipides" min="0" max="100" value="20" required>
                                <span class="unit">grammes</span>
                            </div>
                        </div>
                    </div>

                    <div class="calories-display">
                        <div class="calories-info">
                            <span class="calories-label">Estimation calories :</span>
                            <span id="caloriesEstimate" class="calories-value">390 kcal</span>
                        </div>
                    </div>

                    <div class="meal-type">
                        <label for="typeRepas">Type de repas :</label>
                        <select id="typeRepas" name="typeRepas" required>
                            <option value="">Sélectionnez un type de repas</option>
                            <option value="petit-déjeuner">Petit-déjeuner</option>
                            <option value="déjeuner">Déjeuner</option>
                            <option value="dîner">Dîner</option>
                            <option value="collation">Collation</option>
                            <option value="dessert">Dessert</option>
                        </select>
                    </div>

                    <div class="dietary-needs">
                        <label for="besoinAlimentaire">Besoins alimentaires :</label>
                        <select id="besoinAlimentaire" name="besoinAlimentaire">
                            <option value="">Aucune restriction</option>
                            <option value="vegetarien">Végétarien</option>
                            <option value="vegan">Végétalien</option>
                            <option value="sans_gluten">Sans gluten</option>
                            <option value="sans_lactose">Sans lactose</option>
                            <option value="paleo">Paléo</option>
                            <option value="keto">Keto</option>
                            <option value="low_carb">Faible en glucides</option>
                            <option value="high_protein">Riche en protéines</option>
                        </select>
                    </div>

                    <button type="submit" class="generate-btn">
                        🎯 Générer ma Recette
                    </button>
                </form>
            </div>

            <div id="loading" class="loading hidden">
                <div class="spinner"></div>
                <p>Génération de votre recette personnalisée...</p>
            </div>

            <div id="results" class="results hidden">
                <h3>Votre Recette Personnalisée</h3>
                <div id="recipeContent"></div>
            </div>

            <div id="error" class="error hidden">
                <p>Aucune recette trouvée pour ces critères. Essayez d'ajuster vos macros.</p>
            </div>
        </main>
    </div>

    <script src="script1.js"></script>
</body>
</html>