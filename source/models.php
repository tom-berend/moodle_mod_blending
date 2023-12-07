<?php

namespace Blending;


/****************
 * CC BY-NC-SA 4.0
 * Attribution-NonCommercial-ShareAlike 4.0 International
 *
 * This license requires that reusers give credit to the creator. It allows
 * reusers to distribute, remix, adapt, and build upon the material in any
 * medium or format, for noncommercial purposes only. If others modify or
 * adapt the material, they must license the modified material under identical terms.
 *
 * BY: Credit must be given to the Community Reading Project, who created it.
 *
 * NC: Only noncommercial use of this work is permitted.
 *
 *     Noncommercial means not primarily intended for or directed towards commercial
 *     advantage or monetary compensation.
 *
 * SA: Adaptations must be shared under the same terms.
 *
 * see the license deed here:  https://creativecommons.org/licenses/by-nc-sa/4.0
 *
 ******************/



// a teacher or tutor may have several students.  Students are not likely to be Moodle
// users (they can't read), but tutors are.
//
// the student table lists up to 3 trainers, identified by user->email.


class BlendingTable   //  holds the instance this plugin
{
    public $tblName = 'blendingtable';
    public $tblNameSql = '{blending}';

    public function getContent(int $cmid): string
    {
        global $USER, $DB;
        $sql = "SELECT content FROM {$this->tblNameSql} where id = ?";
        $params = [$cmid];
        $result =  $DB->get_record_sql($sql, $params);  // should only be one
        return $result->content;
    }

    public function putContent(string $jsonLessons, int $cmid)
    {
        global $DB;

        $updatedata = [
            'id' => $cmid,
            'content' => $jsonLessons,
        ];
        $DB->update_record($this->tblName, $updatedata);
    }
}




class StudentTable  // describes a single student
{
    public $tblName = 'blendingstudents';
    public $tblNameSql = '{blendingstudents}';

    public function getStudent(int $ID): array
    {

        if ($GLOBALS['isDebugging'])  // running the test suite
            return [['id' => '999', 'name' => 'DebugStudent', 'lesson' => 'Fat Cat Sat', 'lastlesson' => '1234567', 'teacheremail' => 'test@test.com', 'tutor1email' => 'test1@test.com', 'tutor2email' => 'test2@test.com', 'tutor3email' => 'test3@test.com']];


        global $USER, $DB;
        $sql = "SELECT id,name,teacheremail,tutor1email,tutor2email,tutor3email FROM {$this->tblNameSql} where id = ?";
        $params = [$ID];

        $result =  (array) $DB->get_record_sql($sql, $params);  // should only be one
        return ($result);
    }

    // if you have logged in, you may you have a number of students.
    public function getAllStudents(string $email = ''): array
    {

        if ($GLOBALS['isDebugging'])  // running the test suite
            return [['id' => '999', 'name' => 'DebugStudent', 'lesson' => 'Fat Cat Sat', 'lastlesson' => '1234567', 'teacheremail' => 'test@test.com', 'tutor1email' => 'test1@test.com', 'tutor2email' => 'test2@test.com', 'tutor3email' => 'test3@test.com']];

        global $USER, $DB;

        // this can look up anyone's students, but by default it looks up the logged-in user's students
        if (empty($email)) {
            $email = $USER->email;
        }
        //join student and last lesson in log
        $sql = "SELECT a.id, a.name,a.teacheremail,a.tutor1email,a.tutor2email,a.tutor3email,lesson,lastlesson
                FROM  {$this->tblNameSql} a
                LEFT OUTER JOIN (SELECT studentid, lesson, timecreated as 'lastlesson' FROM  {blendingtraininglog} WHERE lesson != ''
                GROUP BY studentid) as b ON a.id = b.studentid
                WHERE a.teacheremail = ? or a.tutor1email = ? or a.tutor2email = ? or a.tutor3email = ? ORDER BY lastlesson desc";

        $params = [$email, $email, $email, $email];  // can be teacher or one of three tutors

        $result = (array)  $DB->get_records_sql($sql, $params);  // limit so only one record per student
        return ($result);
    }


    // add a student for you, you can add other trains later
    public function insertNewStudent(array $form): int      // returns new ID
    {
        if ($GLOBALS['isDebugging'])  // running the test suite
            return 999;

        global $USER, $DB;

        $student = new \stdClass();
        $student->teacheremail = $USER->email;
        $student->name = $form['name'] ?? '';
        $student->tutor1email = $form['tutor1email'] ?? '';
        $student->tutor2email = $form['tutor2email'] ?? '';
        $student->tutor3email = $form['tutor3email'] ?? '';
        $student->timecreated = time();

        $id = $DB->insert_record($this->tblName, $student);
        return $id;
    }

