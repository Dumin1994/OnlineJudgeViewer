<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClassController extends Controller
{
    protected $admin;

    public function __construct()
    {
        $this->admin = true;
        view()->share('admin', $this->admin);
    }

    public function index($accessKey)
    {
        $files = (function () {
            $res = [];
            foreach (scandir(storage_path() . '/data/') as $file) {
                if (preg_match('/(.*)\.data/', $file, $matches)) {
                    $fileHandle = fopen(storage_path() . '/data/' . $file, 'r');
                    $fileContent = fgets($fileHandle);
                    fclose($fileHandle);
                    $res[$matches[1]] = json_decode($fileContent, true)['name'];
                }
            }
            return $res;
        })();
        return view('admin.class.index', ['accessKey' => $accessKey, 'files' => $files]);
    }

    public function create($accessKey)
    {
        return view('admin.class.create', ['accessKey' => $accessKey]);
    }

    public function store(Request $request, $accessKey)
    {
        // TODO: Use Lumen validator framework ?
        try {
            $this->validate($request, [
                'c-id' => 'required|alpha_num|max:20',
                'c-name' => 'required',
                'c-slug' => 'required'
            ]);

            $id = trim($request->get('c-id'));
            $filename = storage_path() . '/data/' . $id . '.data';
            if (file_exists($filename)) {
                return "该班级已存在.";
            }
            $res = [
                'name' => trim($request->get('c-name')),
                'slug' => trim($request->get('c-slug')),
                'list' => []
            ];
            $res = iconv('gbk', 'utf8', json_encode($res));
            $fileHandle = fopen($filename, 'w');
            fputs($fileHandle, $res);
            fclose($fileHandle);

            return redirect()->route('class-edit', ['AccessKey' => $accessKey, 'id' => $id]);
        } catch (ValidationException $ex) {
            abort(400);
        }
        return -1;
    }

    public function show($id, $accessKey)
    {
        $filename = storage_path() . '/data/' . trim($id) . '.data';
        if (!file_exists($filename))
            abort(404);
        $fileHandle = fopen($filename, 'r');
        $fileContent = fgets($fileHandle);
        fclose($fileHandle);

        $data = json_decode($fileContent, true);

        $className = $data['name'];
        $list = $data['list'];
        return view('admin.class.show', ['accessKey' => $accessKey, 'id' => $id, 'className' => $className, 'list' => $list]);
    }

    public function edit($id, $accessKey)
    {
        $filename = storage_path() . '/data/' . $id . '.data';
        if (file_exists($filename)) {
            $fileHandle = fopen($filename, 'r');
            $fileContent = fgets($fileHandle);
            fclose($fileHandle);
            $tmp = json_decode(iconv('gbk', 'utf8', $fileContent), true);
            return view('admin.class.edit', ['accessKey' => $accessKey, 'id' => $id, 'data' => $tmp]);
        }
        // abort(404);
        return view('share.error');
    }

    public function update(Request $request, $id, $accessKey)
    {
        $tmp = json_encode(json_decode($request->get('data'), true));
        $tmp = iconv('gbk', 'utf8', $tmp);

        // check data
        $obj = json_decode($tmp, true);
        if (!array_key_exists('name', $obj)
            || trim($obj['name']) == ''
            || !array_key_exists('slug', $obj)
            || trim($obj['slug']) == ''
            || !array_key_exists('list', $obj)
            || !is_array($obj['list'])
        )
            abort(400);
        $keyCache = [];
        foreach ($obj['list'] as $key => $value) {
            if (in_array($key, $keyCache))
                abort(400);
            array_push($keyCache, $key);
        }

        // write data to the file
        $filename = storage_path() . '/data/' . $id . '.data';
        if (file_exists($filename)) {
            $fileHandle = fopen($filename, 'w');
            fputs($fileHandle, $tmp);
            fflush($fileHandle);
            fclose($fileHandle);
        } else {
            abort(404);
        }
        return redirect()->route('class-show', ['AccessKey' => $accessKey, 'id' => $id]);
    }

    public function destroy($id, $accessKey)
    {
        $filename = storage_path() . '/data/' . $id . '.data';
        if (file_exists($filename)) {
            unlink($filename);
        }
        return redirect()->route('class-index', ['AccessKey' => $accessKey]);
    }
}