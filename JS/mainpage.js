let numero_bicchieri = 8;
let dimensione_bicchiere = 0.25;

// Funzione per creare e aggiornare la barra delle calorie
async function progresso_barra(){
    const barra = document.getElementById("barra");
    const progresso = document.getElementById("progresso");

    // Messi tutti a 0.0 per avere delle variabili float
    let fabbisogno_calorico = 0.0;
    let calorie_prese = 0.0;
    let calorie_perse = 0.0;
    let calorie_colazione = 0.0;
    let calorie_pranzo = 0.0;
    let calorie_cena = 0.0;
    let calorie_spuntini = 0.0;

    await fetch("../PHP/requests/richiesta_dati.php", { method: 'GET' })
    .then(response => response.json())
    .then(data => {
                        // Inizializzazione delle variabili precedentemente preferite e che serviranno per la dimensione della barra
                        if(data["result"]){
                            fabbisogno_calorico = parseFloat(data["fabbisogno"]);
                            calorie_prese = parseFloat(data["guadagnate"]);
                            calorie_perse = parseFloat(data["perse"]);
                            calorie_colazione = parseFloat(data["colazione"]);
                            calorie_pranzo = parseFloat(data["pranzo"]);
                            calorie_cena = parseFloat(data["cena"]);
                            calorie_spuntini = parseFloat(data["spuntini"]);

                            if(cifre_dopo_virgola(fabbisogno_calorico))
                                fabbisogno_calorico = parseFloat(fabbisogno_calorico.toFixed(2));

                            if(cifre_dopo_virgola(calorie_prese))
                                calorie_prese = parseFloat(calorie_prese.toFixed(2));

                            if(cifre_dopo_virgola(calorie_perse))
                                calorie_perse = parseFloat(calorie_perse.toFixed(2));
                        }   
                        else
                            window.alert("Recupero dati non riuscito!");                
                  })
    .catch(error => window.alert(error));
    
    // Aggiorna il fabbisogno calorico includendo le calorie bruciate con le attività
    fabbisogno_calorico += parseFloat(calorie_perse.toFixed(2));

    crea_barra(progresso, barra, calorie_prese, fabbisogno_calorico);

    const colazione = document.getElementById("colazione");
    const pranzo = document.getElementById("pranzo");
    const cena = document.getElementById("cena");
    const spuntini = document.getElementById("spuntini");
    
    aggiorna_circonferenze(colazione, parseFloat(calorie_colazione), fabbisogno_calorico);
    aggiorna_circonferenze(pranzo, parseFloat(calorie_pranzo), fabbisogno_calorico);
    aggiorna_circonferenze(cena, parseFloat(calorie_cena), fabbisogno_calorico);
    aggiorna_circonferenze(spuntini, parseFloat(calorie_spuntini), fabbisogno_calorico);
}

// Funzione per aggiornare la barra delle calorie
function crea_barra(progresso, barra, calorie, fabbisogno_calorico){
    let dimensione_progresso = parseFloat(calorie * 100 / fabbisogno_calorico);
    dimensione_progresso = dimensione_progresso.toFixed(4);

    if(dimensione_progresso < 0)
        dimensione_progresso = 0;
    else {
        if(dimensione_progresso >= 90 && dimensione_progresso <= 100)
            progresso.style.backgroundColor = "lightblue";
        else{
            if(dimensione_progresso > 100 && dimensione_progresso <= 105){
                progresso.style.backgroundColor = "lightblue";
                dimensione_progresso = 100;
            } 
            else if(dimensione_progresso > 105){
                progresso.style.backgroundColor = "red";
                dimensione_progresso = 100;
            }
        }
    }

    progresso.style.width = dimensione_progresso + "%";

    let numero = fabbisogno_calorico;
    if(cifre_dopo_virgola(numero))
        numero = numero.toFixed(2);

    if(barra.getElementsByTagName("p").length > 0)
        barra.querySelector("p").textContent = calorie.toFixed(0) + " / " + numero + " Kcal";
    else{
        const p = document.createElement("p");
        p.textContent = calorie.toFixed(0) + " / " + numero + " Kcal";
        barra.appendChild(p);
    }
}

