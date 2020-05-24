# World Organism console APP
Tato konzolová aplikace slouží pro vygenerování světa ve kterém se rodí nový organismy. Na začátku je zadáno kolik chceme
x, y dimenzí, iterací a různých typů konkrétního druhu organismu. Alternativa je možnost deserializovat
XML soubor, kde bude předem definované nastavení našeho světa. Ovšem pro jednodušší testování jsou zde
přidány parametry do tohoto commandu.

Jednotlivá pravidla, která jsou naprogramovaná pro vytváření nových organismů (počítám s rohovými elementy konkrétní matice):
* pokud existují v okolí 2 nebo 3 organismy stejného typu, tak se vytvoří
* pokud v okolí ještě není žádný organismus, tak se vytvoří
* pokud existuje 1 nebo žádný organismus stejného typu a zároveň není v okolí prázdno, tak se organismus nevytvoří
* pokud je v okolí více jak 4 organismy stejného typu, tak se nevytvoří

Ostatní podmínky nebyly naprogramovány kvůli nedostatku času. TODO: 
* pokud dva organismy zabírají jeden prvek, jeden z nich musí zemřít
* nový organismus je stejný typ jako jeho rodiče, pokud je tato podmínka platná pro více než jednu
  druh na stejném prvku, potom se náhodně vybere druh pro nový prvek

## Předpoklady pro spuštění
* Composer
* Apache
* PHP v 7.4

## Instalace a spuštění
1. Stáhněte si `repozitář` na lokální zařízení.
2. V rootu, kde se nachází composer spusťte instalaci `composer install`. 
3. Samotný příkaz následně spustíte z rootu projektu následovně `php bin/console app:world`
4. Zadáte postupně jednotlivé parametry 

`DimensionX` - počet X matice 

`DimensionY` - počet Y matice

`Species` - počet různých druhů

`Iterations` - počet iterací rození organismů

Výsledný export XML souboru se nachází ve složce public `/public/world.xml`

## Autor
* **Jurij Povoroznyk** - povoroznykjurij@gmail.com