document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("macrosForm")
    const loading = document.getElementById("loading")
    const results = document.getElementById("results")
    const error = document.getElementById("error")
    const recipeContent = document.getElementById("recipeContent")
    const caloriesEstimate = document.getElementById("caloriesEstimate")
  
    // Initialiser le calcul des calories au chargement
    updateCaloriesEstimate()
  
    form.addEventListener("submit", async (e) => {
      e.preventDefault()
  
      // Récupérer les valeurs du formulaire
      const glucides = document.getElementById("glucides").value
      const proteines = document.getElementById("proteines").value
      const lipides = document.getElementById("lipides").value
      const typeRepas = document.getElementById("typeRepas").value
      const besoinAlimentaire = document.getElementById("besoinAlimentaire").value
  
      // Validation
      if (!typeRepas) {
        alert("Veuillez sélectionner un type de repas")
        return
      }
  
      // Masquer les résultats précédents et afficher le loading
      hideAllSections()
      loading.classList.remove("hidden")
  
      try {
        // Envoyer la requête au serveur PHP
        const formData = new FormData()
        formData.append("glucides", glucides)
        formData.append("proteines", proteines)
        formData.append("lipides", lipides)
        formData.append("typeRepas", typeRepas)
        formData.append("besoinAlimentaire", besoinAlimentaire)
  
        const response = await fetch("generate_recipe.php", {
          method: "POST",
          body: formData,
        })
  
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`)
        }
  
        const data = await response.json()
        console.log("Réponse du serveur:", data) // Pour déboguer
  
        // Masquer le loading
        loading.classList.add("hidden")
  
        if (data.success && data.recipes && data.recipes.length > 0) {
          displayRecipes(data.recipes)
          results.classList.remove("hidden")
        } else {
          console.log("Erreur ou aucune recette:", data.error || "Aucune recette trouvée")
          error.classList.remove("hidden")
        }
      } catch (err) {
        console.error("Erreur:", err)
        loading.classList.add("hidden")
        error.classList.remove("hidden")
      }
    })
  
    function hideAllSections() {
      loading.classList.add("hidden")
      results.classList.add("hidden")
      error.classList.add("hidden")
    }
  
    function displayRecipes(recipes) {
      // Vérifier que recipeContent existe avant de manipuler son innerHTML
      if (!recipeContent) {
        console.error("L'élément recipeContent n'existe pas dans le DOM")
        return
      }
  
      recipeContent.innerHTML = ""
  
      recipes.forEach((recipe) => {
        const recipeCard = createRecipeCard(recipe)
        recipeContent.appendChild(recipeCard)
      })
    }
  
    function createRecipeCard(recipe) {
      const card = document.createElement("div")
      card.className = "recipe-card"
  
      // Déterminer si on affiche un badge de régime alimentaire
      let dietaryBadge = ""
      if (recipe.REGIME_ALIMENTAIRE) {
        dietaryBadge = `<div class="dietary-badge">${recipe.REGIME_ALIMENTAIRE}</div>`
      }
  
      card.innerHTML = `
              <div class="recipe-title">${recipe.TITRE || "Recette sans titre"}</div>
              ${dietaryBadge}
              <div class="recipe-description">${recipe.DESCRIPTION || "Délicieuse recette adaptée à vos macros"}</div>
              
              <div class="recipe-macros">
                  <div class="macro-badge">
                      <div class="macro-value">${recipe.GLUCIDES || 0}g</div>
                      <div class="macro-label">Glucides</div>
                  </div>
                  <div class="macro-badge">
                      <div class="macro-value">${recipe.PROTEINES || 0}g</div>
                      <div class="macro-label">Protéines</div>
                  </div>
                  <div class="macro-badge">
                      <div class="macro-value">${recipe.LIPIDES || 0}g</div>
                      <div class="macro-label">Lipides</div>
                  </div>
                  <div class="macro-badge">
                      <div class="macro-value">${recipe.CALORIES || 0}</div>
                      <div class="macro-label">Calories</div>
                  </div>
              </div>
  
              <div class="recipe-details">
                  <div class="detail-item">
                      <span>⏱️</span>
                      <span>Préparation: ${recipe.TEMPS_PREPARATION || 0} min</span>
                  </div>
                  <div class="detail-item">
                      <span>🔥</span>
                      <span>Cuisson: ${recipe.TEMPS_CUISSON || 0} min</span>
                  </div>
                  <div class="detail-item">
                      <span>👨‍🍳</span>
                      <span>Difficulté: ${recipe.DIFFICULTE || "Novice"}</span>
                  </div>
                  <div class="detail-item">
                      <span>🍽️</span>
                      <span>Portions: ${recipe.PORTIONS || 1}</span>
                  </div>
              </div>
  
              ${
                recipe.INGREDIENTS
                  ? `
                  <div class="recipe-instructions">
                      <h4>🛒 Ingrédients:</h4>
                      <p>${recipe.INGREDIENTS}</p>
                  </div>
              `
                  : ""
              }
  
              ${
                recipe.INSTRUCTIONS
                  ? `
                  <div class="recipe-instructions">
                      <h4>📝 Instructions:</h4>
                      <p>${recipe.INSTRUCTIONS}</p>
                  </div>
              `
                  : ""
              }
          `
  
      return card
    }
  
    // Gestion des inputs pour les macros avec mise à jour des calories
    const inputs = document.querySelectorAll('input[type="number"]')
    inputs.forEach((input) => {
      input.addEventListener("input", () => {
        updateCaloriesEstimate()
      })
    })
  
    function updateCaloriesEstimate() {
      const glucides = Number.parseInt(document.getElementById("glucides").value) || 0
      const proteines = Number.parseInt(document.getElementById("proteines").value) || 0
      const lipides = Number.parseInt(document.getElementById("lipides").value) || 0
  
      // Calcul des calories (4 cal/g pour glucides et protéines, 9 cal/g pour lipides)
      const calories = glucides * 4 + proteines * 4 + lipides * 9
  
      // Mettre à jour l'affichage
      if (caloriesEstimate) {
        caloriesEstimate.textContent = `${calories} kcal`
      }
    }
  
    // Animation pour les inputs
    inputs.forEach((input) => {
      input.addEventListener("focus", function () {
        this.parentElement.style.transform = "scale(1.02)"
      })
  
      input.addEventListener("blur", function () {
        this.parentElement.style.transform = "scale(1)"
      })
    })
  })
  