// Funzione per aggiornare le circonferenze delle varie parti del giorno
function aggiorna_circonferenze(circonferenza, calorie_circonferenza, fabbisogno_calorico){
    let percentuale = 0;
    if(circonferenza.id === "colazione")
        percentuale = 30 / 100;
    else{
        if(circonferenza.id === "pranzo")
            percentuale = 40 / 100;
        else{
            if(circonferenza.id === "cena")
                percentuale = 25 / 100;
            else if(circonferenza.id === "spuntini")
                percentuale = 5 / 100;
        }
    }

    circonferenza.style.strokeDashoffset = 252 - parseFloat(252 * calorie_circonferenza / (fabbisogno_calorico * percentuale));
    if(circonferenza.style.strokeDashoffset < -12.6) {
        circonferenza.style.strokeDashoffset = 0;
        circonferenza.style.stroke = "red";
    } 
    else{
        if(circonferenza.style.strokeDashoffset >= -12.6 && circonferenza.style.strokeDashoffset < 0) {
            circonferenza.style.strokeDashoffset = 0;
            circonferenza.style.stroke = "lightblue";
        }
        else if(circonferenza.style.strokeDashoffset >= 0 && circonferenza.style.strokeDashoffset < 25.2)
            circonferenza.style.stroke = "lightblue";
    }

    let percentuale_calorie = parseFloat(100 * calorie_circonferenza / (fabbisogno_calorico * percentuale));
    let identita = circonferenza.id + "_text";
    let text = document.getElementById(identita);
    text.textContent = percentuale_calorie.toFixed(2) + "%";
}

// Questa funzione inizializza il numero di bicchieri, la dimensione di ciascun bicchiere e il loro stato di riempimento
function inizializza_bicchieri(){
    // Fa una richiesta al server per ottenere questi dati dal file inizializza_bicchieri.php, quindi aggiorna l'interfaccia  
    // web di conseguenza
    fetch("../PHP/requests/inizializza_bicchieri.php", { method: 'GET' })
    .then(response => response.json())
    .then(data => {
                    numero_bicchieri = data["totali"];
                    let numero = data["bevuti"];
                    dimensione_bicchiere = parseFloat(data["dimensione"]);

                    if(cifre_dopo_virgola(dimensione_bicchiere))
                        dimensione_bicchiere = dimensione_bicchiere.toFixed(2); 
                    else
                        dimensione_bicchiere = dimensione_bicchiere.toFixed(0);

                    crea_bicchieri();
                    const msg_acqua = document.getElementById("msg_acqua");
                    let acqua_bevuta = dimensione_bicchiere * numero;

                    if(cifre_dopo_virgola(acqua_bevuta))
                        acqua_bevuta = acqua_bevuta.toFixed(2);
                    else
                        acqua_bevuta = acqua_bevuta.toFixed(0);

                    let stringa = acqua_bevuta + "L";
                    msg_acqua.textContent = stringa;
                    if(numero > 0){
                        numero--;
                        let identita = "a-" + numero;
                        const bicchiere = document.getElementById(identita);
                        let acqua = bicchiere.querySelector(".acqua");
                        acqua.style.height = "0%";
                        
                        modifica_bicchiere(bicchiere);
                    }
                  })
    .catch(error => window.alert(error));
}

// Questa funzione crea dinamicamente bicchieri virtuali nell'interfaccia web in base al numero di bicchieri disponibili
function crea_bicchieri(){
    const bicchieri = document.getElementById("bicchieri");
    const piu = bicchieri.firstChild;
    
    for(let i = 0; i < numero_bicchieri; i++){
        let bicchiere = document.createElement("div");
        bicchiere.classList.add("bicchiere");

        let acqua = document.createElement("div");
        acqua.classList.add("acqua");
        acqua.style.height = "0%";
        bicchiere.appendChild(acqua);
        bicchieri.insertBefore(bicchiere, piu);
        // Ciascun bicchiere ha un identificatore unico (ad esempio: "a-0", "a-1", eccetera ...)
        bicchiere.id = "a-" + i;
        // Aggiungo il gestore di eventi per i bicchieri creati dinamicamente
        bicchiere.addEventListener("click", function() {
            modifica_bicchiere(bicchiere);
        });
    }
}

