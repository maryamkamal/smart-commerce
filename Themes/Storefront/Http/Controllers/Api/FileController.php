<?php
namespace Themes\Storefront\Http\Controllers\Api;

use Modules\Media\Entities\File;




class FileController
{
    public function index()
    {
        $files = File::all();
        return \response()->json($files);
    }
    
    public function file($id)
    {
        
        $file = File::find($id);
        return \response()->json($file);
    }
}