<?php
namespace app\api\controller;
use think\Request;
use think\View;

class Upload
{
    //获取webuploader上传的文件路径并返回给ajax
    public function ajax_upload(){
        // 获取表单上传文件
        $files = request()->file('');
        foreach($files as $file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                $path['name'] = 'public' . DS . 'uploads/' . $info->getSavename();
            }else{
                // 上传失败获取错误信息
                return $this->error($file->getError()) ;
            }
        }
        echo json_encode($path);
    }
}
