function cambio_opzione(event){
    const select = event.currentTarget;

    let scelta = select.value;
    if(scelta == "1")
        giornaliero();
    else{
        if(scelta == "2")
            settimanale();
        else{
            if(scelta == "3")
                mensile();
            else if(scelta == "4")
                annuale();
        }
    }
}

async function crea_barre(){
    const grafici = document.getElementById("grafici");

    // Crea un contenitore per le barre
    const container_barre = document.createElement("div");
    container_barre.id = "container_barre";
    grafici.appendChild(container_barre);

    // Richiama la funzione per creare le barre per Carboidrati, Grassi e Proteine
    costruisci_bar(container_barre, "Carboidrati");
    costruisci_bar(container_barre, "Grassi");
    costruisci_bar(container_barre, "Proteine");

    // Inizializza le variabili
    let fabbisogno_calorico = 0.0;
    let calorie_perse = 0.0;
    let carboidrati = 0.0;
    let grassi = 0.0;
    let proteine = 0.0;
    let p = null;

    // Esegue una richiesta asincrona per ottenere i dati
    await fetch("../PHP/requests/richiesta_dati.php", { method: 'GET' })
        .then(response => response.json())
        .then(data => {
            // Inizializzazione delle variabili con i dati ottenuti dalla richiesta
            if(data["result"]) {
                fabbisogno_calorico = parseFloat(data["fabbisogno"]);
                calorie_perse = parseFloat(data["perse"]);
                carboidrati = parseFloat(data["carboidrati"]);
                proteine = parseFloat(data["proteine"]);
                grassi = parseFloat(data["grassi"]);
            } 
            else {
                window.alert("Recupero dati non riuscito!");
            }
        })
        .catch(error => window.alert(error));

    // Calcola il fabbisogno calorico
    fabbisogno_calorico += parseFloat(calorie_perse);
    if (cifre_dopo_virgola(fabbisogno_calorico))
        fabbisogno_calorico = fabbisogno_calorico.toFixed(2);

    // Calcola i grammi necessari per Carboidrati, Grassi e Proteine
    let grammi_carboidrati = (parseFloat(fabbisogno_calorico) * 50 / 100) / 4;
    let grammi_grassi = (parseFloat(fabbisogno_calorico) * 30 / 100) / 9;
    let grammi_proteine = (parseFloat(fabbisogno_calorico) * 20 / 100) / 4;
    grammi_carboidrati = grammi_carboidrati.toFixed(0);
    grammi_grassi = grammi_grassi.toFixed(0);
    grammi_proteine = grammi_proteine.toFixed(0);

    // Imposta le dimensioni delle barre
    set_bar("Carboidrati", carboidrati, grammi_carboidrati);
    set_bar("Grassi", grassi, grammi_grassi);
    set_bar("Proteine", proteine, grammi_proteine);
}

// Crea una barra all'interno di un contenitore con un identificativo specifico. Funzione usata da "crea_barre".
function costruisci_bar(container, identita){
    const barra = document.createElement("div");
    barra.classList.add("barra");
    container.appendChild(barra);
    const progresso = document.createElement("div");
    progresso.id = identita;
    progresso.classList.add("progresso");
    barra.appendChild(progresso);
}

