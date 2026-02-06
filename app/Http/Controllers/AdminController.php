<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    // 1. TRANG TỔNG QUAN (Dashboard chính)
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Truy cập bị từ chối.');
        }

       $totalLikes   = DB::table('animals')->sum('likes_count');
        $totalAnimals = DB::table('animals')->count();
        $totalUsers = DB::table('users')->where('role', 'user')->count();

        $aiHistory = DB::table('ai_detections')
            ->join('users', 'ai_detections.user_id', '=', 'users.id')
            ->select('ai_detections.*', 'users.name as user_name')
            ->orderBy('ai_detections.created_at', 'desc')
            ->limit(5)
            ->get();

        $animals = DB::table('animals')->orderBy('id', 'desc')->get();

        return view('admin.dashboard', compact(
            'totalLikes',
            'totalAnimals',
            'totalUsers',
            'aiHistory',
            'animals'
        ));
    }

    // 2. TRANG QUẢN LÝ RIÊNG (Hiển thị danh sách để đăng bài)
    public function manageAnimals()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền này.');
        }

        $animals = DB::table('animals')->orderBy('id', 'desc')->get();
        return view('admin.manage_animals', compact('animals'));
    }

    // 3. XỬ LÝ LƯU ĐỘNG VẬT (Khi bấm nút Đăng bài)
   public function storeAnimal(Request $request)
{
    // 1. Kiểm tra dữ liệu đầu vào chặt chẽ hơn
    $request->validate([
        'name' => 'required|string|max:255',
        'scientific_name' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'category' => 'required',
    ]);

    // 2. Xử lý lưu ảnh
    $imagePath = '';
    if ($request->hasFile('image')) {
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads'), $imageName);
        $imagePath = 'uploads/' . $imageName;
    }

    // 3. LƯU VÀO DATABASE
    DB::table('animals')->insert([
        'name' => $request->name,
        'scientific_name' => $request->scientific_name,
        'category' => $request->category,

        // Mẹo: Nếu ô status trống, tự động lưu là 'Ít lo ngại'
        'status' => $request->status ?? 'Ít lo ngại',

        'behavior' => $request->behavior,
        'description' => $request->description,
        'image_url' => $imagePath,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Đã đăng bài thành công!');
}
    // 4. XỬ LÝ CẬP NHẬT ĐỘNG VẬT (Sửa bài đã đăng)
   public function updateAnimal(Request $request, $id)
{
    // 1. Kiểm tra dữ liệu đầu vào (Validation)
    $request->validate([
        'name' => 'required|string|max:255',
        'scientific_name' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // 2. Tìm thông tin con vật cũ để lấy đường dẫn ảnh
    $animal = DB::table('animals')->where('id', $id)->first();
    $imagePath = $animal->image_url;

    // 3. Nếu có upload ảnh mới thì xóa ảnh cũ và lưu ảnh mới
    if ($request->hasFile('image')) {
        if ($imagePath && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads'), $imageName);
        $imagePath = 'uploads/' . $imageName;
    }

    // 4. CẬP NHẬT TẤT CẢ THÔNG TIN VÀO DATABASE
    DB::table('animals')->where('id', $id)->update([
        'name' => $request->name,
        'scientific_name' => $request->scientific_name,
        'category' => $request->category,
        'status' => $request->status,
        'behavior' => $request->behavior,
        'description' => $request->description,

        // --- THÊM CÁC TRƯỜNG MỚI THEO Ý CÔ GIÁO Ở ĐÂY ---
        'animal_class' => $request->animal_class, // Lưu thông tin Lớp (Vd: Mammalia)
        'animal_order' => $request->animal_order, // Lưu thông tin Bộ (Vd: Carnivora)
        'diet_type'    => $request->diet_type,    // Lưu Chế độ ăn (Ăn thịt, Ăn cỏ...)

        'image_url' => $imagePath,
        'updated_at' => now(),
    ]);

    // Quay lại Dashboard với thông báo thành công
    return redirect()->route('admin.dashboard')->with('success', 'Đã cập nhật thông tin động vật thành công!');
}
    // 5. XỬ LÝ XÓA ĐỘNG VẬT
    public function deleteAnimal($id)
    {
        $animal = DB::table('animals')->where('id', $id)->first();

        // Xóa file ảnh trong thư mục public/uploads trước khi xóa DB
        if (File::exists(public_path($animal->image_url))) {
            File::delete(public_path($animal->image_url));
        }

        DB::table('animals')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Đã xóa động vật khỏi hệ thống!');
    }

public function editAnimal($id) {
    $animal = DB::table('animals')->where('id', $id)->first();
    if (!$animal) return redirect()->back()->with('error', 'Không tìm thấy loài vật!');

    return view('admin.edit_animal', compact('animal'));
}

public function updateStatus(Request $request, $id)
{
    if ($request->status == 1) {
        // Nếu Accept: Duyệt cho hiện lên Social
        DB::table('posts')->where('id', $id)->update([
            'status' => 1,
            'updated_at' => now()
        ]);
        $msg = 'Đã phê duyệt bài viết thành công!';
    } else {
        // Nếu Denied: XÓA VĨNH VIỄN BÀI VIẾT
        DB::table('posts')->where('id', $id)->delete();
        $msg = 'Đã xóa bài viết bị từ chối!';
    }

    return redirect()->back()->with('status_msg', $msg);
}

public function indexPost()
{
    // Lấy tất cả bài viết kèm tên người đăng từ Database
    $pendingPosts = DB::table('posts')
        ->join('users', 'posts.user_id', '=', 'users.id')
        ->select('posts.*', 'users.name as user_name')
        ->orderBy('posts.created_at', 'desc')
        ->get();

    // Trả về view admin/post.blade.php bạn đã tạo
    return view('admin.post', compact('pendingPosts'));
}
}
