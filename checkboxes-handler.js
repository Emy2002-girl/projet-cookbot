document.addEventListener('DOMContentLoaded', function () {
    const ingredientCheckboxes = document.querySelectorAll('input[name="ingredients[]"]');
    const ingredientsCount = document.getElementById('ingredients-count');
    
    function updateIngredientsCount() {
        const checkedCount = Array.from(ingredientCheckboxes).filter(cb => cb.checked).length;
    }
})