--Projekt KINO: Weinbacher Andreas 1407626
DROP SEQUENCE staffId_seq;
DROP SEQUENCE reservationid_seq;

--DROP TRIGGER tbl_show_HallFree_Trigger; 


DROP TRIGGER reseservationid_trigger;
ALTER TABLE rel_reservation DROP CONSTRAINT rel_reservation_seats_fk;
DROP TABLE rel_reservation CASCADE CONSTRAINTS;

DROP TRIGGER showId_trigger;
DROP SEQUENCE showId_seq;
ALTER TABLE tbl_show DROP CONSTRAINT tbl_show_ISAN_fk;
DROP TABLE tbl_show CASCADE CONSTRAINTS;

--ALTER TABLE tbl_ticket DROP CONSTRAINT rel_ticket_showid_fk;
DROP TABLE tbl_ticket CASCADE CONSTRAINTS;

ALTER TABLE tbl_hall DROP CONSTRAINT tbl_hall_cinemaId_fk;
DROP TABLE tbl_hall CASCADE CONSTRAINTS;

DROP SEQUENCE cinemaId_seq;
DROP TABLE tbl_cinema CASCADE CONSTRAINTS;

ALTER TABLE tbl_prequels DROP CONSTRAINT tbl_movie_ISAN1_fk;
ALTER TABLE tbl_prequels DROP CONSTRAINT tbl_movie_ISAN2_fk;
DROP TABLE tbl_prequels CASCADE CONSTRAINTS;

ALTER TABLE tbl_actor DROP CONSTRAINT tbl_actor_ISAN_fk;
DROP TABLE tbl_actor CASCADE CONSTRAINTS;

DROP TABLE tbl_movie CASCADE CONSTRAINTS;

ALTER TABLE rel_reguser DROP CONSTRAINT rel_regusr_mail_fk;
DROP TABLE rel_reguser;



DROP TRIGGER staffId_trigger;
ALTER TABLE rel_staff DROP CONSTRAINT unique_staffId;
ALTER TABLE rel_staff DROP CONSTRAINT rel_staff_mail_fk;
DROP TABLE rel_staff;

DROP VIEW GETSHOWINFO;
DROP PROCEDURE GETUSERNAME;

DROP TABLE isa_user CASCADE CONSTRAINTS;