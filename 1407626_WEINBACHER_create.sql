--Projekt KINO: Weinbacher Andreas 1407626

/*
tbl_movie(ISAN, title, genre)
	PK: tbl_movie.ISAN
*/
CREATE TABLE tbl_movie(
  ISAN         NUMBER(15) PRIMARY KEY,
  title        VARCHAR(150) NOT NULL,
  genre       VARCHAR(60) NOT NULL,
  CONSTRAINT tbl_movie_ISAN_chk CHECK (ISAN > 0)
);

/*
tbl_actor(ISAN, actor)
	PK: tbl_actor.ISAN, actor
	FK: tbl_actor.ISAN ◊ tbl_movie.ISAN
*/
CREATE TABLE tbl_actor(
  ISAN         NUMBER(15)
    CONSTRAINT tbl_actor_ISAN_fk REFERENCES tbl_movie(ISAN),
  ACTOR        VARCHAR(50),
  PRIMARY KEY(ISAN,actor)
);

/*
tbl_cinema(cinemaId, cinemaName, zipcode, city, street, streetnumber)
	PK: tbl_cinema.cinemaId
*/
CREATE TABLE tbl_cinema(
  cinemaId        NUMBER(4) PRIMARY KEY NOT NULL,
  cinemaName      VARCHAR(40) NOT NULL,
  zipcode         NUMBER(5),
  city            VARCHAR(40),
  street          VARCHAR(50),
  streetnumber    VARCHAR(4) DEFAULT 0,
  opentimes       VARCHAR(100),
  CONSTRAINT tbl_cinema_zip_chk CHECK (zipcode > 1000)
);

--Sequence for cinemaId
CREATE SEQUENCE cinemaId_seq
  MINVALUE 1
  START WITH 1
  MAXVALUE 100
  INCREMENT BY 1
  NOCACHE;
  
CREATE OR REPLACE TRIGGER cinemaId_trigger
BEFORE INSERT ON tbl_cinema
FOR EACH ROW
BEGIN
  SELECT cinemaId_seq.NEXTVAL
  INTO :new.cinemaId
  FROM dual;
END;
/

/*
tbl_prequels(ISAN1, ISAN2)
	PK: (ISAN1, ISAN2)
	FK: tbl_ prequels.ISAN1 ◊ tbl_movie.ISAN
	FK: tbl_ prequels.ISAN2 ◊ tbl_movie.ISAN
//CHECK ISAN1!= ISAN2
*/

CREATE TABLE tbl_prequels(
  ISAN1        NUMBER(15),
    CONSTRAINT tbl_movie_ISAN1_fk FOREIGN KEY (ISAN1) REFERENCES tbl_movie(ISAN) ON DELETE CASCADE,
  ISAN2        NUMBER(15),
   CONSTRAINT tbl_movie_ISAN2_fk FOREIGN KEY (ISAN2) REFERENCES tbl_movie(ISAN) ON DELETE CASCADE,
  PRIMARY KEY(ISAN1, ISAN2),
  CONSTRAINT tbl_prequels_chk CHECK (ISAN1 != ISAN2)
);

/*
tbl_hall(hallId, cinemaName, disaccess, screenarea)
	PK: (hallId, cinemaName)
	FK: tbl_hall.cinemaName ◊ tbl_cinema.cinemaName
*/
CREATE TABLE tbl_hall(
  hallId          NUMBER(1),
  cinemaId        NUMBER(4)
    CONSTRAINT tbl_hall_cinemaId_fk REFERENCES tbl_cinema(cinemaId),
  disaccess       CHAR(1) DEFAULT 'n',
  screenarea      NUMBER(4),
  PRIMARY KEY(hallId, cinemaId),
  CONSTRAINT tbl_hall_disacc_chk CHECK (disaccess IN ('y','n'))
);
  
/*
tbl_show(showId, ISAN, showstart, length, agerestriction) 
--Date stored is showstart
	PK: (showId)
	FK: tbls_show.ISAN ◊ tbls_movie.ISAN
*/



CREATE TABLE tbl_show(
  showId        NUMBER,  
  ISAN          NUMBER NOT NULL
    CONSTRAINT tbl_show_ISAN_fk REFERENCES tbl_movie(ISAN),
  showstart     DATE NOT NULL,
  showend       DATE NOT NULL,
  hallId        NUMBER(1),
  cinemaId        NUMBER(4),
  agerestriction    NUMBER(2) DEFAULT 6,
  CONSTRAINT tbl_show_hallIdcinemaId_fk
    FOREIGN KEY(hallId,cinemaId)
    REFERENCES tbl_hall(hallId,cinemaId),
  PRIMARY KEY(showId),
  --check if End is not before Begin
  CONSTRAINT tbl_show_EndAfterBegin CHECK (showend>showstart),
  CONSTRAINT tbl_show_age_chk CHECK (agerestriction IN (6,12,16,18))
);

--Sequence for showId
CREATE SEQUENCE showId_seq
  MINVALUE 0
  START WITH 0
  MAXVALUE 99999
  INCREMENT BY 1
  NOCACHE;
  
CREATE OR REPLACE TRIGGER showId_trigger
BEFORE INSERT ON tbl_show
FOR EACH ROW
BEGIN
  SELECT showId_seq.NEXTVAL
  INTO :new.showId
  FROM dual;
END;
/



--check if hall is available at this time
CREATE OR REPLACE TRIGGER tbl_show_HallFree_Trigger
BEFORE INSERT OR UPDATE ON tbl_show
FOR EACH ROW
DECLARE
  cnt NUMBER;
