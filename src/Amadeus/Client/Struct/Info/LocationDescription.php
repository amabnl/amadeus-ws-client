<?php
/**
 * amadeus-ws-client
 *
 * Copyright 2015 Amadeus Benelux NV
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package Amadeus
 * @license https://opensource.org/licenses/Apache-2.0 Apache 2.0
 */

namespace Amadeus\Client\Struct\Info;

/**
 * LocationDescription
 *
 * @package Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class LocationDescription
{
    /**
     * @var string
     */
    public $code;

    /**
     * 1 CCC (Customs Co-operation Council)
     * 10 ODETTE
     * 100 CH, Entreprise des PTT
     * 101 CH, Carbura
     * 102 CH, Centrale suisse pour l'importation du charbon
     * 103 CH, Office fiduciaire des importateurs de denrees
     * 104 CH, Association suisse code des articles
     * 105 DK, Ministry of taxation, Central Customs and Tax
     * 106 FR, Direction generale des douanes et droits indirects
     * 107 FR, INSEE
     * 108 FR, Banque de France
     * 109 GB, H.M. Customs & Excise
     * 11 Lloyd's register of shipping
     * 110 IE, Revenue Commissioners, Customs AEP project
     * 111 US, U.S. Customs Service
     * 112 US, U.S. Census Bureau
     * 113 Uniform Code Council
     * 114 US, ABA (American Bankers Association)
     * 115 US, DODAAC (Department Of Defense Active Agency Code)
     * 116 US, ANSI ASC X12
     * 117 AT, Geldausgabeautomaten-Service Gesellschaft m.b.H.
     * 118 SE, Svenska Bankfoereningen
     * 119 IT, Associazione Bancaria Italiana
     * 12 UIC (International union of railways)
     * 120 IT, Socieata' Interbancaria per l'Automazione
     * 121 CH, Telekurs AG
     * 122 CH, Swiss Securities Clearing Corporation
     * 123 NO, Norwegian Interbank Research Organization
     * 124 NO, Norwegian Bankers' Association
     * 125 FI, The Finnish Bankers' Association
     * 126 US, NCCMA (Account Analysis Codes)
     * 127 DE, ARE (AbRechnungs Einheit)
     * 128 BE, Belgian Bankers' Association
     * 129 BE, Belgian Ministry of Finance
     * 13 ICAO (International Civil Aviation Organization)
     * 130 DK, Danish Bankers Association
     * 131 DE, German Bankers Association
     * 132 GB, BACS Limited
     * 133 GB, Association for Payment Clearing Services
     * 134 GB, APACS (Association of payment clearing services)
     * 135 GB, The Clearing House
     * 136 GB, Article Number Association (UK) Limited
     * 137 AT, Verband oesterreichischer Banken und Bankiers
     * 138 FR, CFONB (Comite francais d'organ. et de normalisation
     * 139 UPU (Universal Postal Union)
     * 14 ICS (International Chamber of Shipping)
     * 140 CEC (Commission of the European Communities), DG/XXI-01
     * 141 CEC (Commission of the European Communities), DG/XXI-B-
     * 142 CEC (Commission of the European Communities), DG/XXXIV
     * 143 NZ, New Zealand Customs
     * 144 NL, Netherlands Customs
     * 145 SE, Swedish Customs
     * 146 DE, German Customs
     * 147 BE, Belgian Customs
     * 148 ES, Spanish Customs
     * 149 IL, Israel Customs
     * 15 RINET (Reinsurance and Insurance Network)
     * 150 HK, Hong Kong Customs
     * 151 JP, Japan Customs
     * 152 SA, Saudi Arabia Customs
     * 153 IT, Italian Customs
     * 154 GR, Greek Customs
     * 155 PT, Portuguese Customs
     * 156 LU, Luxembourg Customs
     * 157 NO, Norwegian Customs
     * 158 FI, Finnish Customs
     * 159 IS, Iceland Customs
     * 16 US, D&B (Dun & Bradstreet Corporation)
     * 160 LI, Liechtenstein authority
     * 161 UNCTAD (United Nations - Conference on Trade And
     * 162 CEC (Commission of the European Communities), DG/XIII-D
     * 163 US, FMC (Federal Maritime Commission)
     * 164 US, DEA (Drug Enforcement Agency)
     * 165 US, DCI (Distribution Codes, INC.)
     * 166 US, National Motor Freight Classification Association
     * 167 US, AIAG (Automotive Industry Action Group)
     * 168 US, FIPS (Federal Information Publishing Standard)
     * 169 CA, SCC (Standards Council of Canada)
     * 17 S.W.I.F.T.
     * 170 CA, CPA (Canadian Payment Association)
     * 171 NL, Interpay Girale Services
     * 172 NL, Interpay Debit Card Services
     * 173 NO, NORPRO
     * 174 DE, DIN (Deutsches Institut fuer Normung)
     * 175 FCI (Factors Chain International)
     * 176 BR, Banco Central do Brazil
     * 177 AU, LIFA (Life Insurance Federation of Australia)
     * 178 AU, SAA (Standards Association of Australia)
     * 179 US, Air transport association of America
     * 18 Conventions on SAD and transit (EC and EFTA)
     * 180 DE, BIA (Berufsgenossenschaftliches Institut fuer
     * 181 Edibuild
     * 182 US, Standard Carrier Alpha Code (Motor)
     * 183 US, American Petroleum Institute
     * 184 AU, ACOS (Australian Chamber of Shipping)
     * 185 DE, BDI (Bundesverband der Deutschen Industrie e.V.)
     * 186 US, GSA (General Services Administration)
     * 187 US, DLMSO (Defense Logistics Management Standards Offic
     * 188 US, NIST (National Institute of Standards and Technolog
     * 189 US, DoD (Department of Defense)
     * 19 FRRC (Federal Reserve Routing Code)
     * 190 US, VA (Department of Veterans Affairs)
     * 191 IAPSO (United Nations Inter-Agency Procurement Services
     * 192 Shipper's association
     * 193 EU, European Telecommunications Informatics Services (E
     * 194 AU, AQIS (Australian Quarantine and Inspection Service)
     * 195 CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)
     * 196 US, COPAS (Council of Petroleum Accounting Society)
     * 197 US, DISA (Data Interchange Standards Association)
     * 198 CO, Superintendencia Bancaria De Colombia
     * 199 FR, Direction de la Comptabilite Publique
     * 2 CEC (Commission of the European Communities)
     * 20 BIC (Bureau International des Containeurs)
     * 200 NL, EAN Netherlands
     * 201 US, WSSA(Wine and Spirits Shippers Association)
     * 202 PT, Banco de Portugal
     * 203 FR, GALIA (Groupement pour l'Amelioration des Liaisons
     * 204 DE, VDA (Verband der Automobilindustrie E.V.)
     * 205 IT, ODETTE Italy
     * 206 NL, ODETTE Netherlands
     * 207 ES, ODETTE Spain
     * 208 SE, ODETTE Sweden
     * 209 GB, ODETTE United Kingdom
     * 21 Assigned by transport company
     * 210 EU, EDI for financial, informational, cost, accounting,
     * 211 FR, EDI for financial, informational, cost, accounting,
     * 212 DE, Deutsch Telekom AG
     * 213 JP, NACCS Center (Nippon Automated Cargo Clearance Syst
     * 214 US, AISI (American Iron and Steel Institute)
     * 215 AU, APCA (Australian Payments Clearing Association)
     * 216 US, Department of Labor
     * 217 US, N.A.I.C. (National Association of Insurance
     * 218 GB, The Association of British Insurers
     * 219 FR, d'ArvA
     * 22 US, ISA (Information Systems Agreement)
     * 220 FI, Finnish tax board
     * 221 FR, CNAMTS (Caisse Nationale de l'Assurance Maladie des
     * 222 DK, Danish National Board of Health
     * 223 DK, Danish Ministry of Home Affairs
     * 224 US, Aluminum Association
     * 225 US, CIDX (Chemical Industry Data Exchange)
     * 226 US, Carbide Manufacturers
     * 227 US, NWDA (National Wholesale Druggist Association)
     * 228 US, EIA (Electronic Industry Association)
     * 229 US, American Paper Institute
     * 23 FR, EDITRANSPORT
     * 230 US, VICS (Voluntary Inter-Industry Commerce Standards)
     * 231 Copper and Brass Fabricators Council
     * 232 GB, Inland Revenue
     * 233 US, OMB (Office of Management and Budget)
     * 234 DE, Siemens AG
     * 235 AU, Tradegate (Electronic Commerce Australia)
     * 236 US, United States Postal Service (USPS)
     * 237 US, United States health industry
     * 238 US, TDCC (Transportation Data Coordinating Committee)
     * 239 US, HL7 (Health Level 7)
     * 24 AU, ROA (Railways of Australia)
     * 240 US, CHIPS (Clearing House Interbank Payment Systems)
     * 241 PT, SIBS (Sociedade Interbancaria de Servicos)
     * 242 NL, Interpay Giraal
     * 243 NL, Interpay Cards
     * 244 US, Department of Health and Human Services
     * 245 DK, EAN (European Article Numbering) Denmark
     * 246 DE, Centrale fuer Coorganisation GMBH
     * 247 US, HBICC (Health Industry Business Communication Counc
     * 248 US, ASTM (American Society of Testing and Materials)
     * 249 IP (Institute of Petroleum)
     * 25 EDITEX (Europe)
     * 250 US, UOP (Universal Oil Products)
     * 251 AU, HIC (Health Insurance Commission)
     * 252 AU, AIHW (Australian Institute of Health and Welfare)
     * 253 AU, NCCH (National Centre for Classification in Health)
     * 254 AU, DOH (Australian Department of Health)
     * 255 AU, ADA (Australian Dental Association)
     * 256 US, AAR (Association of American Railroads)
     * 257 ECCMA (Electronic Commerce Code Management Association)
     * 258 JP, Japanese Ministry of Transport
     * 259 JP, Japanese Maritime Safety Agency
     * 26 NL, Foundation Uniform Transport Code
     * 260 Ediel Nordic forum
     * 261 EEG7, European Expert Group 7 (Insurance)
     * 262 DE, GDV (Gesamtverband der Deutschen
     * 263 CA, CSIO (Centre for Study of Insurance Operations)
     * 264 FR, AGF (Assurances Generales de France)
     * 265 SE, Central bank
     * 266 US, DoA (Department of Agriculture)
     * 267 RU, Central Bank of Russia
     * 268 FR, DGI (Direction Generale des Impots)
     * 269 GRE (Reference Group of Experts)
     * 27 US, FDA (Food and Drug Administration)
     * 270 Concord EDI group
     * 271 InterContainer InterFrigo
     * 272 Joint Automotive Industry agency
     * 273 CH, SCC (Swiss Chambers of Commerce)
     * 274 ITIGG (International Transport Implementation Guideline
     * 275 ES, Banco de Espaa
     * 276 Assigned by Port Community
     * 277 BIGNet (Business Information Group Network)
     * 278 Eurogate
     * 279 NL, Graydon
     * 28 EDITEUR (European book sector electronic data interchan
     * 280 FR, Euler
     * 281 ICODIF/EAN Belgium-Luxembourg
     * 282 DE, Creditreform International e.V.
     * 283 DE, Hermes Kreditversicherungs AG
     * 284 TW, Taiwanese Bankers' Association
     * 285 ES, Asociacin Espaola de Banca
     * 286 SE, TCO (Tjnstemnnes Central Organisation)
     * 287 DE, FORTRAS (Forschungs- und Entwicklungsgesellschaft f
     * 288 OSJD (Organizacija Sotrudnichestva Zeleznih Dorog)
     * 289 JP,JIPDEC/ECPC (Japan Information Processing Developme
     * 29 GB, FLEETNET
     * 290 JP, JAMA
     * 291 JP, JAPIA
     * 292 FI, TIEKE The Information Technology Development Centre
     * 293 DE, VDEW (Verband der Elektrizitatswirtschaft)
     * 294 AT, EAN Austria
     * 295 AU, Australian Therapeutic Goods Administration
     * 296 ITU (International Telecommunication Union)
     * 297 IT, Ufficio IVA
     * 298 ES, AECOC/EAN Spain
     * 3 IATA (International Air Transport Association)
     * 30 GB, ABTA (Association of British Travel Agencies)
     * 31 FI, Finish State Railway
     * 32 PL, Polish State Railway
     * 33 BG, Bulgaria State Railway
     * 34 RO, Rumanian State Railway
     * 35 CZ, Tchechian State Railway
     * 36 HU, Hungarian State Railway
     * 37 GB, British Railways
     * 38 ES, Spanish National Railway
     * 39 SE, Swedish State Railway
     * 4 ICC (International Chamber of Commerce)
     * 40 NO, Norwegian State Railway
     * 41 DE, German Railway
     * 42 AT, Austrian Federal Railways
     * 43 LU, Luxembourg National Railway Company
     * 44 IT, Italian State Railways
     * 45 NL, Netherlands Railways
     * 46 CH, Swiss Federal Railways
     * 47 DK, Danish State Railways
     * 48 FR, French National Railway Company
     * 49 BE, Belgian National Railway Company
     * 5 ISO (International Organization for Standardization)
     * 50 PT, Portuguese Railways
     * 51 SK, Slovakian State Railways
     * 52 IE, Irish Transport Company
     * 53 FIATA (International Federation of Freight Forwarders
     * 54 IMO (International Maritime Organisation)
     * 55 US, DOT (United States Department of Transportation)
     * 56 TW, Trade-van
     * 57 TW, Chinese Taipei Customs
     * 58 EUROFER
     * 59 DE, EDIBAU
     * 6 UN/ECE (United Nations - Economic Commission for Europe
     * 60 Assigned by national trade agency
     * 61 Association Europeenne des Constructeurs de Materiel
     * 62 US, DIstilled Spirits Council of the United States (DIS
     * 63 North Atlantic Treaty Organization (NATO)
     * 64 FR, EDIFRANCE
     * 65 FR, GENCOD
     * 66 MY, Malaysian Customs and Excise
     * 67 MY, Malaysia Central Bank
     * 68 IT, INDICOD/EAN Italy
     * 69 US, National Alcohol Beverage Control Association (NABC
     * 7 CEFIC (Conseil Europeen des Federations de l'Industrie
     * 70 MY, Dagang.Net
     * 71 US, FCC (Federal Communications Commission)
     * 72 US, MARAD (Maritime Administration)
     * 73 US, DSAA (Defense Security Assistance Agency)
     * 74 US, NRC (Nuclear Regulatory Commission)
     * 75 US, ODTC (Office of Defense Trade Controls)
     * 76 US, ATF (Bureau of Alcohol, Tobacco and Firearms)
     * 77 US, BXA (Bureau of Export Administration)
     * 78 US, FWS (Fish and Wildlife Service)
     * 79 US, OFAC (Office of Foreign Assets Control)
     * 8 EDIFICE
     * 80 BRMA/RAA - LIMNET - RINET Joint Venture
     * 81 RU, (SFT) Society for Financial Telecommunications
     * 82 NO, Enhetsregisteret ved Bronnoysundregisterne
     * 83 US, National Retail Federation
     * 84 DE, BRD (Gesetzgeber der Bundesrepublik Deutschland)
     * 85 North America, Telecommunications Industry Forum
     * 86 Assigned by party originating the message
     * 87 Assigned by carrier
     * 88 Assigned by owner of operation
     * 89 Assigned by distributor
     * 9 EAN International
     * 90 Assigned by manufacturer
     * 91 Assigned by seller or seller's agent
     * 92 Assigned by buyer or buyer's agent
     * 93 AT, Austrian Customs
     * 94 AT, Austrian PTT
     * 95 AU, Australian Customs Service
     * 96 CA, Revenue Canada, Customs and Excise
     * 97 CH, Administration federale des contributions
     * 98 CH, Direction generale des douanes
     * 99 CH, Division des importations et exportations, OFAEE
     * ZZZ Mutually defined
     *
     * @var string
     */
    public $agency;

    /**
     * @var string
     */
    public $name;

    /**
     * LocationDescription constructor.
     *
     * @param string|null $code
     * @param string|null $name
     */
    public function __construct($code, $name = null)
    {
        $this->code = $code;
        $this->name = $name;
    }
}
