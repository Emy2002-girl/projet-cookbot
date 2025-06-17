document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("mealplan-form");
    const recipesResults = document.getElementById("recipes-results");

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const gender = document.getElementById("genre-select").value;
        const height = parseFloat(document.getElementById("hauteur").value);
        const weight = parseFloat(document.getElementById("poids").value);
        const age = parseInt(document.getElementById("age").value);
        const goal = document.getElementById("type-repas-select").value;
        const activityLevel = document.getElementById("niveau-activité-select").value;
        const dietaryNeeds = document.getElementById("besoin-alimentaires-select").value;
        const days = parseInt(document.getElementById("durre").value);

        if (isNaN(height) || isNaN(weight) || isNaN(age) || height <= 0 || weight <= 0 || age <= 0) {
            alert("Veuillez entrer des valeurs valides.");
            return;
        }

        recipesResults.innerHTML = '<p>Génération de votre plan alimentaire...</p>';

        fetch("mealplan.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                gender, height, weight, age, goal, activityLevel, dietary_needs: dietaryNeeds, days
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                recipesResults.innerHTML = `<p>${data.message}</p>`;
                return;
            }
            displayMealPlan(data.meal_plan, data.daily_totals, data.calories_target);
        });
    });

    function displayMealPlan(meal_plan, daily_totals, calories_target) {
        let html = `<h2>Objectif calorique quotidien : ${calories_target} kcal</h2>`;

        daily_totals.forEach(day => {
            html += `<div class="day">
                        <h3>Jour ${day.day_number}</h3>
                        <ul>
                            <li>Calories : ${day.total_calories} kcal</li>
                            <li>Protéines : ${day.total_proteins}g</li>
                            <li>Glucides : ${day.total_carbs}g</li>
                            <li>Lipides : ${day.total_fats}g</li>
                        </ul>
                     </div>`;
        });

        html += "<h3>Détail des recettes</h3>";
        meal_plan.forEach(recipe => {
            html += `<div class="recipe">
                        <h4>${recipe.recipe_title}</h4>
                        <ul>
                            <li>Type : ${recipe.meal_type}</li>
                            <li>Calories : ${recipe.calories} kcal</li>
                            <li>Protéines : ${recipe.proteins}g</li>
                            <li>Glucides : ${recipe.carbs}g</li>
                            <li>Lipides : ${recipe.fats}g</li>
                        </ul>
                     </div>`;
        });

        recipesResults.innerHTML = html;
    }
});