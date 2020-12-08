onload = init;

function init() {
  zeButton = document.getElementById("media_imageFile_file");
  zeButton.addEventListener("mouseover", createDOMElement);
}

function createDOMElement() {
  //Créer un élément 'div' + un noeud texte
  let zeDiv = document.createElement("div");

  //Crée un attribur pour la div
  //zeDiv.style ="background:green"; ou
  zeDiv.setAttribute("style", "background:red");

  let content = document.createTextNode(
    zeButton.value
  );

  //On ajoute le texte à la div
  zeDiv.appendChild(content);

  //On ajoute la div à l'id container de l index (existant)
  document.getElementById("container").appendChild(zeDiv);
}
