-- Utworzenie wszystkich tabel bazy GolInfo w Oracle SQL

-- USERS
CREATE TABLE Users (
    user_id NUMBER PRIMARY KEY,
    first_name VARCHAR2(100),
    last_name VARCHAR2(100),
    password VARCHAR2(255) NOT NULL,
    email VARCHAR2(255) UNIQUE NOT NULL,
    who_added NUMBER NOT NULL,
    who_modified NUMBER,
    creation_date DATE NOT NULL,
    modification_date DATE,
    FOREIGN KEY (who_added) REFERENCES Users(user_id),
    FOREIGN KEY (who_modified) REFERENCES Users(user_id)
);

-- PRESS ARTICLES
CREATE TABLE PressArticles (
    id_article NUMBER PRIMARY KEY,
    title VARCHAR2(255) NOT NULL,
	photo BLOB,
    category VARCHAR2(100) NOT NULL,
    content CLOB NOT NULL,
    publication_date DATE NOT NULL,
    user_id NUMBER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- ROLES
CREATE TABLE Roles (
    role_id NUMBER PRIMARY KEY,
    name VARCHAR2(100) NOT NULL,
    is_active CHAR(1) CHECK (is_active IN ('Y', 'N')) NOT NULL,
    creation_date DATE NOT NULL,
    active_until DATE
);

-- USERSROLES
CREATE TABLE UsersRoles (
    user_id_role NUMBER PRIMARY KEY,
    user_id NUMBER NOT NULL,
    role_id NUMBER NOT NULL,
    assignment_date DATE NOT NULL,
    withdrawal_date DATE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (role_id) REFERENCES Roles(role_id)
);

-- CLUBS
CREATE TABLE Clubs (
    club_id NUMBER PRIMARY KEY,
    name VARCHAR2(150) NOT NULL,
    crest BLOB,
    home_stadium VARCHAR2(150),
    is_national CHAR(1) CHECK (is_national IN ('Y', 'N')) NOT NULL
);

-- FAVORITESCLUBS
CREATE TABLE FavouritesClubs (
    favorite_id NUMBER PRIMARY KEY,
    user_id NUMBER NOT NULL,
    club_id NUMBER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (club_id) REFERENCES Clubs(club_id)
);

-- LEAGUES
CREATE TABLE Leagues (
    league_id NUMBER PRIMARY KEY,
    name VARCHAR2(150) NOT NULL,
    country VARCHAR2(100),
    logo BLOB,
    year_of_play NUMBER(4) NOT NULL
);

-- CLUBSLEAGUES
CREATE TABLE ClubsLeagues (
    club_id_league NUMBER PRIMARY KEY,
    club_id NUMBER NOT NULL,
    league_id NUMBER NOT NULL,
    number_of_played_matches NUMBER,
    number_of_won_matches NUMBER,
    number_of_drawn_matches NUMBER,
    number_of_losts NUMBER,
    number_of_points NUMBER,
    FOREIGN KEY (club_id) REFERENCES Clubs(club_id),
    FOREIGN KEY (league_id) REFERENCES Leagues(league_id)
);

-- MATCHES
CREATE TABLE Matches (
    match_id NUMBER PRIMARY KEY,
    league_id NUMBER NOT NULL,
    club_1_id NUMBER NOT NULL,
    club_2_id NUMBER NOT NULL,
    level_of_play VARCHAR2(10) CHECK (level_of_play IN ('Group', '1/128', '1/64', '1/32', '1/16', '1/8', '1/4', '1/2', 'Final')) NOT NULL,
    club_result_1 NUMBER,
    club_result_2 NUMBER,
    duration INTERVAL DAY TO SECOND,
    club_possession_1 NUMBER(5,2),
    club_possession_2 NUMBER(5,2),
    club_chances_1 NUMBER,
    club_chances_2 NUMBER,
    club_corners_1 NUMBER,
    club_corners_2 NUMBER,
    club_free_kicks_1 NUMBER,
    club_free_kicks_2 NUMBER,
    club_penalties_1 NUMBER,
    club_penalties_2 NUMBER,
    club_offsides_1 NUMBER,
    club_offsides_2 NUMBER,
    club_fouls_1 NUMBER,
    club_fouls_2 NUMBER,
    club_passes_1 NUMBER,
    club_passes_2 NUMBER,
    FOREIGN KEY (league_id) REFERENCES Leagues(league_id),
    FOREIGN KEY (club_1_id) REFERENCES Clubs(club_id),
    FOREIGN KEY (club_2_id) REFERENCES Clubs(club_id)
);

-- PLAYERS
CREATE TABLE Players (
    player_id NUMBER PRIMARY KEY,
    name VARCHAR2(100) NOT NULL,
    surname VARCHAR2(100) NOT NULL,
    age NUMBER,
    nationality VARCHAR2(100),
    position VARCHAR2(50),
    photo BLOB,
    year_of_play NUMBER(4) NOT NULL
);

-- PLAYERSCLUBS
CREATE TABLE PlayersClubs (
    player_club_id NUMBER PRIMARY KEY,
    player_id NUMBER NOT NULL,
    club_id_league NUMBER NOT NULL,
    number_of_matches_played NUMBER,
    goals NUMBER,
    assists NUMBER,
    yellow_cards NUMBER,
    red_cards NUMBER,
    FOREIGN KEY (player_id) REFERENCES Players(player_id),
    FOREIGN KEY (club_id_league) REFERENCES ClubsLeagues(club_id_league)
);

-- GOALS
CREATE TABLE Goals (
    goal_id NUMBER PRIMARY KEY,
    match_id NUMBER NOT NULL,
    club_id NUMBER NOT NULL,
    player_id NUMBER NOT NULL,
    minute NUMBER NOT NULL,
    type VARCHAR2(20) CHECK (type IN ('normal', 'own_goal', 'penalty', 'free_kick')) NOT NULL,
    FOREIGN KEY (match_id) REFERENCES Matches(match_id),
    FOREIGN KEY (club_id) REFERENCES Clubs(club_id),
    FOREIGN KEY (player_id) REFERENCES Players(player_id)
);

-- CARDS
CREATE TABLE Cards (
    card_id NUMBER PRIMARY KEY,
    match_id NUMBER NOT NULL,
    club_id NUMBER NOT NULL,
    player_id NUMBER NOT NULL,
    minute NUMBER NOT NULL,
    type VARCHAR2(10) CHECK (type IN ('yellow', 'red')) NOT NULL,
    FOREIGN KEY (match_id) REFERENCES Matches(match_id),
    FOREIGN KEY (club_id) REFERENCES Clubs(club_id),
    FOREIGN KEY (player_id) REFERENCES Players(player_id)
);

-- CHANGES
CREATE TABLE Changes (
    change_id NUMBER PRIMARY KEY,
    match_id NUMBER NOT NULL,
    player_in_id NUMBER NOT NULL,
	player_out_id NUMBER NOT NULL,
    minute NUMBER NOT NULL,
    FOREIGN KEY (match_id) REFERENCES Matches(match_id),
    FOREIGN KEY (player_in_id) REFERENCES Players(player_id),
	FOREIGN KEY (player_out_id) REFERENCES Players(player_id)
);
