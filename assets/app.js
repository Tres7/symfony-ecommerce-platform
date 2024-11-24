import './bootstrap.js';
import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/app.css';

// Utilisation de Stimulus Bridge pour Symfony UX
import { startStimulusApp } from '@symfony/stimulus-bundle';

const app = startStimulusApp();

console.log('Stimulus app initialisée avec Symfony UX 🎉');