BEGIN
  SELECT COUNT(*) INTO cnt FROM tbl_show
    WHERE tbl_show.hallId = :new.hallId AND 
          tbl_show.cinemaId = :new.cinemaId AND
          (( :new.showstart BETWEEN tbl_show.showstart AND tbl_show.showend) OR
           ( :new.showend BETWEEN tbl_show.showstart AND tbl_show.showend));
  IF cnt != 0 THEN
    raise_application_error(-1988, 'Another Show allready plays in this hall at the same time');
  END IF;
END;
/

/*
rel_plays(showId, hallId, cinemaName) 
	PK: (showId, hallId, cinemaName)
	FK: tbl_plays.showId ◊ tbl_show.showId
	FK: tbl_plays.hallId ◊ tbl_hall.hallId
	FK: tbl_plays.cinemaNAme ◊ tbl_cinema.cinemaName
  */
  /*
CREATE TABLE rel_plays(
  showid  NUMBER
    CONSTRAINT fk_showid REFERENCES tbl_show(showid),
  hallid  NUMBER(1)
    CONSTRAINT fk_hallid REFERENCES tbl_hall(hallid),
  cinemaId  NUMBER(4)
    CONSTRAINT fk_cinema REFERENCES tbl_cinema(cinemaId),
    PRIMARY KEY (showid, hallid, cinemaId)
);
*/

/*
isa_user(mailaddress, firstname, lastname)
PK: mailaddress
*/
CREATE TABLE isa_user(
  mailaddress     VARCHAR(40) PRIMARY KEY,
  firstname       VARCHAR(40) NOT NULL,
  lastname        VARCHAR(50) NOT NULL
);

/*
rel_staff(staffId, mailaddress, rank, vacationdays)
PK: mailaddress
FK: rel_staff.mailaddress ◊ isa_user.mailaddress*/
CREATE TABLE rel_staff(
  mailaddress     VARCHAR(40) 
    CONSTRAINT rel_staff_mail_fk REFERENCES isa_user(mailaddress),
  staffId         NUMBER(4),
  --CONSTRAINT unique_staffId UNIQUE (staffId),
  rank            VARCHAR(40),
  vacationdays    NUMBER(2),
  PRIMARY KEY(mailaddress)
);

--Sequence for staffid
CREATE SEQUENCE staffId_seq
  MINVALUE 1000
  START WITH 1000
  MAXVALUE 9999
  INCREMENT BY 1
  NOCACHE;
  
CREATE OR REPLACE TRIGGER staffId_trigger
BEFORE INSERT ON rel_staff
FOR EACH ROW
BEGIN
  SELECT staffId_seq.NEXTVAL
  INTO :new.staffId
  FROM dual;
END;
/

/*
rel_reguser(mailaddress, username, password, moviepoints)
PK: mailaddress
FK: rel_reguser.mailaddress ◊ isa_user.mailaddress */
CREATE TABLE rel_reguser(
  mailaddress     VARCHAR(40) 
    CONSTRAINT rel_regusr_mail_fk REFERENCES isa_user(mailaddress),
  username        VARCHAR(40) NOT NULL
    CONSTRAINT unique_usrname UNIQUE,
  password        VARCHAR(20) NOT NULL,
  moviepoints    NUMBER(4) DEFAULT 0,
  PRIMARY KEY(mailaddress)
);


/*
rel_reservation (reservationId, mailaddress, showId, hallId, cinemaName, rowNr, seatNr)
	PK: (showId, hallId, cinemaName, rowNr, seatNr)
	FK: rel_reservation.mailaddress ◊ tbl_user.mailaddress
rel_reservation.showId ◊ tbl_show.showId
		rel_reservation.hallId ◊ tbl_show.hallId
		rel_reservation.cinema ◊ tbl_show.cinemaName
		rel_reservation.rowNr ◊ tbl_ticket.rowNr
		rel_reservation.seatNr ◊ tbl_ticket.seatNr
*/
CREATE TABLE rel_reservation(
  reservationId   NUMBER(6),
    CONSTRAINT unique_resId UNIQUE (reservationId),
  mailaddress     VARCHAR(40) 
    CONSTRAINT rel_reserv_mail_fk REFERENCES isa_user(mailaddress),
  showId        NUMBER, 
    CONSTRAINT rel_res_showid_fk
    FOREIGN KEY(showId)
    REFERENCES tbl_show(showId),
  rowNr           NUMBER(2),
  seatNr          NUMBER(2),
    PRIMARY KEY(rowNr, seatNr, showId)
);

--Sequence for staffid
CREATE SEQUENCE reservationid_seq
  MINVALUE 0
  START WITH 0
  INCREMENT BY 1
  NOCACHE;
  
  CREATE OR REPLACE TRIGGER reseservationid_trigger
BEFORE INSERT OR UPDATE ON rel_reservation
FOR EACH ROW
BEGIN
  SELECT reservationid_seq.NEXTVAL
  INTO :new.reservationId
  FROM dual;
END;
/

--SELECT USERNAME FROM REL_REGUSER WHERE MAILADDRESS = 'ssmith86@pbs.org';
--STORED PREOCEDURE
create or replace PROCEDURE getusername(MAIL IN VARCHAR, USR OUT VARCHAR) IS
BEGIN 
SELECT USERNAME INTO USR FROM REL_REGUSER WHERE MAILADDRESS = MAIL;
END;
/

--VIEW to get information about show
CREATE VIEW GETSHOWINFO AS 
SELECT rownr, seatnr, showid FROM REL_RESERVATION NATURAL JOIN TBL_SHOW ORDER BY ROWNR, SEATNR;