    public function updateStudent(int $studentID, array $form)
    {
        if ($GLOBALS['isDebugging'])  // running the test suite
            return;

        global $USER, $DB;

        $student = new \stdClass();
        $student->id = $studentID;     // update requires an ID
        $student->name = $form['name'] ?? '';
        $student->tutor1email = $form['tutor1email'] ?? '';
        $student->tutor2email = $form['tutor2email'] ?? '';
        $student->tutor3email = $form['tutor3email'] ?? '';

        $DB->update_record($this->tblName, $student);
    }

    public function deleteStudent(int $studentID)
    {
        global $USER, $DB;

        //first delete all records for this student
        $DB->delete_records($this->tblName, ['id' => $studentID]);

        //then insert a record showing who deleted them
        $logTable = new LogTable();
        $logTable->insertLog($studentID, $USER->email, '', '', '', 0, 'Deleted all records for this student');
    }
}




class LogTable  // we use the log to track progress
{
    public $tblName = 'blendingtraininglog';
    public $tblNameSql = '{blendingtraininglog}';


    // add a student for you, you can add other trains later
    public function insertLog(int $studentID, string $action, string $course, string $lesson = '',  string $result = '', string $score = '0', string $remark = '', int $lessonType = 0)
    {
        if ($GLOBALS['isDebugging'])  // running the test suite
            return 0;  // hope this works


        global $USER, $DB;
        $log = new \stdClass();
        $log->studentid = $studentID;
        $log->tutoremail = $USER->email;
        $log->course = $course;
        $log->lesson = $lesson;
        $log->action = $action;
        $log->result = $result;
        $log->score = intval($score);
        $log->remark = $remark;   // 'remark' beause comment is a reserved word
        $log->lessontype = $lessonType;
        $log->timecreated = time();

        $id = $DB->insert_record($this->tblName, $log);
        return $id;
    }


    public function getStudentAll(int $studentID): array  // lessonType is not yet used, separates blending from other types of lessons
    {
        global $USER, $DB;
        $sql = "SELECT *  FROM {$this->tblNameSql} where studentid = ? ORDER BY timecreated DESC";
        $params = [$studentID];

        $result = $DB->get_records_sql($sql, $params);
        return ($result);
    }


    public function getLastMastered(int $studentID, $course): array  // lessonType is not yet used, separates blending from other types of lessons
    {
        if ($GLOBALS['isDebugging'])  // running the test suite
            return [];  // nothing mastered

        global $USER, $DB;
        $sql = "SELECT id,lesson,timecreated  FROM {$this->tblNameSql} where studentid = ? and course = ? and result = ? ORDER BY timecreated DESC";
        $params = [$studentID, $course, 'mastered'];

        $result = $DB->get_records_sql($sql, $params, '', 1);     // limit 1, we only need the last one
        return ($result);
    }

    public function getLessonTries(int $studentID, string $lesson): array  // lessonType is not yet used, separates blending from other types of lessons
    {
        global $USER, $DB;
        $sql = "SELECT id,lesson,action,result,score, remark,timecreated  FROM {$this->tblNameSql} where studentid = ? and lesson = ? ORDER BY timecreated";
        $params = [$studentID, $lesson];

        $result = $DB->get_records_sql($sql, $params);     // limit 1, we only need the last one
        return ($result);
    }

    public function getAllMastered(int $studentID): array  // lessonType is not yet used, separates blending from other types of lessons
    {
        // MAY HAVE DUPLICATES.   can add 'group by lesson' to the query if you want only one record per mastered

        global $USER, $DB;
        $sql = "SELECT id, lesson FROM {$this->tblNameSql} where studentid = ? and result = ?";
        $params = [$studentID, 'mastered'];

        $result =  (array) $DB->get_records_sql($sql, $params);
        return ($result);
    }


    public function deleteStudent(int $studentID)
    {
        global $USER, $DB;

        //first delete all records for this student
        $DB->delete_records($this->tblName, ['studentid' => $studentID]);

        //then insert a record showing who deleted them
        $this->insertLog($studentID, $USER->email, '', '', '', 0, 'Deleted all records for this student');
    }
}



