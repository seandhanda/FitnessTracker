-- Drop any existing tables first

drop table DemoTable cascade constraints;
drop table Users cascade constraints;
drop table FriendsWith cascade constraints;
drop table SetsGoals cascade constraints;
drop table CaloricBalance cascade constraints;
drop table Tracks cascade constraints;
drop table Trainers cascade constraints;
drop table Certification cascade constraints;
drop table Has cascade constraints;
drop table Workouts cascade constraints;
drop table Does cascade constraints;
drop table Equipment cascade constraints;
drop table Exercise cascade constraints;
drop table Contains cascade constraints;
-- drop table Uses cascade constraints;
drop table Gyms cascade constraints;
drop table BooksAppointment cascade constraints;

-- Drop existing sequences
drop sequence certification_sequence;
drop sequence exercise_sequence;
drop sequence appointment_sequence;

-- Drop existing triggers
drop trigger certification_trigger;
drop trigger exercise_trigger;
drop trigger appointment_trigger;


-- Create sequences to generate unique ids for tables
-- https://stackoverflow.com/questions/7949343/sqlplus-auto-increment-error
CREATE SEQUENCE certification_sequence
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE exercise_sequence
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE appointment_sequence
START WITH 1
INCREMENT BY 1;


-- Create the tables

CREATE TABLE Users
    (Phone CHAR(10) PRIMARY KEY,
	Name VARCHAR(40),
	Weight INTEGER,
	Height INTEGER);

CREATE TABLE FriendsWith
	(friend1_Phone CHAR(10),
	friend2_Phone CHAR(10),
	PRIMARY KEY(friend1_Phone, friend2_Phone),
	FOREIGN KEY(friend1_Phone) REFERENCES Users(Phone) ON DELETE CASCADE,
	FOREIGN KEY(friend2_Phone) REFERENCES Users(Phone) ON DELETE CASCADE);

CREATE TABLE SetsGoals
    (Phone CHAR(10),
    Goal_Name VARCHAR(40),
    Goal_Quantity INTEGER,
    Goal_Progress INTEGER,
    PRIMARY KEY(Phone, Goal_Name),
    FOREIGN KEY(Phone) REFERENCES Users(Phone) ON DELETE CASCADE);

CREATE TABLE CaloricBalance
	(LogDate DATE PRIMARY KEY,
    Intake INTEGER,
	Burned INTEGER);

CREATE TABLE Tracks
	(Phone CHAR(10),
	LogDate DATE,
	PRIMARY KEY(Phone, LogDate),
	FOREIGN KEY(Phone) REFERENCES Users(Phone) ON DELETE CASCADE,
	FOREIGN KEY(LogDate) REFERENCES CaloricBalance(LogDate) ON DELETE CASCADE);

CREATE TABLE Trainers
	(Phone CHAR(10),
	TrainerID INTEGER,
	Specialty VARCHAR(40),
	PRIMARY KEY(Phone, TrainerID),
	FOREIGN KEY(Phone) REFERENCES Users(Phone) ON DELETE CASCADE);

CREATE TABLE Certification
	(ID INTEGER PRIMARY KEY,
	Title VARCHAR(40),
	GetDate DATE);

-- I think the table name here can be more descriptive. I think it would be helpful for
-- development and debugging.
CREATE TABLE Has
	(Phone CHAR(10),
	TrainerID INTEGER,
	Certification_ID INTEGER,
	PRIMARY KEY(Phone, TrainerID, Certification_ID),
	FOREIGN KEY(Phone, TrainerID) REFERENCES Trainers(Phone, TrainerID) ON DELETE CASCADE,
	FOREIGN KEY(Certification_ID) REFERENCES Certification(ID) ON DELETE CASCADE);

CREATE TABLE Workouts
	(Name VARCHAR(40) PRIMARY KEY,
    NumExercises INTEGER);

-- I think the table name here can be more descriptive. I think it would be helpful for
-- development and debugging.
CREATE TABLE Does
    (Phone CHAR(10),
    Workouts_Name VARCHAR(40),
    PRIMARY KEY(Phone, Workouts_Name),
    FOREIGN KEY(Phone) REFERENCES Users(Phone) ON DELETE CASCADE,
    FOREIGN KEY(Workouts_Name) REFERENCES Workouts(Name) ON DELETE CASCADE);

CREATE TABLE Equipment
	(Name VARCHAR(40) PRIMARY KEY,
	Instructions CHAR(200));