// Imposta la dimensione e il testo di una barra specifica. Funzione usata da "crea_barre".
function set_bar(identita, valore, massimo){
    let dimensione_progresso = parseFloat(valore * 100 / massimo);
    const progresso = document.getElementById(identita);
    const barra = progresso.parentNode;

    // Gestisce i colori della barra in base al progresso
    if(dimensione_progresso < 0)
        dimensione_progresso = 0;
    else{
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

    // Imposta la larghezza della barra e il testo
    progresso.style.width = dimensione_progresso + "%";

    if(barra.getElementsByTagName("p").length > 0)
        barra.querySelector("p").textContent = valore.toFixed(0) + " / " + massimo + " g";
    else{
        const p = document.createElement("p");
        p.textContent = identita + ": " + valore.toFixed(0) + " / " + massimo + " g";
        barra.appendChild(p);
    }
}

// In base ai dati ricevuti, crea un elemento "div" nel documento HTML con una lista di vitamine, macronutrienti, 
// micronutrienti e mancate assunzioni.
async function vn_assunte(valore){
    const grafici = document.getElementById("grafici");
    // Creo un oggetto FormData con il valore specificato
    let _data = new FormData();
    _data.set("valore", valore);

     // Eseguo una richiesta POST per recuperare dati dal server
    await fetch("../PHP/requests/recupera_vn.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => { 
                        // Creo un nuovo elemento "div" per contenere le liste di dati
                        const vn = document.createElement("div");
                        vn.id = "vitamine_nutrienti";

                        grafici.appendChild(document.createElement("br"));
                        grafici.appendChild(vn);

                        // Chiamo la funzione "crea_lista" per creare e popolare le liste di dati
                        crea_lista(vn, data);
                  })
    .catch(error => window.alert(error));
}

// Questa funzione crea le liste di dati in base ai dati forniti e le aggiunge all'elemento "vn". Funzione usata da "vn_assunte".
function crea_lista(vn, data){
    for([key, value] of Object.entries(data)){
         // Verifica se la chiave è "Vitamina A", poiché è la prima vitamina che si guarda e crea una lista per le vitamine
        if(key == "Vitamina A"){
            p = document.createElement("p");
            p.textContent = "Lista Vitamine assunte: ";
            p.style.fontWeight = "bold";
            ul = document.createElement("ul");
            vn.appendChild(p);
            vn.appendChild(ul);
        }

        // Verifica se la chiave è "Calcio", poiché è il primo macronutriente che si guarda e crea una lista per i macronutrienti
        if(key == "Calcio"){
            // Se l'elenco precedente è vuoto, aggiungi un elemento "li" con scritto "Nessuno"
            if(!(ul.hasChildNodes())){
                li = document.createElement("li");
                li.textContent = "Nessuna";
                ul.appendChild(li);
            }
            p = document.createElement("p");
            p.textContent = "Lista Macronutrienti assunti: ";
            p.style.fontWeight = "bold";
            ul = document.createElement("ul");
            vn.appendChild(p);
            vn.appendChild(ul);
            
        }

        // Verifica se la chiave è "Cromo", poiché è il primo micronutriente che si guarda e crea una lista per i micronutrienti
        if(key == "Cromo"){
            // Se l'elenco precedente è vuoto, aggiungi un elemento "li" con scritto "Nessuno"
            if(!(ul.hasChildNodes())){
                li = document.createElement("li");
                li.textContent = "Nessuno";
                ul.appendChild(li);
            }
            p = document.createElement("p");
            p.textContent = "Lista Micronutrienti assunti: ";
            p.style.fontWeight = "bold";
            ul = document.createElement("ul");
            vn.appendChild(p);
            vn.appendChild(ul);
        }

        if(value){
            li = document.createElement("li");
            li.textContent = key;
            ul.appendChild(li);
        }                        
    }

    // Se l'elenco precedente è vuoto, aggiungi un elemento "li" con scritto "Nessuno"
    if(!(ul.hasChildNodes())){
        li = document.createElement("li");
        li.textContent = "Nessuno";
        ul.appendChild(li);
    }

    // Crea una nuova lista per le mancate assunzioni
    p = document.createElement("p");
    p.textContent = "Lista di mancate assunzioni: ";
    p.style.fontWeight = "bold";
    ul = document.createElement("ul");
    vn.appendChild(p);
    vn.appendChild(ul);
    li = null;

    for([key, value] of Object.entries(data)){
        // Se il valore è false, crea un elemento "li" nella lista delle mancate assunzioni
        if(!value){
            li = document.createElement("li");
            li.textContent = key;
            ul.appendChild(li);
        }                
    }

    // Se l'elenco precedente è vuoto, aggiungi un elemento "li" con scritto "Nessuno"
    if(!(ul.hasChildNodes())){
        li = document.createElement("li");
        li.textContent = "Nessuno";
        ul.appendChild(li);
    }
}

