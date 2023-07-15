<?php


// a teacher or tutor may have several students.  Students are not likely to be Moodle
// users (they can't read), but trainers are.
//
// the student table lists up to 3 trainers, identified by user->email.



class StudentTable  // describes a single student
{
    public $tblName = 'blendingstudents';
    public $tblNameSql = '{blendingstudents}';

    public function getStudent(int $ID): array
    {
        global $USER, $DB;
        $sql = "SELECT id,name,teacheremail,tutoremail1,tutoremail2,tutoremail3 FROM {$this->tblNameSql} where id = ?";
        $params = [$ID];

        $result =  (array) $DB->get_record_sql($sql, $params);  // should only be one
        return ($result);
    }

    // if you have logged in, you may you have a number of students.
    public function getAllStudents(string $email = ''): array
    {
        global $USER, $DB;

        // this can look up anyone's students, but by default it looks up the logged-in user's students
        if (empty($email)) {
            $email = $USER->email;
        }
        //join student and last lesson in log
        $sql = "SELECT a.id, a.name,a.teacheremail,a.tutoremail1,a.tutoremail2,a.tutoremail3,lesson,lastlesson
                FROM  {$this->tblNameSql} a
                LEFT OUTER JOIN (SELECT studentid, lesson, timecreated as 'lastlesson' FROM  {blendingtraininglog} WHERE lesson != ''
                GROUP BY studentid) as b ON a.id = b.studentid
                WHERE a.teacheremail = ? or a.tutoremail1 = ? or a.tutoremail2 = ? or a.tutoremail3 = ? ORDER BY lastlesson desc";

        $params = [$email, $email, $email, $email];  // can be teacher or one of three tutors

        $result = (array)  $DB->get_records_sql($sql, $params);  // limit so only one record per student
        // printNice($result);
        return ($result);
    }

    // add a student for you, you can add other trains later
    public function insertNewStudent(array $form): int      // returns new ID
    {
        printNice($form, 'inserting Student');
        global $USER, $DB;

        $student = new stdClass();
        $student->teacheremail = $USER->email;
        $student->name = $form['name'] ?? '';
        $student->tutoremail1 = $form['tutoremail1'] ?? '';
        $student->tutoremail2 = $form['tutoremail2'] ?? '';
        $student->tutoremail3 = $form['tutoremail3'] ?? '';
        $student->timecreated = time();

        $id = $DB->insert_record($this->tblName, $student);
        return $id;
    }

    public function updateStudent(int $studentID, array $form)
    {
        global $USER, $DB;

        $student = new stdClass();
        $student->id = $studentID;     // update requires an ID
        $student->name = $form['name'] ?? '';
        $student->tutoremail1 = $form['tutoremail1'] ?? '';
        $student->tutoremail2 = $form['tutoremail2'] ?? '';
        $student->tutoremail3 = $form['tutoremail3'] ?? '';

        $DB->update_record($this->tblName, $student);
    }
}




class LogTable  // we use the log to track progress
{
    public $tblName = 'blendingtraininglog';
    public $tblNameSql = '{blendingtraininglog}';


