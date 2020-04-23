// Ecoute les evenements change sur le conteneur upload
document.querySelector('.added__upload').addEventListener('change',(event) => {
let my_element = event.target;
let fichierSelectionne = my_element.files[0];
const idPhoto =  my_element.getAttribute("img");
document.getElementById(idPhoto).src = window.URL.createObjectURL(fichierSelectionne);
});
