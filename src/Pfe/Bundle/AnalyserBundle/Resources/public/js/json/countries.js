var countries = {
    "Afghanistan" : "AFG",
    "Îles Åland" : "ALA",
    "Albanie" : "ALB",
    "Alg\u00e9rie" : "DZA",
    "Samoa américaine" : "ASM",
    "Andorra" : "ET",
    "Angola" : "AGO",
    "Anguilla" : "AIA",
    "Antarctica" : "ATA",
    "Antigua-et-Barbuda" : "ATG",
    "Argentine" : "ARG",
    "L'Arménie" : "ARM",
    "Aruba" : "ABW",
    "Australia" : "AUS",
    "Autriche" : "AUT",
    "Azerbaïdjan" : "AZE",
    "Bahamas" : "BHS",
    "Bahreïn" : "BHR",
    "Bangladesh" : "BGD",
    "Barbade" : "BRB",
    "Biélorussie" : "BLR",
    "Belgique" : "BEL",
    "Belize" : "BLZ",
    "B\u00e9nin" : "BEN",
    "Bermudes" : "BMU",
    "Bhoutan" : "BTN",
    "La Bolivie, État plurinational de" : "BOL",
    "Bonaire, Saint-Eustache et Saba" : "BES",
    "Bosnie-Herzégovine" : "BIH",
    "Le Botswana" : "BWA",
    "Île Bouvet" : "BVT",
    "Br\u00e9sil" : "Bra",
    "Territoire britannique de l'océan Indien" : "IOT",
    "Brunei Darussalam" : "BRN",
    "Bulgarie" : "BGR",
    "Burkina Faso" : "BFA",
    "Burundi" : "BDI",
    "Le Cambodge" : "KHM",
    "Cameroun" : "CMR",
    "Canada" : "CAN",
    "Cap Vert" : "CPV",
    "Îles Caïmans" : "CYM",
    "R\u00e9publique centrafricaine" : "CAF",
    "Tchad" : "TCD",
    "Chili" : "LCH",
    "La Chine" : "CHN",
    "Christmas Island" : "CXR",
    "Cocos (Keeling)" : "CCK",
    "Colombie" : "COL",
    "Comores" : "COM",
    "Congo" : "COG",
    "R\u00e9publique D\u00e9mocratique du Congo" : "COD",
    "Îles Cook" : "COK",
    "Costa Rica" : "CRI",
    "Côte d'Ivoire" : "CIV",
    "Croatie" : "VRC",
    "Cuba" : "CUB",
    "Curaçao" : "Nettoyons la Terre",
    "Chypre" : "CYP",
    "République tchèque" : "CZE",
    "Danemark" : "DNK",
    "Djibouti" : "DJI",
    "Dominique" : "DMA",
    "République dominicaine" : "DOM",
    "Equateur" : "écus",
    "L'Egypte" : "EGY",
    "El Salvador" : "SLV",
    "La Guinée équatoriale" : "GNQ",
    "Érythrée" : "ERI",
    "Estonie" : "EST",
    "L'Ethiopie" : "ETH",
    "Îles Falkland (Malvinas)" : "FLK",
    "Îles Féroé" : "BOF",
    "Fidji" : "FJI",
    "Finlande" : "FIN",
    "France" : "FRA",
    "Guyane fran\u00e7aise" : "FSI",
    "Polyn\u00e9sie fran\u00e7aise" : "PYF",
    "Terres australes françaises" : "ATF",
    "Gabon" : "GAB",
    "Gambie" : "GMB",
    "Georgia" : "GEO",
    "Allemagne" : "DEU",
    "Ghana" : "GHA",
    "Gibraltar" : "GIB",
    "Grèce" : "GRC",
    "Groenland" : "GRL",
    "Grenade" : "GRD",
    "Guadeloupe" : "BPL",
    "Guam" : "GUM",
    "Guatemala" : "GTM",
    "Guernesey" : "GGY",
    "Guinée" : "GIN",
    "Guinée-Bissau" : "GNB",
    "Guyane" : "GUY",
    "Haïti" : "HTI",
    "Îles Heard et McDonald" : "HMD",
    "Saint-Siège (Cité du Vatican)" : "TVA",
    "Honduras" : "HND",
    "Hong Kong" : "HKG",
    "Hongrie" : "HUN",
    "Islande" : "ISL",
    "India" : "IND",
    "Indonésie" : "IDN",
    "L'Iran, République islamique d '" : "IRN",
    "Iraq" : "IRQ",
    "Irlande" : "IRL",
    "Isle of Man" : "IMN",
    "Israël" : "ISR",
    "Italie" : "LIR",
    "Jamaica" : "JAM",
    "Japon" : "JPN",
    "Jersey" : "JEY",
    "Jordanie" : "JOR",
    "Kazakhstan" : "KAZ",
    "Kenya" : "KEN",
    "Kiribati" : "KIR",
    "La Corée, République populaire démocratique de" : "PRK",
    "La Corée, République de" : "KOR",
    "Koweït" : "KWT",
    "Kirghizistan" : "KGZ",
    "République démocratique populaire lao" : "LAO",
    "La Lettonie" : "LVA",
    "Liban" : "LBN",
    "Lesotho" : "LSO",
    "Liberia" : "LBR",
    "La Libye" : "LBY",
    "Liechtenstein" : "LIE",
    "Lituanie" : "LTU",
    "Luxembourg" : "LUX",
    "Macao" : "MAC",
    "Macédoine, l'ex-République yougoslave de" : "MKD",
    "Madagascar" : "OMD",
    "Le Malawi" : "MWI",
    "Malaisie" : "MYS",
    "Maldives" : "MDV",
    "Mali" : "MLI",
    "Malte" : "MLT",
    "Iles Marshall" : "MHL",
    "Martinique" : "MTQ",
    "Mauritanie" : "RMT",
    "Maurice" : "MUS",
    "Mayotte" : "MYT",
    "Mexique" : "MEX",
    "Micronésie, États fédérés de" : "FSM",
    "R\u00e9publique de Moldova" : "MDA",
    "Monaco" : "AGC",
    "Mongolie" : "MNG",
    "Monténégro" : "MNE",
    "Montserrat" : "MSR",
    "Maroc" : "MAR",
    "Mozambique" : "MOZ",
    "Myanmar" : "MMR",
    "Namibie" : "NAM",
    "Nauru" : "NRU",
    "Nepal" : "NPL",
    "Pays-Bas" : "NLD",
    "Nouvelle-Calédonie" : "NCL",
    "Nouvelle-Zélande" : "NZL",
    "Nicaragua" : "NIC",
    "Niger" : "TNS",
    "Nigeria" : "NGA",
    "Nioué" : "NIU",
    "Île Norfolk" : "NFK",
    "Îles Mariannes du Nord" : "MNP",
    "Norvège" : "NOR",
    "Oman" : "OMN",
    "Pakistan" : "PAK",
    "Palau" : "PLW",
    "Territoires palestiniens" : "PSE",
    "Panama" : "PAN",
    "Papouasie-Nouvelle-Guinée" : "PNG",
    "Le Paraguay" : "PRY",
    "Pérou" : "PER",
    "Philippines" : "PHL",
    "Pitcairn" : "PCN",
    "Pologne" : "POL",
    "Portugal" : "PRT",
    "Puerto Rico" : "PRI",
    "Qatar" : "QAT",
    "Réunion" : "REU",
    "La Roumanie" : "ROU",
    "Fédération de Russie" : "RUS",
    "Rwanda" : "RWA",
    "Saint-Barthélemy" : "BLM",
    "Sainte-Hélène, Ascension et Tristan da Cunha" : "SHN",
    "Saint-Kitts-et-Nevis" : "KNA",
    "Saint Lucia" : "LCA",
    "Saint Martin (partie française)" : "MAF",
    "Saint-Pierre-et-Miquelon" : "SPM",
    "Saint-Vincent-et-les Grenadines" : "CTV",
    "Samoa" : "WSM",
    "San Marino" : "SMR",
    "Sao Tomé et Principe" : "STP",
    "Arabie Saoudite" : "SAU",
    "Sénégal" : "SEN",
    "Serbie" : "SRB",
    "Seychelles" : "CJS",
    "Sierra Leone" : "SLE",
    "Singapour" : "SGP",
    "Sint Maarten (partie néerlandaise)" : "SXM",
    "Slovaquie" : "SVK",
    "Slovénie" : "SVN",
    "Iles Salomon" : "SLB",
    "La Somalie" : "SOM",
    "Afrique du Sud" : "ZAF",
    "La Géorgie du Sud et les îles Sandwich du Sud" : "SGS",
    "Sud-Soudan" : "SSD",
    "Espagne" : "ESP",
    "Sri Lanka" : "LKA",
    "Soudan" : "SDN",
    "Suriname" : "SUR",
    "Svalbard et Jan Mayen" : "SJM",
    "Swaziland" : "SWZ",
    "Suède" : "SWE",
    "Suisse" : "Che",
    "République arabe syrienne" : "SYR",
    "Taiwan, province de Chine" : "TWN",
    "Tadjikistan" : "TJK",
    "La Tanzanie, République-Unie de" : "TZA",
    "Thaïlande" : "THA",
    "Timor-Leste" : "TLS",
    "Togo" : "TGO",
    "Tokélaou" : "TKL",
    "Tonga" : "TON",
    "Trinité-et-Tobago" : "TTO",
    "Tunisie" : "TUN",
    "Turquie" : "TUR",
    "Turkménistan" : "TKM",
    "Îles Turques et Caïques" : "TCA",
    "Tuvalu" : "TUV",
    "L'Ouganda" : "UGA",
    "Ukraine" : "UKR",
    "Emirats Arabes Unis" : "ARE",
    "Royaume-Uni" : "GBR",
    "Etats-Unis" : "USA",
    "Îles mineures éloignées des États-Unis" : "UMI",
    "Uruguay" : "URY",
    "L'Ouzbékistan" : "UZB",
    "Vanuatu" : "VUT",
    "Venezuela (R\u00e9publique bolivarienne du)" : "VEN",
    "Viet Nam" : "VNM",
    "Virgin Islands, en Colombie" : "VGB",
    "Îles Vierges, États-Unis" : "VIR",
    "Wallis et Futuna" : "WLF",
    "Sahara occidental" : "ESH",
    "Yémen" : "YEM",
    "La Zambie" : "ZMB",
    "Zimbabwe" : "ZWE"
};