// Questa funzione gestisce il comportamento del click su un bicchiere.
function modifica_bicchiere(bicchiere){
    let acqua = bicchiere.querySelector(".acqua");
    
    // Se il bicchiere è pieno (altezza dell'elemento "acqua" è "80%"), simula lo svuotamento diminuendo l'altezza 
    // dell'elemento "acqua"
    if(acqua.style.height === "80%")
        svuota_bicchiere(bicchiere);
    // Se il bicchiere è vuoto (altezza dell'elemento "acqua" è "0%"), simula il riempimento aumentando l'altezza 
    // dell'elemento "acqua"
    else
        riempi_bicchiere(bicchiere);
    
}

function riempi_bicchiere(bicchiere){
    let numero = 0;
    
    let acqua = bicchiere.querySelector(".acqua");
    // Al click, aumenta l'altezza dell'elemento "acqua" per simulare il riempimento
    acqua.style.height = "80%";
    let stringa = "";
    stringa = bicchiere.id;
    let dividi = stringa.split("-");
    numero = parseInt(dividi[1]) + 1;
    const msg_acqua = document.getElementById("msg_acqua");
    let acqua_bevuta = dimensione_bicchiere * numero;

    if(cifre_dopo_virgola(acqua_bevuta))
        acqua_bevuta = acqua_bevuta.toFixed(2);
    else
        acqua_bevuta = acqua_bevuta.toFixed(0);

    stringa = acqua_bevuta + "L";
    msg_acqua.textContent = stringa;
    for(let i = parseInt(dividi[1]) - 1; i >= 0; i--){
        stringa = "a-" + i;
        let b = document.getElementById(stringa);
        acqua = b.querySelector(".acqua");
        acqua.style.height = "80%";
    }

    let _data = new FormData();
    _data.set("numero", numero);

    // Invia un aggiornamento al server per tenere traccia delle modifiche
    fetch("../PHP/requests/aggiornamento_bicchieri.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => {
                    if(!data["result"])
                        window.alert("Problema con l'aggiornamento dei bicchieri!");
                  })
    .catch(error => window.alert(error));
}

function svuota_bicchiere(bicchiere){
    let numero = 0;

    let acqua = bicchiere.querySelector(".acqua");
    // Al click, diminuisce l'altezza dell'elemento "acqua" per simulare il riempimento
    acqua.style.height = "0%";
    let stringa = "";
    stringa = bicchiere.id;
    let dividi = stringa.split("-");
    numero = parseInt(dividi[1]);
    const msg_acqua = document.getElementById("msg_acqua");
    let acqua_bevuta = dimensione_bicchiere * dividi[1];

    if(cifre_dopo_virgola(acqua_bevuta))
        acqua_bevuta = acqua_bevuta.toFixed(2);
    else
        acqua_bevuta = acqua_bevuta.toFixed(0);

    stringa = acqua_bevuta + "L";
    msg_acqua.textContent = stringa;
    for(let i = parseInt(dividi[1]) + 1; i < numero_bicchieri; i++){
        stringa = "a-" + i;
        let b = document.getElementById(stringa);
        acqua = b.querySelector(".acqua");
        acqua.style.height = "0%";
        if(i >= 8){
            setTimeout(function(){
                document.getElementById("bicchieri").removeChild(b);
            }, 501);
        }
    }

    if(parseInt(dividi[1]) <= 7)
        numero_bicchieri = 8;
    else{
        numero_bicchieri = parseInt(dividi[1]);
        setTimeout(function(){
            document.getElementById("bicchieri").removeChild(bicchiere);
        }, 500);
    }

    let _data = new FormData();
    _data.set("numero", numero);

    // Invia un aggiornamento al server per tenere traccia delle modifiche
    fetch("../PHP/requests/aggiornamento_bicchieri.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => {
                    if(!data["result"])
                        window.alert("Problema con l'aggiornamento dei bicchieri!");
                  })
    .catch(error => window.alert(error));
}