-- I added the foreign key that references the Equipment table
CREATE TABLE Exercise
	(ID INTEGER PRIMARY KEY,
	Exercise_Name VARCHAR(40),
	MuscleGroup VARCHAR(40),
	Repetitions INTEGER,
	Sets INTEGER,
    Equipment_Name VARCHAR(40),
    FOREIGN KEY(Equipment_Name) REFERENCES Equipment(Name) ON DELETE CASCADE,
    UNIQUE (Exercise_Name, MuscleGroup, Repetitions, Sets));

-- I think the table name here can be more descriptive. I think it would be helpful for
-- development and debugging.
CREATE TABLE Contains
	(Workouts_Name VARCHAR(40),
	Exercise_ID INTEGER,
	PRIMARY KEY(Workouts_Name, Exercise_ID),
	FOREIGN KEY(Workouts_Name) REFERENCES Workouts(Name) ON DELETE CASCADE,
	FOREIGN KEY(Exercise_ID) REFERENCES Exercise(ID) ON DELETE CASCADE);

-- I don't think this table makes sense cause it is saying an exercise
-- needs an equipment but according to our ERD that's not the case. I'm
-- not sure how to represent the total participatory constraint on the Equipment
-- table here.

-- CREATE TABLE Uses
-- 	(Exercise_ID INTEGER PRIMARY KEY,
-- 	Equipment_Name CHAR(20) NOT NULL,
-- 	FOREIGN KEY(Exercise_ID) REFERENCES Exercise(ID) ON DELETE CASCADE,
-- 	FOREIGN KEY(Equipment_Name) REFERENCES Equipment(Name) ON DELETE CASCADE);

CREATE TABLE Gyms
	(Address VARCHAR(60),
	PostalCode CHAR(6),
	Name VARCHAR(50),
	PRIMARY KEY(Address, PostalCode));

CREATE TABLE BooksAppointment
	(ID INTEGER PRIMARY KEY,
	Phone CHAR(10),
    TrainerPhone CHAR(10),
    TrainerID INTEGER,
    Address VARCHAR(60),
    PostalCode CHAR(6),
    ApptDate DATE,
    StartTime CHAR(5),
    EndTime CHAR(5),
    SessionType CHAR(20),
--     PRIMARY KEY(ID, Phone, TrainerID, Address, PostalCode),
    FOREIGN KEY(Phone) REFERENCES Users(Phone) ON DELETE CASCADE,
    FOREIGN KEY(TrainerPhone, TrainerID) REFERENCES Trainers(Phone, TrainerID) ON DELETE CASCADE,
    FOREIGN KEY(Address, PostalCode) REFERENCES Gyms(Address, PostalCode) ON DELETE CASCADE);

-- Create insert triggers that uses a sequence to generate unique ids for tables
-- https://stackoverflow.com/questions/7949343/sqlplus-auto-increment-error
CREATE TRIGGER certification_trigger
    BEFORE INSERT
    ON Certification
    FOR EACH ROW
    BEGIN
        SELECT certification_sequence.nextval INTO :NEW.ID FROM dual;
    END;

/

CREATE TRIGGER exercise_trigger
    BEFORE INSERT
    ON Exercise
    FOR EACH ROW
    BEGIN
        SELECT exercise_sequence.nextval INTO :NEW.ID FROM dual;
    END;

/

CREATE TRIGGER appointment_trigger
    BEFORE INSERT
    ON BooksAppointment
    FOR EACH ROW
    BEGIN
        SELECT appointment_sequence.nextval INTO :NEW.ID FROM dual;
    END;

/

-- Populate table with data

INSERT INTO	Users (Phone, Name, Weight, Height)
VALUES ('7785734567', 'Alice Doe', 134, 178);

INSERT INTO Users (Phone, Name, Weight, Height)
VALUES ('7783334987', 'Bob Woo', 156, 198);

INSERT INTO Users (Phone, Name, Weight, Height)
VALUES ('7786879098', 'Sally Marsh', 156, 198);

INSERT INTO Users (Phone, Name, Weight, Height)
VALUES	('7784758890', 'Gordon Ramsey', 178, 185);

INSERT INTO Users (Phone, Name, Weight, Height)
VALUES	('7781234098', 'Lizzie Smith', 124, 169);

INSERT INTO Users (Phone, Name, Weight, Height)
VALUES	('7786188911', 'Elizabeth Cho', 156, 163);

