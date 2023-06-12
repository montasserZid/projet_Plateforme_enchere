// Récupérer la date actuelle et la date d'expiration de l'enchère
var now = new Date();
var dateFin = new Date("{{ enchere.dateFin }}");

// Calculer la différence entre les deux dates
var diff = dateFin.getTime() - now.getTime();

// Convertir la différence en jours, heures, minutes et secondes
var jours = Math.floor(diff / (1000 * 60 * 60 * 24));
var heures = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
var secondes = Math.floor((diff % (1000 * 60)) / 1000);

// Afficher le temps restant dans l'élément HTML correspondant
document.getElementById("temps_restant").innerHTML = "Termine dans " + jours + "J:" + heures + "H:" + minutes + "M:" + secondes + "S";