// Questa funzione aggiunge un bicchiere all'interfaccia web quando si preme il pulsante "piu"
function aggiungi_bicchiere(piu){
    const bicchieri = document.getElementById("bicchieri");

    let bicchiere = document.createElement("div");
    bicchiere.classList.add("bicchiere");

    let acqua = document.createElement("div");
    acqua.classList.add("acqua");
    acqua.style.height = "0%";
    bicchiere.appendChild(acqua);
    bicchieri.insertBefore(bicchiere, piu);
    bicchiere.id = "a-" + numero_bicchieri;

    numero_bicchieri++;
    // Aggiungo il gestore di eventi per i bicchieri creati dinamicamente
    bicchiere.addEventListener("click", function() {
        modifica_bicchiere(bicchiere);
    });

    setTimeout(function(){
        modifica_bicchiere(bicchiere);
    }, 5);
}

// Ricerca il peso dell'Utente che ha fatto il login e lo mostra
function inizializza_peso(){
    let peso = document.getElementById("peso");
    
    fetch("../PHP/requests/inizializza_peso.php", { method: 'GET' })
    .then(response => response.json())
    .then(data => {
                    if(data["result"]){
                        // Inserisci due pulsanti che aumentano e diminuiscono il peso di 0.1
                        let bminus = document.createElement("button");
                        let bplus = document.createElement("button");
                        let p = document.createElement("p");

                        bminus.id = "minus";
                        bplus.id = "plus";
                        bminus.textContent = "-";
                        bplus.textContent = "+";
                        bminus.addEventListener("click", aggiorna_peso);
                        bplus.addEventListener("click", aggiorna_peso);

                        let numero = parseFloat(data["peso"]);
                        numero = numero.toFixed(1);

                        p.textContent = numero + " Kg";
                        peso.appendChild(bminus);
                        peso.appendChild(p);
                        peso.appendChild(bplus);
                    }
                    else
                        window.alert("Problema con il recupero del peso");
                  })
    .catch(error => window.alert(error));
}

// Aggiorna il peso aumentando o diminuendo di 0.1 il peso a seconda del pulsante cliccato
function aggiorna_peso(event){
    let identita = event.currentTarget.id;
    let peso = document.getElementById("peso").firstChild.nextSibling; 
    let dividi = peso.textContent;
    dividi = dividi.split(" ");
    let numero = parseFloat(dividi[0]);
    numero = parseFloat(numero.toFixed(1));

    if(identita === "plus"){
        if(numero > 650)
            return;
        numero += 0.1; 
    }
    else if(identita === "minus"){
        if(numero < 10)
            return;
        numero -= 0.1;
    }  

    numero = parseFloat(numero.toFixed(1));
    peso.textContent = numero + " Kg";

    let _data = new FormData();
    _data.set("peso", numero);
    
    // Invia al server l'aggiornamento del peso
    fetch("../PHP/requests/aggiornamento_peso.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => {
                    if(!data["result"])
                        window.alert("Problema con l'aggiornamento del peso!");
                    else
                        progresso_barra()                            
                  })
    .catch(error => window.alert(error));
}

function click_attivita(){
    window.location.href = "attivita.php";
}

// Aggiorna la data della pagina una volta cambiata
function inizializza_data(){
    const tempo = document.getElementById("data");

    fetch("../PHP/requests/aggiorna_data_corrente.php", { method: 'GET' })
    .then(response => response.json())
    .then(data => {
                    if(data["result"])
                        tempo.value = data["data"];
                    else
                        window.alert("Problema col cambio di data!");                       
                  })
    .catch(error => window.alert(error));

    tempo.addEventListener("input", cambia_data);
}