// Questa funzione calcola il BMI (Body Mass Index) e lo visualizza nel documento HTML
async function calcolo_bmi(){
    const grafici = document.getElementById("grafici");

    await fetch("../PHP/requests/calcolo_bmi.php", { method: 'GET' })
    .then(response => response.json())
    .then(data => { 
                    if(data["result"]){
                        let bmi = parseFloat(data["bmi"]);

                        if(cifre_dopo_virgola(bmi))
                            bmi = bmi.toFixed(2);
                        let div = document.createElement("div");
                        div.id = "BMI";
                        grafici.appendChild(div);
                        
                        // Chiama la funzione "stampa_bmi" per stampare il valore del BMI e la sua classificazione
                        stampa_bmi(div, bmi);                        
                    }
                    else
                        window.alert("Problema nel recupero dei dati per il calcolo del BMI!");
                  })
    .catch(error => window.alert(error));
}

// Questa funzione stampa il valore del BMI e la sua classificazione in base ai parametri forniti. Usata da "calcolo_bmi". 
function stampa_bmi(div, bmi){
    // Crea due elementi "p" per visualizzare il BMI e la sua classificazione
    let p1 = document.createElement("p");
    div.appendChild(p1);
    let p2 = document.createElement("p");
    div.appendChild(p2);

    // Imposta il testo del primo elemento "p" con il valore del BMI
    p1.textContent = "BMI = " + bmi;

    // Utilizzando uno switch, determina la classificazione del peso in base al valore del BMI
    switch(true){
        case bmi < 16.01:
            p2.textContent = "Classificazione Peso: Grave magrezza (inedia)";
            break;
        case bmi >= 16.01 && bmi < 17.50:
            p2.textContent = "Classificazione Peso: Sottopeso";
            break;
        case bmi >= 17.51 && bmi < 18.50:
            p2.textContent = "Classificazione Peso: Leggermente Sottopeso";
            break;
        case bmi >= 18.5 && bmi <= 25:
            p2.textContent = "Classificazione Peso: Normopeso";
            break;
        case bmi > 25.01 && bmi <= 30:
            p2.textContent = "Classificazione Peso: Leggermente Sovrappeso";
            break;
        case bmi > 30 && bmi <= 35:
            p2.textContent = "Classificazione Peso: Obesità di I classe (Obesità Moderata)";
            break;
        case bmi > 35 && bmi <= 40:
            p2.textContent = "Classificazione Peso: Obesità di II classe (Obesità Grave)";
            break;
        case bmi > 40 && bmi <= 50:
            p2.textContent = "Classificazione Peso: Obesità di III classe (Obesità Gravissima)";
            break;
        case bmi > 50:
            p2.textContent = "Classificazione Peso: Super Obeso";
            break;
    }
}

// Funzione che crea i vari istogrammi
async function crea_istogrammi(valore){
    const grafici = document.getElementById("grafici");
    let _data = new FormData();
    _data.set("valore", valore);

    await fetch("../PHP/requests/recupera_dati_utente.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => {                        
                        crea_istogramma(data, valore, "calorie", "yellow");
                        crea_istogramma_CarbProtFat(data, valore);
                        crea_istogramma(data, valore, "acqua", "aquamarine");
                  })
    .catch(error => window.alert(error));
}

