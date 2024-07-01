# Test Reality City Quest Application API (Source Code)

## Requirements

* PHP version 7.4 or greater.
* MySQL version 8.0 or greater OR MariaDB version 10.5 or greater.
* HTTPS support

## Description

Source Code for Test Reality City Quest Application API. 

It is interactive game, where players need to read riddles, recognise sightseeings and walk city tour. 

Done with Laravel.


## API Methods

post:  /api/auth/login - player login

post:  /api/auth/logout - player logout

post:  /api/auth/checkEmail - check player's email

post:  /api/auth/sendCode - send activation code to a player

post:  /api/auth/checkCode - check player's activation code

post:  /api/auth/changePassword - change players answer


get:  /api/cities/all - cities list

get:  /api/cities/featured - featured cities list

post:  /api/cities/search - search a city


post:  /api/contacts/send - send message using contacts form


get:  /api/games/get/{quest_id} - get game process by quest id

get:  /api/games/next/{quest_id} - get next stage/level

post:  /api/games/checkAnswer/{quest_id} - check player's answer

get:  /api/games/getHint/{quest_id} - get hint

post:  /api/games/getSkip/{quest_id} - get available number of skips

post:  /api/games/setMode/{quest_id} - set game mode

get:  /api/games/getLevel/{quest_id}/{level} - get current game level


get:  /api/modes/ - modes list


get:  /api/pages/about - about project text

get:  /api/pages/howPlay - how play text


get:  /api/quests/all/{city_id} - quests list by city id

get:  /api/quests/featured - featured quests list

get:  /api/quests/get/{id} - get quest by id

get:  /api/quests/done - finished quests list

get:  /api/quests/opened - opened quests list


get:  /api/users/get - get current authorised player

post:  /api/users/saveName - save player's name

post:  /api/users/saveNotes - save player's notifications mode (true/false)

post:  /api/users/savePassword - save player's password


## Changelog

### 1.0
* Released: November 16, 2022

### 2.0
* Released: July 22, 2023

