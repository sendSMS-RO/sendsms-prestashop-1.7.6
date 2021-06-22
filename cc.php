<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 *
 *  @author    Radu Vasile Catalin
 *  @copyright 2020-2020 Any Media Development
 *  @license   AFL
 */

class SendSMSCC
{
    public $country_codes = array(
        0 =>
        array(
            'label' => 'INT (INT)',
            'value' => 'INT',
        ),
        1 =>
        array(
            'label' => 'AC (247)',
            'value' => '247',
        ),
        2 =>
        array(
            'label' => 'AD (376)',
            'value' => '376',
        ),
        3 =>
        array(
            'label' => 'AE (971)',
            'value' => '971',
        ),
        4 =>
        array(
            'label' => 'AF (93)',
            'value' => '93',
        ),
        5 =>
        array(
            'label' => 'AG (1268)',
            'value' => '1268',
        ),
        6 =>
        array(
            'label' => 'AI (1264)',
            'value' => '1264',
        ),
        7 =>
        array(
            'label' => 'AL (355)',
            'value' => '355',
        ),
        8 =>
        array(
            'label' => 'AM (374)',
            'value' => '374',
        ),
        9 =>
        array(
            'label' => 'AO (244)',
            'value' => '244',
        ),
        10 =>
        array(
            'label' => 'AQ (672)',
            'value' => '672',
        ),
        11 =>
        array(
            'label' => 'AR (54)',
            'value' => '54',
        ),
        12 =>
        array(
            'label' => 'AS (1684)',
            'value' => '1684',
        ),
        13 =>
        array(
            'label' => 'AT (43)',
            'value' => '43',
        ),
        14 =>
        array(
            'label' => 'AU (61)',
            'value' => '61',
        ),
        15 =>
        array(
            'label' => 'AW (297)',
            'value' => '297',
        ),
        16 =>
        array(
            'label' => 'AX (358)',
            'value' => '358',
        ),
        17 =>
        array(
            'label' => 'AZ (994)',
            'value' => '994',
        ),
        18 =>
        array(
            'label' => 'BA (387)',
            'value' => '387',
        ),
        19 =>
        array(
            'label' => 'BB (1246)',
            'value' => '1246',
        ),
        20 =>
        array(
            'label' => 'BD (880)',
            'value' => '880',
        ),
        21 =>
        array(
            'label' => 'BE (32)',
            'value' => '32',
        ),
        22 =>
        array(
            'label' => 'BF (226)',
            'value' => '226',
        ),
        23 =>
        array(
            'label' => 'BG (359)',
            'value' => '359',
        ),
        24 =>
        array(
            'label' => 'BH (973)',
            'value' => '973',
        ),
        25 =>
        array(
            'label' => 'BI (257)',
            'value' => '257',
        ),
        26 =>
        array(
            'label' => 'BJ (229)',
            'value' => '229',
        ),
        27 =>
        array(
            'label' => 'BL (590)',
            'value' => '590',
        ),
        28 =>
        array(
            'label' => 'BM (1441)',
            'value' => '1441',
        ),
        29 =>
        array(
            'label' => 'BN (673)',
            'value' => '673',
        ),
        30 =>
        array(
            'label' => 'BO (591)',
            'value' => '591',
        ),
        31 =>
        array(
            'label' => 'BQ (599)',
            'value' => '599',
        ),
        32 =>
        array(
            'label' => 'BR (55)',
            'value' => '55',
        ),
        33 =>
        array(
            'label' => 'BS (1242)',
            'value' => '1242',
        ),
        34 =>
        array(
            'label' => 'BT (975)',
            'value' => '975',
        ),
        35 =>
        array(
            'label' => 'BW (267)',
            'value' => '267',
        ),
        36 =>
        array(
            'label' => 'BY (375)',
            'value' => '375',
        ),
        37 =>
        array(
            'label' => 'BZ (501)',
            'value' => '501',
        ),
        38 =>
        array(
            'label' => 'CA (1)',
            'value' => '1',
        ),
        39 =>
        array(
            'label' => 'CC (61)',
            'value' => '61',
        ),
        40 =>
        array(
            'label' => 'CD (243)',
            'value' => '243',
        ),
        41 =>
        array(
            'label' => 'CF (236)',
            'value' => '236',
        ),
        42 =>
        array(
            'label' => 'CG (242)',
            'value' => '242',
        ),
        43 =>
        array(
            'label' => 'CH (41)',
            'value' => '41',
        ),
        44 =>
        array(
            'label' => 'CI (225)',
            'value' => '225',
        ),
        45 =>
        array(
            'label' => 'CK (682)',
            'value' => '682',
        ),
        46 =>
        array(
            'label' => 'CL (56)',
            'value' => '56',
        ),
        47 =>
        array(
            'label' => 'CM (237)',
            'value' => '237',
        ),
        48 =>
        array(
            'label' => 'CN (86)',
            'value' => '86',
        ),
        49 =>
        array(
            'label' => 'CO (57)',
            'value' => '57',
        ),
        50 =>
        array(
            'label' => 'CR (506)',
            'value' => '506',
        ),
        51 =>
        array(
            'label' => 'CU (53)',
            'value' => '53',
        ),
        52 =>
        array(
            'label' => 'CV (238)',
            'value' => '238',
        ),
        53 =>
        array(
            'label' => 'CW (599)',
            'value' => '599',
        ),
        54 =>
        array(
            'label' => 'CX (61)',
            'value' => '61',
        ),
        55 =>
        array(
            'label' => 'CY (357)',
            'value' => '357',
        ),
        56 =>
        array(
            'label' => 'CZ (420)',
            'value' => '420',
        ),
        57 =>
        array(
            'label' => 'DE (49)',
            'value' => '49',
        ),
        58 =>
        array(
            'label' => 'DJ (253)',
            'value' => '253',
        ),
        59 =>
        array(
            'label' => 'DK (45)',
            'value' => '45',
        ),
        60 =>
        array(
            'label' => 'DM (1767)',
            'value' => '1767',
        ),
        61 =>
        array(
            'label' => 'DO (1849)',
            'value' => '1849',
        ),
        62 =>
        array(
            'label' => 'DZ (213)',
            'value' => '213',
        ),
        63 =>
        array(
            'label' => 'EC (593)',
            'value' => '593',
        ),
        64 =>
        array(
            'label' => 'EE (372)',
            'value' => '372',
        ),
        65 =>
        array(
            'label' => 'EG (20)',
            'value' => '20',
        ),
        66 =>
        array(
            'label' => 'EH (212)',
            'value' => '212',
        ),
        67 =>
        array(
            'label' => 'ER (291)',
            'value' => '291',
        ),
        68 =>
        array(
            'label' => 'ES (34)',
            'value' => '34',
        ),
        69 =>
        array(
            'label' => 'ET (251)',
            'value' => '251',
        ),
        70 =>
        array(
            'label' => 'EU (388)',
            'value' => '388',
        ),
        71 =>
        array(
            'label' => 'FI (358)',
            'value' => '358',
        ),
        72 =>
        array(
            'label' => 'FJ (679)',
            'value' => '679',
        ),
        73 =>
        array(
            'label' => 'FK (500)',
            'value' => '500',
        ),
        74 =>
        array(
            'label' => 'FM (691)',
            'value' => '691',
        ),
        75 =>
        array(
            'label' => 'FO (298)',
            'value' => '298',
        ),
        76 =>
        array(
            'label' => 'FR (33)',
            'value' => '33',
        ),
        77 =>
        array(
            'label' => 'GA (241)',
            'value' => '241',
        ),
        78 =>
        array(
            'label' => 'GB (44)',
            'value' => '44',
        ),
        79 =>
        array(
            'label' => 'GD (1473)',
            'value' => '1473',
        ),
        80 =>
        array(
            'label' => 'GE (995)',
            'value' => '995',
        ),
        81 =>
        array(
            'label' => 'GF (594)',
            'value' => '594',
        ),
        82 =>
        array(
            'label' => 'GG (44)',
            'value' => '44',
        ),
        83 =>
        array(
            'label' => 'GH (233)',
            'value' => '233',
        ),
        84 =>
        array(
            'label' => 'GI (350)',
            'value' => '350',
        ),
        85 =>
        array(
            'label' => 'GL (299)',
            'value' => '299',
        ),
        86 =>
        array(
            'label' => 'GM (220)',
            'value' => '220',
        ),
        87 =>
        array(
            'label' => 'GN (224)',
            'value' => '224',
        ),
        88 =>
        array(
            'label' => 'GP (590)',
            'value' => '590',
        ),
        89 =>
        array(
            'label' => 'GQ (240)',
            'value' => '240',
        ),
        90 =>
        array(
            'label' => 'GR (30)',
            'value' => '30',
        ),
        91 =>
        array(
            'label' => 'GT (502)',
            'value' => '502',
        ),
        92 =>
        array(
            'label' => 'GU (1671)',
            'value' => '1671',
        ),
        93 =>
        array(
            'label' => 'GW (245)',
            'value' => '245',
        ),
        94 =>
        array(
            'label' => 'GY (592)',
            'value' => '592',
        ),
        95 =>
        array(
            'label' => 'HK (852)',
            'value' => '852',
        ),
        96 =>
        array(
            'label' => 'HN (504)',
            'value' => '504',
        ),
        97 =>
        array(
            'label' => 'HR (385)',
            'value' => '385',
        ),
        98 =>
        array(
            'label' => 'HT (509)',
            'value' => '509',
        ),
        99 =>
        array(
            'label' => 'HU (36)',
            'value' => '36',
        ),
        100 =>
        array(
            'label' => 'ID (62)',
            'value' => '62',
        ),
        101 =>
        array(
            'label' => 'IE (353)',
            'value' => '353',
        ),
        102 =>
        array(
            'label' => 'IL (972)',
            'value' => '972',
        ),
        103 =>
        array(
            'label' => 'IM (44)',
            'value' => '44',
        ),
        104 =>
        array(
            'label' => 'IN (91)',
            'value' => '91',
        ),
        105 =>
        array(
            'label' => 'IO (246)',
            'value' => '246',
        ),
        106 =>
        array(
            'label' => 'IQ (964)',
            'value' => '964',
        ),
        107 =>
        array(
            'label' => 'IR (98)',
            'value' => '98',
        ),
        108 =>
        array(
            'label' => 'IS (354)',
            'value' => '354',
        ),
        109 =>
        array(
            'label' => 'IT (39)',
            'value' => '39',
        ),
        110 =>
        array(
            'label' => 'JE (44)',
            'value' => '44',
        ),
        111 =>
        array(
            'label' => 'JM (1876)',
            'value' => '1876',
        ),
        112 =>
        array(
            'label' => 'JO (962)',
            'value' => '962',
        ),
        113 =>
        array(
            'label' => 'JP (81)',
            'value' => '81',
        ),
        114 =>
        array(
            'label' => 'KE (254)',
            'value' => '254',
        ),
        115 =>
        array(
            'label' => 'KG (996)',
            'value' => '996',
        ),
        116 =>
        array(
            'label' => 'KH (855)',
            'value' => '855',
        ),
        117 =>
        array(
            'label' => 'KI (686)',
            'value' => '686',
        ),
        118 =>
        array(
            'label' => 'KM (269)',
            'value' => '269',
        ),
        119 =>
        array(
            'label' => 'KN (1869)',
            'value' => '1869',
        ),
        120 =>
        array(
            'label' => 'KP (850)',
            'value' => '850',
        ),
        121 =>
        array(
            'label' => 'KR (82)',
            'value' => '82',
        ),
        122 =>
        array(
            'label' => 'KW (965)',
            'value' => '965',
        ),
        123 =>
        array(
            'label' => 'KY (1345)',
            'value' => '1345',
        ),
        124 =>
        array(
            'label' => 'KZ (7)',
            'value' => '7',
        ),
        125 =>
        array(
            'label' => 'LA (856)',
            'value' => '856',
        ),
        126 =>
        array(
            'label' => 'LB (961)',
            'value' => '961',
        ),
        127 =>
        array(
            'label' => 'LC (1758)',
            'value' => '1758',
        ),
        128 =>
        array(
            'label' => 'LI (423)',
            'value' => '423',
        ),
        129 =>
        array(
            'label' => 'LK (94)',
            'value' => '94',
        ),
        130 =>
        array(
            'label' => 'LR (231)',
            'value' => '231',
        ),
        131 =>
        array(
            'label' => 'LS (266)',
            'value' => '266',
        ),
        132 =>
        array(
            'label' => 'LT (370)',
            'value' => '370',
        ),
        133 =>
        array(
            'label' => 'LU (352)',
            'value' => '352',
        ),
        134 =>
        array(
            'label' => 'LV (371)',
            'value' => '371',
        ),
        135 =>
        array(
            'label' => 'LY (218)',
            'value' => '218',
        ),
        136 =>
        array(
            'label' => 'MA (212)',
            'value' => '212',
        ),
        137 =>
        array(
            'label' => 'MC (377)',
            'value' => '377',
        ),
        138 =>
        array(
            'label' => 'MD (373)',
            'value' => '373',
        ),
        139 =>
        array(
            'label' => 'ME (382)',
            'value' => '382',
        ),
        140 =>
        array(
            'label' => 'MF (590)',
            'value' => '590',
        ),
        141 =>
        array(
            'label' => 'MG (261)',
            'value' => '261',
        ),
        142 =>
        array(
            'label' => 'MH (692)',
            'value' => '692',
        ),
        143 =>
        array(
            'label' => 'MK (389)',
            'value' => '389',
        ),
        144 =>
        array(
            'label' => 'ML (223)',
            'value' => '223',
        ),
        145 =>
        array(
            'label' => 'MM (95)',
            'value' => '95',
        ),
        146 =>
        array(
            'label' => 'MN (976)',
            'value' => '976',
        ),
        147 =>
        array(
            'label' => 'MO (853)',
            'value' => '853',
        ),
        148 =>
        array(
            'label' => 'MP (1670)',
            'value' => '1670',
        ),
        149 =>
        array(
            'label' => 'MQ (596)',
            'value' => '596',
        ),
        150 =>
        array(
            'label' => 'MR (222)',
            'value' => '222',
        ),
        151 =>
        array(
            'label' => 'MS (1664)',
            'value' => '1664',
        ),
        152 =>
        array(
            'label' => 'MT (356)',
            'value' => '356',
        ),
        153 =>
        array(
            'label' => 'MU (230)',
            'value' => '230',
        ),
        154 =>
        array(
            'label' => 'MV (960)',
            'value' => '960',
        ),
        155 =>
        array(
            'label' => 'MW (265)',
            'value' => '265',
        ),
        156 =>
        array(
            'label' => 'MX (52)',
            'value' => '52',
        ),
        157 =>
        array(
            'label' => 'MY (60)',
            'value' => '60',
        ),
        158 =>
        array(
            'label' => 'MZ (258)',
            'value' => '258',
        ),
        159 =>
        array(
            'label' => 'NA (264)',
            'value' => '264',
        ),
        160 =>
        array(
            'label' => 'NC (687)',
            'value' => '687',
        ),
        161 =>
        array(
            'label' => 'NE (227)',
            'value' => '227',
        ),
        162 =>
        array(
            'label' => 'NF (672)',
            'value' => '672',
        ),
        163 =>
        array(
            'label' => 'NG (234)',
            'value' => '234',
        ),
        164 =>
        array(
            'label' => 'NI (505)',
            'value' => '505',
        ),
        165 =>
        array(
            'label' => 'NL (31)',
            'value' => '31',
        ),
        166 =>
        array(
            'label' => 'NO (47)',
            'value' => '47',
        ),
        167 =>
        array(
            'label' => 'NP (977)',
            'value' => '977',
        ),
        168 =>
        array(
            'label' => 'NR (674)',
            'value' => '674',
        ),
        169 =>
        array(
            'label' => 'NU (683)',
            'value' => '683',
        ),
        170 =>
        array(
            'label' => 'NZ (64)',
            'value' => '64',
        ),
        171 =>
        array(
            'label' => 'OM (968)',
            'value' => '968',
        ),
        172 =>
        array(
            'label' => 'PA (507)',
            'value' => '507',
        ),
        173 =>
        array(
            'label' => 'PE (51)',
            'value' => '51',
        ),
        174 =>
        array(
            'label' => 'PF (689)',
            'value' => '689',
        ),
        175 =>
        array(
            'label' => 'PG (675)',
            'value' => '675',
        ),
        176 =>
        array(
            'label' => 'PH (63)',
            'value' => '63',
        ),
        177 =>
        array(
            'label' => 'PK (92)',
            'value' => '92',
        ),
        178 =>
        array(
            'label' => 'PL (48)',
            'value' => '48',
        ),
        179 =>
        array(
            'label' => 'PM (508)',
            'value' => '508',
        ),
        180 =>
        array(
            'label' => 'PR (1939)',
            'value' => '1939',
        ),
        181 =>
        array(
            'label' => 'PS (970)',
            'value' => '970',
        ),
        182 =>
        array(
            'label' => 'PT (351)',
            'value' => '351',
        ),
        183 =>
        array(
            'label' => 'PW (680)',
            'value' => '680',
        ),
        184 =>
        array(
            'label' => 'PY (595)',
            'value' => '595',
        ),
        185 =>
        array(
            'label' => 'QA (974)',
            'value' => '974',
        ),
        186 =>
        array(
            'label' => 'QN (374)',
            'value' => '374',
        ),
        187 =>
        array(
            'label' => 'QS (252)',
            'value' => '252',
        ),
        188 =>
        array(
            'label' => 'QY (90)',
            'value' => '90',
        ),
        189 =>
        array(
            'label' => 'RE (262)',
            'value' => '262',
        ),
        190 =>
        array(
            'label' => 'RO (40)',
            'value' => '40',
        ),
        191 =>
        array(
            'label' => 'RS (381)',
            'value' => '381',
        ),
        192 =>
        array(
            'label' => 'RU (7)',
            'value' => '7',
        ),
        193 =>
        array(
            'label' => 'RW (250)',
            'value' => '250',
        ),
        194 =>
        array(
            'label' => 'SA (966)',
            'value' => '966',
        ),
        195 =>
        array(
            'label' => 'SB (677)',
            'value' => '677',
        ),
        196 =>
        array(
            'label' => 'SC (248)',
            'value' => '248',
        ),
        197 =>
        array(
            'label' => 'SD (249)',
            'value' => '249',
        ),
        198 =>
        array(
            'label' => 'SE (46)',
            'value' => '46',
        ),
        199 =>
        array(
            'label' => 'SG (65)',
            'value' => '65',
        ),
        200 =>
        array(
            'label' => 'SH (290)',
            'value' => '290',
        ),
        201 =>
        array(
            'label' => 'SI (386)',
            'value' => '386',
        ),
        202 =>
        array(
            'label' => 'SJ (47)',
            'value' => '47',
        ),
        203 =>
        array(
            'label' => 'SK (421)',
            'value' => '421',
        ),
        204 =>
        array(
            'label' => 'SL (232)',
            'value' => '232',
        ),
        205 =>
        array(
            'label' => 'SM (378)',
            'value' => '378',
        ),
        206 =>
        array(
            'label' => 'SN (221)',
            'value' => '221',
        ),
        207 =>
        array(
            'label' => 'SO (252)',
            'value' => '252',
        ),
        208 =>
        array(
            'label' => 'SR (597)',
            'value' => '597',
        ),
        209 =>
        array(
            'label' => 'SS (211)',
            'value' => '211',
        ),
        210 =>
        array(
            'label' => 'ST (239)',
            'value' => '239',
        ),
        211 =>
        array(
            'label' => 'SV (503)',
            'value' => '503',
        ),
        212 =>
        array(
            'label' => 'SX (1721)',
            'value' => '1721',
        ),
        213 =>
        array(
            'label' => 'SY (963)',
            'value' => '963',
        ),
        214 =>
        array(
            'label' => 'SZ (268)',
            'value' => '268',
        ),
        215 =>
        array(
            'label' => 'TA (290)',
            'value' => '290',
        ),
        216 =>
        array(
            'label' => 'TC (1649)',
            'value' => '1649',
        ),
        217 =>
        array(
            'label' => 'TD (235)',
            'value' => '235',
        ),
        218 =>
        array(
            'label' => 'TG (228)',
            'value' => '228',
        ),
        219 =>
        array(
            'label' => 'TH (66)',
            'value' => '66',
        ),
        220 =>
        array(
            'label' => 'TJ (992)',
            'value' => '992',
        ),
        221 =>
        array(
            'label' => 'TK (690)',
            'value' => '690',
        ),
        222 =>
        array(
            'label' => 'TL (670)',
            'value' => '670',
        ),
        223 =>
        array(
            'label' => 'TM (993)',
            'value' => '993',
        ),
        224 =>
        array(
            'label' => 'TN (216)',
            'value' => '216',
        ),
        225 =>
        array(
            'label' => 'TO (676)',
            'value' => '676',
        ),
        226 =>
        array(
            'label' => 'TR (90)',
            'value' => '90',
        ),
        227 =>
        array(
            'label' => 'TT (1868)',
            'value' => '1868',
        ),
        228 =>
        array(
            'label' => 'TV (688)',
            'value' => '688',
        ),
        229 =>
        array(
            'label' => 'TW (886)',
            'value' => '886',
        ),
        230 =>
        array(
            'label' => 'TZ (255)',
            'value' => '255',
        ),
        231 =>
        array(
            'label' => 'UA (380)',
            'value' => '380',
        ),
        232 =>
        array(
            'label' => 'UG (256)',
            'value' => '256',
        ),
        233 =>
        array(
            'label' => 'UK (44)',
            'value' => '44',
        ),
        234 =>
        array(
            'label' => 'US (1)',
            'value' => '1',
        ),
        235 =>
        array(
            'label' => 'UY (598)',
            'value' => '598',
        ),
        236 =>
        array(
            'label' => 'UZ (998)',
            'value' => '998',
        ),
        237 =>
        array(
            'label' => 'VA (39)',
            'value' => '39',
        ),
        238 =>
        array(
            'label' => 'VC (1784)',
            'value' => '1784',
        ),
        239 =>
        array(
            'label' => 'VE (58)',
            'value' => '58',
        ),
        240 =>
        array(
            'label' => 'VG (1284)',
            'value' => '1284',
        ),
        241 =>
        array(
            'label' => 'VI (1340)',
            'value' => '1340',
        ),
        242 =>
        array(
            'label' => 'VN (84)',
            'value' => '84',
        ),
        243 =>
        array(
            'label' => 'VU (678)',
            'value' => '678',
        ),
        244 =>
        array(
            'label' => 'WF (681)',
            'value' => '681',
        ),
        245 =>
        array(
            'label' => 'WS (685)',
            'value' => '685',
        ),
        246 =>
        array(
            'label' => 'XC (991)',
            'value' => '991',
        ),
        247 =>
        array(
            'label' => 'XD (888)',
            'value' => '888',
        ),
        248 =>
        array(
            'label' => 'XG (881)',
            'value' => '881',
        ),
        249 =>
        array(
            'label' => 'XL (883)',
            'value' => '883',
        ),
        250 =>
        array(
            'label' => 'XN (870)',
            'value' => '870',
        ),
        251 =>
        array(
            'label' => 'XP (878)',
            'value' => '878',
        ),
        252 =>
        array(
            'label' => 'XR (979)',
            'value' => '979',
        ),
        253 =>
        array(
            'label' => 'XS (808)',
            'value' => '808',
        ),
        254 =>
        array(
            'label' => 'XT (800)',
            'value' => '800',
        ),
        255 =>
        array(
            'label' => 'XV (882)',
            'value' => '882',
        ),
        256 =>
        array(
            'label' => 'YE (967)',
            'value' => '967',
        ),
        257 =>
        array(
            'label' => 'YT (262)',
            'value' => '262',
        ),
        258 =>
        array(
            'label' => 'ZA (27)',
            'value' => '27',
        ),
        259 =>
        array(
            'label' => 'ZM (260)',
            'value' => '260',
        ),
        260 =>
        array(
            'label' => 'ZW (263)',
            'value' => '263',
        ),
    );
}