INSERT INTO Users (Phone, Name, Weight, Height)
VALUES	('7787812030', 'Monica Geller', 156, 179);

INSERT INTO Users (Phone, Name, Weight, Height)
VALUES	('7788912340', 'Rachel Green', 134, 154);

INSERT INTO Users (Phone, Name, Weight, Height)
VALUES	('7787812030', 'Phoebe Buffay', 134, 142);

INSERT INTO Users (Phone, Name, Weight, Height)
VALUES	('7787819150', 'Chandler Bing', 210, 190);

INSERT INTO Users (Phone, Name, Weight, Height)
VALUES	('7780101234', 'Joey Tribbiani', 210, 195);

INSERT INTO Users (Phone, Name, Weight, Height)
VALUES	('7789083344', 'Ross Geller', 210, 182);

INSERT INTO FriendsWith (friend1_Phone, friend2_Phone)
VALUES	('7785734567', '7784758890');

INSERT INTO FriendsWith (friend1_Phone, friend2_Phone)
VALUES	('7784758890', '7785734567');

INSERT INTO FriendsWith (friend1_Phone, friend2_Phone)
VALUES	('7785734567', '7783334987');

INSERT INTO FriendsWith (friend1_Phone, friend2_Phone)
VALUES	('7783334987', '7785734567');

INSERT INTO FriendsWith (friend1_Phone, friend2_Phone)
VALUES	('7785734567', '7781234098');

INSERT INTO FriendsWith (friend1_Phone, friend2_Phone)
VALUES	('7781234098', '7785734567');

INSERT INTO SetsGoals (Phone, Goal_Name, Goal_Quantity, Goal_Progress)
VALUES	('7785734567', 'Go jogging 10km', 10, 11);

INSERT INTO SetsGoals (Phone, Goal_Name, Goal_Quantity, Goal_Progress)
VALUES	('7783334987', 'Go to the gym 10 times', 10, 12);

INSERT INTO	SetsGoals (Phone, Goal_Name, Goal_Quantity, Goal_Progress)
VALUES	('7786879098', 'Bench Press 120lbs', 120, 110);

INSERT INTO SetsGoals (Phone, Goal_Name, Goal_Quantity, Goal_Progress)
VALUES	('7785734567', 'Do 100lbs Barbell Squats', 100, 50);

INSERT INTO	SetsGoals (Phone, Goal_Name, Goal_Quantity, Goal_Progress)
VALUES	('7781234098', 'Go hiking 5 times', 5, 6);

INSERT INTO CaloricBalance (LogDate, Intake, Burned)
VALUES	(TO_DATE('01/01/2023', 'DD/MM/YYYY'), 2500, 1700);

INSERT INTO	CaloricBalance (LogDate, Intake, Burned)
VALUES	(TO_DATE('02/01/2023', 'DD/MM/YYYY'), NULL, NULL);

INSERT INTO	CaloricBalance (LogDate, Intake, Burned)
VALUES	(TO_DATE('03/01/2023', 'DD/MM/YYYY'), 2250, 2400);

INSERT INTO CaloricBalance (LogDate, Intake, Burned)
VALUES	(TO_DATE('14/01/2023', 'DD/MM/YYYY'), 1700, 1300);

INSERT INTO	CaloricBalance (LogDate, Intake, Burned)
VALUES	(TO_DATE('15/01/2023', 'DD/MM/YYYY'), 2500, 1700);

INSERT INTO	Tracks (Phone, LogDate)
VALUES	('7785734567', TO_DATE('01/01/2023', 'DD/MM/YYYY'));

INSERT INTO Tracks (Phone, LogDate)
VALUES	('7785734567', TO_DATE('02/01/2023', 'DD/MM/YYYY'));

INSERT INTO	Tracks (Phone, LogDate)
VALUES	('7784758890', TO_DATE('01/01/2023', 'DD/MM/YYYY'));

INSERT INTO Tracks (Phone, LogDate)
VALUES	('7781234098', TO_DATE('03/01/2023', 'DD/MM/YYYY'));

INSERT INTO Tracks (Phone, LogDate)
VALUES	('7781234098', TO_DATE('15/01/2023', 'DD/MM/YYYY'));

INSERT INTO Trainers (Phone, TrainerID, Specialty)
VALUES	('7786879098', 23458970, 'Strength and Conditioning');

INSERT INTO Trainers (Phone, TrainerID, Specialty)
VALUES	('7785734567', 23458971, 'HIIT');

