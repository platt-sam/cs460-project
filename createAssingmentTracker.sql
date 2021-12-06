use f21_assignmentTracker;
/******************************************************************************************
*	CS460 Fall 2021
* 	Written by: Peter Jacobson
*	Purpose: Create and populate Task and Course Tables for Assingment Tracker
******************************************************************************************/
SET FOREIGN_KEY_CHECKS = 0; 
-- DROP TABLE IF EXISTS course;
-- DROP TABLE IF EXISTS task;
-- DROP TABLE IF EXISTS grade_change_log;
-- -----------------------------------------
-- Task
-- ----------------------------------------- 
/*
CREATE TABLE task(
	student_id int NOT NULL,
	assignment_id int NOT NULL,
    status enum("Not Started", "In-Progress", "Completed"),
    dateStarted date, -- Changed from dateTime in dictionary
    dateCompleted date, -- Changed from dateTime in dictionary
	PRIMARY KEY (student_id, assignment_id),
	FOREIGN KEY (student_id)
		REFERENCES section (section_id),
	FOREIGN KEY (assignment_id)
		REFERENCES assignment (assignment_id)
);
*/

-- -----------------------------------------
-- Course
-- -----------------------------------------
/*
CREATE TABLE course(
	course_id int NOT NULL PRIMARY KEY,
    course_title VARCHAR(30) NOT NULL,
    course_description VARCHAR(120)
);
*/

-- -----------------------------------------
-- Grade Change Log (For roster_entry trigger)
-- -----------------------------------------
/*
CREATE TABLE grade_change_log(
	primary_id int NOT NULL AUTO_INCREMENT,
	sec_id int NOT NULL,
    stu_id int NOT NULL,
    original_grade enum('A','B','C','D','F'),
    new_grade enum('A','B','C','D','F'),
    update_user VARCHAR(30),
	time_updated TIMESTAMP, 
    PRIMARY KEY(primary_id),
	FOREIGN KEY (stu_id) 
		REFERENCES roster_entry (student_id),
	FOREIGN KEY (sec_id) 
		REFERENCES roster_entry (section_id)   
);
*/

-- Populate course table 
INSERT INTO course (course_id, course_title, course_description) VALUES (4600, "Advanced Databases", NULL);
INSERT INTO course (course_id, course_title, course_description) VALUES (3140, "Computer Organization", "More than putting computers on shelf");
INSERT INTO course (course_id, course_title, course_description) VALUES (1000, "The Joy of Painting", "Art therapy in lithia park with a person we found walking around");
INSERT INTO course (course_id, course_title, course_description) VALUES (3174, "Physics", NULL);
INSERT INTO course (course_id, course_title, course_description) VALUES (2034, "News Reporting", "We get right into the news.");

-- Populate task table
INSERT INTO task (student_id, assignment_id, status, dateStarted, dateCompleted) VALUES (94012345, 2101, "Completed", '2021-10-10', '10-11-21');
INSERT INTO task (student_id, assignment_id, status, dateStarted, dateCompleted) VALUES (94012345, 2105, "In-Progress", '2021-10-10', NULL);
INSERT INTO task (student_id, assignment_id, status, dateStarted, dateCompleted) VALUES (94039013, 2319, "Not Started", NULL, NULL);
INSERT INTO task (student_id, assignment_id, status, dateStarted, dateCompleted) VALUES (94668723, 2105, "Not Started", NULL, NULL);
INSERT INTO task (student_id, assignment_id, status, dateStarted, dateCompleted) VALUES (94206900, 2331, "In-Progress", '2021-11-31', NULL);

SET FOREIGN_KEY_CHECKS = 1; 

--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Sam Code Here


--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Nayeli Code Here


--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Ronin Code Here