function crea_istogramma(data, valore, indice, colore){
    const grafici = document.getElementById("grafici");
    const titolo = document.createElement("h2");
    if(indice == "acqua")
        titolo.textContent = "Istogramma per l'" + indice;
    else
        titolo.textContent = "Istogramma per le " + indice;
    grafici.appendChild(titolo);

    const istogramma = document.createElement("div");
    istogramma.id = "istogramma_" + indice;
    grafici.appendChild(istogramma);

    let massimo = trova_massimo(data, indice);

    if(valore === 3){
        istogramma.style.width = "98%";
        istogramma.style.overflowX = "auto";
    }

    for(let i = 1; i <= data.length; i++){
        let div_principale = document.createElement("div");
        istogramma.appendChild(div_principale);

        const tooltip = crea_tooltip(data[i][indice], indice);
        div_principale.appendChild(tooltip);

        div_principale.addEventListener("mouseenter", () => {
            tooltip.style.visibility = "visible";
        });

        div_principale.addEventListener("mouseleave", () => {
            tooltip.style.visibility = "hidden";
        });

        let colonna = document.createElement("div");
        let altezza = parseFloat(data[i][indice] * 10 / massimo);
        if (cifre_dopo_virgola(altezza)) {
            altezza = altezza.toFixed(4);
        }
        colonna.style.backgroundColor = colore;
        colonna.style.height = altezza + "rem";
        colonna.style.width = "1rem";
        div_principale.appendChild(colonna);

        crea_etichetta_giorno(div_principale, i, valore);
    }
}

function crea_istogramma_CarbProtFat(data, valore){
    const grafici = document.getElementById("grafici");
    const titolo = document.createElement("h2");
    titolo.textContent = "Istogramma per carboidrati, grassi e proteine";
    grafici.appendChild(titolo);

    const grammi = document.createElement("div");
    grammi.id = "istogramma_grammi";
    grafici.appendChild(grammi);

    let massimo = data[1]["carboidrati"] + data[1]["grassi"] + data[1]["proteine"];

    for (let i = 2; i <= data.length; i++) {
        if (parseFloat(data[i]["carboidrati"] + data[i]["grassi"] + data[i]["proteine"]) > massimo) {
            massimo = parseFloat(data[i]["carboidrati"] + data[i]["grassi"] + data[i]["proteine"]);
        }
    }

    if(valore === 3){
        grammi.style.width = "98%";
        grammi.style.overflowX = "auto";
    }

    for(let i = 1; i <= data.length; i++){
        let div_principale = document.createElement("div");
        grammi.appendChild(div_principale);

        let tooltip = document.createElement("div");
        tooltip.classList.add("tooltip");
        div_principale.appendChild(tooltip);

        let p = document.createElement("p");
        p.textContent = "P: " + data[i]["proteine"] + "g";
        p.style.fontSize = "7pt";
        tooltip.appendChild(p);

        p = document.createElement("p");
        p.textContent = "G: " + data[i]["grassi"] + "g";
        p.style.fontSize = "7pt";
        tooltip.appendChild(p);

        p = document.createElement("p");
        p.textContent = "C: " + data[i]["carboidrati"] + "g";
        p.style.fontSize = "7pt";
        tooltip.appendChild(p);

        div_principale.addEventListener("mouseenter", () => {
            tooltip.style.visibility = "visible";
        });

        div_principale.addEventListener("mouseleave", () => {
            tooltip.style.visibility = "hidden";
        });

        let colonna = document.createElement("div");
        let altezza = parseFloat(data[i]["proteine"] * 10 / massimo);
        if(cifre_dopo_virgola(altezza)){
            altezza = altezza.toFixed(4);
        }
        colonna.style.backgroundColor = "red";
        colonna.style.height = altezza + "rem";
        colonna.style.width = "1rem";
        div_principale.appendChild(colonna);

        colonna = document.createElement("div");
        altezza = parseFloat(data[i]["grassi"] * 10 / massimo);
        if(cifre_dopo_virgola(altezza)){
            altezza = altezza.toFixed(4);
        }
        colonna.style.backgroundColor = "white";
        colonna.style.height = altezza + "rem";
        colonna.style.width = "1rem";
        div_principale.appendChild(colonna);

        colonna = document.createElement("div");
        altezza = parseFloat(data[i]["carboidrati"] * 10 / massimo);
        if(cifre_dopo_virgola(altezza)){
            altezza = altezza.toFixed(4);
        }
        colonna.style.backgroundColor = "purple";
        colonna.style.height = altezza + "rem";
        colonna.style.width = "1rem";
        div_principale.appendChild(colonna);

        crea_etichetta_giorno(div_principale, i, valore);
    }
}

