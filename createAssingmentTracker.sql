use f21_assignmentTracker;
/******************************************************************************************
*	CS460 Fall 2021
* 	Written by: Nayeli Equeda Alvarado, Peter Jacobson, Ronin Ganoot, Sam Platt
*	Purpose: SQL source code for 'Assingment Tracker', see implementation here: https://cs460.sou.edu/~platts1/f21_assignmentTracker/
*	Organization: The code below is organized first by table creation, then realtionships (if not implemented in table), then
*			populating tables. After that views, functions, procedures, and triggers along with any tests and comments
*			are organized under their respective developer. 
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
----------------------------- Peter's Implementation Below -----------------------------

-- Students in Course View --
	-- Description: Shows all students by name and ID that are taking a course regardless of what section they are enrolled in
	DROP VIEW students_in_course;

	CREATE VIEW students_in_course AS 
		SELECT student_id, student_first, student_last
		FROM student
		WHERE student_id IN (SELECT student_id FROM roster_entry WHERE section_id IN (SELECT section_id FROM section WHERE course_id = 1000)); 
        
	-- Testing -> Complete, Good test
		SELECT * FROM roster_entry; -- For cross reference
        SELECT * FROM section; -- For cross reference
        SELECT * FROM course; -- For cross reference
		SELECT * FROM students_in_course; -- Look if the view includes students assocaited with the section in roster_entry
        


-- Student Class Standing Function --
	-- Input: student_id
	-- Output: Student's class standing
	-- Description: Output a students class standing based on their total credits earned. If a student has 0-44 freshman, 45-89 sophomore, 90-134 junior, and 135+ senior standing. 
	
    -- See class_standing function below for implementation
    CREATE FUNCTION `class_standing`(p_student_id INT) RETURNS enum('Freshman','Sophmore','Junior','Senior') CHARSET latin1
    READS SQL DATA
	BEGIN
	-- Student Class Standing Function --
		-- Input: student_id
		-- Output: Student's class standing
		-- Description: Output a students class standing based on their total credits earned. 
		-- 				If a student has 0-44 freshman, 45-89 sophomore, 90-134 junior, and 135+ senior standing. 
	
	-- Declare Variables
    DECLARE l_class_standing enum('Freshman', 'Sophmore', 'Junior', 'Senior'); -- Holds class standing to be returned
    DECLARE l_credits int; -- Holds selected students credits
    
    -- Logic to get student class standing
	SELECT student_credits INTO l_credits FROM student WHERE student_id = p_student_id;
    
    IF l_credits <= 44 THEN
		SET l_class_standing = 'Freshman';
	ELSE IF l_credits <= 89 THEN
		SET l_class_standing = 'Sophmore';
	ELSE IF l_credits <= 134 THEN
		SET l_class_standing = 'Junior';
	ELSE
		SET l_class_standing = 'SENIOR';
	END IF; END IF; END IF; -- Not pretty but fixes a syntax error
		
    -- Return class standing
	RETURN l_class_standing;
	END
    
      
    -- Testing -> Complete, Good test
	SELECT * FROM student; -- For cross reference
    SELECT class_standing(94012345); -- expcecting Junior
    SELECT class_standing(94024689); -- expcecting Freshman
    SELECT class_standing(94206900); -- expcecting Senior
    SELECT class_standing(94668723); -- expcecting Senior



-- Student Standing by Course Prodecure --
	-- Parameters: course_id
	-- Description: Given a couse_id the procedure will output the number of students with each class standing.
		
	-- See student_standings_by_course below for implementation
	CREATE PROCEDURE `student_standing_by_course`(p_course_id INT)
	BEGIN
	-- Student Standing by Course Prodecure --
		-- Parameters: course_id
		-- Description: Given a couse_id the procedure will output the number of students with each class standing.
	
    -- Declare Variables
	DECLARE l_class_standing enum('Freshman','Sophmore','Junior','Senior'); -- Holds student class standing
    DECLARE l_student_id int; -- Holds student_id
	DECLARE l_last_row INT DEFAULT 0; -- Holds row num, needed for cursor continue handler
    
    DECLARE c_student_id CURSOR FOR SELECT student_id FROM roster_entry WHERE section_id IN (SELECT section_id FROM section WHERE course_id = p_course_id); -- Declare the needed cursor
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET l_last_row = 1;
        
    -- Make a temporary table to hold class standings
	DROP TABLE IF EXISTS course_standing;
    CREATE TEMPORARY TABLE IF NOT EXISTS course_standing(standing enum('Freshman','Sophmore','Junior','Senior'));
   
   /*
    -- Logic to get number of each standing by course
	SELECT student_id INTO l_student_id FROM roster_entry WHERE section_id IN (SELECT section_id FROM section WHERE course_id = p_course_id);
    SET l_class_standing = class_standing(l_student_id);
	*/
	
    -- Loop through the cursors to populate the temporary table with class standings
    OPEN c_student_id;
    emp_cursor: LOOP
			FETCH c_student_id INTO l_student_id;
				-- Continue logic
				IF l_last_row = 1 THEN
					LEAVE emp_cursor;
				END IF;
                -- Get class standing and put it into temporary table
			    SET l_class_standing = class_standing(l_student_id);
                INSERT INTO course_standing(standing) VALUES (l_class_standing);
		END LOOP emp_cursor;
	CLOSE c_student_id;
	SET l_last_row = 0;
    
    -- Get and print the count of each class standing
    SELECT standing AS 'Class Standing', COUNT(standing) AS 'Count' FROM course_standing GROUP BY standing;
	END

    
    -- Testing -> Complete, Good Test
    SELECT * FROM section; -- For cross reference
    SELECT * FROM roster_entry; -- For cross reference
    SELECT * FROM student; -- For cross reference 
    
    CALL student_standing_by_course(1000); -- Expecting 4 Seniors, 1 Junior
    
    
    
    
