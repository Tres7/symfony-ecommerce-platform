import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/app.css';
import { startStimulusApp } from '@symfony/stimulus-bundle';
import { Application } from "stimulus";
import { definitionsFromContext } from "stimulus/webpack-helpers";

const app = startStimulusApp();
// Créez une instance de l'application Stimulus
const application = Application.start();

// Importez les contrôleurs Stimulus
// const context = require.context('./controllers', true, /\.js$/);
// application.load(definitionsFromContext(context));



console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
