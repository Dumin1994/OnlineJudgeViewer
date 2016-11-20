<?php

/**
 * Created by PhpStorm.
 * User: LiuACG
 * Date: 2016/11/9
 * Time: 15:48
 */
namespace LiuACG\OnlineJudgeViewer;

class ACNumber
{
    private $web = null;

    function __construct()
    {
        if ($this->web == null)
            $this->init();
    }

    function __destruct()
    {
        if ($this->web != null) {
            curl_close($this->web);
        }
        $this->web = null;
    }

    function init()
    {
        $this->web = curl_init();
        curl_setopt($this->web, CURLOPT_HEADER, false);
        curl_setopt($this->web, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36');
        curl_setopt($this->web, CURLOPT_RETURNTRANSFER, true);
    }

    private function matchOne($pattern, $str)
    {
        preg_match($pattern, $str, $matches);
        return ($matches == null) ? [0, 0, 0, 0, 0, 0, 0, 0, 0] : $matches;
    }

    private function matchAll($pattern, $str)
    {
        preg_match_all($pattern, $str, $matches);
        return $matches;
    }

    public function getUserData($user)
    {
        $user = trim($user);
        curl_setopt($this->web, CURLOPT_URL, 'http://acm.two.moe:808/JudgeOnline/userinfo.php?user=' . $user);
        $webContent = curl_exec($this->web);

        $result = null;
        if (preg_match('/No such User!/', $webContent)) {
            $result = ['id' => $user, 'error' => 'No such User!'];
        } else {
            $result['id'] = $user;
            $result['rank'] = (int)$this->matchOne('/No.<td width=25% align=center>(\d+)<td width=70% align=center>/', $webContent)[1];
            $result['submit'] = (int)$this->matchOne('/<td>Submit<td align=center><a href=\'status.php\?user_id=' . $user . '\'>(\d+)<\/a>/', $webContent)[1];
            $result['solved'] = (int)$this->matchOne('/<td>Solved<td align=center><a href=\'status.php\?user_id=' . $user . '&jresult=4\'>(\d+)<\/a>/', $webContent)[1];
            $result['ac'] = (int)$this->matchOne('/<td>AC<td align=center><a href=status.php\?user_id=' . $user . '&jresult=4 >(\d+)<\/a>/', $webContent)[1];
            $result['pe'] = (int)$this->matchOne('/<td>PE<td align=center><a href=status.php\?user_id=' . $user . '&jresult=5 >(\d+)<\/a>/', $webContent)[1];
            $result['wa'] = (int)$this->matchOne('/<td>WA<td align=center><a href=status.php\?user_id=' . $user . '&jresult=6 >(\d+)<\/a>/', $webContent)[1];
            $result['tle'] = (int)$this->matchOne('/<td>TLE<td align=center><a href=status.php\?user_id=' . $user . '&jresult=7 >(\d+)<\/a>/', $webContent)[1];
            $result['mle'] = (int)$this->matchOne('/<td>MLE<td align=center><a href=status.php\?user_id=' . $user . '&jresult=8 >(\d+)<\/a>/', $webContent)[1];
            $result['ole'] = (int)$this->matchOne('/<td>OLE<td align=center><a href=status.php\?user_id=' . $user . '&jresult=9 >(\d+)<\/a>/', $webContent)[1];
            $result['re'] = (int)$this->matchOne('/<td>RE<td align=center><a href=status.php\?user_id=' . $user . '&jresult=10 >(\d+)<\/a>/', $webContent)[1];
            $result['ce'] = (int)$this->matchOne('/<td>CE<td align=center><a href=status.php\?user_id=' . $user . '&jresult=11 >(\d+)<\/a>/', $webContent)[1];
            $result['school'] = $this->matchOne('/<td>School:<td align=center>([^<]*)<\/tr>/', $webContent)[1];
            $result['email'] = $this->matchOne('/<td>Email:<td align=center>([^<]*)<\/tr>/', $webContent)[1];

            $result['problems'] = $this->matchAll('/p\((\d*)\);/', $webContent)[1];

            $result['time_line'] = (function () use (&$webContent) {
                $tSubmitData = $this->matchAll('/d1\.push\(\[(\d+), (\d+)\]\);/', $webContent);
                $tSubmitData = array_map(function ($in1, $in2) {
                    return ['t' . $in1 => (int)$in2];
                }, $tSubmitData[1], $tSubmitData[2]);
                $tSubmitData = (function () use ($tSubmitData) {
                    $tmp = [];
                    foreach ($tSubmitData as $value) {
                        $tmp = array_merge($tmp, $value);
                    }
                    return $tmp;
                })();

                $tAcData = $this->matchAll('/d2\.push\(\[(\d+), (\d+)\]\);/', $webContent);
                $tAcData = array_map(function ($in1, $in2) {
                    return ['t' . $in1 => (int)$in2];
                }, $tAcData[1], $tAcData[2]);
                $tAcData = (function () use ($tAcData) {
                    $tmp = [];
                    foreach ($tAcData as $value) {
                        $tmp = array_merge($tmp, $value);
                    }
                    return $tmp;
                })();

                $tRes = [];
                foreach ($tSubmitData as $key => $value) {
                    //echo $key.'=>'.$value;
                    if (array_key_exists($key, $tAcData)) {
                        $tRes[$key] = [$value, $tAcData[$key]];
                    } else {
                        $tRes[$key] = [$value, 0];
                    }
                }
                ksort($tRes);
                $tRes = array_map(function ($key, $value) {
                    return [(int)substr($key, 1), (int)$value[0], (int)$value[1]];
                }, array_keys($tRes), array_values($tRes));
                return $tRes;
            })();
        }
        return $result;
    }

    public function getUserACList($id)
    {
        $tmp = $this->getUserData($id);
        if (array_key_exists('problems', $tmp))
            return ['id' => $id, 'problems' => $tmp['problems']];
        return ['id' => $id, 'problems' => []];
    }

    function getClassACData($filename)
    {
        $this->init();

        // read file (json format)
        if (!file_exists($filename)) {
            throw new \Exception('file not found.');
        }
        $fileHandle = fopen($filename, 'r');
        $jsonSrc = fgets($fileHandle);
        fclose($fileHandle);

        // json to php array
        $originData = json_decode($jsonSrc, true);
        $className = $originData['name'];
        $classSlug = $originData['slug'];
        $studentList = $originData['list'];

        $summary = [];
        $probList = [];
        foreach ($studentList as $key => $value) {
            $needUpdate = true;
            $data = null;
            if (app('cache')->has($key)) {
                $itemData = null;
                try {
                    $studentACData = iconv('gbk', 'utf8', app('cache')->get($key));
                    //var_dump($studentACData);
                    $data = json_decode($studentACData, true);
                    if (is_null($data)) {
                        echo htmlentities((string)$studentACData);
                        echo json_last_error_msg();
                        echo $studentACData;
                        var_dump($data);
                    }
                    if (!is_null($data)
                        && array_key_exists('update', $data)
                        && array_key_exists('data', $data)
                        && array_key_exists('name', $data)
                    ) {
                        $needUpdate = false;
                    }
                } catch (\Exception $exc) {
                    echo $exc->getMessage();
                }
            }
            if ($needUpdate) {
                $data = [
                    'update' => time(),
                    'data' => $this->getUserACList($key)['problems'],
                    'name' => $value
                ];
                // echo 'update'.$key.'<br/>';
                app('cache')->forever($key, json_encode($data));
            }
            if (is_array($data['data'])) {
                foreach ($data['data'] as $probNum) {
                    if (!array_key_exists($probNum, $probList)) {
                        $probList[$probNum] = 1;
                    } else {
                        $probList[$probNum]++;
                    }
                }
            }
        }

        $summary['className'] = $className;
        $summary['classSlug'] = $classSlug;
        $summary['studentCount'] = count($studentList);
        $summary['solvedCount'] = (function () use (&$probList) {
            $sum = 0;
            foreach ($probList as $value) $sum += $value;
            return $sum;
        })();
        $summary['detail'] = $probList;
        //var_dump($summary);
        return $summary;
    }
}