    // add a student for you, you can add other trains later
    public function insertLog(int $studentID, string $action, string $lesson='',  string $result = '', int $score = 0, string $remark = '', int $lessonType = 0)
    {
        global $USER, $DB;
        $log = new stdClass();
        $log->studentid = $studentID;
        $log->tutoremail = $USER->email;
        $log->course = 1;   // until further notice
        $log->lesson = $lesson;
        $log->action = $action;
        $log->result = $result;
        $log->score = $score;
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


    public function getLastMastered(int $studentID): array  // lessonType is not yet used, separates blending from other types of lessons
    {
        global $USER, $DB;
        $sql = "SELECT id,lesson,timecreated  FROM {$this->tblNameSql} where studentid = ? and result = ? ORDER BY timecreated DESC";
        $params = [$studentID,'mastered'];

        $result = $DB->get_records_sql($sql, $params, '', 1);     // limit 1, we only need the last one
        printNice($result,$sql);
        return ($result);
    }

    public function getLessonTries(int $studentID, string $lesson): array  // lessonType is not yet used, separates blending from other types of lessons
    {
        global $USER, $DB;
        $sql = "SELECT id,lesson,action,result,score, remark,timecreated  FROM {$this->tblNameSql} where studentid = ? and lesson = ? ORDER BY timecreated";
        $params = [$studentID,$lesson];

        $result = $DB->get_records_sql($sql, $params);     // limit 1, we only need the last one
        return ($result);
    }

    public function getAllMastered(int $studentID): array  // lessonType is not yet used, separates blending from other types of lessons
    {
        // MAY HAVE DUPLICATES.   can add 'group by lesson' to the query if you want only one record per mastered

        global $USER, $DB;
        $sql = "SELECT id, lesson FROM {$this->tblNameSql} where studentid = ? and result = ?";
        $params = [$studentID,'mastered'];

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



/*
    public function GetAllStudentsGlobal($order = "lastupdate DESC")
    {

        // all students, sorted by parameter
        //        $result = $this->getAllCargoByWhere('',$order);
        //        return($result);

        $query = "select t1.studentid,
                          t1.StudentName,
                          t1.trainer1,
                          t1.lastupdate,
                          t1.cargo,
                          t2.uuid,
                          t2.EMail,
                          t2.nextdate from `{$this->tableName}` t1 left outer join `abc_CRM` t2 on (t1.trainer1 = t2.EMail)
                          order by t1.lastupdate DESC";

        $resultSet = $this->query($query);
        if (!is_array($resultSet) /*or !isset($resultSet['cargo'])) {
            return (array());
        }
        // special case of no results, we always return an array

        // before returning, we need to unserialize every cargo element back into an array
        $simpleArray = array();
        foreach ($resultSet as $result) {
            $cargo = unserialize($result['cargo']);
            $simpleArray[] = array_merge($cargo, array('uuid' => $result['uuid']),
                array('EMail' => $result['EMail']),
                array('NextDate' => $result['nextdate']),
                array('StudentName' => $cargo['enrollForm']['StudentName']));
        }
        // printNice('xxxx', $simpleArray);
        return ($simpleArray);

    }

    // this plugs in the current user as Trainer1
    // would be safer if this were uncommented
    //function drop(){assertTRUE(false,__METHOD__." is not supported.");}

    public function getAllStudentsByProject($project)
    {

        $where = sprintf("project=%s", $this->quote_smart($project));
        return ($this->getAllCargoByWhere($where));
    }

}

// holds 'event' stuff in cargo, like DisfiguredWriting or tests
class StudentEventTable extends AbstractData implements BasicTableFunctions
{

    // Hold a singleton instance of the class
    private static $instance;

    public function __construct()
    { // private, so can't instantiate from outside class
        parent::__construct();
        $this->tableName = $GLOBALS['dbPrefix'] . '_StudentEvent';
        $this->uuidPrefix = 'E';
        $this->primaryKey = 'uuid';
        $this->secondaryKeys = array('trainerID', 'studentID');
    }

    // The singleton method
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function create()
    {
        $createString =
            "CREATE TABLE IF NOT EXISTS `{$this->tableName}` (
              `uuid`      	        varchar(16)  NOT NULL,
              `trainerID`  	        varchar(64)  NOT NULL,
              `studentID`  	        varchar(32)  NOT NULL,
              `cargo` 	                text,
              `project`                 varchar(32),
              `created`                 int(10) unsigned default 0,
              `createdhuman`            varchar(32),
              `lastupdate`              int(10) unsigned default 0,
              `lastbackup`              int(10) unsigned default 0,
              PRIMARY KEY  (`{$this->primaryKey}`)
            ) DEFAULT CHARSET=utf8;";
        return (assertTrue($this->createTable($createString), 'StudentEvent table is being created'));
    }

    // if you have logged in as a parent or psychologist, you have a number of students.
    public function GetAllEventsbyTeacher($trainerID)
    {

        $query = sprintf("SELECT cargo
                                 FROM $this->tableName
                                 WHERE trainerID=%s
                                 ORDER BY created desc",
            $this->quote_string($trainerID)); // avoids SQL injection attacks
        $result = $this->query($query);
        $unpack = array();
        foreach ($result as $single) {
            $unpack[] = unserialize($single['cargo']);
        }
        return ($unpack);
    }

    // would be safer if this were uncommented
    //function drop(){assertTRUE(false,__METHOD__." is not supported.");}
}

// TrainingLog holds details of all training sessions
class TrainingLog extends AbstractData implements BasicTableFunctions
{

    // Hold a singleton instance of the class
    private static $instance;

    public function __construct()
    { // private, so can't instantiate from outside class
        parent::__construct();
        $this->tableName = $GLOBALS['dbPrefix'] . '_TrainingLog';
        $this->uuidPrefix = 'T';
        $this->primaryKey = 'uuid';
        $this->secondaryKeys = array('created', 'studentID', 'action', 'project', 'trainerID', 'rule', 'result'); // so can sort by date
    }

    // The singleton method
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function create()
    {
        $createString =
            "CREATE TABLE IF NOT EXISTS `{$this->tableName}` (
              `sessionID`  	        varchar(16)  NOT NULL,
              `trainerID`  	        varchar(64)  NOT NULL,
              `studentID`  	        varchar(32)  NOT NULL,
              `action`  	        varchar(32)  NOT NULL,
              `project`                 varchar(32),
              `rule`                    varchar(64),
              `result`                  varchar(64),
              `comment`                 varchar(256),
              `JoomlaName`              varchar(32),
              `remoteAddr`              varchar(32),
              `created`                 int(10) unsigned default 0,
              `createdhuman`            varchar(32),
              `lastupdate`              int(10) unsigned default 0,
              `lastbackup`              int(10) unsigned default 0
            ) DEFAULT CHARSET=utf8;";
        return (assertTrue($this->createTable($createString), $this->tableName . ' table is being created'));
    }

    public function insert($cargo, $key = '')
    {
        assertTRUE(false, "Use insertLOG() instead of insert() for the log file");
    }

    // we use the TrainingSession singleton to get trainerID, studentID, and project
    public function insertLog($action, $rule = '', $result = '', $comment = '')
    {

        $identity = identity::singleton();

        $aArray = array();
        $aArray['sessionID'] = $identity->sessionID();
        $aArray['trainerID'] = $identity->userName();
        $aArray['studentID'] = $identity->studentID();
        $aArray['JoomlaName'] = $identity->name();
        $aArray['project'] = $identity->project();
        $aArray['created'] = time();
        $aArray['createdhuman'] = date($GLOBALS['dateFormat']);
        $aArray['action'] = $action;
        $aArray['rule'] = $rule;
        $aArray['result'] = $result;
        $aArray['comment'] = $comment;
        $aArray['remoteAddr'] = iif(isset($_SERVER['REMOTE_ADDR']), $_SERVER['REMOTE_ADDR']);

        $this->insertArray($aArray);
    }

    public function getHistoryByStudent($studentID)
    {

        $query = sprintf("select trainerID,action,rule,createdhuman,project,sessionID from $this->tableName where studentID=%s order by created",
            $this->quote_smart($studentID));
        $resultSet = $this->query($query);
        return ($resultSet);
    }

    public function getRecentHistory($userOnly = '')
    {

        $limit = '';
        if (!empty($userOnly)) {
            $limit = sprintf("where trainerID = %s ",
                $this->quote_smart($userOnly));
        }

        $query = "select *, count(sessionid) as count from {$this->tableName} $limit group by sessionid order by created desc limit 100";

        return ($this->query($query));
    }

    public function getTrainingSession($sessionID)
    {

        $query = "select * from {$this->tableName} where sessionid = '$sessionID' order by created desc";

        return ($this->query($query));
    }

    public function getAssessment($studentID, $program = 'Assessment')
    {

        // have to break the next stmt into two because the % in the like clause confuses sprintf
        $query = sprintf("select * from {$this->tableName} where studentID=%s ",
            $this->quote_smart($studentID)) . "and rule like '{$program}%' order by created";
        return ($this->query($query));
    }

}
// SystemLog holds error messages and exceptions
class SystemLog extends AbstractData implements BasicTableFunctions
{

    // Hold a singleton instance of the class
    private static $instance;

    // Prevent logging events if we are already writing one (usually DB errors)
    public $inSystemLog;

    public function __construct()
    { // private, so can't instantiate from outside class
        parent::__construct();
        $this->tableName = $GLOBALS['dbPrefix'] . '_SystemLog';
        $this->uuidPrefix = 'L';
        $this->primaryKey = 'uuid';
        $this->secondaryKeys = array('joomlaName', 'created', 'action'); // so can sort by date
        $this->inSystemLog = false;
    }

    // The singleton method
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function create()
    {
        $createString =
            "CREATE TABLE IF NOT EXISTS `{$this->tableName}` (
              `uuid`        	        varchar(16)  NOT NULL,
              `joomlaName`  	        varchar(64)  NOT NULL,
              `action`  	            varchar(32)  NOT NULL,
              `project`                 varchar(32),
              `cargo` 	                text,
              `created`                 int(10) unsigned default 0,
              `createdhuman`            varchar(32),
              `lastupdate`              int(10) unsigned default 0,
              `lastbackup`              int(10) unsigned default 0,
              PRIMARY KEY  (`{$this->primaryKey}`)
            ) DEFAULT CHARSET=utf8;";
        return (assertTrue($this->createTable($createString), $this->tableName . ' table is being created'));
    }

    public function write($action, $comment)
    {

        $identity = identity::singleton();

        $aArray = array();
        $aArray['uuid'] = $this->uuid();
        if ($identity->isValidUser()) {
            $aArray['JoomlaName'] = $identity->userName();
            $aArray['project'] = $identity->project();
        } else {
            $aArray['JoomlaName'] = 'Not logged in';
            $aArray['project'] = '';
        }
        $aArray['created'] = time();
        $aArray['createdhuman'] = date($GLOBALS['dateFormat']);
        $aArray['action'] = $action;
        $aArray['cargo'] = $comment;

        if ($this->inSystemLog == true) {
            //Recursive call to SystemLog while logging another message (usually a DB error)
            return;
        } else {
            $this->inSystemLog = true; // now if a logging event happens, we
            $this->insertArray($aArray); //      don't write a second time
        }

//        printNice('Database',"<hr />$action<br>$comment");

        $this->inSystemLog = false;
    }

    public function getLast20()
    {
        $ret = $this->statement("select * from {$this->tableName} order by created desc LIMIT 20");
        $return = array();
        foreach ($ret as $element) {
            $return[] = "<b>{$element['action']}  <i>{$element['joomlaName']}</i>  {$element['createdhuman']}</b><br/>" .
            urldecode(htmlspecialchars_decode(html_entity_decode(html_entity_decode($element['cargo']))));
        }

        return ($return);
    }

}

// LessonResults holds one record per studentID/lesson, with an array in the cargo
class LessonResults extends AbstractData implements BasicTableFunctions
{

    // Hold a singleton instance of the class
    private static $instance;

    public function __construct()
    { // private, so can't instantiate from outside class
        parent::__construct();
        $this->tableName = $GLOBALS['dbPrefix'] . '_LessonResults';
        $this->uuidPrefix = 'R';
        $this->primaryKey = 'studentLesson';
        $this->secondaryKeys = array('studentID', 'lessonKey', 'studentID');
        $this->inSystemLog = false;
    }

    // The singleton method
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function create()
    {
        $createString =
            "CREATE TABLE IF NOT EXISTS `{$this->tableName}` (
              `lessonKey`      	        varchar(64)  NOT NULL,
              `studentID`  	            varchar(32)  NOT NULL,
              `studentLesson`  	        varchar(96)  NOT NULL,
              `project`                 varchar(32),
              `cargo` 	                text,
              `created`                 int(10) unsigned default 0,
              `createdhuman`            varchar(32),
              `lastupdate`              int(10) unsigned default 0,
              `lastbackup`              int(10) unsigned default 0,
              PRIMARY KEY  (`{$this->primaryKey}`)
            ) DEFAULT CHARSET=utf8;";
        return (assertTrue($this->createTable($createString), $this->tableName . ' table is being created'));
    }

    public function write($newLesson)
    { // lessonResult is the POST array
        trace(__CLASS__, __METHOD__, __FILE__);

        // there is only one record per lesson, so if it exists then we READ it
        //    and append $lessonResult to the cargo

        assertTRUE(is_array($newLesson));

        $identity = identity::singleton();
        $newLesson['userName'] = $identity->userName();
        $newLesson['sessionID'] = $identity->sessionID();
        $newLesson['created'] = time();
        $newLesson['createdhuman'] = date($GLOBALS['dateFormat']);

        // lets us find records quickly
        $key = $identity->studentID() . $newLesson['lessonKey'];
        $key = substr($key, 0, 64); // may truncate
        $key = str_replace("'", "*", $key); // can't have quotes in $key

        $resultSet = $this->query("select cargo from {$this->tableName} where studentLesson = \"$key\"");
        assertTRUE(count($resultSet) < 2, "Found more than one LessonResult for '$key'");

        if (count($resultSet) == 0) { // didn't find the key, so add a new record

            $cargo = array();
            // need the three primary keys in the cargo
            $cargo['project'] = $identity->project();
            $cargo['studentID'] = $identity->studentID();
            $cargo['lessonKey'] = $newLesson['lessonKey'];
            $cargo['studentlesson'] = $key;
            $cargo['lessons'] = array();
            $cargo['lessons'][] = $newLesson;

            $aArray = array();
            $aArray['studentID'] = $identity->studentID();
            $aArray['lessonKey'] = $newLesson['lessonKey'];
            $aArray['studentlesson'] = $key;
            $aArray['project'] = $identity->project();
            $aArray['created'] = time();
            $aArray['createdhuman'] = date($GLOBALS['dateFormat']);
            $aArray['cargo'] = serialize($cargo);
            $this->insertArray($aArray); // and write.

        } else { // already have this record, just add the cargo
            $result = $resultSet[0];
            $cargo = unserialize($result['cargo']);
            $cargo['lessons'][] = $newLesson;

            $this->updateByKey($key, $cargo); // not serialized here
        }
    }
    public function getLessonRecords($studentID, $lessonKey = '')
    { // returns ONE record

        $key = $studentID . $lessonKey;
        $resultSet = $this->query("select cargo from {$this->tableName} where studentlesson = \"$key\"");

        $result = array();
        foreach ($resultSet as $r) // unpack the results into a single array
        {
            foreach ($r as $s) {
                $result[] = unserialize($s);
                //echo "returns resultSet ".$s.'<br><br>';
            }
        }

        return ($result);
    }
}

class Projects extends AbstractData implements BasicTableFunctions
{

    // Hold a singleton instance of the class
    private static $instance;

    public function __construct()
    { // private, so can't instantiate from outside class
        parent::__construct();
        $this->tableName = $GLOBALS["dbPrefix"] . '_Projects';
        $this->uuidPrefix = 'P';
        $this->primaryKey = 'uuid';
        $this->secondaryKeys = array('shortName'); // so can sort by date
    }

    // The singleton method
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function create()
    {
        $createString =
            "CREATE TABLE IF NOT EXISTS `{$this->tableName}` (
              `uuid`      	            varchar(16)  NOT NULL,
              `project`                 varchar(32)  NOT NULL,
              `cargo` 	                text,
              `created`                 int(10) unsigned default 0,
              `createdhuman`            varchar(32),
              `lastupdate`              int(10) unsigned default 0,
              `lastbackup`              int(10) unsigned default 0,
              PRIMARY KEY  (`{$this->primaryKey}`)
            ) DEFAULT CHARSET=utf8;";
        return (assertTrue($this->createTable($createString), $this->tableName . ' table is being created'));
    }

}

class Users extends AbstractData implements BasicTableFunctions
{

    // Hold a singleton instance of the class
    private static $instance;

    public function __construct()
    { // private, so can't instantiate from outside class
        parent::__construct();
        $this->tableName = $GLOBALS["dbPrefix"] . '_Users';
        $this->uuidPrefix = 'U';
        $this->primaryKey = 'uuid';
        $this->secondaryKeys = array('UserName', 'EMail', 'Project');
    }

    // The singleton method
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function create()
    {
        $createString =
            "CREATE TABLE IF NOT EXISTS `{$this->tableName}` (
              `uuid`      	            varchar(16)  NOT NULL,
              `UserName`                varchar(64)  NOT NULL,
              `EMail`                   varchar(64)  NOT NULL,
              `Project`                 varchar(32),
              `cargo` 	                text,
              `created`                 int(10) unsigned default 0,
              `createdhuman`            varchar(32),
              `lastupdate`              int(10) unsigned default 0,
              `lastbackup`              int(10) unsigned default 0,
              PRIMARY KEY  (`{$this->primaryKey}`)
            ) DEFAULT CHARSET=utf8;";
        return (assertTrue($this->createTable($createString), $this->tableName . ' table is being created'));
    }

    public function insertUser($cargo)
    {
        trace(__CLASS__, __METHOD__, __FILE__, $cargo['EMail']);

        // do not allow if the email already exists  (dup userName is ok)
        if ($this->getUserEMail($cargo['EMail'])) {
            assertTRUE(false, "insertUser Fails - EMail '{$cargo['EMail']}' already exists");
            trace(__CLASS__, __METHOD__, __FILE__, "Fails - EMail '{$cargo['EMail']}' already exists");
            return (false);
        }

        // UserName is optional but necessary for DB,
        //  we'll create it here if necessary
        if (!isset($cargo['UserName'])) {
            $name = explode('@', $cargo['EMail']);
            $cargo['UserName'] = $name[0];
            assertTRUE(!empty($cargo['UserName']));
        }

        // Project is optional but necessary for DB,
        //  we'll create it here if necessary
        if (!isset($cargo['Project'])) {
            $cargo['Project'] = 'Unknown';
        }

        return ($this->insert($cargo));
    }

    public function getUserEMail($EMail)
    {
        if ($result = $this->getUserCargo($EMail)) {
            return ($result['EMail']);
        }

        return (false); // lots of reasons this could happen
    }

    public function getUserCargo($EMail)
    { // returns ONE record
        trace(__CLASS__, __METHOD__, __FILE__, "user='$EMail'");
        $safeUser = $this->quote_smart($EMail); // don't like injections
        $resultSet = $this->query("select * from {$this->tableName} where EMail = $safeUser");

        if (count($resultSet) > 1) {
            assertTRUE(false, "Seems to be a duplicate user '$EMail' - denying access");
            return (false);
        }

        //// if NO results, then we can try against userName
        if (count($resultSet) == 0) {
            $resultSet = $this->query("select * from {$this->tableName} where userName = $safeUser or EMail = $safeUser");
            if (count($resultSet) > 1) {
                // but don't ASSERT because our tester will fail, it just doesn't work
                trace(__CLASS__, __METHOD__, __FILE__, "user='$EMail' we seem to have a duplicate user - denying access");
                return (false);
            }
        }

        if (count($resultSet) == 0) {
            return (false);
        }

        // ok, exactly 1 user

        $rSet = current($resultSet); // don't iterate, only one record.
        $cargo = unserialize($rSet['cargo']);

        // sanity check - don't want to allow tampering here
        if ($cargo['EMail'] !== $rSet['EMail']) {
            assertTRUE(false, "Cargo EMail doesn't match record for {$rSet['EMail']}");
            return (false);
        }

        return ($cargo);
    }

    public function deleteUserEMail($EMail)
    {
        trace(__CLASS__, __METHOD__, __FILE__, "EMail='$EMail'");

        if ($result = $this->getUserCargo($EMail)) { // if exists and ONLY ONE
            $safeUser = $this->quote_smart($EMail); // don't like injections
            $resultSet = $this->query("delete from {$this->tableName} where EMail = $safeUser");
        }
    }

    public function getDistinctProjects()
    {
        $resultSet = $this->query("select distinct Project from {$this->tableName}");
        $result = array();
        foreach ($resultSet as $row) {
            $result[] = $row['Project'];
        }

        return ($result);
    }

    public function getUsersByProjects($project = '')
    {

        $where = '';
        if (!empty($project)) {
            $where = "where Project = " . $this->quote_smart($project);
        }
        // don't like injections

        $resultSet = $this->query("select cargo from {$this->tableName} $where order by lastupdate desc");

        $result = array();
        foreach ($resultSet as $r) // unpack the results into a single array
        {
            foreach ($r as $s) {
                $result[] = unserialize($s);
                //echo "returns resultSet ".$s.'<br><br>';
            }
        }

        return ($result);
    }
}
*/