INSERT INTO Trainers (Phone, TrainerID, Specialty)
VALUES	('7786879098', 23458973, 'Bodybuilding');

INSERT INTO Trainers (Phone, TrainerID, Specialty)
VALUES	('7781234098', 23458972, 'Weight Loss');

INSERT INTO Trainers (Phone, TrainerID, Specialty)
VALUES	('7784758890', 23458978, 'Functional Training');

INSERT INTO Certification (Title, GetDate)
VALUES	('BCRPA Personal Trainer', TO_DATE('12/08/2014', 'DD/MM/YYYY'));

INSERT INTO	Certification (Title, GetDate)
VALUES	('ISSA Certification', TO_DATE('15/08/2022', 'DD/MM/YYYY'));

INSERT INTO Certification (Title, GetDate)
VALUES	('ISSA Certification', TO_DATE('15/08/2022', 'DD/MM/YYYY'));

INSERT INTO Certification (Title, GetDate)
VALUES	('NSCA-Certified Personal Trainer', TO_DATE('04/01/2014', 'DD/MM/YYYY'));

INSERT INTO Certification (Title, GetDate)
VALUES	('ACE Certified Personal Trainer', TO_DATE('17/05/2015', 'DD/MM/YYYY'));

INSERT INTO Has (Phone, TrainerID, Certification_ID)
VALUES	('7786879098', 23458970, 1);

INSERT INTO Has (Phone, TrainerID, Certification_ID)
VALUES	('7785734567', 23458971, 2);

INSERT INTO Has (Phone, TrainerID, Certification_ID)
VALUES	('7786879098', 23458973, 3);

INSERT INTO Has (Phone, TrainerID, Certification_ID)
VALUES	('7781234098', 23458972, 4);

INSERT INTO Has (Phone, TrainerID, Certification_ID)
VALUES	('7784758890', 23458978, 5);

INSERT INTO Workouts (Name, NumExercises)
VALUES	('HIT: Legs and Core', 3);

INSERT INTO Workouts (Name, NumExercises)
VALUES	('Intense Abs', 3);

INSERT INTO	Workouts (Name, NumExercises)
VALUES	('Biceps and Triceps', 3);

INSERT INTO Workouts (Name, NumExercises)
VALUES	('Super Upper Body Strength', 3);

INSERT INTO Workouts (Name, NumExercises)
VALUES	('Endurance and Core Strength', 3);

INSERT INTO Does (Phone, Workouts_Name)
VALUES	('7785734567', 'Super Upper Body Strength');

INSERT INTO	Does (Phone, Workouts_Name)
VALUES	('7783334987', 'Biceps and Triceps');

INSERT INTO	Does (Phone, Workouts_Name)
VALUES	('7786879098', 'Intense Abs');

INSERT INTO	Does (Phone, Workouts_Name)
VALUES	('7784758890', 'Super Upper Body Strength');

INSERT INTO	Does (Phone, Workouts_Name)
VALUES	('7781234098', 'HIT: Legs and Core');

INSERT INTO	Equipment (Name, Instructions)
VALUES	('Yoga Mat', '1. Unroll the yoga mat 2. Position the mat right side up 3. Place hands and feet on the mat');

INSERT INTO	Equipment (Name, Instructions)
VALUES	('DumbBells', '1. Hold one dumbbell in each hand 2. Grip the dumbbell firmly');

INSERT INTO	Equipment (Name, Instructions)
VALUES	('Leg Press Machine', '1. Sit on the machine and place your feet shoulder width apart on the platform 2. Extend your legs without locking them 3. Bring your legs back and repeat');

INSERT INTO	Equipment (Name, Instructions)
VALUES	('Lat Pulldown Machine', '1. Sit on the seat and grip the bar shoulder width apart 2. Pull the bar down to your chest slowly 3. Release the bar back up and repeat');

INSERT INTO	Equipment (Name, Instructions)
VALUES	('Pull-Up Bar', '1. Grip the bar with your arms positions slightly wider than shoulder width apart 2. Pull yourself up until your chin reaches the bar 3. Lower yourself down and repeat');

INSERT INTO	Exercise (Exercise_Name, MuscleGroup, Repetitions, Sets, Equipment_Name)
VALUES	('DumbBell Bench Press', 'Upper Body', 8, 5, 'DumbBells');

INSERT INTO	Exercise (Exercise_Name, MuscleGroup, Repetitions, Sets, Equipment_Name)
VALUES	('DumbBell Bent Over Row', 'Upper Body', 8, 5, 'DumbBells');

