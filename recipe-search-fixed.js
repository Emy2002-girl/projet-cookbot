document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM chargé, initialisation du script...")
  
    // Vérifier que tous les éléments nécessaires existent
    const requiredElements = [
      "generate-recipes",
      "generate-recipe",
      "portion",
      "duree-select",
      "niveau-cuisine-select",
      "besoin-alimentaires-select",
    ]
  
    const missingElements = []
    for (const id of requiredElements) {
      if (!document.getElementById(id)) {
        missingElements.push(id)
      }
    }
  
    if (missingElements.length > 0) {
      console.error("Éléments manquants:", missingElements)
    } else {
      console.log("Tous les éléments requis sont présents")
    }
  
    // Ajouter l'écouteur d'événement au bouton de génération
    const generateButton = document.getElementById("generate-recipes")
    if (generateButton) {
      generateButton.addEventListener("click", (e) => {
        e.preventDefault() // Empêcher la soumission du formulaire
        console.log("Bouton générer cliqué")
        generateRecipe()
      })
      console.log("Écouteur ajouté au bouton générer")
    } else {
      console.error("Bouton générer non trouvé")
    }
  
    // Gestion du bouton "Commencer à cuisiner" dans le hero
    const startBtn = document.getElementById("startBtn")
    if (startBtn) {
      startBtn.addEventListener("click", () => {
        document.getElementById("generate-recipe").scrollIntoView({
          behavior: "smooth",
          block: "center",
        })
        setTimeout(() => {
          document.getElementById("generate-recipe").focus()
        }, 800)
      })
    }
  
    // Gestion du bouton "Commencer gratuitement" dans le footer
    const commencerBtn = document.querySelector(".commencerr")
    if (commencerBtn) {
      commencerBtn.addEventListener("click", () => {
        document.getElementById("generate-recipe").scrollIntoView({
          behavior: "smooth",
          block: "center",
        })
        setTimeout(() => {
          document.getElementById("generate-recipe").focus()
        }, 800)
      })
    }
  
    // Validation en temps réel pour les portions
    const portionInput = document.getElementById("portion")
    if (portionInput) {
      portionInput.addEventListener("input", function () {
        const value = Number.parseInt(this.value)
        if (value < 1) this.value = 1
        if (value > 20) this.value = 20
      })
    }
  })
  
  async function generateRecipe() {
    console.log("Fonction generateRecipe appelée")
  
    // Récupérer TOUS les champs du formulaire avec les bons IDs
    const demande = document.getElementById("generate-recipe").value.trim() || ""
    const portions = Number.parseInt(document.getElementById("portion").value) || 2
    const duree = Number.parseInt(document.getElementById("duree-select").value) || 30
    const niveau = document.getElementById("niveau-cuisine-select").value || "debutant"
    const regime = document.getElementById("besoin-alimentaires-select").value || "standard"
  
    console.log("Données collectées:", { demande, portions, duree, niveau, regime })
  
    // Validation des champs
    if (!demande) {
      alert("Veuillez entrer le nom d'une recette ou un type de plat")
      document.getElementById("generate-recipe").focus()
      return
    }
  
    if (portions < 1 || portions > 20) {
      alert("Le nombre de portions doit être entre 1 et 20")
      document.getElementById("portion").focus()
      return
    }
  
    if (!duree) {
      alert("Veuillez sélectionner une durée")
      document.getElementById("duree-select").focus()
      return
    }
  
    if (!niveau) {
      alert("Veuillez sélectionner votre niveau")
      document.getElementById("niveau-cuisine-select").focus()
      return
    }
  
    // Créer ou récupérer la zone de résultats
    let resultsContainer = document.getElementById("recipe-results")
    if (!resultsContainer) {
      resultsContainer = document.createElement("div")
      resultsContainer.id = "recipe-results"
      resultsContainer.className = "recipe-results-container"
  
      // Insérer AVANT la section footer
      const footerSection = document.querySelector("footer")
      if (footerSection) {
        footerSection.parentNode.insertBefore(resultsContainer, footerSection)
      } else {
        // Fallback: insérer après le formulaire
        const form = document.getElementById("recipe-generator-form")
        form.parentNode.insertBefore(resultsContainer, form.nextSibling)
      }
    }
  
    // Afficher un indicateur de chargement
    resultsContainer.innerHTML = `
          <div class="contain">
              <div class="loading-container">
                  <div class="loading-spinner"></div>
                  <h3>Recherche de la recette parfaite...</h3>
                  <div class="search-info">
                      <p><strong>Recherche:</strong> ${demande}</p>
                      <p><strong>Portions:</strong> ${portions}</p>
                      <p><strong>Durée max:</strong> ${duree} minutes</p>
                      <p><strong>Niveau:</strong> ${niveau}</p>
                      <p><strong>Régime:</strong> ${regime}</p>
                  </div>
              </div>
          </div>
      `
  
    // Faire défiler vers les résultats
    resultsContainer.scrollIntoView({ behavior: "smooth", block: "start" })
  
    try {
      console.log("Envoi de la requête vers generate_recipe_compatible.php")
  
      const response = await fetch("searche_recipes.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({
          demande: demande,
          portions: portions,
          duree: duree,
          niveau: niveau,
          regime: regime,
        }),
      })
  
      console.log("Statut de la réponse:", response.status)
  
      if (!response.ok) {
        throw new Error(`Erreur HTTP: ${response.status}`)
      }
  
      const result = await response.json()
      console.log("Résultat reçu:", result)
  
      if (result.success && result.recipeName) {
        showRecipeResult(result, resultsContainer)
      } else if (result.error) {
        showError(result.error, result.suggestion, result.debug, resultsContainer)
      } else {
        showError("Format de réponse inattendu", null, null, resultsContainer)
      }
    } catch (error) {
      console.error("Erreur JS Fetch:", error)
      showError("Impossible de se connecter au serveur : " + error.message, null, null, resultsContainer)
    }
  }
  
  function showRecipeResult(recipe, container) {
    console.log("Affichage de la recette:", recipe.recipeName)
  
    container.innerHTML = `
          <div class="contain recipe-result-card">
              <div class="recipe-header">
                  <div class="case">✓</div>
                  <h2>${recipe.recipeName}</h2>
                  <div class="recipe-meta">
                      <span class="meta-item">
                          <i class="fas fa-clock"></i>
                          Préparation: ${recipe.prepTime} min
                      </span>
                      <span class="meta-item">
                          <i class="fas fa-fire"></i>
                          Cuisson: ${recipe.cookTime} min
                      </span>
                      <span class="meta-item">
                          <i class="fas fa-users"></i>
                          ${recipe.portions} portion(s)
                      </span>
                      <span class="meta-item difficulty-${recipe.difficulty.toLowerCase().replace(/[éèê]/g, "e")}">
                          <i class="fas fa-star"></i>
                          ${recipe.difficulty}
                      </span>
                  </div>
                  <p class="recipe-description">${recipe.description}</p>
              </div>
              
              <div class="recipe-content">
                  <div class="ingredients-section">
                      <h3><i class="fas fa-list"></i> Ingrédients</h3>
                      <ul class="ingredients-list">
                          ${recipe.ingredients
                            .filter((ingredient) => ingredient.trim() !== "")
                            .map(
                              (ingredient) => `
                              <li class="ingredient-item">
                                  <i class="fas fa-check-circle"></i>
                                  <span>${ingredient}</span>
                              </li>
                          `,
                            )
                            .join("")}
                      </ul>
                  </div>
                  
                  <div class="instructions-section">
                      <h3><i class="fas fa-utensils"></i> Instructions</h3>
                      <ol class="instructions-list">
                          ${recipe.instructions
                            .filter((instruction) => instruction.trim() !== "")
                            .map(
                              (instruction, index) => `
                              <li class="instruction-item">
                                  <span class="step-number">${index + 1}</span>
                                  <span class="step-text">${instruction}</span>
                              </li>
                          `,
                            )
                            .join("")}
                      </ol>
                  </div>
              </div>
              
              <div class="recipe-actions">
                  <button class="btn-save" onclick="saveRecipe(${recipe.originalRecipeId})">
                      <i class="fas fa-heart"></i>
                      Sauvegarder
                  </button>
                  <button class="btn-share" onclick="shareRecipe('${recipe.recipeName}')">
                      <i class="fas fa-share"></i>
                      Partager
                  </button>
                  <button class="btn-print" onclick="printRecipe()">
                      <i class="fas fa-print"></i>
                      Imprimer
                  </button>
                  <button class="btn-new-search" onclick="newSearch()">
                      <i class="fas fa-search"></i>
                      Nouvelle recherche
                  </button>
              </div>
          </div>
      `
  }
  
  function showError(message, suggestion, debug, container) {
    console.log("Affichage d'erreur:", message)
  
    const suggestionHtml = suggestion ? `<p class="suggestion"><strong>Suggestion:</strong> ${suggestion}</p>` : ""
    let debugHtml = ""
  
    if (debug && debug.search_criteria) {
      debugHtml = `
              <div class="search-criteria">
                  <h4>Critères de recherche utilisés:</h4>
                  <ul>
                      <li><strong>Recette:</strong> ${debug.search_criteria.demande}</li>
                      <li><strong>Portions:</strong> ${debug.search_criteria.portions}</li>
                      <li><strong>Durée max:</strong> ${debug.search_criteria.duree} minutes</li>
                      <li><strong>Niveau:</strong> ${debug.search_criteria.niveau}</li>
                      <li><strong>Régime:</strong> ${debug.search_criteria.regime}</li>
                  </ul>
                  <p><strong>Recettes trouvées:</strong> ${debug.total_recipes_found}</p>
                  <p><strong>Après filtrage:</strong> ${debug.after_filtering}</p>
              </div>
          `
    }
  
    container.innerHTML = `
          <div class="contain error-result-card">
              <div class="error-container">
                  <div class="case error-case">✗</div>
                  <div class="error-icon">
                      <i class="fas fa-exclamation-triangle"></i>
                  </div>
                  <h3>Aucune recette trouvée</h3>
                  <p>${message}</p>
                  ${suggestionHtml}
                  ${debugHtml}
                  <button class="btn-retry" onclick="newSearch()">
                      <i class="fas fa-search"></i>
                      Modifier les critères
                  </button>
              </div>
          </div>
      `
  }
  
  function newSearch() {
    // Supprimer les résultats précédents
    const resultsContainer = document.getElementById("recipe-results")
    if (resultsContainer) {
      resultsContainer.remove()
    }
  
    // Remonter vers le formulaire
    document.getElementById("generate-recipe").scrollIntoView({
      behavior: "smooth",
      block: "center",
    })
  
    // Remettre le focus sur le champ de recherche
    document.getElementById("generate-recipe").focus()
  }
  
  // Fonctions utilitaires
  function saveRecipe(recipeId) {
    alert(`Recette ${recipeId} sauvegardée dans vos favoris !`)
  }
  
  function shareRecipe(recipeName) {
    if (navigator.share) {
      navigator.share({
        title: recipeName,
        text: `Découvrez cette délicieuse recette : ${recipeName}`,
        url: window.location.href,
      })
    } else {
      const url = window.location.href
      navigator.clipboard
        .writeText(url)
        .then(() => {
          alert("Lien copié dans le presse-papiers !")
        })
        .catch(() => {
          prompt("Copiez ce lien:", url)
        })
    }
  }
  
  function printRecipe() {
    const recipeContainer = document.querySelector(".recipe-result-card")
    if (!recipeContainer) {
      alert("Impossible d'imprimer la recette")
      return
    }
  
    const printWindow = window.open("", "_blank")
    printWindow.document.write(`
          <html>
              <head>
                  <title>Recette - CookBot</title>
                  <style>
                      body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
                      .recipe-header h2 { color: #333; font-size: 2em; margin-bottom: 15px; }
                      .recipe-meta { display: flex; gap: 15px; margin: 15px 0; flex-wrap: wrap; }
                      .meta-item { background: #f0f0f0; padding: 8px 15px; border-radius: 15px; font-size: 0.9em; }
                      .recipe-description { font-style: italic; margin: 15px 0; padding: 10px; background: #f9f9f9; }
                      .recipe-content { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin: 20px 0; }
                      .ingredients-section, .instructions-section { background: #f8f9fa; padding: 20px; border-radius: 10px; }
                      .ingredients-list, .instructions-list { padding-left: 0; list-style: none; }
                      .ingredient-item, .instruction-item { margin: 8px 0; padding: 5px 0; }
                      .step-number { background: #10B981; color: white; width: 25px; height: 25px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.8em; }
                      .recipe-actions { display: none; }
                      @media print { .recipe-actions { display: none !important; } }
                      @media (max-width: 600px) { .recipe-content { grid-template-columns: 1fr; } }
                  </style>
              </head>
              <body>
                  ${recipeContainer.innerHTML}
              </body>
          </html>
      `)
    printWindow.document.close()
    setTimeout(() => printWindow.print(), 500)
  }
  