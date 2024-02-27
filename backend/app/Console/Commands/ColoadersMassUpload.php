<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Coloaders;
use App\Country;

class ColoadersMassUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coloaders:mass-upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mass Upload Coloaders';
    protected $agent_list = [
          [
              "country" => "Italy",
              "name" => "Arimar International - Italy",
              "emails" => "lorenzo.fumelli@arimar.it, arturo.piras@arimar.it",
              "number" => "Ph +39 055 7220804",
          ],
          [
              "name" => "Mondiale VGL - Italy",
              "emails" =>
                  "agandolfo@mondialevgl.com, ttritone@mondialevgl.com, gbarbera@mondialevgl.com, scalogero@mondialevgl.com, mrusso@mondialevgl.com",
              "number" => "39-3498273874",
          ],
          [
              "name" => "KLTI srl -Italy",
              "emails" => "sales@klti.net, vittorio@klti.net",
          ],
          [
              "country" => "Egypt",
              "name" => "Aafab Global Logistics - Egypt",
              "emails" => "aafabeasternafrica@aafabgloballogistics.com",
          ],
          [
              "name" => "SFS Global Logistics - Egypt",
              "emails" => "marian@sfsgloballogistics.com",
              "number" => " +202 23519761; +202 23518861; +202 23518693",
          ],
          [
              "country" => "Spain",
              "name" => "OPERINTER BARCELONA - Spain",
              "emails" =>
                  "comercial.barcelona@operinter.com, mfernandez@operinter.com, pricing.overseas@operinter.com, alexis.nguedom@operinter.com",
              "number" => " 93 262 73 73 / Ext. 1324",
          ],
          [
              "name" => "Noatum Logistics - Spain (Valencia)",
              "emails" => "pricinges@noatumlogistics.com",
              "number" => " +34 649 491 843",
          ],
          [
              "name" => "SPARBER GROUP - Spain (Valencia)",
              "emails" => "valencia@sparber.es, amparo.delma@sparber.es",
              "number" => " +34 96 3164760",
          ],
          [
              "name" => "Pancargo Freight Services - Spain (Valencia)",
              "emails" => "sea@pancargo.net, Andres.Montero@pancargo.net",
              "number" => "696729344",
          ],
          [
              "country" => "Portugal",
              "name" => "All ways Cargo - Portugal",
              "emails" =>
                  "francisco.martins@allwayscargo.com,  pedro.sousa@allwayscargo.com",
          ],
          [
              "country" => "Pakistan",
              "name" => "Star Trans Logistics - Pakistan",
              "emails" =>
                  "sajid@startranslogistics.com, raees@startranslogistics.com, docs@startranslogistics.com",
          ],
          [
              "name" => "STLS - Pakistan",
              "emails" => "info@stls.com.pk, ops@stls.com.pk",
          ],
          [
              "name" => "Universal Freighters - Pakistan",
              "emails" => "DuleepG@universalfreighters.com",
              "number" => "94-11-2436378/9.",
          ],
          [
              "country" => "Turkey",
              "name" => "Compass Logistics - Turkey",
              "emails" => "Berrin.Ayhan@compasslog.com",
          ],
          [
              "name" => "Bati group  - Turkey",
              "emails" =>
                  "istanbul@batigroup.com.tr, sales21@batigroup.com.tr, pricing@batigroup.com.tr",
              "number" => " +90 212 293 2400 +90 554 334 2249",
          ],
          [
              "name" => "Galpi - Turkey",
              "emails" => "globalnetwork@galpi.com.tr, tinag@galpi.com.tr",
              "number" =>
                  "0850 755 0 GLT (458) / +90 212 310 50 42 +90 549 837 44 15",
          ],
          ["name" => "Yen Logistic  - Turkey", "emails" => "info@yenlogistic.com"],
          [
              "name" => "Keşif Nakliyat  - Turkey",
              "emails" => "sea@kesifnakliyat.com",
              "number" => "Tel +902124663890",
          ],
          [
              "name" => "CONTURK SHIPPING  - Turkey",
              "emails" => "overseas@conturk.com",
          ],
          [
              "name" => "Asset  - Turkey",
              "emails" =>
                  "gulce.karamanoznur@assetgli.com, bora.palabiyik@assetgli.com",
          ],
          [
              "name" => "TRANSARYA - - Turkey",
              "emails" => "senalp@transarya.com.tr",
              "number" => "T: +90 216 306 0076 M:+90 532 472 49 92",
          ],
          [
              "name" => "ACT Denizyolu Taşımacılığı Lojistik Ltd",
              "emails" => "usa.marketing@actshipping.com.tr",
          ],
          [
              "country" => "South Africa - Johannesburg",
              "name" => "Mega freight -South Africa - Johannesburg",
              "emails" => "dilene@megafreight.co.za, pam.rumble@megafreight.co.za",
              "number" => "T :+90 212 3352703 M:+90 532 4724992",
          ],
          [
              "country" => "Europe:",
              "name" => "GL forwarding - Europe",
              "emails" => "info@glforwarding.com",
          ],
          [
              "country" => "UK",
              "name" => "GL forwarding - UK",
              "emails" => "info@glforwarding.com",
          ],
          [
              "name" => "Quality Freight Services Ltd - UK",
              "emails" => "Cheralyn@qualityfreight.co.uk",
              "number" => "M +44 (0)7376631304",
          ],
          [
              "name" => "FLYSHIP LOGISTICS LTD - UK",
              "emails" =>
                  "kumar@flyshiplogistics.co.uk, Sales@flyshiplogistics.co.uk",
              "number" => "Mobile & Whatsapp: +44(0)7748250025",
          ],
          [
              "name" => "Davies Turner & Co Ltd - UK",
              "emails" => "RickyHitchen@daviesturner.co.uk",
          ],
          [
              "name" => "Andersen Harvey - UK",
              "emails" =>
                  "info@andersenharvey.co.uk, colin@andersenharvey.co.uk, richard@andersenharvey.co.uk",
          ],
          [
              "country" => "Belgium: Antwerp",
              "name" => "GL forwarding - Belgium",
              "emails" => "info@glforwarding.com",
          ],
          [
              "name" => "Interfreight Antwerp NV - Belgium",
              "emails" => "sales@interfreight.be",
          ],
          [
              "name" => "TPL - Transport & Project Logistics bvba - Belgium",
              "emails" => "sales@tpl.be",
              "number" => "GSM: +32 (0)474 91 67 24TEL: +32 (0)3 454 02 67",
          ],
          [
              "country" => "Germany",
              "name" => "GL forwarding - Germany",
              "emails" => "info@glforwarding.com",
          ],
          [
              "name" => "SENATOR INTERNATIONAL  - Germany",
              "emails" =>
                  "info-fra@fra.senator-international.com, info-ham@ham.senator-international.com",
          ],
          [
              "name" => "Bati group (Tur-Ger-Italy)",
              "emails" =>
                  "harun@batilogistics.de, aytan@batilogistics.de, ops@batilogistics.de",
              "number" => "Mob : +49 (0) 174 597 7776",
          ],
          [
              "name" => "Emex Express Deutschland, AIR  - Germany",
              "emails" => "info@emexexpress.de",
              "number" => "Mob: + 49 0170 1755500",
          ],
          [
              "name" => "Elbfair Logistics GmbH - Germany",
              "emails" =>
                  "cargo@elbfair.de, h.pires@elbfair.de;, d.schneider@elbfair.de, cargo@elbfair.de, h.guela@elbfair.de",
          ],
          [
              "name" => "Beyoglu Internationale Spedition GmbH, Hamburg  - Germany",
              "emails" => "oversea@beyoglu-spedition.de, sales@beyoglu-spedition.de",
          ],
          [
              "country" => "Dubai",
              "name" => "Asian Cargo - Dubai",
              "emails" => "info@asiancargo.ae",
              "number" => "Mob: +971 50 8002139",
          ],
          [
              "name" => "Asia Shipping - Dubai",
              "emails" =>
                  "rameez.afzal@ae-asgroup.com, operations@ae-asgroup.com, nazim.parkar@ae-asgroup.com, sales@ae-asgroup.com, Himanshu.ramesh@ae-asgroup.com",
          ],
          [
              "country" => "Taiwan",
              "name" => "NAF Freight - Taiwan",
              "emails" => "max.chan@naffreight.com, shelane.chen@naffreight.com",
              "number" => "Mobile: (886) 928650195",
          ],
          [
              "name" => "HONOUR LANE SHIPPING LIMITED -Taiwan",
              "emails" => "taiwan@hlsholding.com,",
          ],
          [
              "name" => "3L-LEEMARK LOGISTICS LTD -Taiwan",
              "emails" => "taipei@3l-leemark.com",
          ],
          [
              "country" => "Poland",
              "name" => "BOXON Logistics Sp. z o.o. - Poland",
              "emails" => "sales@box-on.pl, mb@box-on.pl, mt@box-on.pl",
              "number" => "Mob. 0048 728 992 027",
          ],
          ["name" => "GL forwarding - Poland", "emails" => "info@glforwarding.com"],
          [
              "name" => "Ziegler Group - Poland",
              "emails" => "pierre.henry@zieglergroup.com",
          ],
          [
              "country" => "Israel",
              "name" => "Itrans Logistics - Israel",
              "emails" => "Amir@iTransIL.com, operation@iTransil.com",
              "number" => "Mobile : + (972) 54-5890175",
          ],
          ["name" => "Ruth Cargo -Israel", "emails" => "zeev@ruthcargo.co.il"],
          [
              "name" => "One Express -Israel",
              "emails" => "freight@onexpress.co.il, asaf@onexpress.co.il",
          ],
          [
              "country" => "Bangladesh",
              "name" => "GTS Logistics - Bangladesh",
              "emails" =>
                  "masud@gtslogistics.org, shiplu@gtslogistics.org, s_alam@gtslogistics.org",
          ],
          [
              "name" => "Cargo One - Bangladesh",
              "emails" =>
                  "rajaul@cargoonelimited.com, info@cargoonelimited.com,ctg@cargoonelimited.com,rahim@cargoonelimited.com",
              "number" => "P: +88 02 48 957668, M: +88 017 93519191",
          ],
          [
              "name" => "Silver Bridge Logistics Ltd. - Bangladesh",
              "emails" => "operation@silverbridgelogistics.com",
              "number" => "Mobile : + 880 1894963164",
          ],
          [
              "name" => "REAL LOGISTICS LIMITED - Bangladesh",
              "emails" => "sukanta@reallogisticsbd.com",
              "number" => "Mob : +88-01677300852",
          ],
          [
              "name" => "Fastway Sea-Air Transport Services Ltd - Bangladesh",
              "emails" =>
                  "goutam@fastwayseaair.net, zaman@fastwayseaair.net, m.rahman@fastwayseaair.net,shesir@fastwayseaair.net",
              "number" => "Cell : +88 01536-242897",
          ],
          [
              "name" => "Express Worldwide - Bangladesh",
              "emails" => "nafiz.absar@express-worldwide.com",
          ],
          [
              "country" => "Peru (South America)",
              "name" => "Ocean Air Cargo Peru - Peru",
              "emails" => "gerencia.comercial@oceanaircargoperu.com",
          ],
          [
              "country" => "Philippines",
              "name" => "HONOUR LANE SHIPPING LIMITED -Philippines",
              "emails" =>
                  "arabellaph@hlsholding.com, gerlieph@hlsholding.com, luisaph@hlsholding.com, renatoph@hlsholding.com, vanessaph@hlsholding.com, ligertph@hlsholding.com",
          ],
          [
              "name" => "NAF Global Inc. - Philippines",
              "emails" =>
                  "fe.jolejole@naffreight.com, susan.soriano@naffreight.com, henry.fung@naffreight.com",
              "number" => "Direct: (63) 2-851-1135",
          ],
          [
              "name" => "Genesis Freight - Philippines",
              "emails" =>
                  "paula@genesisfreight.com.ph, pamela@genesisfreight.com.ph,rose@genesisfreight.com.ph,ruth@genesisfreight.com.ph,",
              "number" => "Tel. No.: (+632) 8 405 0341 to 44",
          ],
          [
              "country" => "Russia",
              "name" => "Eastwest Forwarding - Russia",
              "emails" => "info@eastwestforwarding.com",
          ],
          ["name" => "EWF (Kaliningrad) - Russia", "emails" => "jan@ewf.eu"],
          [
              "country" => "Brazil",
              "name" => "VMLOG - Brazil",
              "emails" =>
                  "inside.sales@vmlog.com.br, sabrine@vmlog.com.br, jhonatan@vmlog.com.br, david@vmlog.com.br",
              "number" =>
                  "Mobile: 55 47 98818-2977Phone BR: 55 47 2103-4401Phone USA: 1 754 227-1203",
          ],
          [
              "name" => "AMTRANS - Brazil",
              "emails" => "r.luz@amtrans.com.br ,a.nogueira@amtrans.com.br",
              "number" => "M: +55 47 991158264 / 55 47 99614-5880",
          ],
          [
              "name" => "Asia Shipping Brasil - Brazil",
              "emails" =>
                  "levy.dias@br-asgroup.com, geovane.cunha@br-asgroup.com, eduardo.souza@br-asgroup.com, filipe.pacheco@br-asgroup.com",
              "number" => "Levy +55 47 99777.9334",
          ],
          [
              "country" => "Ecuador",
              "name" => "IMPOEX - Ecuador",
              "emails" => "consolidadora@impoex.ec, info@impoex.ec",
          ],
          [
              "country" => "Manzanillo, Mexico",
              "name" => "Acapulco Shipping Agencies - Mexico",
              "emails" => "sales@acapulcoagencies.com",
          ],
          [
              "country" =>
                  "Serbia/Serbia, Montenegro, Bosnia & Herzegovina, Croatia, Slovenia and Macedonia",
              "name" =>
                  "Euro Logistics -Serbia/Serbia, Montenegro, Bosnia & Herzegovina, Croatia, Slovenia and Macedonia",
              "emails" => "dunja.kapetanovic@eurologistic.rs",
          ],
          [
              "country" => "Slovakia",
              "name" => "Ocean Shipping - Slovakia",
              "emails" => "ingrid.hlavacova@oceanshipping.sk",
          ],
          [
              "country" => "Ukraine",
              "name" => "OPK Group - Ukraine",
              "emails" => "Alexey@opkgroup.com.ua,",
          ],
          [
              "country" => "Vietnam",
              "name" => "Bee Logistics - Vietnam",
              "emails" => "terry.vnsgn@beelogistics.com",
          ],
          ["name" => "Voltrans - Vietnam", "emails" => "gnt.jesse@voltransvn.com"],
          [
              "country" => "Malaysia",
              "name" => "HONOUR LANE SHIPPING LIMITED - Malaysia",
              "emails" =>
                  "justintenmy@hlsholding.com, jessieleemy@hlsholding.com, sharoncheongmy@hlsholding.com, wanyinmy@hlsholding.com, jeannieyongmy@hlsholding.com, nevilleganmy@hlsholding.com",
          ],
          [
              "name" => "SHANGHAI LE STAR INTERNATIONAL SHIPPING CO - Malaysia",
              "emails" => "YUKI@shlestar.com.cn, albert@shlestar.com.cn",
          ],
          [
              "country" => "Thailand",
              "name" => "HONOUR LANE SHIPPING LIMITED - Thailand",
              "emails" =>
                  "itsareeyapornth@hlsholding.com, worawutth@hlsholding.com, suwannath@hlsholding.com, supapornth@hlsholding.com, CharnchaiTH@hlsholding.com",
          ],
          [
              "country" => "Indonesia",
              "name" => "HONOUR LANE SHIPPING LIMITED - Indonesia",
              "emails" =>
                  "jumiatiid@hlsholding.com, priliid@hlsholding.com, anamid@hlsholding.com, hendiid@hlsholding.com, dewantaid@hlsholding.com,  joyid@hlsholding.com",
          ],
          [
              "name" => "Nirwana Persada Cargo - Indonesia",
              "emails" => "sales@npcargo.co.id, cs@npcargo.co.id, info@npcargo.co.id",
              "number" => "Whatsapp: +62 812-3145-4603",
          ],
          [
              "country" => "South Korea",
              "name" => "HONOUR LANE SHIPPING LIMITED - South Korea",
              "emails" =>
                  "daphnekimkr@hlsholding.com, victorleekr@hlsholding.com, jennyjeongkr@hlsholding.com, emmaleekr@hlsholding.com, mileswonkr@hlsholding.com, emmaleekr@hlsholding.com, mileswonkr@hlsholding.com, jrparkkr@hlsholding.com",
          ],
          [
              "name" => "YONG SUNG LOGISTICS CO., LTD.  - South Korea",
              "emails" => "admin@yslogic.co.kr",
          ],
          [
              "name" => "Famex Global Logistics Co. Ltd  - South Korea",
              "emails" => "famex@famexgls.com",
          ],
          [
              "country" => "Cambodia",
              "name" => "AIR SEA FREIGHT ( CAMBODIA ) CO.,LTD - Cambodia",
              "emails" => "info@asfcambodia.com",
              "number" => "Cell Phone : 855)99 664 678",
          ],
          [
              "name" => "HONOUR LANE SHIPPING LIMITED -  Cambodia",
              "emails" =>
                  "williamcb@hlsholding.com, kimtousaycb@hlsholding.com, briansruncb@hlsholding.com, kevintithcb@hlsholding.com, sreyrothlaycb@hlsholding.com, chhongvoeurncb@hlsholding.com, ahsamincb@hlsholding.com, williamcb@hlsholding.com",
          ],
          [
              "country" => "Colombia",
              "name" => "Agentranscol - Colombia",
              "emails" => "l.almonacid@agentranscol.com",
              "number" => "Mobile: 311 454 87 79",
          ],
          [
              "name" => "Cargotrans Group Ltd - Colombia",
              "emails" => "comercial@cargotrans.co,  info@cargotrans.co",
          ],
          [
              "name" => "Neptune Logistics SAS - Colombia",
              "emails" => "info@neptunlog.com",
          ],
          [
              "country" => "Venezuela",
              "name" => "Logística OMMS 2018, C.A. - Venezuela",
              "emails" => "director@logisticaomms.com",
              "number" => "Mobile: +58-424-1825663",
          ],
          [
              "name" => "DLINTCORP - Venezuela",
              "emails" =>
                  "dlintcorp.gerenciaccs1@gmail.com, dlintcorp.gerencia@yahoo.com,dlintcorp.gerenciaccs@gmail.com",
              "number" =>
                  "Mobile: +58-412-7095358 / +58-424-6984160 / +58-412-6725058 / +58-412-1214635",
          ],
          [
              "name" => "Grupo Logistico Costania - Venezuela",
              "emails" =>
                  "vdacosta@costania.com, mdacosta@costania.com, shermoso@costania.com,jpino@costania.com",
              "number" => "Ph: +588417166709",
          ],
          [
              "country" => "Australia",
              "name" => "Freight World - Australia",
              "emails" => "ratequottes@freight-world.com.au",
          ],
          [
              "country" => "Budapest, Hungary",
              "name" => "AIRMAX Cargo Budapest Zrt. - Hungary",
              "emails" =>
                  "air@airmaxcargo.com, sea@airmaxcargo.com, sales@airmaxcargo.com",
              "number" => "Cell: +36 70 681 7562 air manager",
          ],
          [
              "country" => "Switzerland",
              "name" => "COMBIFREIGHT GmbH - Switzerland",
              "emails" => "vlad.banko@combifreight.com, info@combifreight.ch",
              "number" => "T. +41 (0) 79 953 9005",
          ],
          [
              "name" => "Ruwa Sped AG - Switzerland",
              "emails" => "ocean@ruwasped.ch",
              "number" => "Office Tel : +41 44 537 93 93",
          ],
          [
              "name" => "SJZ TRANSGLOBAL - Switzerland",
              "emails" => "sjz@sjztransglobal.com",
              "number" => "T. +41 (0) 31 311 46 46",
          ],
          [
              "country" => "Peru",
              "name" => "ZENTRUM LOGISTIC PERU S.A.C. - Peru",
              "emails" =>
                  "jmartinez@zentrumlogistic.com, Arturo.consonno@zentrumlogistic.com, rocio.hinojosa@zentrumlogistic.com, sales@zentrumlogistic.com",
              "number" => "Mobile : (511) 987863806",
          ],
          [
              "name" => "TEAM PERUVIAN CARGO S.A.C. - Peru",
              "emails" =>
                  "mayrah@teamperuviancargo.com, gsolis@teamperuviancargo.com",
              "number" => "CEL. 969872083",
          ],
          [
              "country" => "Croatia",
              "name" => "Cargomind - Croatia",
              "emails" => "ZAG-Ocean@cargomind.com,",
              "number" => "Phone: +385 (1) 6438 718",
          ],
          [
              "name" => "Maurice Ward Group - Croatia",
              "emails" => "croatia@mauriceward.com",
              "number" => "Cell: +(385) 91 615 2823",
          ],
          [
              "country" => "Ecuador",
              "name" => "CDC-LATAM S.A. - Ecuador",
              "emails" => "emma@cdc-latam.com, comercialgye@cdc-latam.com",
              "number" => "Cel +593099-933-3232",
          ],
          [
              "country" => "Sri Lanka",
              "name" => "Dinlanka Logistics (Pvt)Ltd - Sri Lanka",
              "emails" => "dinraj.fernando@dinlanka.com",
              "number" => "Mob: 94-777224704",
          ],
          [
              "name" => "Cargo Boat Company Limited - Sri Lanka",
              "emails" =>
                  "suneth@cargo-boat.com, hemani@cargo-boat.com,ameen-ur.rahman@cargo-boat.com",
              "number" => "Mobile: + 94 77-7319501",
          ],
          [
              "name" => "Specialised Shipping Services - Sri Lanka",
              "emails" =>
                  "mktg@sss.lk, gisantha@sss.lk, brian@sss.lk, harsha@sss.lk, dumindha@sss.lk",
              "number" => "94 77 0876000",
          ],
      ];


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $country_id = null;
        $bar = $this->output->createProgressBar(count($this->agent_list));
        foreach ($this->agent_list as $key => $agent) {
            // code...
            $emails = [];
            $email = null;
            $name = null;
            $number = null;

            if(isset($agent['country'])){
                $country = Country::where('name', $agent['country'])->first();
                if($country && $country->id){
                    $country_id = $country->id;
                }else{
                    $country_id = null;
                }
            }
            if(isset($agent['emails'])){
                $ems = explode(",", $agent['emails']);
                foreach ($ems as $key => $em) {
                    // code...
                    $em = trim($em);
                    if(is_null($email)) $email = $em;
                    else array_push($emails, ["email" => $em]);
                }
            }
            if(isset($agent['name'])) $name = trim($agent['name']);
            if(isset($agent['number'])) $number = trim($agent['number']);

            $coloader = Coloaders::updateOrCreate(
                ['email' => $email],
                ['name' => $name, 'emails' => $emails, 'number' => $number, 'country_id' => $country_id]
            );
            $bar->advance();
        }
        $bar->finish();
        return 0;
    }


}