function trova_massimo(data, indice){
    let massimo = data[1][indice];

    for(let i = 2; i <= data.length; i++){
        if(data[i][indice] > massimo)
            massimo = data[i][indice];
    }

    return massimo;
}

function crea_tooltip(text, indice){
    let tooltip = document.createElement("div");
    tooltip.classList.add("tooltip");
    
    let numero = parseFloat(text);
    numero = numero.toFixed(0);
    
    let p = document.createElement("p");
    if(indice === "calorie")
        p.textContent = numero + " Kcal";
    if(indice === "acqua")
        p.textContent = numero + " L";
    p.style.fontSize = "7pt";
    tooltip.appendChild(p);
    
    return tooltip;
}

function crea_etichetta_giorno(div, i, valore){
    let etichetta_giorno = document.createElement("div");

    if(valore === 2){
        switch (true) {
            case i === 1:
                etichetta_giorno.textContent = "Domenica";
                break;
            case i === 2:
                etichetta_giorno.textContent = "Lunedì";
                break;
            case i === 3:
                etichetta_giorno.textContent = "Martedì";
                break;
            case i === 4:
                etichetta_giorno.textContent = "Mercoledì";
                break;
            case i === 5:
                etichetta_giorno.textContent = "Giovedì";
                break;
            case i === 6:
                etichetta_giorno.textContent = "Venerdì";
                break;
            case i === 7:
                etichetta_giorno.textContent = "Sabato";
                break;
        }
        etichetta_giorno.style.fontSize = "10pt";
    } 
    else{
        etichetta_giorno.textContent = i;
        etichetta_giorno.style.fontSize = "10pt";
    }

    div.appendChild(etichetta_giorno);
}

async function giornaliero(){
    const grafici = document.getElementById("grafici");
    while(grafici.hasChildNodes())
        grafici.removeChild(grafici.firstChild);

    await crea_barre();
    let br = document.createElement("br");
    grafici.appendChild(br);
    await vn_assunte(1);
    br = document.createElement("br");
    grafici.appendChild(br);
    await calcolo_bmi();
}

async function settimanale(){
    const grafici = document.getElementById("grafici");
    while(grafici.hasChildNodes())
        grafici.removeChild(grafici.firstChild);

    await crea_istogrammi(2);
    let br = document.createElement("br");
    grafici.appendChild(br);
    await vn_assunte(2);
}

async function mensile(){
    const grafici = document.getElementById("grafici");
    while(grafici.hasChildNodes())
        grafici.removeChild(grafici.firstChild);

    await crea_istogrammi(3);
    let br = document.createElement("br");
    grafici.appendChild(br);
    await vn_assunte(3);
}

async function annuale(){
    const grafici = document.getElementById("grafici");
    while(grafici.hasChildNodes())
        grafici.removeChild(grafici.firstChild);
    
    await crea_istogrammi(4);
    let br = document.createElement("br");
    grafici.appendChild(br);
    await vn_assunte(4);
}

function cifre_dopo_virgola(numero){
    // Converto il numero in una stringa
    var numero_stringa = numero.toString();  

    // Uso un'espressione regolare per verificare se ci sono cifre dopo la virgola
    var regex = /(\.\d*[1-9])|(\.\d*[1-9]0+)$/;
    return regex.test(numero_stringa);
}

function inizializzazione(){
    let select = document.getElementById("selezione");
    select.addEventListener("change", cambio_opzione);
    giornaliero();
}