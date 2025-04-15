import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

import { startStimulusApp } from '@symfony/stimulus-bundle';
import { Chart } from '@symfony/ux-chartjs';

// Démarrage de l'application Stimulus
const app = startStimulusApp();

// Ce contrôleur est nécessaire pour afficher le graphique
app.register('chart', Chart);