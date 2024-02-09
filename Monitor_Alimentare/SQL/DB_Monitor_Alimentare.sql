# Progettazione Web 
UNLOCK TABLES;
DROP DATABASE IF EXISTS DB_Monitor_Alimentare; 
CREATE DATABASE DB_Monitor_Alimentare; 
USE DB_Monitor_Alimentare; 

# Host: localhost    Database: DB_Monitor_Alimentare

DROP TABLE IF EXISTS Utente;
CREATE TABLE Utente(
    Email VARCHAR(100) NOT NULL,
    Password VARCHAR(255) NOT NULL, 
    Nome VARCHAR(50) NOT NULL,
    Cognome VARCHAR(50) NOT NULL,
    Sesso VARCHAR(1) NOT NULL,
    DataNascita DATE NOT NULL, 
    Statura INT NOT NULL, # In cm
    Peso FLOAT NOT NULL, # In KG
    LivelloAttivita VARCHAR(50) NOT NULL,
    Dieta VARCHAR(50) NOT NULL, 
    DimensioneBicchiere FLOAT NOT NULL, 
    FabbisognoCalorico INT NOT NULL, 
    PRIMARY KEY (Email)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS Cibo;
CREATE TABLE Cibo(
    ID INT AUTO_INCREMENT,
    Nome VARCHAR(255) NOT NULL,
    Autore VARCHAR(100) DEFAULT "Monitor Alimentare",
    Vegetariano BOOLEAN DEFAULT FALSE,
    Vegano BOOLEAN DEFAULT FALSE,
    # VITAMINE
    VitaminaA BOOLEAN DEFAULT FALSE, 
    VitaminaB1 BOOLEAN DEFAULT FALSE, 
    VitaminaB2 BOOLEAN DEFAULT FALSE,
    VitaminaB3 BOOLEAN DEFAULT FALSE,
    VitaminaB5 BOOLEAN DEFAULT FALSE, 
    VitaminaB6 BOOLEAN DEFAULT FALSE, 
    VitaminaB7 BOOLEAN DEFAULT FALSE,
    VitaminaB11 BOOLEAN DEFAULT FALSE, 
    VitaminaB12 BOOLEAN DEFAULT FALSE,
    VitaminaC BOOLEAN DEFAULT FALSE,
    VitaminaD BOOLEAN DEFAULT FALSE,
    VitaminaE BOOLEAN DEFAULT FALSE,
    VitaminaK BOOLEAN DEFAULT FALSE,
    # MACRONUTRIENTI
    Calcio BOOLEAN DEFAULT FALSE, 
    Fosforo BOOLEAN DEFAULT FALSE,
    Magnesio BOOLEAN DEFAULT FALSE,
    Sodio BOOLEAN DEFAULT FALSE,
    Potassio BOOLEAN DEFAULT FALSE,
    Zolfo BOOLEAN DEFAULT FALSE,
    # MICRONUTRIENTI
    Cromo BOOLEAN DEFAULT FALSE,
    Ferro BOOLEAN DEFAULT FALSE,
    Fluoro BOOLEAN DEFAULT FALSE,
    Iodio BOOLEAN DEFAULT FALSE,
    Manganese BOOLEAN DEFAULT FALSE,
    Rame BOOLEAN DEFAULT FALSE,
    Selenio BOOLEAN DEFAULT FALSE,
    Zinco BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS Porzione;
CREATE TABLE Porzione(
    Cibo INT NOT NULL, 
    Catalogazione VARCHAR(50) NOT NULL,
    Calorie FLOAT NOT NULL, # In KCal
    Carboidrati FLOAT NOT NULL, # In g
    Proteine FLOAT NOT NULL, # In g
    Grassi FLOAT NOT NULL, # In g
    Quantita INT NOT NULL, 
    UnitaMisura VARCHAR(10) NOT NULL,
    PRIMARY KEY (Cibo, Catalogazione),
    FOREIGN KEY (Cibo) REFERENCES Cibo(ID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS Pasto;
CREATE TABLE Pasto(
    Utente VARCHAR(100) NOT NULL,
    Cibo INT NOT NULL, 
    Data DATE NOT NULL,
    Catalogazione VARCHAR(50) NOT NULL,
    Orario VARCHAR(50) NOT NULL,
    Quantita FLOAT NOT NULL,
    PRIMARY KEY (Utente, Cibo, Data, Catalogazione, Orario),
    FOREIGN KEY (Utente) REFERENCES Utente(Email),
    FOREIGN KEY (Cibo, Catalogazione) REFERENCES Porzione(Cibo, Catalogazione)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS Bicchieri;
CREATE TABLE Bicchieri(
    Utente VARCHAR(100) NOT NULL,
    Data DATE NOT NULL,
    Numero INT NOT NULL,
    PRIMARY KEY (Utente, Data),
    FOREIGN KEY (Utente) REFERENCES Utente(Email)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS Esercizi;
CREATE TABLE Esercizi(
    Nome VARCHAR(100) NOT NULL,
    UnitaMisura VARCHAR(10) NOT NULL,
    Calorie FLOAT NOT NULL, # In KCal (le calorie sono considerate per una persona che pesa 100 Kg e poi viene fatta una proporzione)
    Autore VARCHAR(100) NOT NULL DEFAULT "Monitor Alimentare",
    PRIMARY KEY (Nome, UnitaMisura)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS Attivita;
CREATE TABLE Attivita(
    Utente VARCHAR(100) NOT NULL,
    Esercizio VARCHAR(100) NOT NULL,
    UnitaMisura VARCHAR(10) NOT NULL,
    Data DATE NOT NULL,
    Quantita FLOAT NOT NULL,
    PRIMARY KEY (Utente, Esercizio, UnitaMisura, Data),
    FOREIGN KEY (Utente) REFERENCES Utente(Email),
    FOREIGN KEY (Esercizio, UnitaMisura) REFERENCES Esercizi(Nome, UnitaMisura)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES Utente WRITE;
    INSERT INTO Utente(Email, Password, Nome, Cognome, Sesso, DataNascita, Statura, Peso, LivelloAttivita, Dieta, DimensioneBicchiere, FabbisognoCalorico)
    VALUES ('f.panattoni2@studenti.unipi.it', '$2y$10$frxYmn/0S.P28vzgv0vB/e1716xpiAKY9sL42TXHbYwKV8dObhcRu', 'Francesco', 'Panattoni', 'M', '1998-02-04', 184, 100.4, 'Moderato', 'Classica', 0.25, 3932);
UNLOCK TABLES;

LOCK TABLES Cibo WRITE;
# Popolamento con cibi random (senza criterio)

INSERT INTO Cibo (Nome, Vegetariano, VitaminaB1, VitaminaB2, VitaminaB6, Calcio, Fosforo, Magnesio, Zolfo, Ferro, Fluoro)
VALUES ('Pizza Margherita',  TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE,TRUE);

INSERT INTO Cibo (Nome,Vegetariano, Vegano,VitaminaA, VitaminaC, Calcio, Ferro, Sodio)
VALUES
  ('Mela', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE), #2
  ('Pane (Bianco)', TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, TRUE), #3
  ('Salmone (Cotto)', FALSE, FALSE, TRUE, FALSE, TRUE, TRUE, TRUE), #4
  ('Carota', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE), #5
  ('Spinaci', TRUE, TRUE, TRUE, TRUE, FALSE, TRUE, FALSE), #6
  ('Latte', TRUE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE), #7
  ('Latte Macchiato', TRUE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE), #8
  ('Uova', TRUE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE), #9
  ('Banana', TRUE, TRUE, FALSE, TRUE, FALSE, FALSE, FALSE), #10
  ('Pollo', FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE); #11
  
 INSERT INTO Cibo (Nome, Vegetariano, VitaminaB1, VitaminaB2, VitaminaB6, Calcio, Fosforo, Magnesio, Zolfo, Ferro, Fluoro)
VALUES ('Pasta al Pomodoro',  TRUE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE); #12

INSERT INTO Cibo (Nome,Vegetariano, Vegano,VitaminaA, VitaminaC, Calcio, Ferro, Sodio)
VALUES
  ('Cetriolo', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE), #13
  ('Riso Bianco', TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE), #14
  ('Tonno', FALSE, FALSE, TRUE, FALSE, TRUE, TRUE, TRUE), #15
  ('Zucchine', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE), #16
  ('Piselli', TRUE, TRUE, TRUE, TRUE, FALSE, TRUE, FALSE), #17
  ('Yogurt', TRUE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE), #18
  ('Caffe', TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, TRUE), #19
  ('Manzo', FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE); #20
  
INSERT INTO Cibo (Nome, Vegetariano, VitaminaB1, VitaminaB2, VitaminaB6, Calcio, Fosforo, Magnesio, Zolfo, Ferro, Fluoro)
VALUES ('Pasta Genovese',  TRUE, FALSE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE), #21
('Bistecca di Maiale', FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE, FALSE, FALSE); #22

INSERT INTO Cibo (Nome,Vegetariano, Vegano,VitaminaA, VitaminaC, Calcio, Ferro, Sodio)
VALUES
  ('Carciofi', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE), #23
  ('Pesce Spada', FALSE, FALSE, TRUE, FALSE, TRUE, TRUE, TRUE), #24
  ('Radicchio', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE), #25
  ('Arancia', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE), #26
  ('Sogliola', FALSE, FALSE, TRUE, FALSE, TRUE, TRUE, TRUE), #27
  ('Asparagi', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE), #28
  ('Mirtilli', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE), #29
  ('Pasta al Nero di Seppia', TRUE, TRUE, FALSE, TRUE, FALSE, FALSE, FALSE), #30
  ('Gamberi', FALSE, FALSE, TRUE, FALSE, TRUE, TRUE, TRUE), #31
  ('Cavolo', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE); #32
  
  
# Popolamento con Primi Piatti
INSERT INTO Cibo (Nome, Vegetariano, VitaminaB1, VitaminaB2, VitaminaB6, Calcio, Fosforo, Magnesio, Zolfo, Ferro, Fluoro)
VALUES
    ('Lasagna al Forno', TRUE, FALSE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, FALSE), #33
    ('Pasta al Limone', TRUE, TRUE, FALSE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE), #34
    ('Pasta alla Carbonara', TRUE, FALSE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, FALSE), #35
    ('Gnocchi al Pesto', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, TRUE, FALSE); #36
    
INSERT INTO Cibo (Nome, Vegetariano, VitaminaB1, VitaminaB2, VitaminaB6, Calcio, Fosforo, Magnesio, Zolfo, Ferro, Fluoro)
VALUES
    ('Tagliatelle al Tartufo', TRUE, TRUE, FALSE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE), #37
    ('Risotto ai Funghi', TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE), #38
    ('Farfalle al Salmone', FALSE, FALSE, TRUE, FALSE, TRUE, TRUE, TRUE, TRUE, TRUE, FALSE), #39
    ('Cannelloni', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, TRUE, FALSE), #40
    ('Linguine al Pesto', TRUE, TRUE, FALSE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE); #41

INSERT INTO Cibo (Nome, Vegetariano, VitaminaB1, VitaminaB2, VitaminaB6, Calcio, Fosforo, Magnesio, Zolfo, Ferro, Fluoro)
VALUES
    ('Penne alla Vodka', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, TRUE, FALSE), #42
    ('Fusilli Primavera', TRUE, TRUE, FALSE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE); #43

INSERT INTO Cibo (Nome, Vegetariano, Vegano, VitaminaB1, VitaminaB2, VitaminaB6, Calcio, Fosforo, Magnesio, Zolfo, Ferro)
VALUES
    ('Tagliatelle al Tartufo', TRUE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE); #44

INSERT INTO Cibo (Nome, Vegetariano, Vegano, VitaminaA, VitaminaC, Calcio, Ferro, Sodio)
VALUES
    ('Risotto ai Funghi', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE), #45
    ('Linguine al Limone', TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE), #46
    ('Pappardelle al Cinghiale', FALSE, FALSE, TRUE, FALSE, TRUE, TRUE, TRUE); #47
    
INSERT INTO Cibo (Nome, Vegetariano, Vegano, VitaminaB1, VitaminaB2, VitaminaB6, Calcio, Fosforo, Magnesio, Zolfo, Fluoro)
VALUES
    ('Lasagne alla Bolognese', FALSE, FALSE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, FALSE); #48

INSERT INTO Cibo (Nome, Vegetariano, Vegano, VitaminaA, VitaminaC, Calcio, Ferro, Sodio)
VALUES
    ("Penne Arrabbiata", TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #49
    ('Tortellini in Brodo', FALSE, FALSE, TRUE, FALSE, TRUE, TRUE, TRUE); #50
    
    
# Popolamento con Secondi Piatti

INSERT INTO Cibo (Nome, Autore, Vegetariano, Vegano, VitaminaA, VitaminaB1, VitaminaB2, Calcio, Fosforo, Magnesio, Ferro)
VALUES 
  ('Pollo al Curry', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE), #51
  ('Bistecca alla Griglia', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE), #52
  ('Salmone alla Brace', 'Monitor Alimentare', FALSE, FALSE, TRUE, FALSE, FALSE, TRUE, TRUE, TRUE, TRUE), #53
  ('Petto Anatra con Arancia', 'Monitor Alimentare', FALSE, FALSE, TRUE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE), #54
  ('Costolette di Maiale', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE); #55
  
INSERT INTO Cibo (Nome, Autore, Vegetariano, Vegano, VitaminaA, VitaminaB1, VitaminaB2, Calcio, Fosforo, Magnesio, Ferro)
VALUES 
  ('Trota al Forno', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE, TRUE),  # 56
  ('Braciola di Maiale', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE), #57 
  ('Baccala alla Vicentina', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE, TRUE, TRUE), #58 
  ('Merluzzo alla Griglia', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE, TRUE, TRUE), #59
  ('Cotoletta di Pollo', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE, TRUE); #60
  
INSERT INTO Cibo (Nome, Autore, Vegetariano, Vegano, VitaminaA, VitaminaB1, VitaminaB2, Calcio, Fosforo, Magnesio, Ferro)
VALUES 
  ('Gamberoni alla Brace', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE), #61
  ('Pancia di Maiale Arrosto', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE),  #62
  ('Sogliola alla Mugnaia', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE, TRUE, TRUE), #63
  ('Pollo alla Milanese', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE, TRUE, TRUE); #64
  
INSERT INTO Cibo (Nome, Autore, Vegetariano, Vegano, VitaminaA, VitaminaB1, VitaminaB2, Calcio, Fosforo, Magnesio, Ferro)
VALUES 
  ('Filetto di Maiale al Rosmarino', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE), #65
  ('Tonno Grigliato', 'Monitor Alimentare', FALSE, FALSE, TRUE, FALSE, FALSE, TRUE, TRUE, TRUE, TRUE), #66
  ('Scaloppine al Vino Bianco', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE, TRUE, TRUE), #67
  ('Agnello alla Menta', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE, TRUE); #68
  
INSERT INTO Cibo (Nome, Autore, Vegetariano, Vegano, VitaminaA, VitaminaB1, VitaminaB2, Calcio, Fosforo, Magnesio, Ferro)
VALUES 
  ('Filetto di Sogliola ai Capperi', 'Monitor Alimentare', FALSE, FALSE, TRUE, FALSE, FALSE, TRUE, TRUE, TRUE, TRUE), #69
  ('Vitello Tonnato', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE, TRUE), #70
  ('Coniglio alla Senape', 'Monitor Alimentare', FALSE, FALSE, FALSE, FALSE, FALSE, TRUE, TRUE, TRUE, TRUE); #71
    
    
# Popolamento con Contorni

INSERT INTO Cibo (Nome, Autore, Vegetariano, Vegano, VitaminaB1, VitaminaB2, VitaminaB3, VitaminaB5, VitaminaB6, VitaminaB7, VitaminaB11, VitaminaB12, VitaminaC, Magnesio, Sodio, Potassio, Zolfo, Cromo, Ferro, Fluoro, Iodio, Manganese, Rame, Selenio, Zinco)
VALUES 
  ('Pure di Patate', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #72
  ('Insalata Caprese', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #73
  ('Verdure Grigliate', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #74
  ('Funghi Trifolati', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #75
  ('Asparagi al Burro', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE); #76

INSERT INTO Cibo (Nome, Autore, Vegetariano, Vegano, VitaminaB1, VitaminaB2, VitaminaB3, VitaminaB5, VitaminaB6, VitaminaB7, VitaminaB11, VitaminaB12, VitaminaC, Magnesio, Sodio, Potassio, Zolfo, Cromo, Ferro, Fluoro, Iodio, Manganese, Rame, Selenio, Zinco)
VALUES 
  ('Zucchine Grigliate', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #77
  ('Ratatouille', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #78
  ('Pure di Patate al Burro', 'Monitor Alimentare', FALSE, FALSE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #79
  ('Insalata di Pomodori', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #80
  ('Asparagi alla Parmigiana', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE); #81

INSERT INTO Cibo (Nome, Autore, Vegetariano, Vegano, VitaminaB1, VitaminaB2, VitaminaB3, VitaminaB5, VitaminaB6, VitaminaB7, VitaminaB11, VitaminaB12, VitaminaC, Magnesio, Sodio, Potassio, Zolfo, Cromo, Ferro, Fluoro, Iodio, Manganese, Rame, Selenio, Zinco)
VALUES 
  ('Couscous con Verdure', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #82
  ('Insalata Mista', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #83
  ('Pomodori al Forno', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #84
  ('Funghi Saltati', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE); #85

INSERT INTO Cibo (Nome, Autore, Vegetariano, Vegano, VitaminaB2, VitaminaB3, VitaminaB5, VitaminaB6, VitaminaB11, VitaminaB12, VitaminaC, Magnesio, Sodio, Potassio, Zolfo, Cromo, Fluoro, Iodio, Manganese, Rame, Selenio, Zinco)
VALUES 
  ('Asparagi al Burro Fuso', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #86,
  ('Patate Arrosto', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #87,
  ('Cavolfiore al Vapore', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE); #88
    
    
# Popolamento con Dolci

INSERT INTO Cibo (Nome, Autore, Vegetariano,VitaminaB1, VitaminaB2, VitaminaB3, VitaminaB5, VitaminaB6, Calcio, Ferro, Zinco)
VALUES 
  ('Tiramisu', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #89
  ('Panna Cotta', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #90
  ('Crostata di Frutta', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #91
  ('Profiteroles', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #92
  ('Gelato alla Vaniglia', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE); #93

INSERT INTO Cibo (Nome, Autore, Vegetariano, VitaminaB1, VitaminaB2, VitaminaB3, VitaminaB5, VitaminaB6, Calcio, Ferro, Zinco)
VALUES 
  ('Cheesecake al Cioccolato', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #94
  ('Torta al Limone', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #95
  ('Mousse al Cioccolato', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #96
  ('Tiramisu alle Fragole', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #97
  ('Panna Cotta al Caramello', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE); #98
  
INSERT INTO Cibo (Nome, Autore, Vegetariano, VitaminaB1, VitaminaB2, VitaminaB3, VitaminaB5, VitaminaB6, Calcio, Ferro, Zinco)
VALUES 
  ('Muffin al Cioccolato', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #99
  ('Cannoli Siciliani', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #100
  ('Pandoro', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #101
  ('Zuppa Inglese', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #102
  ('Cheesecake', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE); #103

INSERT INTO Cibo (Nome, Autore, Vegetariano, VitaminaB1, VitaminaB2, VitaminaB3, VitaminaB5, VitaminaB6, Calcio, Ferro, Zinco)
VALUES 
  ('Gelato al Cioccolato', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #104
  ('Cannolo al Pistacchio', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #105
  ('Macedonia di Frutta', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #106
  ('Panettone', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE); #107

INSERT INTO Cibo (Nome, Autore, Vegetariano, VitaminaA, VitaminaB1, VitaminaB2, Calcio, Ferro, Zinco)
VALUES 
  ('Gelato alla Fragola', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #108
  ('Gelato al Limone', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #109
  ('Cono Gelato', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE); #110
  
INSERT INTO Cibo (Nome, Autore, Vegetariano, VitaminaA, VitaminaB1, VitaminaB2, Calcio, Ferro, Zinco)
VALUES 
  ('Gelato al Pistacchio', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #111
  ('Gelato alla Fragola e Limone', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #112
  ('Gelato alla Nocciola', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE); #113
  
INSERT INTO Cibo (Nome, Autore, Vegetariano, VitaminaA, VitaminaB1, VitaminaB2, Calcio, Ferro, Zinco)
VALUES 
  ('Gelato al Cocco', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #114
  ('Gelato alla Vaniglia', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #115
  ('Gelato al Cioccolato Fondente', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE), #116
  ('Gelato al Caramello Salato', 'Monitor Alimentare', TRUE, TRUE, TRUE,  TRUE, TRUE, TRUE, TRUE), #117
  ('Gelato alla Crema', 'Monitor Alimentare', TRUE, TRUE,TRUE, TRUE, TRUE, TRUE, TRUE); #118
    
    
# Popolamento con Bevande

INSERT INTO Cibo (Nome, Vegetariano, Autore, VitaminaB2, Calcio)
VALUES 
  ('Succo di Arancia', 'Monitor Alimentare', TRUE, TRUE, TRUE), #119
  ('Coca Cola', 'Monitor Alimentare', TRUE, TRUE, TRUE), #120
  ('Ginseng', 'Monitor Alimentare', TRUE, TRUE, TRUE), #121
  ('Tequila', 'Monitor Alimentare', TRUE, TRUE, TRUE), #122
  ('Vino Rosso', 'Monitor Alimentare', TRUE, TRUE, TRUE), #123
  ('Birra', 'Monitor Alimentare', TRUE, TRUE, TRUE); #124
  
  INSERT INTO Cibo (Nome, Vegetariano, Autore, VitaminaB2, Calcio)
VALUES 
  ('Vino Bianco', 'Monitor Alimentare', TRUE, TRUE,  TRUE), #125
  ('Birra Bionda', 'Monitor Alimentare', TRUE, TRUE, TRUE), #126
  ('Birra Rossa', 'Monitor Alimentare', TRUE, TRUE, TRUE), #127
  ('Mojito', 'Monitor Alimentare', TRUE, TRUE, TRUE), #128
  ('Mojito Soda', 'Monitor Alimentare', TRUE, TRUE, TRUE), #129
  ('Lemon Soda', 'Monitor Alimentare', TRUE, TRUE, TRUE), #130
  ('Aranciata', 'Monitor Alimentare', TRUE, TRUE, TRUE), #131
  ('Succo di Frutta', 'Monitor Alimentare', TRUE, TRUE, TRUE), #132
  ('Sprite', 'Monitor Alimentare', TRUE, TRUE, TRUE), #133
  ('Te' , 'Monitor Alimentare', TRUE, TRUE, FALSE); #134
    
    
# Popolamento con Cornetti e Bomboloni
INSERT INTO Cibo (Nome, Vegetariano, Autore, VitaminaB1, VitaminaB2, Calcio, Ferro)
VALUES 
  ('Cornetto Vuoto', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE), #135
  ('Cornetto alla Crema', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE), #136
  ('Cornetto alla Nutella', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE), #137
  ('Cornetto al Cioccolato', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE), #138
  ('Cornetto alla Marmellata', 'Monitor Alimentare', TRUE, TRUE, TRUE, TRUE, TRUE), #139
  ('Cornetto alla Panna', 'Monitor Alimentare',TRUE, TRUE, TRUE, TRUE, TRUE); #140

UNLOCK TABLES;

LOCK TABLES Porzione WRITE;
INSERT INTO Porzione(Cibo, Catalogazione, Calorie, Carboidrati, Proteine, Grassi, Quantita, UnitaMisura)
VALUES (1, "Piatto (360 g)", 972, 187.2,  28.8, 21.6, 360, "g"), # Pizza Margherita
                   (1, "g",  2.7, 0.52, 0.08, 0.06, 1, "g"), # Pizza Margherita
                   (2, "g", 0.52, 0.14, 0.003, 0.002, 1, "g"), #Mela
                   (2, "Frutto (182 g)", 94.64, 25.48, 0.55, 0.364, 182, "g"), #Mela
                   (2, "Metà Frutto (91 g)", 47.32, 12.74, 0.28, 0.18, 91, "g"), #Mela
                   (2, "A fette (109 g)", 56.68, 15.26, 0.33, 0.22, 109, "g"), #Mela
                   (3, "g", 2.65, 0.49, 0.09, 0.03, 1, "g"), #Pane
                   (3, "Fetta (28 g)", 74.2, 13.72, 2.6, 0.84, 28, "g"), #Pane
                   (4, "g", 2.06, 0, 0.22, 0.12, 1, "g"), # Salmone
                   (4, "Filetto (124 g)", 255.44, 0, 27.28, 14.88, 356, "g"), # Salmone
                   (4, "Filetto (356 g)", 733.36, 0, 78.32, 42.72, 356, "g"), # Salmone
                   (5, "g (cruda)", 0.41, 0.01, 0.009, 0.002, 1, "g"), # Carota
                   (5, "g (cotta)", 0.35, 0.009, 0.008, 0.001, 1, "g"), # Carota
                   (5, "Intero (61 g cruda)", 25.01, 0.61, 0.55, 0.12, 61, "g"), # Carota
                   (5, "Intero (61 g cotta)", 21.35, 0.55, 0.49, 0.06, 61, "g"), # Carota
                   (5, "Intero (150 g cruda)", 61.5, 1.5, 1.35, 0.3, 150, "g"), # Carota
                   (5, "Intero (150 g cotta)", 52.5, 1.35, 1.2, 0.15, 150, "g"), # Carota
                   (6, "g", 0.23, 0.004, 0.003, 0.001, 1, "g"), # Spinaci
                   (6, "Portata (120 g)", 27.6, 0.48, 0.36, 0.12, 120, "g" ), # Spinaci
                   (6, "Portata (340 g)", 78.2, 1.36, 1.02, 0.34, 340, "g" ), # Spinaci
                   (7, "ml", 0.42, 0.05, 0.03, 0.01, 1, "ml"), # Latte
                   (7, 'Tazza (300 ml)', 126, 15, 9, 3, 300, "ml"), # Latte
                   (8, "ml", 0.44, 0.06, 0.04, 0.01, 1, "ml"), # Latte Macchiato
                   (8, "Tazza (300 ml)", 132, 18, 12, 3, 300, "ml"), # Latte Macchiato
                   (9, "g", 1.2, 0.01, 0.12, 0.09, 1, "g"), # Uova
                   (9, "Pezzo (60 g)", 72, 0.06, 7.2, 5.4, 60, "g"), #Uova
                   (10, "g", 0.89, 0.23, 0.002, 0.001, 1, "g"), # Banana
                   (10, "Frutto (150 g)", 133.5, 34.5, 0.3, 0.15, 150, "g"), # Banana
                   (11, "g", 1.65, 0, 0.31, 0.036, 1, "g"), # Pollo
                   (11, "Arrosto (178 g)", 425, 0, 48.6, 24.2, 178, "g"), # Pollo
                   (11, "Sovracoscia (116 g)", 266, 0, 26.3, 17.2, 116, "g"), # Pollo
                   (11, "Petto (58 g)", 114, 0, 17.3, 4.5, 58, "g"), # Pollo
                   (11, "Hamburger (60 g)", 172, 7, 8.9, 11.7, 60, "g"), # Pollo
                   (12, "Piatto (350 g)", 460, 80.5, 10.4, 6.2, 350, "g"), # Pasta al Pomodoro
                   (12, "g", 1.31, 0.23, 0.03, 0.02, 1, "g"), # Pasta al Pomodoro
                   (13, "g", 0.14, 0.03, 0.009, 0.003, 1, "g"), # Cetriolo
                   (13, "Singolo (120 g)", 16.8, 3.6, 1.08, 0.36, 120, "g"), # Cetriolo
                   (13, "Metà (60 g)", 8.4, 1.8, 0.54, 0.18, 60, "g"), # Cetriolo
                   (13, "A fette (86 g)", 12.04, 2.58, 0.774, 0.258, 86, "g"), # Cetriolo
                   (14, "g", 3.5, 0.75, 0.07, 0., 1, "g"), # Riso Bianco
                   (14, "Piatto (150 g)", 750, 112.5, 10.5, 0, 150, "g"), # Riso Bianco
                   (14, "Piatto (300 g)", 1050, 225, 21, 0, 300, "g"), # Riso Bianco
                   (15, "g", 2.47, 0, 0.27, 0.15, 1, "g"), # Tonno
                   (15, "Scatoletta (80 g)", 197.6, 0, 21.6, 12, 80, "g"), # Tonno
                   (16, "g", 0.16, 0.04, 0.01, 0.005, 1, "g"), # Zucchine
                   (16, "Pezzo (100 g)", 16, 4, 1, 0.5, 100, "g"), # Zucchine
                   (17, "g", 0.42, 0.08, 0.03, 0.002, 1, "g"), # Piselli
                   (17, "Cucchiaio (75 g)", 31.5, 6, 2.25, 0.15, 75, "g"), # Piselli
                   (18, "ml", 0.42, 0.05, 0.03, 0.01, 1, "ml"), # Yogurt
                   (18, 'Ciotola (200 ml)', 84, 10, 6, 2, 200, "ml"), # Yogurt
                   (19, "ml", 0.2, 0.02, 0.03, 0.01, 1, "ml"), # Caffè
                   (19, 'Tazzina (30 ml)', 6, 0.6, 0.9, 0.3, 30, "ml"), # Caffè
                   (20, "g", 1.8, 0, 0.34, 0.04, 1, "g"), # Manzo
                   (20, "Bistecca (150 g)", 270, 0, 51, 6, 150, "g"), # Manzo
                   (20, "Hamburger (80 g)", 144, 0, 27.2, 3.2, 80, "g"), # Manzo 
                   (21, "Piatto (340 g)", 625, 74.8, 14.2, 28.2, 340, "g"), # Pasta Genovese
                   (21, "g", 1.84, 0.22, 0.042, 0.21, 1, "g"), # Pasta Genovese
                   (22, "Bistecca (160 g)", 384, 0, 64, 14.4, 160, "g"), # Bistecca di Maiale
                   (22, "g", 2.4, 0, 0.4, 0.144, 1, "g"), # Bistecca di Maiale
                   (23, "g", 0.47, 0.1, 0.04, 0.004, 1, "g"), # Carciofi
                   (23, "Foglia (28 g)", 13.16, 2.8, 0.224, 0.0224, 28, "g"), # Carciofi
                   (24, "g", 1.84, 0, 0.22, 0.096, 1, "g"), # Pesce Spada
                   (24, "Filetto (180 g)", 331.2, 0, 39.6, 172.8, 180, "g"), # Pesce Spada
                   (25, "g", 0.16, 0.035, 0.009, 0.0032, 1, "g"), # Radicchio
                   (25, "Foglia (24 g)", 3.84, 0.84, 0.216, 0.0768, 24, "g"), # Radicchio
                   (26, "g", 0.43, 0.09, 0.008, 0.002, 1, "g"), # Arancia
                   (26, "Frutto (130 g)", 55.9, 11.7, 1.04, 0.26, 130, "g"), # Arancia
                   (27, "g", 1.55, 0, 0.31, 0.08, 1, "g"), # Sogliola al Limone
                   (27, "Filetto (150 g)", 232.5, 0, 46.5, 12, 150, "g"), # Sogliola
                   (28, "g", 0.27, 0.05, 0.024, 0.0027, 1, "g"), # Asparagi
                   (28, "Pezzo (90 g)", 24.3, 4.5, 2.16, 0.243, 90, "g"), # Asparagi
                   (29, "g", 0.3, 0.075, 0.005, 0.001, 1, "g"), # Mirtilli
                   (29, "Pugno (100 g)", 30, 7.5, 0.5, 0.1, 100, "g"), # Mirtilli
                   (30, "Piatto (160 g)", 250, 45, 6.8, 4.2, 160, "g"), # Pasta al Nero di Seppia
                   (31, "g", 1.19, 0.02, 0.23, 0.001, 1, "g"), # Gamberi
                   (32, "g", 0.23, 0.004, 0.002, 0.001, 1, "g"), # Cavolo
                   (33, "Piatto (300 g)", 550, 48, 20, 30, 300, "g"), # Lasagna al Forno
                   (33, "g", 1.83, 0.16, 0.067, 0.1, 1, "g"), # Lasagna al Forno
                   (34, "Piatto (150 g)", 257.14, 42.85, 5.14, 8.57, 150, "g"), # Pasta al Limone
                   (34, "g", 1.71, 0.28, 0.006, 0.3, 1, "g"), # Pasta al Limone
                   (35, "Piatto (150 g)", 284.12, 27.63, 9.95 , 15, 150, "g"), # Pasta alla Carbonara
                   (35, "g", 1.89, 0.19, 0.0672, 0.1064, 1, "g"), # Pasta alla Carbonara
                   (36, "Piatto (450 g)", 650, 100, 11, 25, 450, "g"), # Gnocchi al Pesto
                   (36, "g", 1.44, 0.36, 0.036, 0.09, 1, "g"), # Gnocchi al Pesto
                   (37, "Piatto (380 g)", 680, 100, 12.8, 28.4, 380, "g"), # Tagliatelle al Tartufo
                   (37, "g", 1.79, 0.26, 0.049, 0.2176, 1, "g"), # Tagliatelle al Tartufo
                   (38, "Piatto (350 g)", 560, 92, 12, 18, 350, "g"), # Risotto ai Funghi
                   (38, "g", 1.6, 0.26, 0.0056, 0.18, 1, "g"), # Risotto ai Funghi
                   (39, "Piatto (400 g)", 780, 90, 26.4, 36.4, 400, "g"), # Farfalle al Salmone
                   (39, "g", 1.95, 0.23, 0.066, 0.091, 1, "g"), # Farfalle al Salmone
                   (40, "Piatto (420 g)", 720, 80, 24.8, 36.4, 420, "g"), # Cannelloni
                   (40, "g", 1.71, 0.19, 0.065, 0.0966, 1, "g"), # Cannelloni
                   (41, "Piatto (350 g)", 650, 100, 11.2, 19.6, 350, "g"), # Linguine al Pesto
                   (41, "g", 1.85, 0.28, 0.0042, 0.1484, 1, "g"), # Linguine al Pesto
                   (42, "Piatto (380 g)", 670, 80, 14.4, 32, 380, "g"), # Penne alla Vodka
                   (42, "g", 1.7632, 0.2105, 0.0474, 0.0842, 1, "g"), # Penne alla Vodka
                   (43, "Piatto (320 g)", 540, 100, 12.8, 8.8, 320, "g"), # Fusilli Primavera
                   (43, "g", 1.6875, 0.3125, 0.0075, 0.0275, 1, "g"), # Fusilli Primavera
                   (44, "Piatto (400 g)", 700, 90, 18, 32, 400, "g"), # Tagliatelle al Tartufo
                   (44, "g", 1.75, 0.225, 0.045, 0.08, 1, "g"), # Tagliatelle al Tartufo
                   (45, "Piatto (420 g)", 750, 110, 14, 30, 420, "g"), # Risotto ai Funghi
                   (45, "g", 1.7857, 0.2619, 0.0333, 0.0714, 1, "g"), # Risotto ai Funghi
                   (46, "Piatto (380 g)", 660, 85, 12, 30, 380, "g"), # Linguine al Limone
                   (46, "g", 1.7368, 0.2237, 0.0411, 0.0789, 1, "g"), # Linguine al Limone
                   (47, "Piatto (450 g)", 850, 110, 24, 32, 450, "g"), # Pappardelle al Cinghiale
                   (47, "g", 1.8889, 0.2444, 0.0533, 0.0711, 1, "g"), # Pappardelle al Cinghiale
                   (48, "Piatto (150 g)", 300, 35, 14, 12, 150, "g"), # Lasagne alla Bolognese
                   (48, "g", 2, 0.2333, 0.0933, 0.08, 1, "g"), # Lasagne alla Bolognese
                   (49, "Piatto (150 g)", 400, 75, 9, 9, 150, "g"), # Penne Arrabbiata
                   (49, "g", 2.6667, 0.5, 0.06, 0.06, 1, "g"), # Penne Arrabbiata
                   (50, "Piatto (150 g)", 200, 23, 10, 9, 150, "g"), # Tortellini in Brodo
                   (50, "g", 1.3333, 0.15, 0.0667, 0.06, 1, "g"), # Tortellini in Brodo
                   (51, 'Porzione (200 g)', 270, 0, 54, 6, 200, 'g'), # Pollo al Curry
                   (51, 'g', 1.35, 0, 0.27, 0.03, 1, 'g'), # Pollo al Curry
                   (52, 'Bistecca (250 g)', 525, 0, 65, 30, 250, 'g'), # Bistecca alla Griglia
                   (52, 'g', 2.1, 0, 0.26, 0.12, 1, 'g'), # Bistecca alla Griglia
                   (53, 'Piatto (180 g)', 396, 0, 54, 18, 180, 'g'), # Salmone alla Brace
                   (53, 'g', 2.2, 0, 0.3, 0.1, 1, 'g'), # Salmone alla Brace
                   (54, 'Piatto (180 g)', 318, 0, 45, 15, 180, 'g'), # Petto Anatra con Arancia
                   (54, 'g', 1.77, 0, 0.25, 0.0833, 1, 'g'), # Petto Anatra con Arancia
                   (55, 'Costolette di Maiale (300 g)', 630, 0, 45, 48, 300, 'g'), # Costolette di Maiale
                   (55, 'g', 2.1, 0, 0.15, 1.6, 1, 'g'), # Costolette di Maiale
                   (56, 'Piatto (200 g)', 340, 0, 42, 18, 200, 'g'), # Trota al Forno
                   (56, 'g', 1.7, 0, 0.21, 0.09, 1, 'g'), # Trota al Forno
                   (57, 'Piatto (100 g)', 230, 0, 19, 16.5, 100, 'g'), # Braciola di Maiale
                   (57, 'Piatto (250 g)', 575, 0, 47.5, 41.25, 250, 'g'), # Braciola di Maiale
                   (57, 'g', 2.3, 0, 0.19, 0.165, 1, 'g'), # Braciola di Maiale
                   (58, 'Piatto (220 g)', 430, 0, 56, 20, 220, 'g'), # Baccalà alla Vicentina
                   (58, 'g', 1.95, 0, 0.2545, 0.1136, 1, 'g'), # Baccalà alla Vicentina
                   (59, 'Piatto (180 g)', 270, 0, 54, 6, 180, 'g'), # Merluzzo alla Griglia
                   (59, 'g', 1.5, 0, 0.3, 0.067, 1, 'g'), # Merluzzo alla Griglia
                   (60, 'Piatto (100 g)', 196.67, 0, 24, 9, 100, 'g'), # Cotoletta di Pollo
                   (60, 'g', 1.9667, 0, 0.24, 0.09, 1, 'g'), # Cotoletta di Pollo
                   (61, 'Piatto (250 g)', 275, 0, 50, 7.5, 250, 'g'), # Gamberoni alla Brace
                   (61, 'g', 1.1, 0, 0.2, 0.03, 1, 'g'), # Gamberoni alla Brace
                   (62, 'Piatto (300 g)', 720, 0, 42, 60, 300, 'g'), # Pancia di Maiale Arrosto
                   (62, 'g', 2.4, 0, 0.14, 1.2, 1, 'g'), # Pancia di Maiale Arrosto
                   (63, 'Piatto (220 g)', 300, 0, 50, 10, 220, 'g'), # Sogliola alla Mugnaia
                   (63, 'g', 1.3636, 0, 0.2273, 0.0455, 1, 'g'), # Sogliola alla Mugnaia
                   (64, 'Piatto (280 g)', 520, 0, 52.5, 16.8, 280, 'g'), # Pollo alla Milanese
                   (64, 'g', 1.8571, 0, 0.1875, 0.06, 1, 'g'), # Pollo alla Milanese
                   (65, 'Piatto (250 g)', 520, 0, 57.5, 30, 250, 'g'), # Filetto di Maiale al Rosmarino
                   (65, 'g', 2.08, 0, 0.23, 1.2, 1, 'g'), # Filetto di Maiale al Rosmarino
                   (66, 'Piatto (180 g)', 216, 0, 49.5, 1.8, 180, 'g'), # Tonno Grigliato
                   (66, 'g', 1.2, 0, 0.275, 0.01, 1, 'g'), # Tonno Grigliato
                   (67, 'Piatto (220 g)', 280, 0, 38.5, 11, 220, 'g'), # Scaloppine al Vino Bianco
                   (67, 'g', 1.2727, 0, 0.175, 0.05, 1, 'g'), # Scaloppine al Vino Bianco
                   (68, 'Piatto (280 g)', 420, 0, 63, 17.6, 280, 'g'), # Agnello alla Menta
                   (68, 'g', 1.5, 0, 0.225, 0.08, 1, 'g'), # Agnello alla Menta
                   (69, 'Piatto (180 g)', 230, 0, 45, 5, 180, 'g'), # Filetto di Sogliola ai Capperi
                   (69, 'g', 1.2778, 0, 0.25, 0.0278, 1, 'g'), # Filetto di Sogliola ai Capperi
                   (70, 'Piatto (250 g)', 350, 0, 47.5, 16.25, 250, 'g'), # Vitello Tonnato
                   (70, 'g', 1.4, 0, 0.19, 0.065, 1, 'g'), # Vitello Tonnato
                   (71, 'Piatto (280 g)', 392, 0, 56, 16.8, 280, 'g'), # Coniglio alla Senape
                   (71, 'g', 1.4, 0, 0.2, 0.06, 1, 'g'), # Coniglio alla Senape
                   (72, 'Piatto (200 g)', 180, 40, 4, 0, 200, 'g'), # Purè di Patate
                   (72, 'g', 0.9, 0.2, 0.02, 0, 1, 'g'), # Purè di Patate
                   (73, 'Piatto (250 g)', 150, 8, 6, 12, 250, 'g'), # Insalata Caprese
                   (73, 'g', 0.6, 0.032, 0.024, 0.048, 1, 'g'), # Insalata Caprese
                   (74, 'Piatto (180 g)', 60, 10, 2, 2, 180, 'g'), # Verdure Grigliate
                   (74, 'g', 0.33, 0.0556, 0.0111, 0.0111, 1, 'g'), # Verdure Grigliate
                   (75, 'Piatto (150 g)', 50, 10, 5, 0, 150, 'g'), # Funghi Trifolati
                   (75, 'g', 0.33, 0.0667, 0.0333, 0, 1, 'g'), # Funghi Trifolati
                   (76, 'Piatto (180 g)', 180, 8, 4, 14, 180, 'g'), # Asparagi al Burro
                   (76, 'g', 1, 0.0444, 0.0222, 0.0778, 1, 'g'), # Asparagi al Burro
                   (77, 'Piatto (150 g)', 80, 15, 2, 2, 150, 'g'), # Zucchine Grigliate
                   (77, 'g', 0.53, 0.1, 0.0133, 0.0133, 1, 'g'), # Zucchine Grigliate
                   (78, 'Piatto (180 g)', 90, 17, 2, 3, 180, 'g'), # Ratatouille
                   (78, 'g', 0.5, 0.0944, 0.0111, 0.0167, 1, 'g'), # Ratatouille
                   (79, 'Piatto (160 g)', 120, 15, 2, 6, 160, 'g'), # Pure di Patate al Burro
                   (79, 'g', 0.75, 0.0938, 0.0125, 0.0375, 1, 'g'), # Pure di Patate al Burro
                   (80, 'Piatto (100 g)', 20, 5, 0.5, 0, 100, 'g'), # Insalata di Pomodori
                   (80, 'Piatto (200 g)', 40, 10, 1, 0, 200, 'g'), # Insalata di Pomodori
                   (80, 'g', 0.2, 0.05, 0.005, 0, 1, 'g'), # Insalata di Pomodori
                   (81, 'Piatto (150 g)', 60, 12, 3, 1, 150, 'g'), # Asparagi alla Parmigiana
                   (81, 'g', 0.4, 0.08, 0.02, 0.0067, 1, 'g'), # Asparagi alla Parmigiana
                   (82, 'Piatto (180 g)', 90, 18, 3, 0.7, 180, 'g'), # Couscous con Verdure
                   (82, 'g', 0.5, 0.1, 0.0167, 0.0047, 1, 'g'), # Couscous con Verdure
                   (83, 'Tazza (138 g)', 22, 4.4, 1.7, 0.2, 138, 'g'), # Insalata Mista
                   (83, 'g', 0.016, 0.0003, 0.0001, 0.0001, 1, 'g'), # Insalata Mista
                   (84, 'Piatto (200 g)', 80, 20, 2, 0.5, 200, 'g'), # Pomodori al Forno
                   (84, 'g', 0.4, 0.1, 0.01, 0.0025, 1, 'g'), # Pomodori al Forno
                   (85, 'Piatto (170 g)', 85, 20, 3, 0.5, 170, 'g'), # Funghi Saltati
                   (85, 'g', 0.5, 0.1176, 0.0176, 0.0029, 1, 'g'), # Funghi Saltati
                   (86, 'Piatto (150 g)', 90, 15, 3, 3, 150, 'g'), # Asparagi al Burro
                   (86, 'g', 0.6, 0.1, 0.02, 0.02, 1, 'g'), # Asparagi al Burro
                   (87, 'Piatto (160 g)', 95, 15, 2, 3, 160, 'g'), # Patate Arrosto
                   (87, 'g', 0.5938, 0.0938, 0.0125, 0.0188, 1, 'g'), # Patate Arrosto
                   (88, 'Piatto (150 g)', 70, 15, 2, 1, 150, 'g'), # Cavolfiore al Vapore
                   (88, 'g', 0.4667, 0.1, 0.0133, 0.0067, 1, 'g'), # Cavolfiore al Vapore
                   (89, 'Piatto (125 g)', 250, 31.25, 6.25, 11.25, 125, 'g'), #Tiramisù
                   (89, 'g', 2, 0.25, 0.05, 0.09, 1, 'g'), #Tiramisù
                   (90, 'Piatto (150 g)', 280, 30, 6, 14, 150, 'g'), # Panna Cotta
                   (90, 'g', 1.8667, 0.2, 0.04, 0.1867, 1, 'g'), # Panna Cotta
                   (91, 'Piatto (170 g)', 340, 44, 6, 15, 170, 'g'), # Crostata di Frutta
                   (91, 'g', 2, 0.2353, 0.0412, 0.1765, 1, 'g'),  # Crostata di Frutta
                   (92, 'Piatto (180 g)', 360, 44, 8, 16, 180, 'g'),  # Profiteroles
                   (92, 'g', 2, 0.2222, 0.0494, 0.0889, 1, 'g'),  # Profiteroles
                   (93, 'Pallina (60 g)', 120, 15, 3, 5.4, 60, 'g'),  # Gelato alla Vaniglia
                   (93, 'Piatto (160 g)', 320, 40, 8, 14.4, 160, 'g'),  # Gelato alla Vaniglia
                   (93, 'g', 2, 0.25, 0.05, 0.09, 1, 'g'),  # Gelato alla Vaniglia
                   (94, 'Piatto (150 g)', 450, 45, 7.5, 27, 150, 'g'), # Cheesecake al Cioccolato
                   (94, 'g', 3, 0.3, 0.05, 0.18, 1, 'g'), # Cheesecake al Cioccolato
                   (95, 'Piatto (140 g)', 380, 44, 5, 20, 140, 'g'), # Torta al Limone
                   (95, 'g', 2.7143, 0.3143, 0.004, 0.1429, 1, 'g'), # Torta al Limone
                   (96, 'Piatto (160 g)', 480, 46, 6, 28, 160, 'g'), # Mousse al Cioccolato
                   (96, 'g', 3, 0.2875, 0.0375, 0.175, 1, 'g'), # Mousse al Cioccolato
                   (97, 'Piatto (125 g)', 325, 37.5, 5, 17.5, 125, 'g'), # Tiramisù alle Fragole
                   (97, 'g', 2.6, 0.3, 0.04, 0.14, 1, 'g'), # Tiramisù alle Fragole
                   (98, 'Piatto (140 g)', 420, 46, 5, 24, 140, 'g'), # Panna Cotta al Caramello
                   (98, 'g', 3, 0.3214, 0.0357, 0.1714, 1, 'g'), # Panna Cotta al Caramello
                   (99, 'Piatto (120 g)', 360, 42, 6, 18, 120, 'g'), # Muffin al Cioccolato
                   (99, 'g', 3, 0.35, 0.06, 0.15, 1, 'g'), # Muffin al Cioccolato
                   (100, 'Piatto (100 g)', 364, 38, 6, 22, 100, 'g'), # Cannoli Siciliani
                   (100, 'g', 3.64, 0.38, 0.06, 0.22, 1, 'g'), # Cannoli Siciliani
                   (101, 'Piatto (160 g)', 512, 72, 8, 24, 160, 'g'), # Pandoro
                   (101, 'g', 3.2, 0.45, 0.07, 0.15, 1, 'g'), # Pandoro
                   (102, 'Piatto (150 g)', 405, 45, 6, 24, 150, 'g'), # Zuppa Inglese
                   (102, 'g', 2.7, 0.3, 0.04, 0.16, 1, 'g'), # Zuppa Inglese
                   (103, 'Piatto (125 g)', 375, 45, 6.25, 19.375, 125, 'g'), # Cheesecake
                   (103, 'g', 3, 0.36, 0.05, 0.155, 1, 'g'), # Cheesecake
                   (104, 'Pallina (60 g)', 156, 16.2, 2.52, 9, 60, 'g'),  # Gelato al Cioccolato
                   (104, 'Piatto (150 g)', 390, 41.1, 6.3, 22.5, 150, 'g'), # Gelato al Cioccolato
                   (104, 'g', 2.6, 0.27, 0.042, 0.15, 1, 'g'), # Gelato al Cioccolato
                   (105, 'Piatto (100 g)', 292, 26.9, 4.6, 18.2, 100, 'g'), # Cannolo al Pistacchio
                   (105, 'g', 2.92, 0.269, 0.046, 0.182, 1, 'g'), # Cannolo al Pistacchio
                   (106, 'Piatto (200 g)', 74, 19, 1, 0, 200, 'g'), # Macedonia di Frutta
                   (106, 'g', 0.37, 0.095, 0.005, 0, 1, 'g'), # Macedonia di Frutta
                   (107, 'Piatto (160 g)', 544, 76.8, 9.6, 22.4, 160, 'g'), # Panettone
                   (107, 'g', 3.4, 0.48, 0.06, 0.14, 1, 'g'), # Panettone
                   (108, 'Pallina (60 g)', 43, 8.6, 0.4, 0.4, 60, 'g'), # Gelato alla Fragola
                   (108, 'Pallina', 43, 8.6, 0.4, 0.4, 60, 'g'), # Gelato alla Fragola
                   (109, 'Pallina (60 g)', 44, 10.2, 0.5, 0.2, 60, 'g'), # Gelato al Limone
                   (109, 'Pallina', 44, 10.2, 0.5, 0.2, 60, 'g'), # Gelato al Limone
                   (110, 'Cono (4 g)', 17, 3.2, 0.3, 0.3, 1, 'Cono'), # Cono Gelato
                   (111, 'Pallina (60 g)', 43.8, 9.0, 0.6, 0.6, 60, 'g'), # Gelato al Pistacchio
                   (111, 'g', 0.73, 0.15, 0.01, 0.01, 1, 'g'), # Gelato al Pistacchio
                   (112, 'Pallina (60 g)', 43.8, 9.0, 0.6, 0.6, 60, 'g'), # Gelato alla Fragola e Limone
                   (112, 'g', 0.73, 0.15, 0.01, 0.01, 1, 'g'), # Gelato alla Fragola e Limone
                   (113, 'Pallina (60 g)', 43.8, 9.0, 0.6, 0.6, 60, 'g'), # Gelato alla Nocciola
                   (113, 'g', 0.73, 0.15, 0.01, 0.01, 1, 'g'), # Gelato alla Nocciola
                   (114, 'Pallina (60 g)', 43.8, 9.0, 0.6, 0.6, 60, 'g'), # Gelato al Cocco
                   (114, 'g', 0.73, 0.15, 0.01, 0.01, 1, 'g'), # Gelato al Cocco
                   (115, 'Pallina (60 g)', 43.8, 9.0, 0.6, 0.6, 60, 'g'), # Gelato alla Vaniglia
                   (115, 'g', 0.73, 0.15, 0.01, 0.01, 1, 'g'), # Gelato alla Vaniglia
                   (116, 'Pallina (60 g)', 43.8, 9.0, 0.6, 0.6, 60, 'g'), # Gelato al Cioccolato Fondente
                   (116, 'g', 0.73, 0.15, 0.01, 0.01, 1, 'g'), # Gelato al Cioccolato Fondente
                   (117, 'Pallina (60 g)', 43.8, 9.0, 0.6, 0.6, 60, 'g'), # Gelato al Caramello Salato
                   (117, 'g', 0.73, 0.15, 0.01, 0.01, 1, 'g'), # Gelato al Caramello Salato 
                   (118, 'Pallina (60 g)', 43.8, 9.0, 0.6, 0.6, 60, 'g'), # Gelato alla Crema
                   (118, 'g', 0.73, 0.15, 0.01, 0.01, 1, 'g'), # Gelato alla Crema
                   (119, 'ml', 0.4, 8.2, 0.5, 0.1, 1, 'ml'), # Succo d'Arancia
                   (119, 'Lattina (330 ml)', 132, 26.7, 1.7, 0.3, 330, 'ml'), # Succo d'Arancia
                   (119, 'Bicchiere (250 ml)', 100, 20.5, 1.3, 0.3, 250, 'ml'), # Succo d'Arancia
                   (120, 'ml', 0.42, 10.6, 0.0, 0.0, 1, 'ml'), # Coca Cola
                   (120, 'Lattina (330 ml)', 139, 35, 0, 0, 330, 'ml'), # Coca Cola
                   (121, 'ml', 0.2, 0.1, 0.0, 0.0, 1, 'ml'), # Ginseng
                   (121, 'Bicchiere (137 ml)', 27.4, 13.7, 0, 0, 137, 'ml'), # Ginseng
                   (122, 'ml', 73, 0, 0, 0, 1, 'ml'), # Tequila
                   (122, 'Calice (100 ml)', 73, 0, 0, 0, 100, 'ml'), # Tequila
                   (123, 'ml', 83, 2.4, 0.1, 0.0, 1, 'ml'), # Vino Rosso
                   (123, 'Calice (100 ml)', 83, 2.4, 0.1, 0.0, 100, 'ml'), # Vino Rosso
                   (124, 'ml', 43, 3.6, 0.5, 0.0, 1, 'ml'), # Birra
                   (124, 'Lattina (330 ml)', 142, 12, 1.7, 0.0, 330, 'ml'), # Birra
                   (124, 'Pinta (500 ml)', 215, 18, 2.5, 0.0, 500, 'ml'), # Birra
                   (125, 'ml', 82, 0.8, 0.1, 0.0, 1, 'ml'), # Vino Bianco
                   (125, 'Calice (100 ml)', 82, 0.8, 0.1, 0.0, 100, 'ml'), # Vino Bianco
                   (126, 'ml', 43, 3.6, 0.5, 0.0, 1, 'ml'), # Birra Bionda
                   (126, 'Lattina (330 ml)', 142, 12, 1.7, 0.0, 330, 'ml'), # Birra Bionda
                   (126, 'Pinta (500 ml)', 215, 18, 2.5, 0.0, 500, 'ml'), # Birra Bionda
                   (127, 'ml', 43, 3.6, 0.5, 0.0, 1, 'ml'), # Birra Rossa
                   (127, 'Lattina (330 ml)', 142, 12, 1.7, 0.0, 330, 'ml'), # Birra Rossa
                   (127, 'Pinta (500 ml)', 215, 18, 2.5, 0.0, 500, 'ml'), # Birra Rossa
                   (128, 'ml', 43, 11, 0, 0, 1, 'ml'), # Mojito
                   (128, 'Calice (100 ml)', 43, 11, 0, 0, 100, 'ml'), # Mojito
                   (129, 'ml', 5.2, 0.13, 0, 0, 1, 'ml'), # Mojito Soda
                   (129, 'Lattina (330 ml)', 172, 42.9, 0, 0, 100, 'ml'), # Mojito Soda
                   (130, 'ml', 43, 11, 0, 0, 1, 'ml'), # Lemon Soda
                   (130, 'Lattina (330 ml)', 142, 37, 0, 0, 330, 'ml'), # Lemon Soda
                   (130, 'Bicchiere (250 ml)', 107, 28, 0, 0, 250, 'ml'), # Lemon Soda
                   (131, 'ml', 43, 10, 0, 0, 1, 'ml'), # Aranciata
                   (131, 'Lattina (330 ml)', 142, 33, 0, 0, 330, 'ml'), # Aranciata
                   (131, 'Bicchiere (250 ml)', 107, 25, 0, 0, 250, 'ml'), # Aranciata
                   (132, 'ml', 54, 13, 0.4, 0.2, 1, 'ml'), # Succo di Frutta
                   (132, 'Lattina (330 ml)', 178, 43, 1.3, 0.6, 330, 'ml'), # Succo di Frutta
                   (132, 'Bicchiere (250 ml)', 135, 33, 1, 0.5, 250, 'ml'), # Succo di Frutta
                   (133, 'ml', 43, 11, 0, 0, 1, 'ml'), # Sprite
                   (133, 'Lattina (330 ml)', 142, 37, 0, 0, 330, 'ml'), # Sprite
                   (133, 'Bicchiere (250 ml)', 107, 28, 0, 0, 250, 'ml'), # Sprite
                   (134, 'ml', 1, 0.2, 0.0, 0.0, 1, 'ml'), # Te
                   (134, 'Calice (100 ml)', 1, 0.2, 0.0, 0.0, 100, 'ml'), # Te
                   (135, 'g', 0.331, 0.123, 0.046, 0.173, 1, 'g'), # Cornetto Vuoto
                   (135, 'Croissant (60 g)', 19.9, 7.3, 2.7, 10.4, 60, 'g'), # Cornetto Vuoto
                   (136, 'g', 0.675, 0.174, 0.057, 0.346, 1, 'g'), # Cornetto alla Crema
                   (136, 'Croissant (60 g)', 40.5, 10.4, 3.4, 20.8, 60, 'g'), # Cornetto alla Crema
                   (137, 'g', 0.690, 0.205, 0.060, 0.355, 1, 'g'), # Cornetto alla Nutella
                   (137, 'Croissant (60 g)', 41.4, 12.3, 3.6, 21.3, 60, 'g'), # Cornetto alla Nutella
                   (138, 'g', 0.693, 0.206, 0.061, 0.357, 1, 'g'), # Cornetto al Cioccolato
                   (138, 'Croissant (60 g)', 41.6, 12.4, 3.7, 21.4, 60, 'g'), # Cornetto al Cioccolato
                   (139, 'g', 0.696, 0.207, 0.061, 0.359, 1, 'g'), # Cornetto alla Marmellata
                   (139, 'Croissant (60 g)', 41.8, 12.4, 3.7, 21.5, 60, 'g'), # Cornetto alla Marmellata
                   (140, 'g', 0.699, 0.208, 0.062, 0.361, 1, 'g'), # Cornetto alla Marmellata
                   (140, 'Croissant (60 g)', 42.0, 12.5, 3.7, 21.6, 60, 'g'); # Cornetto alla Marmellata
UNLOCK TABLES;

LOCK TABLES Pasto WRITE;
    INSERT INTO Pasto(Utente, Cibo, Data, Catalogazione, Orario, Quantita)
    VALUES   ('f.panattoni2@studenti.unipi.it', '1', '2023-10-23', 'Piatto (360 g)', 'Cena', '2'),
                        ('f.panattoni2@studenti.unipi.it', '1', '2023-10-26', 'Piatto (360 g)', 'Cena', '1'),
                        ('f.panattoni2@studenti.unipi.it', '7', '2023-10-22', 'Tazza (300 ml)', 'Colazione', '1'),
                        ('f.panattoni2@studenti.unipi.it', '8', '2023-10-24', 'Tazza (300 ml)', 'Colazione', '1'),
                        ('f.panattoni2@studenti.unipi.it', '8', '2023-10-26', 'Tazza (300 ml)', 'Colazione', '2'),
                        ('f.panattoni2@studenti.unipi.it', '12', '2023-10-24', 'Piatto (350 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '12', '2023-10-26', 'Piatto (350 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '18', '2023-10-23', 'Ciotola (200 ml)', 'Colazione', '1'),
                        ('f.panattoni2@studenti.unipi.it', '19', '2023-10-25', 'Tazzina (30 ml)', 'Colazione', '2'),
                        ('f.panattoni2@studenti.unipi.it', '19', '2023-10-27', 'Tazzina (30 ml)', 'Colazione', '1'),
                        ('f.panattoni2@studenti.unipi.it', '22', '2023-10-25', 'Bistecca (160 g)', 'Cena', '2'),
                        ('f.panattoni2@studenti.unipi.it', '22', '2023-10-25', 'g', 'Cena', '40'),
                        ('f.panattoni2@studenti.unipi.it', '23', '2023-10-24', 'Foglia (28 g)', 'Cena', '1'),
                        ('f.panattoni2@studenti.unipi.it', '26', '2023-10-28', 'Frutto (130 g)', 'Cena', '1'),
                        ('f.panattoni2@studenti.unipi.it', '30', '2023-10-25', 'Piatto (160 g)', 'Pranzo', '2'),
                        ('f.panattoni2@studenti.unipi.it', '33', '2023-10-28', 'Piatto (300 g)', 'Pranzo', '2'),
                        ('f.panattoni2@studenti.unipi.it', '35', '2023-10-23', 'g', 'Pranzo', '200'),
                        ('f.panattoni2@studenti.unipi.it', '35', '2023-10-25', 'g', 'Pranzo', '180'),
                        ('f.panattoni2@studenti.unipi.it', '36', '2023-10-27', 'Piatto (450 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '40', '2023-10-22', 'Piatto (420 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '48', '2023-10-22', 'Piatto (150 g)', 'Cena', '3'),
                        ('f.panattoni2@studenti.unipi.it', '48', '2023-10-27', 'Piatto (150 g)', 'Cena', '1'),
                        ('f.panattoni2@studenti.unipi.it', '50', '2023-10-29', 'Piatto (150 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '51', '2023-10-29', 'Porzione (200 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '52', '2023-10-24', 'Bistecca (250 g)', 'Cena', '1'),
                        ('f.panattoni2@studenti.unipi.it', '52', '2023-10-26', 'Bistecca (250 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '60', '2023-10-23', 'Piatto (100 g)', 'Pranzo', '2'),
                        ('f.panattoni2@studenti.unipi.it', '60', '2023-10-27', 'Piatto (100 g)', 'Pranzo', '2'),
                        ('f.panattoni2@studenti.unipi.it', '64', '2023-10-28', 'Piatto (280 g)', 'Cena', '1'),
                        ('f.panattoni2@studenti.unipi.it', '64', '2023-10-29', 'Piatto (280 g)', 'Cena', '1'),
                        ('f.panattoni2@studenti.unipi.it', '68', '2023-10-27', 'Piatto (280 g)', 'Cena', '1'),
                        ('f.panattoni2@studenti.unipi.it', '75', '2023-10-28', 'Piatto (150 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '80', '2023-10-27', 'Piatto (100 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '83', '2023-10-23', 'Tazza (138 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '83', '2023-10-26', 'Tazza (138 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '83', '2023-10-29', 'g', 'Cena', '80'),
                        ('f.panattoni2@studenti.unipi.it', '83', '2023-10-29', 'Tazza (138 g)', 'Cena', '1'),
                        ('f.panattoni2@studenti.unipi.it', '86', '2023-10-22', 'Piatto (150 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '86', '2023-10-28', 'Piatto (150 g)', 'Cena', '1'),
                        ('f.panattoni2@studenti.unipi.it', '87', '2023-10-24', 'Piatto (160 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '89', '2023-10-22', 'g', 'Cena', '100'),
                        ('f.panattoni2@studenti.unipi.it', '89', '2023-10-24', 'Piatto (125 g)', 'Spuntini', '1'),
                        ('f.panattoni2@studenti.unipi.it', '95', '2023-10-25', 'g', 'Colazione', '100'),
                        ('f.panattoni2@studenti.unipi.it', '95', '2023-10-26', 'Piatto (140 g)', 'Spuntini', '1'),
                        ('f.panattoni2@studenti.unipi.it', '95', '2023-10-29', 'Piatto (140 g)', 'Pranzo', '1'),
                        ('f.panattoni2@studenti.unipi.it', '100', '2023-10-29', 'Piatto (100 g)', 'Cena', '3'),
                        ('f.panattoni2@studenti.unipi.it', '103', '2023-10-22', 'Piatto (125 g)', 'Colazione', '1'),
                        ('f.panattoni2@studenti.unipi.it', '103', '2023-10-28', 'Piatto (125 g)', 'Colazione', '2'),
                        ('f.panattoni2@studenti.unipi.it', '104', '2023-10-28', 'Pallina (60 g)', 'Spuntini', '1'),
                        ('f.panattoni2@studenti.unipi.it', '110', '2023-10-28', 'Cono (4 g)', 'Spuntini', '1'),
                        ('f.panattoni2@studenti.unipi.it', '110', '2023-10-29', 'Cono (4 g)', 'Colazione', '1'),
                        ('f.panattoni2@studenti.unipi.it', '111', '2023-10-28', 'Pallina (60 g)', 'Spuntini', '1'),
                        ('f.panattoni2@studenti.unipi.it', '114', '2023-10-28', 'Pallina (60 g)', 'Spuntini', '1'),
                        ('f.panattoni2@studenti.unipi.it', '116', '2023-10-29', 'Pallina (60 g)', 'Colazione', '1'),
                        ('f.panattoni2@studenti.unipi.it', '118', '2023-10-29', 'Pallina (60 g)', 'Colazione', '1'),
                        ('f.panattoni2@studenti.unipi.it', '119', '2023-10-27', 'Bicchiere (250 ml)', 'Colazione', '1'),
                        ('f.panattoni2@studenti.unipi.it', '120', '2023-10-26', 'Lattina (330 ml)', 'Cena', '2'),
                        ('f.panattoni2@studenti.unipi.it', '123', '2023-10-29', 'Calice (100 ml)', 'Pranzo', '2'),
                        ('f.panattoni2@studenti.unipi.it', '136', '2023-10-27', 'Croissant (60 g)', 'Colazione', '1'),
                        ('f.panattoni2@studenti.unipi.it', '137', '2023-10-23', 'Croissant (60 g)', 'Spuntini', '1'),
                        ('f.panattoni2@studenti.unipi.it', '137', '2023-10-29', 'g', 'Spuntini', '75'),
                        ('f.panattoni2@studenti.unipi.it', '138', '2023-10-24', 'Croissant (60 g)', 'Colazione', '1');    
UNLOCK TABLES;
  
LOCK TABLES Esercizi WRITE;
    INSERT INTO Esercizi (Nome, UnitaMisura, Calorie, Autore)
    VALUES
      ('Camminare', 'passi', 0.07, 'Monitor Alimentare'),
      ('Camminare', 'min', 3.9, 'Monitor Alimentare'),
      ('Dormire', 'h', 0.1, 'Monitor Alimentare'),
      ('Basket', 'min', 7.4, 'Monitor Alimentare'),
      ('Ciclismo', 'min', 5.8, 'Monitor Alimentare'),
      ('Corsa', 'min', 10.9, 'Monitor Alimentare'),
      ('Nuoto', 'min', 7.7, 'Monitor Alimentare'),
      ('Yoga', 'min', 2.2, 'Monitor Alimentare'),
      ('Sollevamento Pesi', 'min', 5.2, 'Monitor Alimentare'),
      ('Allenamento HIIT', 'min', 12.1, 'Monitor Alimentare'),
      ('Crossfit', 'min', 9.6, 'Monitor Alimentare'),
      ('Pilates', 'min', 2.8, 'Monitor Alimentare'),
      ('Zumba', 'min', 6.3, 'Monitor Alimentare'),
      ('Tai Chi', 'min', 3.4, 'Monitor Alimentare'),
      ('Calcio', 'min', 8.5, 'Monitor Alimentare'),
      ('Boxe', 'min', 10.3, 'Monitor Alimentare'),
      ('Studiare', 'min', 1.3, 'Monitor Alimentare'),
      ('Scherma', 'min', 5.8, 'Monitor Alimentare'),
      ('Scherma Medievale', 'min', 7, 'Monitor Alimentare'),
      ('Pattinare', 'min', 6.5, 'Monitor Alimentare'),
      ('Pallavolo', 'min', 4.9, 'Monitor Alimentare'),
      ('Scalare', 'min', 8.7, 'Monitor Alimentare'),
      ('Ginnastica Artistica', 'min', 6.1, 'Monitor Alimentare'),
      ('Canottaggio', 'min', 7.3, 'Monitor Alimentare'),
      ('Sci di Fondo', 'min', 9.0, 'Monitor Alimentare'),
      ('Canoa', 'min', 5.6, 'Monitor Alimentare'),
      ('Pallavolo sulla Spiaggia', 'min', 5.2, 'Monitor Alimentare'),
      ('Escursionismo', 'min', 4.2, 'Monitor Alimentare'),
      ('Aerobica', 'min', 5.7, 'Monitor Alimentare'),
      ('Golf', 'min', 4.0, 'Monitor Alimentare'),
      ('Aerobica Acquatica', 'min', 6.8, 'Monitor Alimentare'),
      ('Karate', 'min', 8.1, 'Monitor Alimentare'),
      ('Paddleboarding', 'min', 3.5, 'Monitor Alimentare'),
      ('Pattinaggio su Ghiaccio', 'min', 7.2, 'Monitor Alimentare'),
      ('Nuoto Sincronizzato', 'min', 6.6, 'Monitor Alimentare'),
      ('Paddle Tennis', 'min', 4.1, 'Monitor Alimentare'),
      ('Skateboarding', 'min', 5.0, 'Monitor Alimentare'),
      ('Canottaggio Indoor', 'min', 8.0, 'Monitor Alimentare'),
      ('Allenamento Militare', 'min', 10.2, 'Monitor Alimentare'),
      ('Ping Pong', 'min', 3.0, 'Monitor Alimentare'),
      ('Alpinismo', 'min', 7.8, 'Monitor Alimentare'),
      ('Capoeira', 'min', 9.6, 'Monitor Alimentare'),
      ('Salto con il Paracadute', 'min', 4.5, 'Monitor Alimentare'),
      ('Arrampicata su Ghiaccio', 'min', 8.9, 'Monitor Alimentare'),
      ('Squash', 'min', 9.5, 'Monitor Alimentare'),
      ('Ginnastica in Acqua', 'min', 5.2, 'Monitor Alimentare'),
      ('Yoga Caldo', 'min', 5.9, 'Monitor Alimentare'),
      ('Escursionismo su Neve', 'min', 7.0, 'Monitor Alimentare'),
      ('Surf', 'min', 4.3, 'Monitor Alimentare'),
      ('Hockey su Prato', 'min', 8.7, 'Monitor Alimentare'),
      ('Beach Soccer', 'min', 6.5, 'Monitor Alimentare'),
      ('Scalata su Roccia', 'min', 9.4, 'Monitor Alimentare'),
      ('Badminton', 'min', 4.2, 'Monitor Alimentare'),
      ('Biliardo', 'min', 2.6, 'Monitor Alimentare'),
      ('Caccia', 'min', 3.5, 'Monitor Alimentare'),
      ('Hockey su Ghiaccio', 'min', 7.3, 'Monitor Alimentare'),
      ('Nuoto a Stile Libero', 'min', 8.0, 'Monitor Alimentare'),
      ('Pugilato', 'min', 11.2, 'Monitor Alimentare'),
      ('Arrampicata su Roccia Indoor', 'min', 6.1, 'Monitor Alimentare'),
      ('Pallamano', 'min', 9.0, 'Monitor Alimentare'),
      ('Powerlifting', 'min', 4.8, 'Monitor Alimentare'),
      ('Lancio del Martello', 'min', 7.9, 'Monitor Alimentare'),
      ('Nuoto con Pinne', 'min', 9.7, 'Monitor Alimentare'),
      ('Balletto Classico', 'min', 4.0, 'Monitor Alimentare'),
      ('Triathlon', 'min', 11.5, 'Monitor Alimentare');
UNLOCK TABLES;

LOCK TABLE Attivita WRITE;
    INSERT INTO Attivita(Utente, Esercizio, UnitaMisura, Data, Quantita)
    VALUES ('f.panattoni2@studenti.unipi.it', 'Balletto Classico', 'min', '2023-10-22', '10'),
                       ('f.panattoni2@studenti.unipi.it', 'Basket', 'min', '2023-10-22', '45'),
                       ('f.panattoni2@studenti.unipi.it', 'Basket', 'min', '2023-10-24', '45'),
                       ('f.panattoni2@studenti.unipi.it', 'Biliardo', 'min', '2023-10-23', '10'),
                       ('f.panattoni2@studenti.unipi.it', 'Camminare', 'min', '2023-10-23', '5'),
                       ('f.panattoni2@studenti.unipi.it', 'Camminare', 'passi', '2023-10-22', '18332'),
                       ('f.panattoni2@studenti.unipi.it', 'Camminare', 'passi', '2023-10-23', '15237'),
                       ('f.panattoni2@studenti.unipi.it', 'Camminare', 'passi', '2023-10-24', '12345'),
                       ('f.panattoni2@studenti.unipi.it', 'Camminare', 'passi', '2023-10-25', '23019'),
                       ('f.panattoni2@studenti.unipi.it', 'Camminare', 'passi', '2023-10-26', '12309'),
                       ('f.panattoni2@studenti.unipi.it', 'Camminare', 'passi', '2023-10-27', '12982'),
                       ('f.panattoni2@studenti.unipi.it', 'Camminare', 'passi', '2023-10-28', '15632'),
                       ('f.panattoni2@studenti.unipi.it', 'Camminare', 'passi', '2023-10-29', '36355'),
                       ('f.panattoni2@studenti.unipi.it', 'Dormire', 'h', '2023-10-22', '8'),
                       ('f.panattoni2@studenti.unipi.it', 'Dormire', 'h', '2023-10-23', '7'),
                       ('f.panattoni2@studenti.unipi.it', 'Dormire', 'h', '2023-10-24', '8'),
                       ('f.panattoni2@studenti.unipi.it', 'Dormire', 'h', '2023-10-25', '8'),
                       ('f.panattoni2@studenti.unipi.it', 'Dormire', 'h', '2023-10-26', '6'),
                       ('f.panattoni2@studenti.unipi.it', 'Dormire', 'h', '2023-10-27', '9'),
                       ('f.panattoni2@studenti.unipi.it', 'Dormire', 'h', '2023-10-28', '8'),
                       ('f.panattoni2@studenti.unipi.it', 'Dormire', 'h', '2023-10-29', '10'),
                       ('f.panattoni2@studenti.unipi.it', 'Salto con il Paracadute', 'min', '2023-10-28', '15'),
                       ('f.panattoni2@studenti.unipi.it', 'Studiare', 'min', '2023-10-25', '180'),
                       ('f.panattoni2@studenti.unipi.it', 'Studiare', 'min', '2023-10-26', '180');
UNLOCK TABLES;

LOCK TABLES Bicchieri WRITE;
    INSERT INTO Bicchieri(Utente, Data, Numero)
    VALUES  ('f.panattoni2@studenti.unipi.it', '2023-10-22', '4'),
                       ('f.panattoni2@studenti.unipi.it', '2023-10-23', '4'),
                       ('f.panattoni2@studenti.unipi.it', '2023-10-24', '8'),
                       ('f.panattoni2@studenti.unipi.it', '2023-10-25', '10'),
                       ('f.panattoni2@studenti.unipi.it', '2023-10-26', '4'),
                       ('f.panattoni2@studenti.unipi.it', '2023-10-27', '2'),
                       ('f.panattoni2@studenti.unipi.it', '2023-10-28', '2'),
                       ('f.panattoni2@studenti.unipi.it', '2023-10-29', '8');
UNLOCK TABLES;
