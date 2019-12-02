<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/12/1
 * Time: 15:32
 */
require("db_config.php");
$connectionOptions = array(
    "Database" => $dbname, // update me
    "Uid" => $user, // update me
    "PWD" => $pass // update me
);
$conn = sqlsrv_connect($servername, $connectionOptions);
$deptSql = "create table department
<<<<<<< HEAD
            (dept_name nvarchar (50),
            campus nvarchar (20) not null ,
=======
            (dept_name varchar (50),
            campus varchar (20) not null ,
>>>>>>> fe08ea4a6ada23796eac24eab2dc7cc3df64804c
            primary key (dept_name))";
sqlsrv_query($conn,$deptSql);
$userSql = "create table users
            (username character(4),
            password varchar(10) not null ,
            primary key (username))";
sqlsrv_query($conn,$userSql);
$studentSql = "create table student
              (student_id character(4),
<<<<<<< HEAD
              student_name nvarchar(40) not null ,
              gender varchar(2) not null ,
              dept_name nvarchar(50),
=======
              student_name varchar(40) not null ,
              gender varchar(2) not null ,
              dept_name varchar(50),
>>>>>>> fe08ea4a6ada23796eac24eab2dc7cc3df64804c
              total_credit numeric(4,1) default 0,
              primary key (student_id),
              foreign key (dept_name) references department)";
sqlsrv_query($conn,$studentSql);
$instructorSql = "create table instructor
                  (instructor_id character(4),
<<<<<<< HEAD
                  instructor_name nvarchar(40) not null ,
                  title nvarchar(20),
                  dept_name nvarchar(50),
=======
                  instructor_name varchar(40) not null ,
                  title varchar(20),
                  dept_name varchar(50),
>>>>>>> fe08ea4a6ada23796eac24eab2dc7cc3df64804c
                  primary key (instructor_id),
                  foreign key (dept_name) references department)";
sqlsrv_query($conn,$instructorSql);
$courseSql = "create table course
              (course_id character(4),
<<<<<<< HEAD
              course_name nvarchar(50) not null ,
              dept_name nvarchar(50),
=======
              course_name varchar(50) not null ,
              dept_name varchar(50),
>>>>>>> fe08ea4a6ada23796eac24eab2dc7cc3df64804c
              credit numeric(3,1) not null ,
              primary key (course_id),
              foreign key (dept_name) references department)";
sqlsrv_query($conn,$courseSql);
$classroomSql = "create table classroom
                (room_no varchar(4),
<<<<<<< HEAD
                building nvarchar(20),
=======
                building varchar(20),
>>>>>>> fe08ea4a6ada23796eac24eab2dc7cc3df64804c
                room_capacity numeric(3,0) not null ,
                primary key (room_no,building))";
sqlsrv_query($conn,$classroomSql);
$timeSlotSql = "create table time_slot
                (time_slot_id character(4),
                day varchar(20),
                start_time time,
                end_time time not null ,
                primary key (time_slot_id,day,start_time))";
sqlsrv_query($conn,$timeSlotSql);
$sectionSql = "create table section
               (section_id character(4),
               course_id character(4),
               semester varchar(6),
               year numeric(4,0),
               room_no varchar(4),
<<<<<<< HEAD
               building nvarchar(20),
=======
               building varchar(20),
>>>>>>> fe08ea4a6ada23796eac24eab2dc7cc3df64804c
               time_slot_id character(4),
               take_num numeric(3,0) default 0,
               sec_capacity numeric(3,0) not null ,
               primary key (section_id,course_id,semester,year),
               foreign key (course_id) references course,
               foreign key (room_no,building) references classroom)";
sqlsrv_query($conn,$sectionSql);
$takesSql = "create table takes
             (student_id character(4),
             section_id character(4),
             course_id character(4),
             semester varchar(6),
             year numeric(4,0),
             grade varchar(2),
             primary key (student_id,section_id,course_id,semester,year),
             foreign key (section_id,course_id,semester,year) references section,
             foreign key (student_id) references student)";
sqlsrv_query($conn,$takesSql);
$teachesSql = "create table teaches
              (instructor_id character(4),
              section_id character(4),
              course_id character(4),
              semester varchar(6),
              year numeric(4,0),
              primary key (instructor_id,section_id,course_id,semester,year),
              foreign key (section_id,course_id,semester,year) references section)";
sqlsrv_query($conn,$teachesSql);
$examSql = "create table exam
            (exam_id character(4),
<<<<<<< HEAD
            exam_name nvarchar(8) not null ,
=======
            exam_name varchar(8) not null ,
>>>>>>> fe08ea4a6ada23796eac24eab2dc7cc3df64804c
            section_id character(4),
            course_id character(4),
            semester varchar(6),
            year numeric(4,0),
            time_slot_id character(4),
            room_no varchar(4),
<<<<<<< HEAD
            building nvarchar(20),
=======
            building varchar(20),
>>>>>>> fe08ea4a6ada23796eac24eab2dc7cc3df64804c
            primary key (exam_id),
            foreign key (section_id,course_id,semester,year) references section,
            foreign key (room_no,building) references classroom)";
sqlsrv_query($conn,$examSql);
$takeApplySql = "create table take_apply
                (apply_id varchar(4),
                student_id character(4),
                instructor_id character(4),
                section_id character(4),
                course_id character(4),
                semester varchar(6),
                year numeric(4,0),
<<<<<<< HEAD
                content nvarchar(400),
                apply_time timestamp not null ,
                state nvarchar(8) not null ,
=======
                content varchar(400),
                apply_time timestamp not null ,
                state varchar(8) not null ,
>>>>>>> fe08ea4a6ada23796eac24eab2dc7cc3df64804c
                primary key (apply_id),
                foreign key (student_id) references student,
                foreign key (instructor_id) references instructor,
                foreign key (section_id,course_id,semester,year) references section)";
<<<<<<< HEAD
sqlsrv_query($conn,$takeApplySql);
$dropSecSql = "create table dropSec
        (student_id character(4),
        section_id character(4),
        course_id character(4),
        semester varchar(6),
        year numeric(4,0),
        primary key (student_id,section_id,course_id,semester,year),
        foreign key (section_id,course_id,semester,year) references section)";
sqlsrv_query($conn,$dropSecSql);
=======
sqlsrv_query($conn,$takeApplySql);
>>>>>>> fe08ea4a6ada23796eac24eab2dc7cc3df64804c
