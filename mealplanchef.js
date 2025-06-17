document.addEventListener("DOMContentLoaded", () => {
  // Référence aux éléments du formulaire
  const generateButton = document.getElementById("generate-recipes")
  const genreSelect = document.getElementById("genre-select")
  const hauteurInput = document.getElementById("hauteur")
  const poidsInput = document.getElementById("poids")
  const ageInput = document.getElementById("age")
  const objectifSelect = document.getElementById("type-repas-select")
  const activiteSelect = document.getElementById("niveau-activité-select")
  const regimeSelect = document.getElementById("besoin-alimentaires-select")
  const dureeInput = document.getElementById("duree")
  const recipesResults = document.getElementById("recipes-results")
  const recipesContainer = document.getElementById("recipes-container")
  const recipeModal = document.getElementById("recipe-modal")
  const modalContent = document.getElementById("modal-recipe-content")
  const closeModal = document.querySelector(".close-modal")

  // Script pour le menu utilisateur
  const userButton = document.getElementById("userButton")
  const userMenu = document.getElementById("userMenu")

  if (userButton) {
    userButton.addEventListener("click", () => {
      userMenu.classList.toggle("active")
    })

    // Fermer le menu si on clique ailleurs
    document.addEventListener("click", (event) => {
      if (!userButton.contains(event.target) && !userMenu.contains(event.target)) {
        userMenu.classList.remove("active")
      }
    })
  }

  // Vérifier que tous les éléments sont bien chargés
  if (
    !generateButton ||
    !genreSelect ||
    !hauteurInput ||
    !poidsInput ||
    !ageInput ||
    !objectifSelect ||
    !activiteSelect ||
    !regimeSelect ||
    !dureeInput ||
    !recipesResults ||
    !recipesContainer ||
    !recipeModal ||
    !modalContent
  ) {
    console.error("Certains éléments du formulaire n'ont pas été trouvés")
  }

  // Fermer la modal quand on clique sur X
  if (closeModal) {
    closeModal.addEventListener("click", () => {
      recipeModal.style.display = "none"
    })
  }

  // Fermer la modal quand on clique en dehors
  window.addEventListener("click", (event) => {
    if (event.target === recipeModal) {
      recipeModal.style.display = "none"
    }
  })

  // Gestionnaire d'événement pour le bouton de génération
  if (generateButton) {
    generateButton.addEventListener("click", () => {
      // Validation des entrées
      const gender = genreSelect ? genreSelect.value : "homme"
      const height = hauteurInput ? Number.parseFloat(hauteurInput.value) : 0
      const weight = poidsInput ? Number.parseFloat(poidsInput.value) : 0
      const age = ageInput ? Number.parseInt(ageInput.value) : 0
      const goal = objectifSelect ? objectifSelect.value : "maintien"
      const activityLevel = activiteSelect ? activiteSelect.value : "sedentaire"
      const dietaryNeeds = regimeSelect ? regimeSelect.value : "standard"
      const days = dureeInput ? Number.parseInt(dureeInput.value) || 7 : 7

      console.log("Données du formulaire:", {
        gender,
        height,
        weight,
        age,
        goal,
        activityLevel,
        dietaryNeeds,
        days,
      })

      if (isNaN(height) || isNaN(weight) || isNaN(age) || height <= 0 || weight <= 0 || age <= 0) {
        alert("Veuillez entrer des valeurs valides pour votre taille, poids et âge")
        return
      }

      // Afficher un indicateur de chargement
      recipesContainer.innerHTML = '<div class="loading">Génération de votre plan alimentaire...</div>'
      recipesResults.style.display = "block"

      // Envoyer la requête au serveur
      fetch("mealplanchef_process.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          gender: gender,
          height: height,
          weight: weight,
          age: age,
          goal: goal,
          activity_level: activityLevel,
          days: days,
          dietary_needs: dietaryNeeds,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          console.log("Réponse du serveur:", data)

          if (data.error) {
            recipesContainer.innerHTML = `<div class="error">${data.error}</div>`
            return
          }

          if (!data.success) {
            recipesContainer.innerHTML = `<div class="error">${data.message || "Une erreur est survenue lors de la génération du plan alimentaire."}</div>`
            return
          }

          // Afficher les résultats
          displayMealPlan(data.meal_plan, data.daily_totals, data.calories_target)
        })
        .catch((error) => {
          console.error("Erreur:", error)
          recipesContainer.innerHTML = `<div class="error">Une erreur est survenue lors de la génération du plan alimentaire: ${error.message}</div>`
        })
    })
  }

  // Fonction pour afficher le plan de repas
  function displayMealPlan(mealPlan, dailyTotals, caloriesTarget) {
    recipesContainer.innerHTML = ""

    // Créer un conteneur pour le résumé
    const summaryContainer = document.createElement("div")
    summaryContainer.className = "meal-plan-summary"
    summaryContainer.innerHTML = `
              <h3>Résumé de votre plan alimentaire</h3>
              <p>Objectif calorique quotidien: <strong>${caloriesTarget} kcal</strong></p>
              <p>Durée du plan: <strong>${dailyTotals.length} jours</strong></p>
          `
    recipesContainer.appendChild(summaryContainer)

    // Organiser les repas par jour
    const mealsByDay = {}
    mealPlan.forEach((meal) => {
      if (!mealsByDay[meal.day_number]) {
        mealsByDay[meal.day_number] = []
      }
      mealsByDay[meal.day_number].push(meal)
    })

    // Créer un accordéon pour chaque jour
    Object.keys(mealsByDay).forEach((day) => {
      const dayContainer = document.createElement("div")
      dayContainer.className = "meal-plan-day"

      // Trouver les totaux pour ce jour
      const dayTotal = dailyTotals.find((total) => total.day_number == day)

      // Créer l'en-tête du jour (accordéon)
      const dayHeader = document.createElement("div")
      dayHeader.className = "meal-plan-day-header"
      dayHeader.innerHTML = `
                  <h3>Jour ${day}</h3>
                  <div class="day-macros">
                      <span>Calories: ${dayTotal.total_calories} kcal</span>
                      <span>Protéines: ${dayTotal.total_proteins}g</span>
                      <span>Glucides: ${dayTotal.total_carbs}g</span>
                      <span>Lipides: ${dayTotal.total_fats}g</span>
                  </div>
                  <span class="accordion-icon">▼</span>
              `
      dayContainer.appendChild(dayHeader)

      // Créer le contenu du jour (initialement caché)
      const dayContent = document.createElement("div")
      dayContent.className = "meal-plan-day-content"
      dayContent.style.display = "none"

      // Ajouter chaque repas
      mealsByDay[day].forEach((meal) => {
        const mealCard = document.createElement("div")
        mealCard.className = "meal-card"
        mealCard.innerHTML = `
                      <h4>${formatMealType(meal.meal_type)}: ${meal.recipe_title}</h4>
                      <div class="meal-macros">
                          <span>Calories: ${meal.calories} kcal</span>
                          <span>Protéines: ${meal.proteins}g</span>
                          <span>Glucides: ${meal.carbs}g</span>
                          <span>Lipides: ${meal.fats}g</span>
                      </div>
                      <button class="view-recipe-btn" data-recipe-id="${meal.recipe_id}">Voir la recette</button>
                  `
        dayContent.appendChild(mealCard)
      })

      dayContainer.appendChild(dayContent)
      recipesContainer.appendChild(dayContainer)

      // Ajouter l'événement pour ouvrir/fermer l'accordéon
      dayHeader.addEventListener("click", () => {
        if (dayContent.style.display === "none") {
          dayContent.style.display = "block"
          dayHeader.querySelector(".accordion-icon").textContent = "▲"
        } else {
          dayContent.style.display = "none"
          dayHeader.querySelector(".accordion-icon").textContent = "▼"
        }
      })
    })

    // Ajouter des événements pour les boutons "Voir la recette"
    document.querySelectorAll(".view-recipe-btn").forEach((button) => {
      button.addEventListener("click", (e) => {
        const recipeId = e.target.getAttribute("data-recipe-id")
        const recipe = mealPlan.find((meal) => meal.recipe_id == recipeId)
        showRecipeDetails(recipe)
      })
    })

    // Ajouter des boutons d'action pour le plan complet
    const actionsContainer = document.createElement("div")
    actionsContainer.className = "meal-plan-actions"
    actionsContainer.innerHTML = `
              <button id="save-meal-plan" class="action-btn">Sauvegarder ce plan</button>
              <button id="shopping-list" class="action-btn">Générer liste de courses</button>
              <button id="print-meal-plan" class="action-btn">Imprimer le plan</button>
          `
    recipesContainer.appendChild(actionsContainer)

    // Ajouter des événements pour les boutons d'action
    document.getElementById("save-meal-plan").addEventListener("click", () => {
      alert("Fonctionnalité à implémenter: Sauvegarder le plan de repas")
    })

    document.getElementById("shopping-list").addEventListener("click", () => {
      generateShoppingList(mealPlan)
    })

    document.getElementById("print-meal-plan").addEventListener("click", () => {
      printMealPlan(mealPlan, dailyTotals, caloriesTarget)
    })
  }

  // Fonction pour formater le type de repas
  function formatMealType(mealType) {
    switch (mealType) {
      case "petit-déjeuner":
        return "Petit-déjeuner"
      case "déjeuner":
        return "Déjeuner"
      case "dîner":
        return "Dîner"
      case "collation":
        return "Collation"
      default:
        return mealType
    }
  }

  // Fonction pour afficher les détails d'une recette
  function showRecipeDetails(recipe) {
    // Formater les ingrédients
    let ingredientsList = ""
    if (recipe.ingredients && recipe.ingredients.length > 0) {
      ingredientsList = '<ul class="ingredients-list">'
      recipe.ingredients.forEach((ingredient) => {
        ingredientsList += `<li>${ingredient.QUANTITE || ""} ${ingredient.UNITE || ""} ${ingredient.NOM}</li>`
      })
      ingredientsList += "</ul>"
    }

    // Formater les instructions
    let instructionsList = ""
    if (recipe.instructions) {
      const instructions = recipe.instructions.split("\n")
      instructionsList = '<ol class="instructions-list">'
      instructions.forEach((instruction) => {
        if (instruction.trim() !== "") {
          instructionsList += `<li>${instruction.trim()}</li>`
        }
      })
      instructionsList += "</ol>"
    }

    // Remplir la modal
    modalContent.innerHTML = `
              <h2>${recipe.recipe_title}</h2>
              <div class="recipe-details">
                  <div class="recipe-macros-detail">
                      <h3>Macronutriments</h3>
                      <p><strong>Calories:</strong> ${recipe.calories} kcal</p>
                      <p><strong>Protéines:</strong> ${recipe.proteins}g</p>
                      <p><strong>Glucides:</strong> ${recipe.carbs}g</p>
                      <p><strong>Lipides:</strong> ${recipe.fats}g</p>
                  </div>
              </div>
              <div class="recipe-content">
                  <h3>Ingrédients</h3>
                  ${ingredientsList}
                  <h3>Instructions</h3>
                  ${instructionsList}
              </div>
              <div class="recipe-actions">
                  <button class="save-recipe" data-recipe-id="${recipe.recipe_id}">Sauvegarder la recette</button>
                  <button class="add-to-shopping" data-recipe-id="${recipe.recipe_id}">Ajouter à la liste de courses</button>
              </div>
          `

    // Ajouter des gestionnaires d'événements pour les boutons d'action
    const saveButton = modalContent.querySelector(".save-recipe")
    if (saveButton) {
      saveButton.addEventListener("click", () => {
        saveRecipe(recipe.recipe_id)
      })
    }

    const shoppingButton = modalContent.querySelector(".add-to-shopping")
    if (shoppingButton) {
      shoppingButton.addEventListener("click", () => {
        addToShoppingList(recipe)
      })
    }

    // Afficher la modal
    recipeModal.style.display = "flex"
  }

  // Fonction pour sauvegarder une recette
  function saveRecipe(recipeId) {
    alert("Fonctionnalité à implémenter: Sauvegarder la recette " + recipeId)
  }

  // Fonction pour ajouter à la liste de courses
  function addToShoppingList(recipe) {
    alert("Fonctionnalité à implémenter: Ajouter la recette à la liste de courses")
  }

  // Fonction pour générer une liste de courses pour tout le plan
  function generateShoppingList(mealPlan) {
    // Créer un objet pour stocker les ingrédients
    const ingredients = {}

    // Parcourir toutes les recettes et agréger les ingrédients
    mealPlan.forEach((meal) => {
      if (meal.ingredients && meal.ingredients.length > 0) {
        meal.ingredients.forEach((ingredient) => {
          const name = ingredient.NOM
          const quantity = Number.parseFloat(ingredient.QUANTITE) || 0
          const unit = ingredient.UNITE || ""

          if (!ingredients[name]) {
            ingredients[name] = { quantity, unit }
          } else {
            // Si l'unité est la même, additionner les quantités
            if (ingredients[name].unit === unit) {
              ingredients[name].quantity += quantity
            } else {
              // Sinon, garder séparé
              if (!ingredients[name].alternatives) {
                ingredients[name].alternatives = []
              }
              ingredients[name].alternatives.push({ quantity, unit })
            }
          }
        })
      }
    })

    // Créer la modal pour la liste de courses
    modalContent.innerHTML = `
              <h2>Liste de courses</h2>
              <div class="shopping-list-content">
                  <ul class="shopping-list">
                      ${Object.entries(ingredients)
                        .map(([name, info]) => {
                          let item = `<li>
                              <input type="checkbox" id="item-${name.replace(/\s+/g, "-")}">
                              <label for="item-${name.replace(/\s+/g, "-")}">
                                  ${info.quantity > 0 ? `${info.quantity} ${info.unit} de ` : ""}${name}
                              </label>
                          </li>`

                          if (info.alternatives && info.alternatives.length > 0) {
                            info.alternatives.forEach((alt, index) => {
                              item += `<li>
                                      <input type="checkbox" id="item-${name.replace(/\s+/g, "-")}-${index}">
                                      <label for="item-${name.replace(/\s+/g, "-")}-${index}">
                                          ${alt.quantity > 0 ? `${alt.quantity} ${alt.unit} de ` : ""}${name}
                                      </label>
                                  </li>`
                            })
                          }

                          return item
                        })
                        .join("")}
                  </ul>
              </div>
              <div class="shopping-list-actions">
                  <button id="print-shopping-list" class="action-btn">Imprimer la liste</button>
                  <button id="email-shopping-list" class="action-btn">Envoyer par email</button>
              </div>
          `

    // Ajouter des événements pour les boutons d'action
    document.getElementById("print-shopping-list").addEventListener("click", () => {
      window.print()
    })

    document.getElementById("email-shopping-list").addEventListener("click", () => {
      alert("Fonctionnalité à implémenter: Envoyer la liste par email")
    })

    // Afficher la modal
    recipeModal.style.display = "flex"
  }

  // Fonction pour imprimer le plan de repas
  function printMealPlan(mealPlan, dailyTotals, caloriesTarget) {
    const printWindow = window.open("", "_blank")

    // Organiser les repas par jour
    const mealsByDay = {}
    mealPlan.forEach((meal) => {
      if (!mealsByDay[meal.day_number]) {
        mealsByDay[meal.day_number] = []
      }
      mealsByDay[meal.day_number].push(meal)
    })

    // Créer le contenu HTML à imprimer
    let printContent = `
              <html>
              <head>
                  <title>Plan de repas</title>
                  <style>
                      body { font-family: Arial, sans-serif; margin: 20px; }
                      h1 { color: #10b981; text-align: center; }
                      .summary { text-align: center; margin-bottom: 30px; }
                      .day { margin-bottom: 30px; border: 1px solid #ddd; padding: 15px; border-radius: 8px; }
                      .day-header { display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
                      .meal { margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #eee; }
                      .meal:last-child { border-bottom: none; }
                      .meal-title { font-weight: bold; margin-bottom: 5px; }
                      .meal-macros { color: #666; font-size: 0.9em; }
                      .day-total { background-color: #f9f9f9; padding: 10px; border-radius: 5px; margin-top: 15px; }
                      @media print {
                          .day { page-break-inside: avoid; }
                      }
                  </style>
              </head>
              <body>
                  <h1>Votre Plan de Repas</h1>
                  <div class="summary">
                      <p>Objectif calorique quotidien: <strong>${caloriesTarget} kcal</strong></p>
                      <p>Durée du plan: <strong>${dailyTotals.length} jours</strong></p>
                  </div>
          `

    // Ajouter chaque jour
    Object.keys(mealsByDay).forEach((day) => {
      const dayTotal = dailyTotals.find((total) => total.day_number == day)

      printContent += `
                  <div class="day">
                      <div class="day-header">
                          <h2>Jour ${day}</h2>
                      </div>
              `

      // Ajouter chaque repas
      mealsByDay[day].forEach((meal) => {
        printContent += `
                      <div class="meal">
                          <div class="meal-title">${formatMealType(meal.meal_type)}: ${meal.recipe_title}</div>
                          <div class="meal-macros">
                              Calories: ${meal.calories} kcal | 
                              Protéines: ${meal.proteins}g | 
                              Glucides: ${meal.carbs}g | 
                              Lipides: ${meal.fats}g
                          </div>
                      </div>
                  `
      })

      // Ajouter le total du jour
      printContent += `
                  <div class="day-total">
                      <strong>Total du jour:</strong> 
                      Calories: ${dayTotal.total_calories} kcal | 
                      Protéines: ${dayTotal.total_proteins}g | 
                      Glucides: ${dayTotal.total_carbs}g | 
                      Lipides: ${dayTotal.total_fats}g
                  </div>
                  </div>
              `
    })

    printContent += `
              </body>
              </html>
          `

    printWindow.document.open()
    printWindow.document.write(printContent)
    printWindow.document.close()

    // Attendre que le contenu soit chargé avant d'imprimer
    printWindow.onload = () => {
      printWindow.print()
    }
  }
})
