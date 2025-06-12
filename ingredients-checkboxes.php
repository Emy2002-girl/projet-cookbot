<!-- Alternative avec checkboxes -->
<div class="contain">
    <div class="etapes">
        <div class="case">1</div>
        <h2 style="padding-top: 8px;">Ajoutez les ingrédients que vous avez à la maison.</h2>
        <p style="padding-top: 8px;">Cochez les ingrédients que vous avez dans votre cuisine.</p>
    </div>
    <div class="ingredients-checkboxes">
        <div class="ingredients-grid">
            <label class="ingredient-checkbox">
                <input type="checkbox" name="ingredients[]" value="Tomate">
                <span class="checkmark"></span>
                <span class="ingredient-name">Tomate</span>
            </label>
            <label class="ingredient-checkbox">
                <input type="checkbox" name="ingredients[]" value="Oeuf">
                <span class="checkmark"></span>
                <span class="ingredient-name">Œuf</span>
            </label>
            <label class="ingredient-checkbox">
                <input type="checkbox" name="ingredients[]" value="Farine">
                <span class="checkmark"></span>
                <span class="ingredient-name">Farine</span>
            </label>
            <label class="ingredient-checkbox">
                <input type="checkbox" name="ingredients[]" value="Lait">
                <span class="checkmark"></span>
                <span class="ingredient-name">Lait</span>
            </label>
            <label class="ingredient-checkbox">
                <input type="checkbox" name="ingredients[]" value="Poulet">
                <span class="checkmark"></span>
                <span class="ingredient-name">Poulet</span>
            </label>
            <label class="ingredient-checkbox">
                <input type="checkbox" name="ingredients[]" value="Riz">
                <span class="checkmark"></span>
                <span class="ingredient-name">Riz</span>
            </label>
            <label class="ingredient-checkbox">
                <input type="checkbox" name="ingredients[]" value="Oignon">
                <span class="checkmark"></span>
                <span class="ingredient-name">Oignon</span>
            </label>
            <label class="ingredient-checkbox">
                <input type="checkbox" name="ingredients[]" value="Ail">
                <span class="checkmark"></span>
                <span class="ingredient-name">Ail</span>
            </label>
            <label class="ingredient-checkbox">
                <input type="checkbox" name="ingredients[]" value="Huile d'olive">
                <span class="checkmark"></span>
                <span class="ingredient-name">Huile d'olive</span>
            </label>
            <label class="ingredient-checkbox">
                <input type="checkbox" name="ingredients[]" value="Sel">
                <span class="checkmark"></span>
                <span class="ingredient-name">Sel</span>
            </label>
            <label class="ingredient-checkbox">
                <input type="checkbox" name="ingredients[]" value="Poivre">
                <span class="checkmark"></span>
                <span class="ingredient-name">Poivre</span>
            </label>
            <label class="ingredient-checkbox">
                <input type="checkbox" name="ingredients[]" value="Beurre">
                <span class="checkmark"></span>
                <span class="ingredient-name">Beurre</span>
            </label>
        </div>
        
        <!-- Compteur d'ingrédients sélectionnés -->
        <div class="ingredients-counter">
            <span id="ingredients-count">0</span> ingrédient(s) sélectionné(s)
        </div>
    </div>
</div>