// Variables globales
let selectedTools = [];
let selectedTime = 5;

// Éléments DOM
const timeSlider = document.getElementById('timeSlider');
const timeValue = document.getElementById('timeValue');
const sliderProgress = document.getElementById('sliderProgress');
const toolCheckboxes = document.querySelectorAll('input[name="tools"]');

// Fonction pour mettre à jour la barre de progression du slider
function updateSliderProgress() {
    const value = timeSlider.value;
    const min = timeSlider.min;
    const max = timeSlider.max;
    const percentage = ((value - min) / (max - min)) * 100;
    sliderProgress.style.width = percentage + '%';
}

// Fonction pour mettre à jour l'affichage du temps
function updateTimeDisplay() {
    selectedTime = parseInt(timeSlider.value);
    timeValue.textContent = selectedTime;
    updateSliderProgress();
    saveToLocalStorage();
}

// Fonction pour gérer la sélection des outils
function handleToolSelection(checkbox) {
    const toolValue = checkbox.value;
    
    if (checkbox.checked) {
        if (!selectedTools.includes(toolValue)) {
            selectedTools.push(toolValue);
        }
    } else {
        selectedTools = selectedTools.filter(tool => tool !== toolValue);
    }
    
    // Effet visuel pour l'élément sélectionné
    const toolItem = checkbox.closest('.tool-item');
    if (checkbox.checked) {
        toolItem.style.backgroundColor = 'rgba(59, 130, 246, 0.1)';
        toolItem.style.borderRadius = '6px';
        toolItem.style.padding = '8px';
    } else {
        toolItem.style.backgroundColor = 'transparent';
        toolItem.style.padding = '0';
    }
    
    saveToLocalStorage();
    console.log('Outils sélectionnés:', selectedTools);
}

// Fonction pour sauvegarder dans localStorage
function saveToLocalStorage() {
    const preferences = {
        tools: selectedTools,
        time: selectedTime
    };
    localStorage.setItem('kitchenPreferences', JSON.stringify(preferences));
}

// Fonction pour charger depuis localStorage
function loadFromLocalStorage() {
    const saved = localStorage.getItem('kitchenPreferences');
    
    if (saved) {
        try {
            const preferences = JSON.parse(saved);
            
            // Restaurer les outils sélectionnés
            if (preferences.tools && Array.isArray(preferences.tools)) {
                preferences.tools.forEach(toolId => {
                    const checkbox = document.getElementById(toolId);
                    if (checkbox) {
                        checkbox.checked = true;
                        handleToolSelection(checkbox);
                    }
                });
            }
            
            // Restaurer le temps sélectionné
            if (preferences.time) {
                timeSlider.value = preferences.time;
                updateTimeDisplay();
            }
            
            console.log('Préférences chargées:', preferences);
        } catch (error) {
            console.error('Erreur lors du chargement des préférences:', error);
        }
    }
}

// Fonction pour obtenir toutes les sélections actuelles
function getCurrentSelections() {
    return {
        tools: selectedTools,
        time: selectedTime
    };
}

// Fonction pour réinitialiser toutes les sélections
function resetAllSelections() {
    // Réinitialiser les checkboxes
    toolCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
        const toolItem = checkbox.closest('.tool-item');
        toolItem.style.backgroundColor = 'transparent';
        toolItem.style.padding = '0';
    });
    
    // Réinitialiser le slider
    timeSlider.value = 15;
    updateTimeDisplay();
    
    // Vider le tableau des outils
    selectedTools = [];
    
    // Nettoyer localStorage
    localStorage.removeItem('kitchenPreferences');
    
    console.log('Toutes les sélections ont été réinitialisées');
}

// Fonction pour valider les sélections
function validateSelections() {
    const selections = getCurrentSelections();
    
    if (selections.tools.length === 0) {
        alert('Veuillez sélectionner au moins un ustensile de cuisine.');
        return false;
    }
    
    if (selections.time < 5) {
        alert('Veuillez sélectionner un temps d\'au moins 5 minutes.');
        return false;
    }
    
    return true;
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Charger les préférences sauvegardées
    loadFromLocalStorage();
    
    // Initialiser la barre de progression
    updateSliderProgress();
    
    // Event listener pour le slider de temps
    timeSlider.addEventListener('input', updateTimeDisplay);
    
    // Event listeners pour les checkboxes
    toolCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            handleToolSelection(this);
        });
    });
    
    // Ajouter des animations au survol
    const toolLabels = document.querySelectorAll('.checkbox-container');
    toolLabels.forEach(label => {
        label.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(2px)';
        });
        
        label.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
});

// Fonctions utilitaires supplémentaires
window.kitchenSelector = {
    getCurrentSelections,
    resetAllSelections,
    validateSelections,
    saveToLocalStorage,
    loadFromLocalStorage
};