// Cambia la data della pagina
function cambia_data(event){
    const tempo = event.currentTarget;
    
    if(tempo.value === ""){
        let data_corrente = new Date();
        let mese = data_corrente.getMonth() + 1;
        let stringa = data_corrente.getFullYear() + "-" + mese + "-" + data_corrente.getDate();
        tempo.value = stringa;
    }                

    let _data = new FormData();
    _data.set("data", tempo.value);
    
    fetch("../PHP/cambia_data.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => {
                    if(data["result"])
                        window.location.href = 'mainpage.php';
                    else
                        window.alert("Problema col cambio di data!");                       
                  })
    .catch(error => window.alert(error));
}

// Questa funzione esegue una serie di operazioni per recuperare dati relativi alle attività fisiche e visualizzarli in una tabella
async function inizializza_info_attivita(){
    const tab = document.getElementById("info_attivita");

    await fetch("../PHP/requests/recupera_info_attivita.php", { method: 'GET' })
    .then(response => response.json())
    .then(data => {
                    if(data["result"]){
                        // inizializza la tabella con id = "info_attivita"
                        let r = document.createElement("tr");
                        let c = document.createElement("td");
                        c.textContent = "Camminare";
                        r.appendChild(c);

                        c = document.createElement("td");
                        c.textContent = "Dormire";
                        r.appendChild(c);

                        c = document.createElement("td");
                        c.textContent = "Calorie Bruciate";
                        r.appendChild(c);

                        c = document.createElement("td");
                        c.textContent = "Attività Svolte";
                        r.appendChild(c);
                        tab.appendChild(r);

                        r = document.createElement("tr");
                        c = document.createElement("td");

                        if(data["passi approssimati"] != 0 && data["passi"] != 0){
                            let passi = data["passi"] + data["passi approssimati"];
                            c.textContent = "Circa " + passi + " passi";  
                        }
                        else{
                            let passi = data["passi"];
                            c.textContent = passi + " passi";  
                        }
                        r.appendChild(c);

                        c = document.createElement("td");
                        c.textContent = data["sonno"] + " ore di sonno";
                        r.appendChild(c);

                        c = document.createElement("td");
                        let valore = parseFloat(data["bruciate"]);
                        if(cifre_dopo_virgola(valore))
                            valore = valore.toFixed(2);
                        
                        c.textContent = valore + " Kcal";
                        r.appendChild(c);

                        c = document.createElement("td");
                        c.textContent = data["attivita"];
                        r.appendChild(c);

                        tab.appendChild(r);
                    }
                    else
                        window.alert("Problema col recupero di dati!");                       
                  })
    .catch(error => window.alert(error));
}

function cifre_dopo_virgola(numero){
    // Converto il numero in una stringa
    var numero_stringa = numero.toString();

    // Uso un'espressione regolare per verificare se ci sono cifre dopo la virgola
    var regex = /(\.\d*[1-9])|(\.\d*[1-9]0+)$/;
    return regex.test(numero_stringa);
}

function inizializzazione(){
    const menu_button = document.getElementById("menu");
    const menu_dropdown = document.getElementById("menu-dropdown");

    // Inizializzazione dei vari elementi dell'interfaccia web
    progresso_barra();
    inizializza_data();
    inizializza_bicchieri();
    inizializza_peso();
    inizializza_info_attivita()

    // Creazione del menù a tendina
    menu_button.addEventListener("click", function(){
                                                        if(menu_dropdown.style.display === "block"){
                                                            menu_dropdown.style.display = "none";
                                                            menu_button.style.margin = "0.1% 0%";
                                                        }
                                                        else{
                                                            menu_dropdown.style.display = "block";
                                                            menu_button.style.margin = "0.1% 0.1%";
                                                        }
                                                });

    document.addEventListener("click", function(event){
                                                        if(event.target !== menu_button && event.target !== menu_dropdown)
                                                            menu_dropdown.style.display = "none";
                                                    });

    menu_dropdown.addEventListener("click", function(event) {
                                                                event.stopPropagation(); 
                                                                // Evita la chiusura del menu al cliccare su di esso
                                                            });
}