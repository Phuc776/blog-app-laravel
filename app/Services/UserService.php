<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function getUser(User $user)
    {
        return $user;
    }

    public function updateUser($params, $user)
    {
        // Cập nhật tên
        if(isset($params['name'])){
            $user->name = $params['name'];
        }
        
        // Kiểm tra nếu avatar là file ảnh hợp lệ
        if (isset($params['avatar']) && $params['avatar']->isValid()) {
            if ($user->avatar) {
                $oldAvatarPath = public_path(str_replace(url('/'), '', $user->avatar));

                // Xóa ảnh cũ nếu tồn tại
                if (File::exists($oldAvatarPath)) {
                    File::delete($oldAvatarPath);
                }
            }

            // Lấy file ảnh
            $file = $params['avatar'];
            
            // Tạo tên file duy nhất
            $uniqueFileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $file->move(public_path('avatars'), $uniqueFileName);
            
            // Cập nhật đường dẫn avatar vào cơ sở dữ liệu
            $user->avatar = url('avatars/' . $uniqueFileName);
        }

        // Lưu thay đổi vào cơ sở dữ liệu
        $user->save();

        // Trả về thông tin đã cập nhật
        return $user->only(['name', 'avatar']);
    }

}
