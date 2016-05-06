<?php
$available_tags=array (
	'TAG_PUBLIC',
	'TAG_PRIVATE',
	'TAG_MAINTENANCE',
  'TAG_REPAIR',
	'TAG_SPARES',
	'TAG_WHEELS',
  'TAG_FUEL',
	'TAG_RESTORATION',
	'default' => 'TAG_OTHER',
);

$no_sho_tags=array('TAG_PUBLIC',	'TAG_PRIVATE');  //tags with no enable/disable checkboxes

$user_units = array(
  'distance' => 'km',
  'currency' => '&euro;',
);

$car_data_bits=array(
  'BIT_PROD_DATE',
  'BIT_DISPLACEMENT',
  'BIT_POWER_HP',
  'BIT_POWER KW',
  'BIT_FUEL_TYPE',
  'BIT_COLOR_CODE',
  'BIT_TRIM_COLOR_CODE',
  'BIT_NO_SEATS',
  'BIT_NO_DOORS',
  'BIT_TRANS_TYPE',
  'BIT_GVWR',

  'BIT_ODO',
  'BIT_WHEEL_SIZE',
  'BIT_TYRE_SIZE',
  'BIT_TYRE_TYPE',
  'BIT_WINDSHIELD_WIPER',
  'default' => 'BIT_OTHER'
)


/*
access levels
99 owner (everything)
70 service provider (add event, add car data, no delete, no event edit except self-added)






Tr. priemonÄ—s tipas iš sÄ…raÅ¡o pavadinimas ir dar langelis- kodas tipo M1

MarkÄ— - pasirinkimas iš sÄ…rašo

Modelis - pasirinkimas iš s sÄ…rašo

Modifikacija - paÄiam Ä¯rašyti tipo Aero, arba expression, vector, Alpine ir t.t

Valstybinis numeris

VIN numeris (nurodytiems variantams atsidarytÅ³ 11 langeliÅ³ t.y pagrindinei masei)

Gamybos metai pilna versija metai mÄ—nuo diena (gerai atrodo toks skaitliukas kur sukioji skaiÄius kaip kodinÄ—j spynoj)

Darbinis tÅ«ris cm kubiniais

Galia kW arba AG - manau galimybÄ— pasirinkti, o užkulisiuose skaiÄiuoklÄ— paverstÅ³ visaip kaip reikia

Kuro tipas (reiktÅ³ pažiÅ«rÄ—ti kas po ko eiliš¡kume kuras po ko)

Spalva iš sÄ…rašo plius spalvos koodas ir Trim kodas keli langeliai arba pritaikyti kai ne vienos spalvos.

SÄ—dimÅ³ vietÅ³ skaiÄius- iš sÄ…rašo
DurÅ³ skaiÄius iš sÄ…rašo

>PavarÅ³ dÄ—žÄ—s tipas

Mases irgi reiktÅ³ surašyti , bet dar išsiaiškinsiu kokias 


CREATE TABLE IF NOT EXISTS `users` (
`user_id` int(11) NOT NULL COMMENT 'auto incrementing user_id of each user, unique index',
  `session_id` varchar(48) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'stores session cookie id to prevent session concurrency',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
  `user_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s activation status',
  `user_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s deletion status',
  `user_account_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'user''s account type (basic, premium, etc)',
  `user_has_avatar` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 if user has a local avatar, 0 if not',
  `user_remember_me_token` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s remember-me cookie token',
  `user_creation_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the creation of user''s account',
  `user_suspension_timestamp` bigint(20) DEFAULT NULL COMMENT 'Timestamp till the end of a user suspension',
  `user_last_login_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of user''s last login',
  `user_failed_logins` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s failed login attempts',
  `user_last_failed_login` int(10) DEFAULT NULL COMMENT 'unix timestamp of last failed login attempt',
  `user_activation_hash` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s email verification hash string',
  `user_password_reset_hash` char(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s password reset code',
  `user_password_reset_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the password reset request',
  `user_provider_type` text COLLATE utf8_unicode_ci,
  `user_uuid` char(36) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_lang` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'EN',
  `last_car` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `user_data` blob NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';




*/
 ?>