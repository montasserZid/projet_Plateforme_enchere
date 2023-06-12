
const imgElems = document.querySelectorAll('.ico-coeur img');


 
imgElems.forEach(function(imgElem) {
  imgElem.addEventListener('click', function() {

    if (imgElem.src == 'http://localhost/monta/projet/dembele/version-v2/MVC-examen/assets/img/icone-svg/heart-rempli.svg') {

    let idTimbre = imgElem.getAttribute('data-timbre');
    let idMembre = imgElem.getAttribute('data-membre');
    // console.log(isFilled);
      window.location.href = 'admin?rFavoris&idMembre='+idMembre+'&idTimbre='+idTimbre;
      isFilled = false;
   
    } else {
        let idTimbre = imgElem.getAttribute('data-timbre');
        let idMembre = imgElem.getAttribute('data-membre');
        window.location.href = 'admin?aFavoris&idMembre='+idMembre+'&idTimbre='+idTimbre;

    }
  });
});


document.querySelector('.bouton_recherche').addEventListener("click", function() {
  console.log('hello');
  let valeur_recherche = document.getElementById('recherche').value;
  console.log(valeur_recherche);
  document.querySelector('.nouveaux_arrivages').style.display = 'none';
  document.querySelector('.inputRecherche').value = valeur_recherche;

});
