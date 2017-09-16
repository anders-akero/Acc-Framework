CREATE SCHEMA IF NOT EXISTS `webshop` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;

CREATE TABLE IF NOT EXISTS `webshop`.`product` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` varchar(32) NOT NULL,
	`description` text NOT NULL,
	`stock` INTEGER NOT NULL,
	`price` double NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `webshop`.`cart` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`session_id` varchar(40) NOT NULL,
	`product_id` INTEGER UNSIGNED NOT NULL,
	`quantity` INTEGER NOT NULL,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `webshop`.`product`
(`name`, `description`, `stock`, `price`)
VALUES
('Raspberry Pi', 'Enkel enkortsdator utvecklad av Raspberry Pi Foundation för att göra det möjligt för fler att lära sig datorteknik och programmering utan att det skall kosta för mycket pengar.
 
 Datorn är klar att köra men måste kompletteras med SDHC-kort för lagring av operativsystem och data.
 Datorn strömmatas med 5V DC 3,5W via USB micro B kontakt.
 
 Det finns många färdiga applikationer utvecklade till Raspberry Pi och fler tillkommer från användare över hela välden kontinuerligt. En av de populärare tillämpningarna är att använda den som mediaspelare med XBMC. Det går även att köra Android OS på den.
 
 Datorn levereras utan tillbehör och manual. För mer information hänvisas till Raspberry Pis hemsida. 
 
 Mått (BxHxD): 92x17x64mm.', 0, 339),
('Raspberry Pi Type B Case - Black', 'Releasedatum	2013-04-03
 Vårt artikelnr	173603
 Tillv. artikelnr	ASM1900004_21
 Utvecklare	RS', 5, 99),
('TP-LINK 150Mbps wireless N Nano ', 'Gränssnitt
 • USB 2.0-anslutning	1 st
 
 Nätverk
 • W-lan	Ja
 • Trådlösa protokoll	IEEE 802.11b/g, IEEE 802.11n
 • Kryptering	64-bit WEP, 128-bit WEP, WPA, WPA2
 
 Generell information
 Dimensioner (B x H x D)	18,6 mm x 7,1 mm x 15 mm', 15, 129),
('Deltaco USB 2.0 Kabel A->Micro B', 'Upplagd	2011-12-13
 Vårt artikelnr	146371
 Tillv. artikelnr	USB-301S
 Utvecklare	Deltaco', 1, 89),
('Deltaco Strömadapter från 230V t', 'Perfekt liten pryl för att kunna ladda olika enheter via USB. T.ex. Mobiltelefon PDA, MP3-spelare. Eller varför inte driva en USB-fläkt eller koppvärmare? 
 
 Denna ger 5V med 1A.', 3, 75),
('Raspberry Pi Type B Case - White', 'Releasedatum	2013-04-03
 Vårt artikelnr	173605
 Tillv. artikelnr	ASM1900004_11
 Utvecklare	RS', 4, 99);
