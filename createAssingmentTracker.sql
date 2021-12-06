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