INSERT INTO	Exercise (Exercise_Name, MuscleGroup, Repetitions, Sets, Equipment_Name)
VALUES	('Drop Squats', 'Lower Body', 10, 5, 'Yoga Mat');

INSERT INTO	Exercise (Exercise_Name, MuscleGroup, Repetitions, Sets, Equipment_Name)
VALUES	('Mountain Climbers', 'Lower Body', 20, 3, 'Yoga Mat');

INSERT INTO	Exercise (Exercise_Name, MuscleGroup, Repetitions, Sets, Equipment_Name)
VALUES	('Plank Walk', 'Core', 15, 2, 'Yoga Mat');

INSERT INTO	Contains (Workouts_Name, Exercise_ID)
VALUES	('Intense Abs', 4);

INSERT INTO	Contains (Workouts_Name, Exercise_ID)
VALUES	('HIT: Legs and Core', 3);

INSERT INTO	Contains (Workouts_Name, Exercise_ID)
VALUES	('Super Upper Body Strength', 1);

INSERT INTO	Contains (Workouts_Name, Exercise_ID)
VALUES	('Biceps and Triceps', 2);

INSERT INTO	Contains (Workouts_Name, Exercise_ID)
VALUES	('Endurance and Core Strength', 5);

INSERT INTO	Gyms (Address, PostalCode, Name)
VALUES	('2155 Allison Rd, Vancouver, BC', 'V6T1T5', 'Golds Gym University MarketPlace');

INSERT INTO	Gyms (Address, PostalCode, Name)
VALUES	('6138 Student Union Blvd, Vancouver, BC', 'V6T1Z1', 'ARC @ UBC Life Building');

INSERT INTO	Gyms (Address, PostalCode, Name)
VALUES	('6000 Student Union Blvd, Vancouver, BC', 'V6T1T5', 'BirdCoop Fitness Centre');

INSERT INTO	Gyms (Address, PostalCode, Name)
VALUES	('6108 Thunderbird Blvd Unit 1, Vancouver, BC', 'V6T1Z3', 'UBC BodyWorks Fitness Centre');

INSERT INTO	Gyms (Address, PostalCode, Name)
VALUES	('5740 Toronto Rd #205, Vancouver, BC', 'V6T2H7', 'Little Rock Fitness');

INSERT INTO	BooksAppointment (Phone,TrainerPhone, TrainerID, Address, PostalCode, ApptDate, StartTime, EndTime, SessionType)
VALUES	('7785734567','7786879098', 23458970, '5740 Toronto Rd #205, Vancouver, BC', 'V6T2H7', TO_DATE('02/10/2023', 'DD/MM/YYYY'),  '14:00', '15:00', 'Upper Body');

INSERT INTO	BooksAppointment (Phone,TrainerPhone, TrainerID, Address, PostalCode, ApptDate, StartTime, EndTime, SessionType)
VALUES	('7783334987', '7785734567', 23458971, '5740 Toronto Rd #205, Vancouver, BC', 'V6T2H7', TO_DATE('02/10/2023', 'DD/MM/YYYY'), '14:00', '15:00', 'Lower Body');

INSERT INTO	BooksAppointment (Phone, TrainerPhone, TrainerID, Address, PostalCode, ApptDate, StartTime, EndTime, SessionType)
VALUES	('7786879098', '7786879098', 23458973, '5740 Toronto Rd #205, Vancouver, BC', 'V6T2H7', TO_DATE('02/10/2023', 'DD/MM/YYYY'),  '14:00', '15:00', 'Cardio');

INSERT INTO	BooksAppointment (Phone, TrainerPhone, TrainerID, Address, PostalCode, ApptDate, StartTime, EndTime, SessionType)
VALUES	('7784758890', '7785734567', 23458971, '5740 Toronto Rd #205, Vancouver, BC', 'V6T2H7', TO_DATE('02/10/2023', 'DD/MM/YYYY'),  '14:00', '15:00', 'Conditioning');

INSERT INTO	BooksAppointment (Phone, TrainerPhone, TrainerID, Address, PostalCode, ApptDate, StartTime, EndTime, SessionType)
VALUES	('7781234098', '7786879098', 23458970, '5740 Toronto Rd #205, Vancouver, BC', 'V6T2H7', TO_DATE('02/10/2023', 'DD/MM/YYYY'),  '14:00', '15:00', 'Pilates');

