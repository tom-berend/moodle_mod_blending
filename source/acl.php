<?php

/////// this is a simple access-control list (ACL) for this plugin's permissions
//  if ACL('teacher'){

function ACL(string $role): bool   // testing whether we have sufficicient access
{
                                    ///   admin > author > teacher > tutor > student > guest
    $acl = new BlendingACL();
    $contextRole = $acl->ACL_contextRole();        // what we are
    return $acl->ACL_Eval($role, $contextRole);
}

/////////////////////////////////////////////////////////////////////////////////

class BlendingACL
{

    function ACL_Eval(string $role, string $contextRole): bool
    { // call acl('teacher'), will return true for admin, author, teacher

        $role = strtolower(($role));  // not case sensitive

        $roles = [0 => 'admin', 1 => 'author', 2 => 'teacher', 3 => 'tutor', 4 => 'student', 5 => 'guest'];
        assertTrue(in_array($role, $roles), "Don't know how to handle '$role' in Utils::ACL()");

        assertTrue(in_array($contextRole, $roles), "Don't know how to handle '$contextRole' in Utils::ACL()"); // this is just a test of $roles

        $rolePosition = array_search($role, $roles);
        $contextPosition = array_search($contextRole, $roles);
        $ACLResult = $contextPosition <= $rolePosition;


        return $ACLResult;
    }


    function ACL_contextRole()
    {
        global $USER;

        // if not logged in, then a guest
        if ($USER->id == 0) {
            $contextRole = 'guest';    // default
        } else {

            // we are sure that the user is logged in, so safe to get context
            $context = context_module::instance($blending['courseID']);

            // find the highest match
            if (has_capability('mod/blending:admin', $context)) {
                $contextRole = 'admin';
            } elseif (has_capability('mod/blending:author', $context)) {
                $contextRole = 'author';
            } elseif (has_capability('mod/blending:teacher', $context)) {
                $contextRole = 'teacher';
            } elseif (has_capability('mod/blending:tutor', $context)) {
                $contextRole = 'tutor';
            } elseif (has_capability('mod/blending:student', $context)) {
                $contextRole = 'student';
            } else {
                $contextRole = 'guest';   // can still require login
            }
        }

        printNice($contextRole, 'Your ContextRole');
        return $contextRole;
    }
}