-- Section Status Trigger -- 
	-- Description: When a grade is updated in the roster table the instructor_id, dateTime, and change will be logged into a rosterLog table.

	-- Run a grade update to test for implementation
    	CREATE DEFINER=`jacobsonp`@`%` TRIGGER `grade_change_AFTER_UPDATE` AFTER UPDATE ON `roster_entry` FOR EACH ROW
	BEGIN
	-- Section Status Trigger -- 
		-- Description: When a grade is updated in the roster table the instructor_id, dateTime, and change will be logged into a rosterLog table.
   	INSERT INTO grade_change_log(primary_id,sec_id, stu_id, original_grade, new_grade, update_user, time_updated)
		VALUES(NULL,NEW.section_id,NEW.student_id,OLD.grade,NEW.grade,USER(),CURRENT_TIMESTAMP);

	END
    
    
    -- Testing -> In-Progress, Failed (Unknown column 'section_id' in 'field list'; The trigger is unable to identify fields from the attached table
    SELECT * FROM roster_entry; -- For Cross Reference
	UPDATE roster_entry SET grade = 'A' WHERE section_id = 3201 AND student_id = 94012345; 
	SELECT * FROM roster_entry;
    SELECT * FROM grade_change_log;


--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
----------------------------- Sam's Implementation Below -----------------------------



--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
----------------------------- Nayeli's Implementation Below -----------------------------



--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
----------------------------- Ronins's Implementation Below -----------------------------
-- INSTRUCTOR TABLE
CREATE TABLE instructor (
	instructor_id INTEGER NOT NULL,
    instructor_first VARCHAR(30) NOT NULL,
	instructor_last VARCHAR(30) NOT NULL,
	instructor_title ENUM ('Doctor', 'Associate Professor', 'Adjunct Instructor', 'Professor'),
    instructor_dob DATE NOT NULL,
    instructor_tenure BOOLEAN,
    PRIMARY KEY (instructor_id)
);

-- create a table to log all changes to assignments
-- DROP TABLE IF EXISTS assignment_updates;
CREATE TABLE assignment_updates (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
		assignment_id INT(11) NOT NULL,
        old_assignment_name varchar (30), -- holds old assignment
		new_assignment_name varchar (30), -- holds new assignment
        date_changed date,
        changed_by varchar(50),
        PRIMARY KEY (id)
);

-- RELATIONSHIPS
-- ADDING FKS TO 

-- TASK
ALTER TABLE task
	ADD CONSTRAINT assignment_fk
    FOREIGN KEY (assignment_id)
    REFERENCES assignment (assignment_id);

ALTER TABLE task
	ADD CONSTRAINT student_fk
    FOREIGN KEY (student_id)
    REFERENCES student (student_id);

-- ASSIGNMENT
ALTER TABLE assignment
	ADD CONSTRAINT section_fk
    FOREIGN KEY (section_id)
    REFERENCES section (section_id);

-- SECTION
ALTER TABLE section
	ADD CONSTRAINT course_fk
    FOREIGN KEY (course_id) 
    REFERENCES course (course_id);
    
ALTER TABLE section
	ADD CONSTRAINT instructor_fk
	FOREIGN KEY (instructor_id) 
    REFERENCES instructor (instructor_id);

-- ROSTER ENTRY
ALTER TABLE roster_entry
	ADD CONSTRAINT section_fk
    FOREIGN KEY (section_id)
    REFERENCES section (section_id);

ALTER TABLE roster_entry
	ADD CONSTRAINT student_fk
    FOREIGN KEY (student_id)
    REFERENCES student (student_id);

SET FOREIGN_KEY_CHECKS = 0;

-- DATA
INSERT INTO instructor (instructor_id, instructor_first, instructor_last, instructor_title, instructor_dob, instructor_tenure)
VALUES ('94042474', 'Bob', 'Ross', 'Doctor', '1976-05-20', TRUE);
INSERT INTO instructor (instructor_id, instructor_first, instructor_last, instructor_title, instructor_dob, instructor_tenure)
VALUES ('97382675', 'Jimmy', 'Neutron', 'Doctor', '1975-06-03', FALSE);
INSERT INTO instructor (instructor_id, instructor_first, instructor_last, instructor_title, instructor_dob, instructor_tenure)
VALUES ('94064189', 'Ba', 'lake', 'Associate Professor', '1977-10-06', FALSE);
INSERT INTO instructor (instructor_id, instructor_first, instructor_last, instructor_title, instructor_dob, instructor_tenure)
VALUES ('92356783', 'Mary', 'Jane', 'Adjunct Instructor', '1978-01-02', FALSE);
INSERT INTO instructor (instructor_id, instructor_first, instructor_last, instructor_title, instructor_dob, instructor_tenure)
VALUES ('93786240', 'Naruto', 'Uzumkai', 'Professor', '1980-10-10', TRUE);

-- Implementing View
CREATE VIEW v_enrolled_students
AS SELECT section_id, count(distinct student_id) FROM roster_entry GROUP BY (section_id);

-- Testing the view
SELECT * FROM v_enrolled_students WHERE section_id = 3201;

-- Testing to see what my procedure would look like
-- to see how many assignments are associated with each course
SELECT course_title, assignment_id, assignment_name, status, dateStarted, dateCompleted FROM assignment
NATURAL JOIN task, course where assignment_id = 2101 and course_id = 4600;

-- Creating procedure
-- Start
CREATE PROCEDURE `Assignment_to_course_status`(p_assignment_id INT,
p_course_id INT)
BEGIN

SELECT DISTINCT course_title, assignment_id, assignment_name, status, dateStarted, dateCompleted FROM assignment
NATURAL JOIN task, course where assignment_id = p_assignment_id and course_id = p_course_id;

END
-- End of procedure

-- call procedure
call Assignment_to_course_status (2101, 4600);


-- Testing to see what my function would look like
-- to see if it counts the number of assignments
SELECT count(assignment_id) AS 'Number of assignments' FROM assignment
NATURAL JOIN section WHERE section_id = 3398;

-- Creating Function
-- Start
CREATE FUNCTION `count_Assignments`(p_section_id INT) RETURNS int(11)
    READS SQL DATA
BEGIN
-- holds number of assignments
DECLARE num_of_assignments INT (11);

SELECT count(assignment_id) AS 'Number of assignments' INTO  num_of_assignments FROM
assignment
NATURAL JOIN section WHERE section_id = p_section_id;

RETURN num_of_assignments;
END
-- End of Function

-- Testing Function
SELECT count_Assignments (3398);

-- Creating the Trigger
-- Start
CREATE TRIGGER `f21_assignmentTracker`.`assignment_AFTER_UPDATE` AFTER UPDATE ON `assignment` FOR EACH ROW
BEGIN
IF NEW.assignment_name != OLD.assignment_name THEN
			INSERT INTO assignment_updates
	VALUES (NULL, NEW.assignment_id, OLD.assignment_name, NEW.assignment_name, sysdate(), current_user());
    END IF;
END
-- End

-- Testing Trigger
-- To see updates on their assignment based upon assignment id
SELECT * FROM assignment_updates;
UPDATE assignment SET assignment_name = 'Assignment 14'
WHERE assignment_id = 2331;
SELECT * FROM assignment_updates;
rollback;
