<?php

namespace App\Services;

class FileUpload
{
    public static function upload($request, $file_name, $directory)
    {
        if ($request->hasFile($file_name)) {
            $file = $request->$file_name;
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $up_path = "uploads/".date('Y-m')."/$directory/";
            $path = $file->move($up_path, $filename);
            if ($file->getError()) {
                $request->session()->flash('warning', $file->getErrorMessage());

                return false;
            }

            return $path;
        }
    }
}
