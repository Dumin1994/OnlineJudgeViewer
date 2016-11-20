<?php

namespace App\Http\Controllers;

use LiuACG\OnlineJudgeViewer\ACNumber;

class ApiInfoController extends Controller
{
    public function user($id)
    {
        $ac = new ACNumber();
        return response()->json($ac->getUserData($id));
    }

    public function compareUser($id1, $id2)
    {
        $ac = new ACNumber();
        $user1 = $ac->getUserACList($id1)['problems'];
        $user2 = $ac->getUserACList($id2)['problems'];
        $res1 = array_values(array_diff($user1, $user2));
        $res2 = array_values(array_diff($user2, $user1));
        $res3 = array_values(array_intersect($user1, $user2));
        return response()->json(['user1' => $res1, 'user2' => $res2, 'common' => $res3]);
    }

    public function getClass($id)
    {
        $ac = new ACNumber();
        return response()->json($ac->getClassACData(storage_path() . '/data/' . $id . '.data'));
    }

    public function summary()
    {
        $ret = (function () {
            $files = (function () {
                $res = [];
                foreach (scandir(storage_path() . '/data/') as $file) {
                    if (preg_match('/(.*)\.data/', $file, $matches))
                        array_push($res, $matches[1]);
                }
                return $res;
            })();

            $res = [];
            $ac = new ACNumber();
            foreach ($files as $file) {
                $arr = $ac->getClassACData(storage_path() . '/data/' . $file . '.data');
                unset($arr['detail']);
                $arr['classID'] = $file;
                asort($arr);
                array_push($res, $arr);
            }
            return $res;
        })();
        return response()->json($ret);
    }
}
