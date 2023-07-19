var selections = {};

function selectOption(option, selectionId) {
  var selectionContainer = document.getElementById(selectionId);
  var cards = selectionContainer.getElementsByClassName('card');

  for (var i = 0; i < cards.length; i++) {
    var card = cards[i];
    if (card.classList.contains('selected')) {
      card.classList.remove('selected');
    }
  }

  var selectedCard = event.target.closest('.card');
  selectedCard.classList.add('selected');
  selections[selectionId] = option;

  // Check if the selected president is the same as the selected secretary
  var selectedPresident = selections['presidentSelection'];
  var selectedSecretary = selections['secretarySelection'];

  if (selectedPresident === 'Voto en blanco' || selectedSecretary === 'Voto en blanco') {
    document.getElementById('nextBtn').disabled = false;
  } else if (selectedPresident && selectedSecretary && selectedPresident === selectedSecretary) {
    document.getElementById('nextBtn').disabled = true;
    alert('No puedes seleccionar la misma lista para presidente y secretaria.');
  } else {
    document.getElementById('nextBtn').disabled = false;
  }
}


function nextPage(page) {
  if (page === 'secretary') {
    if (selections['presidentSelection']) {
      var container = document.getElementsByClassName('container')[0];
      container.innerHTML = `
        <h1>Elije Secretarias</h1>
        <div id="secretarySelection">
        <div class="card" onclick="selectOption('Lista PEV', 'secretarySelection')">
        <img src="2.png" class="card-img-top" alt="Logo Lista A">
            <div class="card-body">
            <h5 class="card-title">Lista PEV</h5>
              <p class="card-text">Candidatos:</p>
              <ul class="card-text">
                <li>Bradley Bazan - Secretario General</li>
                <li>Esteban Correa - Secretario de Deportes 2</li>
                <li>Josefina Figueroa - Secretaria de Finanzas 3</li>
                <li>Enzo Salvatierra - Secretario de Cultura</li>
                <li>Maria Pia - Secretaria de Difusión</li>
                <li>Angel Pazo - Secretario de Actas</li>
              </ul>
            </div>
          </div>
          <div class="card" onclick="selectOption('Lista MIVP', 'secretarySelection')">
            <img src="4.png" class="card-img-top" alt="Logo Lista B">
            <div class="card-body">
            <h5 class="card-title">Lista MIVP</h5>
              <p class="card-text">Candidatos:</p>
              <ul class="card-text">
                <li>Ludmila Carabajal - Secretaria General</li>
                <li>Lourdes D'Andrea - Secretaria de Actas</li>
                <li>Dorcas Mercado - Secretaria de Finanzas</li>
                <li>Juan Lucero - Secretario de 'Eventos'</li>
                <li>Benjamin Zarate - Secretario de Deportes</li>
                <li>Alma Gottardi - Secretaria de difusión</li>
              </ul>
            </div>
          </div>
          <div class="card" onclick="selectOption('Voto en blanco', 'secretarySelection')">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Blanco.svg/1200px-Blanco.svg.png" class="card-img-top" alt="Logo Voto en blanco">
            <div class="card-body">
              <h5 class="card-title">Voto en blanco</h5>
              <p class="card-text">No se selecciona ninguna candidata.</p>
            </div>
          </div>
        </div>
        <button type="button" class="btn btn-dark" id="nextBtn" onclick="nextPage('summary')" disabled>Siguiente</button>
      `;
    } else {
      alert('Por favor, elige una opción antes de continuar.');
    }
  } else if (page === 'summary') {
    var container = document.getElementsByClassName('container')[0];
    container.innerHTML = `
      <h1>Resumen de selecciones</h1>
      <p>Presidente: ${selections['presidentSelection']}</p>
      <p>Secretaria: ${selections['secretarySelection']}</p>
      <button onclick="changeSelection()">Cambiar selección</button>
      <button onclick="sendVote()">Enviar voto</button>
    `;
  }
}

function changeSelection() {
location.reload();
}


function sendVote() {
var selectedPresident = selections['presidentSelection'];
var selectedSecretary = selections['secretarySelection'];

// Obtener el DNI del usuario mediante un cuadro de diálogo de entrada
var dni = prompt("Por favor, ingresa tu DNI:");

if (dni) {
// Realizar solicitud AJAX al archivo PHP para verificar el DNI
var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
  if (this.readyState === 4 && this.status === 200) {
    var response = this.responseText;
    if (response === "true") {
      // El DNI está habilitado, guardar los votos en la base de datos
      var voteXhttp = new XMLHttpRequest();
      voteXhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          alert(this.responseText);
        }
      };
      voteXhttp.open("POST", "enviar_voto.php", true);
      voteXhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      voteXhttp.send("president=" + selectedPresident + "&secretary=" + selectedSecretary);

      selections = {};
      window.location.href = "/ELECCIONES2023/miweb-master/miweb-master/BOLETA/BOLETAUNICA/listo.html";
    } else if (response === "duplicate") {
      window.location.href = "/ELECCIONES2023/miweb-master/miweb-master/BOLETA/BOLETAUNICA/error.html";
      alert("El DNI ya se ingresó anteriormente.");
    } else if (response === "not_allowed") {
      alert("El DNI no está habilitado para votar.");
      window.location.href = "/ELECCIONES2023/miweb-master/miweb-master/BOLETA/BOLETAUNICA/error.html";
    } else {
      alert("El DNI no está en la base de datos.");
    }
  }
};
xhttp.open("POST", "dni.php", true);
xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhttp.send("dni=" + dni);
} else {
alert("Por favor, ingresa tu DNI.");
}
}