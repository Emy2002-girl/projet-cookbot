document.addEventListener('DOMContentLoaded', function() {
    const ingredientSelect = document.getElementById('ingredient-select');
    const ingredientsList = document.getElementById('ingredients-list');
    
    if (ingredientSelect && ingredientsList) {
        // Fonction pour mettre à jour l'affichage des ingrédients sélectionnés
        function updateSelectedIngredients() {
            const selectedOptions = Array.from(ingredientSelect.selectedOptions);
            const selectedValues = selectedOptions.map(option => option.value).filter(value => value !== '');
            
            // Vider la liste actuelle
            ingredientsList.innerHTML = '';
            
            if (selectedValues.length === 0) {
                ingredientsList.innerHTML = '<span style="color: #6b7280; font-style: italic;">Aucun ingrédient sélectionné</span>';
                return;
            }
            
            // Créer les badges pour chaque ingrédient sélectionné
            selectedValues.forEach(ingredient => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'selected-item';
                
                const itemText = document.createElement('span');
                itemText.textContent = ingredient;
                
                const removeBtn = document.createElement('button');
                removeBtn.className = 'remove-item';
                removeBtn.innerHTML = '×';
                removeBtn.title = `Retirer ${ingredient}`;
                removeBtn.addEventListener('click', () => {
                    // Désélectionner l'option correspondante
                    const optionToRemove = Array.from(ingredientSelect.options).find(option => option.value === ingredient);
                    if (optionToRemove) {
                        optionToRemove.selected = false;
                        updateSelectedIngredients();
                    }
                });
                
                itemDiv.appendChild(itemText);
                itemDiv.appendChild(removeBtn);
                ingredientsList.appendChild(itemDiv);
            });
        }
        
        // Écouter les changements de sélection
        ingredientSelect.addEventListener('change', updateSelectedIngredients);
        
        // Initialiser l'affichage
        updateSelectedIngredients();
        
        // Ajouter un message d'aide
        const helpText = document.createElement('div');
        helpText.className = 'help-text';
        helpText.innerHTML = '💡 Maintenez <strong>Ctrl</strong> (ou <strong>Cmd</strong> sur Mac) pour sélectionner plusieurs ingrédients';
        ingredientSelect.parentNode.appendChild(helpText);
    }
});