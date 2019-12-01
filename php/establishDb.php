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
            (dept_name varchar (50),
            campus varchar (20) not null ,
            primary key (dept_name))";
sqlsrv_query($conn,$deptSql);
$userSql = "create table users
            (username character(4),
            password varchar(10) not null ,
            primary key (username))";
sqlsrv_query($conn,$userSql);
$studentSql = "create table student
              (student_id character(4),
              student_name varchar(40) not null ,
              gender varchar(2) not null ,
              dept_name varchar(50),
              total_credit numeric(4,1) default 0,
              primary key (student_id),
              foreign key (dept_name) references department)";
sqlsrv_query($conn,$studentSql);
$instructorSql = "create table instructor
                  (instructor_id character(4),
                  instructor_name varchar(40) not null ,
                  title varchar(20),
                  dept_name varchar(50),
                  primary key (instructor_id),
                  foreign key (dept_name) references department)";
sqlsrv_query($conn,$instructorSql);
$courseSql = "create table course
              (course_id character(4),
              course_name varchar(50) not null ,
              dept_name varchar(50),
              credit numeric(3,1) not null ,
              primary key (course_id),
              foreign key (dept_name) references department)";
sqlsrv_query($conn,$courseSql);
$classroomSql = "create table classroom
                (room_no varchar(4),
                building varchar(20),
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
               building varchar(20),
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
            exam_name varchar(8) not null ,
            section_id character(4),
            course_id character(4),
            semester varchar(6),
            year numeric(4,0),
            time_slot_id character(4),
            room_no varchar(4),
            building varchar(20),
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
                content varchar(400),
                apply_time timestamp not null ,
                state varchar(8) not null ,
                primary key (apply_id),
                foreign key (student_id) references student,
                foreign key (instructor_id) references instructor,
                foreign key (section_id,course_id,semester,year) references section)";
sqlsrv_query($conn,$takeApplySql);